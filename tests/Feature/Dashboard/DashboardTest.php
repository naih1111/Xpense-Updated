<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use App\Models\Expense;
use App\Models\Income;
use App\Models\FinancialGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_dashboard_page_can_be_rendered()
    {
        $response = $this->actingAs($this->user)
            ->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_dashboard_shows_recent_transactions()
    {
        // Create recent expenses
        Expense::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'date' => now(),
        ]);

        // Create recent incomes
        Income::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'date' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('recentTransactions', function ($transactions) {
            return $transactions->count() === 5;
        });
    }

    public function test_dashboard_shows_active_goals()
    {
        // Create active goals
        FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
            'target_amount' => 1000.00,
            'current_amount' => 500.00,
            'target_date' => now()->addMonths(3),
        ]);

        FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
            'target_amount' => 2000.00,
            'current_amount' => 1500.00,
            'target_date' => now()->addMonths(6),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('activeGoals', function ($goals) {
            return $goals->count() === 2 &&
                   $goals->every(fn($goal) => $goal->current_amount < $goal->target_amount);
        });
    }

    public function test_dashboard_shows_monthly_summary()
    {
        // Create current month's transactions
        Expense::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 500.00,
            'date' => now(),
        ]);

        Income::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 2000.00,
            'date' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('monthlySummary', function ($summary) {
            return $summary['totalIncome'] === 2000.00 &&
                   $summary['totalExpenses'] === 500.00 &&
                   $summary['netAmount'] === 1500.00;
        });
    }

    public function test_dashboard_shows_category_distribution()
    {
        // Create expenses in different categories
        Expense::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 300.00,
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
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('categoryDistribution', function ($distribution) {
            return $distribution->has('Food') &&
                   $distribution->has('Transport') &&
                   $distribution['Food'] === 300.00 &&
                   $distribution['Transport'] === 200.00;
        });
    }

    public function test_dashboard_shows_upcoming_goals()
    {
        // Create upcoming goals
        FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
            'target_date' => now()->addDays(5),
        ]);

        FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
            'target_date' => now()->addDays(10),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('upcomingGoals', function ($goals) {
            return $goals->count() === 2 &&
                   $goals->every(fn($goal) => $goal->target_date > now());
        });
    }

    public function test_dashboard_shows_savings_rate()
    {
        // Create monthly income and expenses
        Income::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 3000.00,
            'date' => now(),
        ]);

        Expense::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 2000.00,
            'date' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('savingsRate', 33.33); // (3000 - 2000) / 3000 * 100
    }

    public function test_dashboard_shows_budget_status()
    {
        // Create monthly budget and expenses
        Expense::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 800.00,
            'category' => 'Food',
            'date' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('budgetStatus', function ($status) {
            return $status->has('Food') &&
                   $status['Food']['spent'] === 800.00;
        });
    }

    public function test_dashboard_shows_only_user_data()
    {
        $otherUser = User::factory()->create();

        // Create data for other user
        Expense::factory()->create([
            'user_id' => $otherUser->id,
            'amount' => 100.00,
        ]);

        // Create data for current user
        Expense::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 200.00,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('monthlySummary', function ($summary) {
            return $summary['totalExpenses'] === 200.00;
        });
    }
} 