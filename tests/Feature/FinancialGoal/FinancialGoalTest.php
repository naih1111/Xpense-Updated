<?php

namespace Tests\Feature\FinancialGoal;

use App\Models\User;
use App\Models\FinancialGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialGoalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_financial_goals_page_can_be_rendered()
    {
        $response = $this->actingAs($this->user)
            ->get('/financial-goals');

        $response->assertStatus(200);
    }

    public function test_user_can_create_financial_goal()
    {
        $goalData = [
            'title' => 'Save for Vacation',
            'target_amount' => 5000.00,
            'current_amount' => 0.00,
            'target_date' => now()->addMonths(6)->format('Y-m-d'),
            'category' => 'Travel',
            'description' => 'Save for summer vacation',
        ];

        $response = $this->actingAs($this->user)
            ->post('/financial-goals', $goalData);

        $response->assertRedirect('/financial-goals');
        $this->assertDatabaseHas('financial_goals', [
            'user_id' => $this->user->id,
            'title' => 'Save for Vacation',
            'target_amount' => 5000.00,
            'current_amount' => 0.00,
            'category' => 'Travel',
            'description' => 'Save for summer vacation',
        ]);
    }

    public function test_user_can_update_financial_goal()
    {
        $goal = FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $updateData = [
            'title' => 'Updated Goal',
            'target_amount' => 6000.00,
            'current_amount' => 2000.00,
            'target_date' => now()->addMonths(8)->format('Y-m-d'),
            'category' => 'Education',
            'description' => 'Updated description',
        ];

        $response = $this->actingAs($this->user)
            ->put("/financial-goals/{$goal->id}", $updateData);

        $response->assertRedirect('/financial-goals');
        $this->assertDatabaseHas('financial_goals', [
            'id' => $goal->id,
            'title' => 'Updated Goal',
            'target_amount' => 6000.00,
            'current_amount' => 2000.00,
            'category' => 'Education',
            'description' => 'Updated description',
        ]);
    }

    public function test_user_can_delete_financial_goal()
    {
        $goal = FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/financial-goals/{$goal->id}");

        $response->assertRedirect('/financial-goals');
        $this->assertDatabaseMissing('financial_goals', [
            'id' => $goal->id,
        ]);
    }

    public function test_user_cannot_access_other_users_goals()
    {
        $otherUser = User::factory()->create();
        $goal = FinancialGoal::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/financial-goals/{$goal->id}");

        $response->assertStatus(403);
    }

    public function test_financial_goal_validation()
    {
        $response = $this->actingAs($this->user)
            ->post('/financial-goals', [
                'title' => '',
                'target_amount' => 'invalid',
                'current_amount' => 'invalid',
                'target_date' => 'invalid-date',
                'category' => '',
                'description' => '',
            ]);

        $response->assertSessionHasErrors([
            'title',
            'target_amount',
            'current_amount',
            'target_date',
            'category',
        ]);
    }

    public function test_goal_progress_calculation()
    {
        $goal = FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
            'target_amount' => 1000.00,
            'current_amount' => 500.00,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/financial-goals/{$goal->id}");

        $response->assertStatus(200);
        $response->assertViewHas('goal', function ($viewGoal) {
            return $viewGoal->progress_percentage === 50;
        });
    }

    public function test_goal_categories_are_limited_to_predefined_list()
    {
        $response = $this->actingAs($this->user)
            ->post('/financial-goals', [
                'title' => 'Test Goal',
                'target_amount' => 1000.00,
                'current_amount' => 0.00,
                'target_date' => now()->addMonths(3)->format('Y-m-d'),
                'category' => 'Invalid Category',
                'description' => 'Test description',
            ]);

        $response->assertSessionHasErrors(['category']);
    }

    public function test_goal_completion_status()
    {
        $goal = FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
            'target_amount' => 1000.00,
            'current_amount' => 1000.00,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/financial-goals/{$goal->id}");

        $response->assertStatus(200);
        $response->assertViewHas('goal', function ($viewGoal) {
            return $viewGoal->is_completed === true;
        });
    }
} 