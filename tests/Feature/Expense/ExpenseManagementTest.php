<?php

namespace Tests\Feature\Expense;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_expenses_page_can_be_rendered()
    {
        $response = $this->actingAs($this->user)
            ->get('/expenses');

        $response->assertStatus(200);
    }

    public function test_user_can_create_expense()
    {
        $expenseData = [
            'amount' => 100.50,
            'description' => 'Test Expense',
            'category' => 'Food',
            'date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)
            ->post('/expenses', $expenseData);

        $response->assertRedirect('/expenses');
        $this->assertDatabaseHas('expenses', [
            'user_id' => $this->user->id,
            'amount' => 100.50,
            'description' => 'Test Expense',
            'category' => 'Food',
        ]);
    }

    public function test_user_can_update_expense()
    {
        $expense = Expense::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $updateData = [
            'amount' => 150.75,
            'description' => 'Updated Expense',
            'category' => 'Transport',
            'date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)
            ->put("/expenses/{$expense->id}", $updateData);

        $response->assertRedirect('/expenses');
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'amount' => 150.75,
            'description' => 'Updated Expense',
            'category' => 'Transport',
        ]);
    }

    public function test_user_can_delete_expense()
    {
        $expense = Expense::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/expenses/{$expense->id}");

        $response->assertRedirect('/expenses');
        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
        ]);
    }

    public function test_user_cannot_access_other_users_expenses()
    {
        $otherUser = User::factory()->create();
        $expense = Expense::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/expenses/{$expense->id}");

        $response->assertStatus(403);
    }

    public function test_expense_validation()
    {
        $response = $this->actingAs($this->user)
            ->post('/expenses', [
                'amount' => 'invalid',
                'description' => '',
                'category' => '',
                'date' => 'invalid-date',
            ]);

        $response->assertSessionHasErrors(['amount', 'description', 'category', 'date']);
    }

    public function test_expense_listing_shows_only_user_expenses()
    {
        // Create expenses for the current user
        Expense::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        // Create expenses for another user
        $otherUser = User::factory()->create();
        Expense::factory()->count(2)->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/expenses');

        $response->assertStatus(200);
        $response->assertViewHas('expenses', function ($expenses) {
            return $expenses->count() === 3 && 
                   $expenses->every(fn($expense) => $expense->user_id === $this->user->id);
        });
    }
} 