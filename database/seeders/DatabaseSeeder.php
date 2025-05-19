<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Budget;
use App\Models\FinancialGoal;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a demo user if no users exist
        if (!User::exists()) {
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'monthly_income' => 5000
            ]);
        } else {
            $user = User::first();
        }

        // Delete existing categories for this user
        Category::where('user_id', $user->id)->delete();

        // Default expense categories
        $expenseCategories = [
            'Food & Dining',
            'Transportation',
            'Housing',
            'Utilities',
            'Healthcare',
            'Entertainment',
            'Shopping',
            'Education',
            'Personal Care',
            'Others'
        ];

        // Create expense categories
        foreach ($expenseCategories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'type' => 'expense',
                'user_id' => $user->id,
                'description' => 'Default expense category'
            ]);
        }

        // Create sample expenses
        $expenseCategories = Category::where('user_id', $user->id)
            ->where('type', 'expense')
            ->get();

        foreach ($expenseCategories as $category) {
            Expense::factory()->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'type' => 'need',
                'amount' => rand(100, 1000),
                'description' => 'Sample ' . $category->name . ' expense',
                'date' => now()
            ]);
        }

        // Create sample budgets
        foreach ($expenseCategories as $category) {
            Budget::factory()->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'amount' => rand(1000, 3000),
                'period' => 'monthly',
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth()
            ]);
        }

        // Create sample financial goals
        FinancialGoal::factory(3)->create([
            'user_id' => $user->id,
        ]);
    }
}
