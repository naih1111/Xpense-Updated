<?php

namespace Tests\Feature\Budget;

use App\Models\User;
use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_budget_page_can_be_rendered()
    {
        $response = $this->actingAs($this->user)
            ->get('/budgets');

        $response->assertStatus(200);
    }

    public function test_user_can_create_budget()
    {
        $budgetData = [
            'category' => 'Food',
            'amount' => 500.00,
            'period' => 'monthly',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonth()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)
            ->post('/budgets', $budgetData);

        $response->assertRedirect('/budgets');
        $this->assertDatabaseHas('budgets', [
            'user_id' => $this->user->id,
            'category' => 'Food',
            'amount' => 500.00,
            'period' => 'monthly',
        ]);
    }

    public function test_user_can_update_budget()
    {
        $budget = Budget::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $updateData = [
            'category' => 'Transport',
            'amount' => 300.00,
            'period' => 'monthly',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonth()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)
            ->put("/budgets/{$budget->id}", $updateData);

        $response->assertRedirect('/budgets');
        $this->assertDatabaseHas('budgets', [
            'id' => $budget->id,
            'category' => 'Transport',
            'amount' => 300.00,
        ]);
    }

    public function test_user_can_delete_budget()
    {
        $budget = Budget::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/budgets/{$budget->id}");

        $response->assertRedirect('/budgets');
        $this->assertDatabaseMissing('budgets', [
            'id' => $budget->id,
        ]);
    }

    public function test_user_cannot_access_other_users_budgets()
    {
        $otherUser = User::factory()->create();
        $budget = Budget::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/budgets/{$budget->id}");

        $response->assertStatus(403);
    }

    public function test_budget_validation()
    {
        $response = $this->actingAs($this->user)
            ->post('/budgets', [
                'category' => '',
                'amount' => 'invalid',
                'period' => 'invalid',
                'start_date' => 'invalid-date',
                'end_date' => 'invalid-date',
            ]);

        $response->assertSessionHasErrors([
            'category',
            'amount',
            'period',
            'start_date',
            'end_date',
        ]);
    }

    public function test_budget_progress_calculation()
    {
        // Create a budget
        $budget = Budget::factory()->create([
            'user_id' => $this->user->id,
            'category' => 'Food',
            'amount' => 1000.00,
            'period' => 'monthly',
        ]);

        // Create expenses in the same category
        Expense::factory()->create([
            'user_id' => $this->user->id,
            'category' => 'Food',
            'amount' => 300.00,
            'date' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get("/budgets/{$budget->id}");

        $response->assertStatus(200);
        $response->assertViewHas('budget', function ($viewBudget) {
            return $viewBudget->spent_amount === 300.00 &&
                   $viewBudget->remaining_amount === 700.00 &&
                   $viewBudget->progress_percentage === 30;
        });
    }

    public function test_budget_categories_are_limited_to_predefined_list()
    {
        $response = $this->actingAs($this->user)
            ->post('/budgets', [
                'category' => 'Invalid Category',
                'amount' => 1000.00,
                'period' => 'monthly',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addMonth()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors(['category']);
    }

    public function test_budget_periods_are_limited_to_predefined_list()
    {
        $response = $this->actingAs($this->user)
            ->post('/budgets', [
                'category' => 'Food',
                'amount' => 1000.00,
                'period' => 'invalid-period',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addMonth()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors(['period']);
    }

    public function test_budget_end_date_must_be_after_start_date()
    {
        $response = $this->actingAs($this->user)
            ->post('/budgets', [
                'category' => 'Food',
                'amount' => 1000.00,
                'period' => 'monthly',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->subDay()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors(['end_date']);
    }

    public function test_budget_listing_shows_only_user_budgets()
    {
        // Create budgets for the current user
        Budget::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        // Create budgets for another user
        $otherUser = User::factory()->create();
        Budget::factory()->count(2)->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/budgets');

        $response->assertStatus(200);
        $response->assertViewHas('budgets', function ($budgets) {
            return $budgets->count() === 3 && 
                   $budgets->every(fn($budget) => $budget->user_id === $this->user->id);
        });
    }
} 