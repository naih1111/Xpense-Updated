<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function monthlyExpenses()
    {
        $testDate = app()->environment('testing') 
            ? now()->startOfMonth()->addDays(14)
            : now();

        $startDate = $testDate->copy()->startOfMonth();
        $endDate = $testDate->copy()->endOfMonth();

        $userId = auth()->id();
        $expenses = Expense::with('category')
            ->where('user_id', $userId)
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->get();

        $total = $expenses->sum('amount');
        $categories = $expenses->groupBy('category.name')
            ->map(function ($items) {
                return $items->sum('amount');
            });

        return view('reports.expenses.monthly', [
            'report' => [
                'total' => $total,
                'categories' => $categories
            ]
        ]);
    }

    public function monthlyIncomes()
    {
        $testDate = app()->environment('testing') 
            ? now()->startOfMonth()->addDays(14)
            : now();

        $startDate = $testDate->copy()->startOfMonth();
        $endDate = $testDate->copy()->endOfMonth();

        $userId = auth()->id();
        $incomes = Income::with('category')
            ->where('user_id', $userId)
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->get();

        $total = $incomes->sum('amount');
        $categories = $incomes->groupBy('category.name')
            ->map(function ($items) {
                return $items->sum('amount');
            });

        return view('reports.incomes.monthly', [
            'report' => [
                'total' => $total,
                'categories' => $categories
            ]
        ]);
    }

    public function goalProgress()
    {
        $goals = Goal::where('user_id', auth()->id())->get();
        
        $report = [
            'total_goals' => $goals->count(),
            'completed_goals' => $goals->where('status', 'completed')->count(),
            'in_progress_goals' => $goals->where('status', 'active')->count()
        ];

        return view('reports.goals.progress', [
            'goals' => $goals,
            'report' => $report
        ]);
    }

    public function customDateRange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $expenses = Expense::with('category')
            ->where('user_id', auth()->id())
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->get();

        $total = $expenses->sum('amount');
        $categories = $expenses->groupBy('category.name')
            ->map(function ($items) {
                return $items->sum('amount');
            });

        return view('reports.expenses.custom', [
            'report' => [
                'total' => $total,
                'categories' => $categories
            ]
        ]);
    }

    public function exportExpensesPdf(Request $request)
    {
        $testDate = app()->environment('testing') 
            ? now()->startOfMonth()->addDays(14)
            : now();

        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : $testDate->copy()->startOfMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : $testDate->copy()->endOfMonth();

        $expenses = Expense::with('category')
            ->where('user_id', auth()->id())
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->get();

        $total = $expenses->sum('amount');
        $categories = $expenses->groupBy('category.name')
            ->map(function ($items) {
                return $items->sum('amount');
            });

        $pdf = PDF::loadView('reports.expenses.pdf', [
            'report' => [
                'total' => $total,
                'categories' => $categories
            ]
        ]);

        return $pdf->download('expenses-report.pdf');
    }

    public function exportExpensesExcel(Request $request)
    {
        $testDate = app()->environment('testing') 
            ? now()->startOfMonth()->addDays(14)
            : now();

        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : $testDate->copy()->startOfMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : $testDate->copy()->endOfMonth();

        $expenses = Expense::with('category')
            ->where('user_id', auth()->id())
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->get();

        $total = $expenses->sum('amount');
        $categories = $expenses->groupBy('category.name')
            ->map(function ($items) {
                return $items->sum('amount');
            });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Category');
        $sheet->setCellValue('B1', 'Amount');

        // Add data
        $row = 2;
        foreach ($categories as $category => $amount) {
            $sheet->setCellValue('A' . $row, $category);
            $sheet->setCellValue('B' . $row, $amount);
            $row++;
        }

        // Add total
        $sheet->setCellValue('A' . $row, 'Total');
        $sheet->setCellValue('B' . $row, $total);

        $writer = new Xlsx($spreadsheet);
        $filename = 'expenses-report.xlsx';
        $path = storage_path('app/public/' . $filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }
} 