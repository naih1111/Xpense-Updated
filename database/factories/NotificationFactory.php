<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'type' => $this->faker->randomElement(['goal_progress', 'goal_completed', 'goal_deadline', 'budget_limit']),
            'data' => json_encode(['progress_percentage' => $this->faker->numberBetween(0, 100), 'goal_id' => $this->faker->randomNumber(), 'days_remaining' => $this->faker->numberBetween(1, 30)]),
            'read_at' => null,
        ];
    }
}
