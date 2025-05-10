<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Budget;
use App\Models\FinancialGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Get the current month's start and end dates
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
            
            // Get total expenses and income for the current month
            $totalExpenses = $user->getTotalExpenses($startDate, $endDate);
            $totalIncome = $user->getTotalIncome($startDate, $endDate);
            $netSavings = $user->getNetSavings($startDate, $endDate);
            
            // Get recent transactions with eager loading
            $recentExpenses = $user->expenses()
                ->with('category')
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($expense) {
                    return (object)[
                        'type' => 'expense',
                        'description' => $expense->description,
                        'amount' => $expense->amount,
                        'date' => $expense->date
                    ];
                });
                
            $recentIncomes = $user->incomes()
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($income) {
                    return (object)[
                        'type' => 'income',
                        'description' => $income->description,
                        'amount' => $income->amount,
                        'date' => $income->date
                    ];
                });

            // Combine and sort recent transactions
            $recentTransactions = $recentExpenses->concat($recentIncomes)
                ->sortByDesc('date')
                ->take(5);
                
            // Get active financial goals
            $financialGoals = $user->financialGoals()
                ->where('status', 'in_progress')
                ->latest()
                ->take(3)
                ->get();

            // Only pass income data if there are income records
            if ($recentIncomes->isEmpty()) {
                $totalIncome = null;
            }

            return view('dashboard', compact(
                'totalExpenses',
                'totalIncome',
                'netSavings',
                'recentExpenses',
                'recentIncomes',
                'financialGoals',
                'recentTransactions'
            ));
        } catch (\Exception $e) {
            Log::error('Dashboard Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Initialize empty collections for all variables
            $totalExpenses = 0;
            $totalIncome = 0;
            $netSavings = 0;
            $recentExpenses = collect();
            $recentIncomes = collect();
            $financialGoals = collect();
            $recentTransactions = collect();
            
            return view('dashboard', compact(
                'totalExpenses',
                'totalIncome',
                'netSavings',
                'recentExpenses',
                'recentIncomes',
                'financialGoals',
                'recentTransactions'
            ))->with('error', 'Unable to load dashboard data. Please try again later.');
        }
    }
}