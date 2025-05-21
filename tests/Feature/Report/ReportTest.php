<?php

namespace Tests\Feature\Report;

use App\Models\Expense;
use App\Models\Goal;
use App\Models\Income;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $otherUser;
    protected $testDate;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();
        
        // Set a fixed test date (15th of current month)
        $this->testDate = now()->startOfMonth()->addDays(14);
        
        // Create test categories
        $foodCategory = Category::create([
            'name' => 'Food',
            'type' => 'expense',
            'user_id' => $this->user->id
        ]);

        $transportCategory = Category::create([
            'name' => 'Transport',
            'type' => 'expense',
            'user_id' => $this->user->id
        ]);

        $salaryCategory = Category::create([
            'name' => 'Salary',
            'type' => 'income',
            'user_id' => $this->user->id
        ]);

        $bonusCategory = Category::create([
            'name' => 'Bonus',
            'type' => 'income',
            'user_id' => $this->user->id
        ]);
        
        // Create test expenses for the first user
        Expense::create([
            'user_id' => $this->user->id,
            'type' => 'need',
            'amount' => 100.00,
            'description' => 'Test expense 1',
            'date' => $this->testDate->format('Y-m-d'),
            'category_id' => $foodCategory->id
        ]);
        
        Expense::create([
            'user_id' => $this->user->id,
            'type' => 'want',
            'amount' => 50.00,
            'description' => 'Test expense 2',
            'date' => $this->testDate->format('Y-m-d'),
            'category_id' => $transportCategory->id
        ]);
        
        // Create test incomes for the first user
        Income::create([
            'user_id' => $this->user->id,
            'amount' => 1000.00,
            'description' => 'Test income 1',
            'date' => $this->testDate->format('Y-m-d'),
            'category_id' => $salaryCategory->id
        ]);
        
        Income::create([
            'user_id' => $this->user->id,
            'amount' => 500.00,
            'description' => 'Test income 2',
            'date' => $this->testDate->format('Y-m-d'),
            'category_id' => $bonusCategory->id
        ]);
        
        // Create test goals for the first user
        Goal::create([
            'user_id' => $this->user->id,
            'title' => 'Test Goal 1',
            'description' => 'Test goal description 1',
            'target_amount' => 1000.00,
            'current_amount' => 1000.00,
            'status' => 'completed',
            'target_date' => $this->testDate->format('Y-m-d')
        ]);
        
        Goal::create([
            'user_id' => $this->user->id,
            'title' => 'Test Goal 2',
            'description' => 'Test goal description 2',
            'target_amount' => 2000.00,
            'current_amount' => 1000.00,
            'status' => 'active',
            'target_date' => $this->testDate->addMonth()->format('Y-m-d')
        ]);
    }

    public function test_reports_page_can_be_rendered()
    {
        $response = $this->actingAs($this->user)
            ->get('/reports');

        $response->assertStatus(200);
    }

    public function test_monthly_expense_report()
    {
        $response = $this->actingAs($this->user)
            ->get('/reports/expenses/monthly');

        $response->assertStatus(200);
        $response->assertViewHas('report', function ($report) {
            return $report['total'] === 150.00 &&
                   $report['categories']['Food'] === 100.00 &&
                   $report['categories']['Transport'] === 50.00;
        });
    }

    public function test_monthly_income_report()
    {
        $response = $this->actingAs($this->user)
            ->get('/reports/incomes/monthly');

        $response->assertStatus(200);
        $response->assertViewHas('report', function ($report) {
            return $report['total'] === 1500.00 &&
                   $report['categories']['Salary'] === 1000.00 &&
                   $report['categories']['Bonus'] === 500.00;
        });
    }

    public function test_goal_progress_report()
    {
        $response = $this->actingAs($this->user)
            ->get('/reports/goals');

        $response->assertStatus(200);
        $response->assertViewHas('goals', function ($goals) {
            return count($goals) === 2 &&
                   $goals[0]['title'] === 'Test Goal 1' &&
                   $goals[0]['status'] === 'completed' &&
                   $goals[1]['title'] === 'Test Goal 2' &&
                   $goals[1]['status'] === 'active';
        });
    }

    public function test_custom_date_range_report()
    {
        $response = $this->actingAs($this->user)
            ->get('/reports/expenses/custom?' . http_build_query([
                'start_date' => $this->testDate->format('Y-m-d'),
                'end_date' => $this->testDate->format('Y-m-d')
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('report', function ($report) {
            return $report['total'] === 150.00 &&
                   $report['categories']['Food'] === 100.00 &&
                   $report['categories']['Transport'] === 50.00;
        });
    }

    public function test_export_report_to_pdf()
    {
        $response = $this->actingAs($this->user)
            ->get('/reports/expenses/export/pdf?' . http_build_query([
                'start_date' => $this->testDate->format('Y-m-d'),
                'end_date' => $this->testDate->format('Y-m-d')
            ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_export_report_to_excel()
    {
        $response = $this->actingAs($this->user)
            ->get('/reports/expenses/export/excel?' . http_build_query([
                'start_date' => $this->testDate->format('Y-m-d'),
                'end_date' => $this->testDate->format('Y-m-d')
            ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_user_cannot_access_other_users_data()
    {
        $response = $this->actingAs($this->otherUser)
            ->get('/reports/expenses/monthly');

        $response->assertStatus(200);
        $response->assertViewHas('report', function ($report) {
            return $report['total'] === 0.00 && empty($report['categories']);
        });
    }

    public function test_report_with_no_data()
    {
        $response = $this->actingAs($this->otherUser)
            ->get('/reports/expenses/monthly');

        $response->assertStatus(200);
        $response->assertViewHas('report', function ($report) {
            return $report['total'] === 0.00 && empty($report['categories']);
        });
    }

    public function test_report_with_invalid_date_range()
    {
        $response = $this->actingAs($this->user)
            ->get('/reports/expenses/custom?' . http_build_query([
                'start_date' => 'invalid-date',
                'end_date' => 'invalid-date'
            ]));

        $response->assertStatus(422);
    }

    public function test_report_with_future_dates()
    {
        $futureDate = now()->addYear();
        
        $response = $this->actingAs($this->user)
            ->get('/reports/expenses/custom?' . http_build_query([
                'start_date' => $futureDate->format('Y-m-d'),
                'end_date' => $futureDate->format('Y-m-d')
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('report', function ($report) {
            return $report['total'] === 0.00 && empty($report['categories']);
        });
    }
} 