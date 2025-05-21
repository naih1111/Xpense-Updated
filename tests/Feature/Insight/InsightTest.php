<?php

namespace Tests\Feature\Insight;

use App\Models\User;
use App\Models\Expense;
use App\Models\Income;
use App\Models\FinancialGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsightTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_insights_page_can_be_rendered()
    {
        $response = $this->actingAs($this->user)
            ->get('/insights');

        $response->assertStatus(200);
    }

    public function test_monthly_expense_summary()
    {
        // Create expenses for current month
        Expense::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'amount' => 100.00,
            'date' => now(),
        ]);

        // Create expenses for previous month
        Expense::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'amount' => 100.00,
            'date' => now()->subMonth(),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/insights/expenses/monthly');

        $response->assertStatus(200);
        $response->assertViewHas('monthlyTotal', 300.00);
        $response->assertViewHas('previousMonthTotal', 200.00);
    }

    public function test_monthly_income_summary()
    {
        // Create incomes for current month
        Income::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'amount' => 1000.00,
            'date' => now(),
        ]);

        // Create incomes for previous month
        Income::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 1000.00,
            'date' => now()->subMonth(),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/insights/incomes/monthly');

        $response->assertStatus(200);
        $response->assertViewHas('monthlyTotal', 2000.00);
        $response->assertViewHas('previousMonthTotal', 1000.00);
    }

    public function test_expense_categories_breakdown()
    {
        // Create expenses in different categories
        Expense::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 100.00,
            'category' => 'Food',
            'date' => now(),
        ]);

        Expense::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 200.00,
            'category' => 'Transport',
            'date' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/insights/expenses/categories');

        $response->assertStatus(200);
        $response->assertViewHas('categories', function ($categories) {
            return $categories->has('Food') && 
                   $categories->has('Transport') &&
                   $categories['Food'] === 100.00 &&
                   $categories['Transport'] === 200.00;
        });
    }

    public function test_income_categories_breakdown()
    {
        // Create incomes in different categories
        Income::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 1000.00,
            'category' => 'Salary',
            'date' => now(),
        ]);

        Income::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 500.00,
            'category' => 'Bonus',
            'date' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/insights/incomes/categories');

        $response->assertStatus(200);
        $response->assertViewHas('categories', function ($categories) {
            return $categories->has('Salary') && 
                   $categories->has('Bonus') &&
                   $categories['Salary'] === 1000.00 &&
                   $categories['Bonus'] === 500.00;
        });
    }

    public function test_net_worth_calculation()
    {
        // Create incomes
        Income::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 2000.00,
            'date' => now(),
        ]);

        // Create expenses
        Expense::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 1000.00,
            'date' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/insights/net-worth');

        $response->assertStatus(200);
        $response->assertViewHas('netWorth', 1000.00);
    }

    public function test_goal_progress_summary()
    {
        // Create goals with different progress
        FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
            'target_amount' => 1000.00,
            'current_amount' => 500.00,
        ]);

        FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
            'target_amount' => 2000.00,
            'current_amount' => 2000.00,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/insights/goals/progress');

        $response->assertStatus(200);
        $response->assertViewHas('goals', function ($goals) {
            return $goals->count() === 2 &&
                   $goals->contains(fn($goal) => $goal->progress_percentage === 50) &&
                   $goals->contains(fn($goal) => $goal->progress_percentage === 100);
        });
    }

    public function test_user_cannot_access_other_users_insights()
    {
        $otherUser = User::factory()->create();
        
        // Create data for other user
        Expense::factory()->create([
            'user_id' => $otherUser->id,
            'amount' => 100.00,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/insights/expenses/monthly');

        $response->assertStatus(200);
        $response->assertViewHas('monthlyTotal', 0.00);
    }

    public function test_date_range_filtering()
    {
        // Create expenses in different date ranges
        Expense::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 100.00,
            'date' => now()->subDays(5),
        ]);

        Expense::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 200.00,
            'date' => now()->subDays(15),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/insights/expenses?start_date=' . now()->subDays(10)->format('Y-m-d') . 
                  '&end_date=' . now()->format('Y-m-d'));

        $response->assertStatus(200);
        $response->assertViewHas('total', 100.00);
    }
} 