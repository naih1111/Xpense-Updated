<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinancialCalculationsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_calculate_total_income()
    {
        // Create multiple income records
        $this->user->incomes()->createMany([
            [
                'amount' => 1000.00,
                'description' => 'Salary',
                'date' => now(),
            ],
            [
                'amount' => 500.00,
                'description' => 'Freelance',
                'date' => now(),
            ],
        ]);

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        $totalIncome = $this->user->getTotalIncome($startDate, $endDate);
        
        $this->assertEquals(1500.00, $totalIncome);
    }

    public function test_can_calculate_total_expenses()
    {
        // Create multiple expense records
        $this->user->expenses()->createMany([
            [
                'amount' => 300.00,
                'description' => 'Groceries',
                'type' => 'expense',
                'date' => now(),
            ],
            [
                'amount' => 700.00,
                'description' => 'Rent',
                'type' => 'expense',
                'date' => now(),
            ],
        ]);

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        $totalExpenses = $this->user->getTotalExpenses($startDate, $endDate);
        
        $this->assertEquals(1000.00, $totalExpenses);
    }

    public function test_can_calculate_net_savings()
    {
        // Create income
        $this->user->incomes()->create([
            'amount' => 2000.00,
            'description' => 'Salary',
            'date' => now(),
        ]);

        // Create expenses
        $this->user->expenses()->create([
            'amount' => 1200.00,
            'description' => 'Rent',
            'type' => 'expense',
            'date' => now(),
        ]);

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        $netSavings = $this->user->getNetSavings($startDate, $endDate);
        
        $this->assertEquals(800.00, $netSavings);
    }

    public function test_negative_net_savings_when_expenses_exceed_income()
    {
        // Create income
        $this->user->incomes()->create([
            'amount' => 1000.00,
            'description' => 'Salary',
            'date' => now(),
        ]);

        // Create expenses that exceed income
        $this->user->expenses()->create([
            'amount' => 1500.00,
            'description' => 'Emergency expense',
            'type' => 'expense',
            'date' => now(),
        ]);

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        $netSavings = $this->user->getNetSavings($startDate, $endDate);
        
        $this->assertEquals(-500.00, $netSavings);
    }

    public function test_calculations_respect_date_range()
    {
        // Create income in current month
        $this->user->incomes()->create([
            'amount' => 1000.00,
            'description' => 'Current month salary',
            'date' => now(),
        ]);

        // Create income in next month
        $this->user->incomes()->create([
            'amount' => 1000.00,
            'description' => 'Next month salary',
            'date' => now()->addMonth(),
        ]);

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        $totalIncome = $this->user->getTotalIncome($startDate, $endDate);
        
        // Should only include current month's income
        $this->assertEquals(1000.00, $totalIncome);
    }
} 