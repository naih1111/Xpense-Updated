<?php

namespace Tests\Feature\Income;

use App\Models\User;
use App\Models\Income;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncomeManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_incomes_page_can_be_rendered()
    {
        $response = $this->actingAs($this->user)
            ->get('/incomes');

        $response->assertStatus(200);
    }

    public function test_user_can_create_income()
    {
        $incomeData = [
            'amount' => 2000.00,
            'description' => 'Monthly Salary',
            'category' => 'Salary',
            'date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)
            ->post('/incomes', $incomeData);

        $response->assertRedirect('/incomes');
        $this->assertDatabaseHas('incomes', [
            'user_id' => $this->user->id,
            'amount' => 2000.00,
            'description' => 'Monthly Salary',
            'category' => 'Salary',
        ]);
    }

    public function test_user_can_update_income()
    {
        $income = Income::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $updateData = [
            'amount' => 2500.00,
            'description' => 'Updated Salary',
            'category' => 'Bonus',
            'date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)
            ->put("/incomes/{$income->id}", $updateData);

        $response->assertRedirect('/incomes');
        $this->assertDatabaseHas('incomes', [
            'id' => $income->id,
            'amount' => 2500.00,
            'description' => 'Updated Salary',
            'category' => 'Bonus',
        ]);
    }

    public function test_user_can_delete_income()
    {
        $income = Income::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/incomes/{$income->id}");

        $response->assertRedirect('/incomes');
        $this->assertDatabaseMissing('incomes', [
            'id' => $income->id,
        ]);
    }

    public function test_user_cannot_access_other_users_incomes()
    {
        $otherUser = User::factory()->create();
        $income = Income::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/incomes/{$income->id}");

        $response->assertStatus(403);
    }

    public function test_income_validation()
    {
        $response = $this->actingAs($this->user)
            ->post('/incomes', [
                'amount' => 'invalid',
                'description' => '',
                'category' => '',
                'date' => 'invalid-date',
            ]);

        $response->assertSessionHasErrors(['amount', 'description', 'category', 'date']);
    }

    public function test_income_listing_shows_only_user_incomes()
    {
        // Create incomes for the current user
        Income::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        // Create incomes for another user
        $otherUser = User::factory()->create();
        Income::factory()->count(2)->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/incomes');

        $response->assertStatus(200);
        $response->assertViewHas('incomes', function ($incomes) {
            return $incomes->count() === 3 && 
                   $incomes->every(fn($income) => $income->user_id === $this->user->id);
        });
    }

    public function test_income_categories_are_limited_to_predefined_list()
    {
        $response = $this->actingAs($this->user)
            ->post('/incomes', [
                'amount' => 1000.00,
                'description' => 'Test Income',
                'category' => 'Invalid Category',
                'date' => now()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors(['category']);
    }
} 