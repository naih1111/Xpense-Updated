<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\FinancialGoalController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\InsightController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
        
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Expense routes
    Route::resource('expenses', ExpenseController::class);

    // Income routes
    Route::resource('incomes', IncomeController::class);
    Route::get('/income/create', [IncomeController::class, 'create'])->name('income.create');
    Route::post('/income', [IncomeController::class, 'store'])->name('income.store');

    // Goal routes
    Route::resource('personal-goals', GoalController::class);

    // Budgets
    Route::resource('budgets', BudgetController::class);
    
    // Financial Goals
    Route::resource('financial-goals', FinancialGoalController::class);
    Route::patch('financial-goals/{goal}/update-progress', [FinancialGoalController::class, 'updateProgress'])->name('financial-goals.update-progress');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Insights
    Route::get('/insights', [InsightController::class, 'index'])->name('insights.index');
    Route::get('/insights/filter', [InsightController::class, 'filter'])->name('insights.filter');
    
    // Password
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});
