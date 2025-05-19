<?php

namespace Database\Factories;

use App\Models\FinancialGoal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancialGoalFactory extends Factory
{
    protected $model = FinancialGoal::class;

    public function definition(): array
    {
        $targetAmount = $this->faker->randomFloat(2, 1000, 10000);
        
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'target_amount' => $targetAmount,
            'current_amount' => $this->faker->randomFloat(2, 0, $targetAmount),
            'target_date' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'status' => 'in_progress',
            'user_id' => User::factory(),
        ];
    }

    public function completed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'current_amount' => $attributes['target_amount'],
                'status' => 'completed',
            ];
        });
    }

    public function failed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'target_date' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
                'current_amount' => $this->faker->randomFloat(2, 0, $attributes['target_amount'] - 0.01),
                'status' => 'failed',
            ];
        });
    }
} 