<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InsightController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get date range (default to current month)
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Get expenses by category
        $expensesByCategory = $this->getExpensesByCategory($startDate, $endDate);
        
        // Get monthly spending trend
        $monthlySpendingTrend = $this->getMonthlySpendingTrend();
        
        // Get income vs expenses with trends
        $incomeVsExpenses = $this->getIncomeVsExpenses($startDate, $endDate);
        
        // Get top spending categories
        $topSpendingCategories = $this->getTopSpendingCategories($startDate, $endDate);

        // Get recent expenses
        $recentExpenses = $user->expenses()
            ->with('category')
            ->whereBetween('date', [$startDate, $endDate])
            ->latest()
            ->take(5)
            ->get();
        
        return view('insights.index', compact(
            'expensesByCategory',
            'monthlySpendingTrend',
            'incomeVsExpenses',
            'topSpendingCategories',
            'startDate',
            'endDate',
            'recentExpenses'
        ));
    }
    
    public function filter(Request $request)
    {
        $user = Auth::user();
        
        // Get date range from request
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        // Get expenses by category
        $expensesByCategory = $this->getExpensesByCategory($startDate, $endDate);
        
        // Get monthly spending trend
        $monthlySpendingTrend = $this->getMonthlySpendingTrend();
        
        // Get income vs expenses with trends
        $incomeVsExpenses = $this->getIncomeVsExpenses($startDate, $endDate);
        
        // Get top spending categories
        $topSpendingCategories = $this->getTopSpendingCategories($startDate, $endDate);

        // Get recent expenses
        $recentExpenses = $user->expenses()
            ->with('category')
            ->whereBetween('date', [$startDate, $endDate])
            ->latest()
            ->take(5)
            ->get();
        
        return view('insights.index', compact(
            'expensesByCategory',
            'monthlySpendingTrend',
            'incomeVsExpenses',
            'topSpendingCategories',
            'startDate',
            'endDate',
            'recentExpenses'
        ));
    }
    
    private function createSampleData($user)
    {
        // Create sample categories if none exist
        if (!Category::where('user_id', $user->id)->exists()) {
            $categories = [
                ['name' => 'Food & Dining', 'type' => 'expense'],
                ['name' => 'Transportation', 'type' => 'expense'],
                ['name' => 'Housing', 'type' => 'expense'],
                ['name' => 'Utilities', 'type' => 'expense'],
                ['name' => 'Entertainment', 'type' => 'expense'],
            ];
            
            foreach ($categories as $category) {
                Category::create([
                    'user_id' => $user->id,
                    'name' => $category['name'],
                    'type' => $category['type'],
                ]);
            }
        }
        
        // Create sample income
        Income::create([
            'user_id' => $user->id,
            'amount' => 50000.00,
            'description' => 'Monthly Salary',
            'date' => Carbon::now(),
        ]);
        
        // Create sample expenses
        $categories = Category::where('user_id', $user->id)->get();
        $expenses = [
            ['amount' => 5000.00, 'description' => 'Grocery Shopping'],
            ['amount' => 2000.00, 'description' => 'Gas'],
            ['amount' => 15000.00, 'description' => 'Rent'],
            ['amount' => 2000.00, 'description' => 'Electricity Bill'],
            ['amount' => 3000.00, 'description' => 'Movie Night'],
        ];
        
        foreach ($expenses as $index => $expense) {
            Expense::create([
                'user_id' => $user->id,
                'category_id' => $categories[$index]->id,
                'amount' => $expense['amount'],
                'description' => $expense['description'],
                'date' => Carbon::now(),
            ]);
        }
    }
    
    private function getExpensesByCategory($startDate, $endDate)
    {
        $user = Auth::user();
        
        $categories = Category::where('user_id', $user->id)
            ->where('type', 'expense')
            ->get();
            
        $data = [];
        
        foreach ($categories as $category) {
            $amount = $category->expenses()
                ->where('user_id', $user->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');
                
            if ($amount > 0) {
                $data[] = [
                    'name' => $category->name ?? 'Uncategorized',
                    'amount' => $amount,
                ];
            }
        }
        
        // If no categories have expenses, add a default entry
        if (empty($data)) {
            $data[] = [
                'name' => 'No Expenses',
                'amount' => 0,
            ];
        }
        
        return $data;
    }
    
    private function getMonthlySpendingTrend()
    {
        $user = Auth::user();
        
        // Get the last 6 months
        $months = [];
        $amounts = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $amount = Expense::where('user_id', $user->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');
                
            $months[] = $date->format('M Y');
            $amounts[] = $amount;
        }
        
        return [
            'labels' => $months,
            'data' => $amounts,
        ];
    }
    
    private function getIncomeVsExpenses($startDate, $endDate)
    {
        $user = Auth::user();
        
        // Current period
        $currentIncome = Income::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
            
        $currentExpenses = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
            
        // Previous period (same duration)
        $periodDuration = $startDate->diffInDays($endDate);
        $previousStartDate = $startDate->copy()->subDays($periodDuration);
        $previousEndDate = $startDate->copy()->subDay();
        
        $previousIncome = Income::where('user_id', $user->id)
            ->whereBetween('date', [$previousStartDate, $previousEndDate])
            ->sum('amount');
            
        $previousExpenses = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$previousStartDate, $previousEndDate])
            ->sum('amount');
            
        // Calculate trends
        $incomeTrend = $previousIncome > 0 ? (($currentIncome - $previousIncome) / $previousIncome) * 100 : 0;
        $expensesTrend = $previousExpenses > 0 ? (($currentExpenses - $previousExpenses) / $previousExpenses) * 100 : 0;
        
        return [
            'income' => $currentIncome,
            'expenses' => $currentExpenses,
            'balance' => $currentIncome - $currentExpenses,
            'incomeTrend' => $incomeTrend,
            'expensesTrend' => $expensesTrend,
            'balanceTrend' => $previousIncome - $previousExpenses > 0 ? 
                (($currentIncome - $currentExpenses) - ($previousIncome - $previousExpenses)) / ($previousIncome - $previousExpenses) * 100 : 0
        ];
    }
    
    private function getTopSpendingCategories($startDate, $endDate)
    {
        $user = Auth::user();
        
        $categories = Category::where('user_id', $user->id)
            ->where('type', 'expense')
            ->get();
            
        $data = [];
        
        foreach ($categories as $category) {
            $currentAmount = $category->expenses()
                ->where('user_id', $user->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');
                
            if ($currentAmount > 0) {
                // Calculate trend from previous period
                $periodDuration = $startDate->diffInDays($endDate);
                $previousStartDate = $startDate->copy()->subDays($periodDuration);
                $previousEndDate = $startDate->copy()->subDay();
                
                $previousAmount = $category->expenses()
                    ->where('user_id', $user->id)
                    ->whereBetween('date', [$previousStartDate, $previousEndDate])
                    ->sum('amount');
                    
                $trend = $previousAmount > 0 ? (($currentAmount - $previousAmount) / $previousAmount) * 100 : 0;
                
                $data[] = [
                    'name' => $category->name ?? 'Uncategorized',
                    'amount' => $currentAmount,
                    'trend' => $trend
                ];
            }
        }
        
        // If no categories have expenses, add a default entry
        if (empty($data)) {
            $data[] = [
                'name' => 'No Expenses',
                'amount' => 0,
                'trend' => 0
            ];
        }
        
        // Sort by amount in descending order
        usort($data, function($a, $b) {
            return $b['amount'] <=> $a['amount'];
        });
        
        return $data;
    }
} 