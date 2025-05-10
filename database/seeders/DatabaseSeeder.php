<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Budget;
use App\Models\FinancialGoal;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo user
        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
            'monthly_income' => 5000.00,
        ]);

        // Create expense categories
        $expenseCategories = [
            'Housing' => 'Rent, mortgage, repairs, etc.',
            'Transportation' => 'Car payments, fuel, public transit',
            'Food' => 'Groceries and dining out',
            'Utilities' => 'Electricity, water, internet',
            'Healthcare' => 'Medical expenses and insurance',
            'Entertainment' => 'Movies, hobbies, subscriptions',
        ];

        foreach ($expenseCategories as $name => $description) {
            Category::factory()->create([
                'user_id' => $user->id,
                'name' => $name,
                'description' => $description,
                'type' => 'expense',
            ]);
        }

        // Create income categories
        $incomeCategories = [
            'Salary' => 'Regular employment income',
            'Freelance' => 'Freelance and contract work',
            'Investments' => 'Investment returns and dividends',
            'Other' => 'Miscellaneous income sources',
        ];

        foreach ($incomeCategories as $name => $description) {
            Category::factory()->create([
                'user_id' => $user->id,
                'name' => $name,
                'description' => $description,
                'type' => 'income',
            ]);
        }

        // Create sample expenses
        $categories = Category::where('user_id', $user->id)
            ->where('type', 'expense')
            ->get();

        foreach ($categories as $category) {
            Expense::factory(3)->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
            ]);
        }

        // Create sample incomes
        $categories = Category::where('user_id', $user->id)
            ->where('type', 'income')
            ->get();

        foreach ($categories as $category) {
            Income::factory(2)->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
            ]);
        }

        // Create sample budgets
        $expenseCategories = Category::where('user_id', $user->id)
            ->where('type', 'expense')
            ->get();

        foreach ($expenseCategories as $category) {
            Budget::factory()->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
            ]);
        }

        // Create sample financial goals
        FinancialGoal::factory(3)->create([
            'user_id' => $user->id,
        ]);
    }
}
