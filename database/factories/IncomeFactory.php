<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'amount' => $this->faker->randomFloat(2, 1000, 10000),
            'description' => $this->faker->sentence,
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
} 