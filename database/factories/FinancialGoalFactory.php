<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancialGoalFactory extends Factory
{
    public function definition(): array
    {
        $targetAmount = $this->faker->randomFloat(2, 1000, 50000);
        $currentAmount = $this->faker->randomFloat(2, 0, $targetAmount);
        
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'target_amount' => $targetAmount,
            'current_amount' => $currentAmount,
            'target_date' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'status' => 'in_progress',
        ];
    }
} 