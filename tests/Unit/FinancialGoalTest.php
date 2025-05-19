<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\FinancialGoal;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinancialGoalTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create();
    }

    public function test_can_create_financial_goal()
    {
        $goalData = [
            'name' => 'Test Goal',
            'description' => 'Test Description',
            'target_amount' => 1000.00,
            'current_amount' => 500.00,
            'target_date' => now()->addMonths(3)->format('Y-m-d'),
        ];

        $goal = $this->user->financialGoals()->create($goalData);

        $this->assertDatabaseHas('financial_goals', [
            'id' => $goal->id,
            'user_id' => $this->user->id,
            'name' => $goalData['name'],
            'target_amount' => $goalData['target_amount'],
            'current_amount' => $goalData['current_amount'],
        ]);

        // Verify that an expense was created for the initial amount
        $this->assertDatabaseHas('expenses', [
            'user_id' => $this->user->id,
            'amount' => $goalData['current_amount'],
            'type' => 'saving',
        ]);
    }

    public function test_can_delete_financial_goal_and_remove_expense()
    {
        // Create a financial goal
        $goal = $this->user->financialGoals()->create([
            'name' => 'Test Goal',
            'description' => 'Test Description',
            'target_amount' => 1000.00,
            'current_amount' => 500.00,
            'target_date' => now()->addMonths(3),
        ]);

        // Create associated expense
        $expense = $this->user->expenses()->create([
            'amount' => 500.00,
            'description' => 'Test Goal: Test Description',
            'type' => 'saving',
            'date' => now(),
        ]);

        // Delete the goal
        $goal->delete();

        // Verify the goal was deleted
        $this->assertDatabaseMissing('financial_goals', ['id' => $goal->id]);

        // Verify the associated expense was deleted
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }

    public function test_can_update_financial_goal_amount()
    {
        $goal = $this->user->financialGoals()->create([
            'name' => 'Test Goal',
            'description' => 'Test Description',
            'target_amount' => 1000.00,
            'current_amount' => 500.00,
            'target_date' => now()->addMonths(3),
        ]);

        // Update the current amount
        $goal->update(['current_amount' => 750.00]);

        $this->assertDatabaseHas('financial_goals', [
            'id' => $goal->id,
            'current_amount' => 750.00,
        ]);

        // Verify expense was updated
        $this->assertDatabaseHas('expenses', [
            'user_id' => $this->user->id,
            'amount' => 750.00,
            'type' => 'saving',
        ]);
    }

    public function test_cannot_set_current_amount_greater_than_target()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->user->financialGoals()->create([
            'name' => 'Test Goal',
            'description' => 'Test Description',
            'target_amount' => 1000.00,
            'current_amount' => 1500.00, // Greater than target
            'target_date' => now()->addMonths(3),
        ]);
    }
} 