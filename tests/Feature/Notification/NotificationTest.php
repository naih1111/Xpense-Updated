<?php

namespace Tests\Feature\Notification;

use App\Models\User;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\FinancialGoal;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_notifications_page_can_be_rendered()
    {
        $response = $this->actingAs($this->user)
            ->get('/notifications');

        $response->assertStatus(200);
    }

    public function test_budget_limit_notification()
    {
        // Create a budget
        $budget = Budget::factory()->create([
            'user_id' => $this->user->id,
            'category' => 'Food',
            'amount' => 500.00,
            'period' => 'monthly',
        ]);

        // Create an expense that exceeds 80% of the budget
        Expense::factory()->create([
            'user_id' => $this->user->id,
            'category' => 'Food',
            'amount' => 450.00,
            'date' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/notifications');

        $response->assertStatus(200);
        $response->assertViewHas('notifications', function ($notifications) {
            return $notifications->contains(function ($notification) {
                return $notification->type === 'budget_alert' &&
                       $notification->data['category'] === 'Food' &&
                       $notification->data['percentage'] >= 80;
            });
        });
    }

    public function test_goal_progress_notification()
    {
        // Create a goal
        $goal = FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
            'target_amount' => 1000.00,
            'current_amount' => 900.00,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/notifications');

        $response->assertStatus(200);
        $response->assertViewHas('notifications', function ($notifications) {
            return $notifications->contains(function ($notification) {
                return $notification->type === 'goal_progress' &&
                       $notification->data['progress_percentage'] >= 90;
            });
        });
    }

    public function test_goal_completion_notification()
    {
        // Create a completed goal
        $goal = FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
            'target_amount' => 1000.00,
            'current_amount' => 1000.00,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/notifications');

        $response->assertStatus(200);
        $response->assertViewHas('notifications', function ($notifications) {
            return $notifications->contains(function ($notification) {
                return $notification->type === 'goal_completed' &&
                       $notification->data['goal_id'] === $goal->id;
            });
        });
    }

    public function test_upcoming_goal_deadline_notification()
    {
        // Create a goal with upcoming deadline
        $goal = FinancialGoal::factory()->create([
            'user_id' => $this->user->id,
            'target_date' => now()->addDays(5),
        ]);

        $response = $this->actingAs($this->user)
            ->get('/notifications');

        $response->assertStatus(200);
        $response->assertViewHas('notifications', function ($notifications) {
            return $notifications->contains(function ($notification) {
                return $notification->type === 'goal_deadline' &&
                       $notification->data['days_remaining'] <= 7;
            });
        });
    }

    public function test_user_can_mark_notification_as_read()
    {
        $notification = Notification::factory()->create([
            'user_id' => $this->user->id,
            'read_at' => null,
        ]);

        $response = $this->actingAs($this->user)
            ->patch("/notifications/{$notification->id}/read");

        $response->assertStatus(200);
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_mark_all_notifications_as_read()
    {
        // Create multiple unread notifications
        Notification::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'read_at' => null,
        ]);

        $response = $this->actingAs($this->user)
            ->patch('/notifications/read-all');

        $response->assertStatus(200);
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $this->user->id,
            'read_at' => null,
        ]);
    }

    public function test_user_can_delete_notification()
    {
        $notification = Notification::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/notifications/{$notification->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
        ]);
    }

    public function test_user_cannot_access_other_users_notifications()
    {
        $otherUser = User::factory()->create();
        $notification = Notification::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/notifications/{$notification->id}");

        $response->assertStatus(403);
    }

    public function test_notification_listing_shows_only_user_notifications()
    {
        // Create notifications for the current user
        Notification::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        // Create notifications for another user
        $otherUser = User::factory()->create();
        Notification::factory()->count(2)->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/notifications');

        $response->assertStatus(200);
        $response->assertViewHas('notifications', function ($notifications) {
            return $notifications->count() === 3 && 
                   $notifications->every(fn($notification) => $notification->user_id === $this->user->id);
        });
    }

    public function test_notification_count_updates()
    {
        // Create unread notifications
        Notification::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'read_at' => null,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/notifications');

        $response->assertStatus(200);
        $response->assertViewHas('unreadCount', 3);
    }
} 