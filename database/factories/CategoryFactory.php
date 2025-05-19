<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'type' => $this->faker->randomElement(['income', 'expense']),
            'user_id' => User::factory(),
        ];
    }

    public function expense(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'expense',
            ];
        });
    }

    public function income(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'income',
            ];
        });
    }
} 