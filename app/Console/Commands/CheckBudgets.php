<?php

namespace App\Console\Commands;

use App\Models\Budget;
use App\Models\Category;
use App\Notifications\BudgetAlert;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckBudgets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budgets:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check budgets and send notifications for approaching or exceeded limits';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting budget check...');

        // Get all categories with budgets
        $categories = Category::with(['budgets.user', 'expenses' => function ($query) {
            $query->whereMonth('date', Carbon::now()->month)
                  ->whereYear('date', Carbon::now()->year);
        }])->get();

        foreach ($categories as $category) {
            $this->processCategory($category);
        }

        $this->info('Budget check completed!');
    }

    /**
     * Process a category and its budgets
     */
    protected function processCategory(Category $category)
    {
        foreach ($category->budgets as $budget) {
            $user = $budget->user;
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            // Calculate total spent for this category in current month
            $totalSpent = $category->expenses->sum('amount');
            
            // Calculate percentage used
            $percentage = ($totalSpent / $budget->amount) * 100;

            // Send notification if budget is at 80% or exceeded
            if ($percentage >= 80) {
                $user->notify(new BudgetAlert(
                    $category,
                    $percentage,
                    $totalSpent,
                    $budget->amount
                ));

                $this->info("Notification sent to {$user->name} for {$category->name} category");
            }
        }
    }
} 