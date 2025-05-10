<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_expenses()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $this->assertTrue($user->expenses->contains($expense));
    }

    public function test_user_can_have_incomes()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $income = Income::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $this->assertTrue($user->incomes->contains($income));
    }

    public function test_user_can_calculate_total_expenses()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        
        Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 100
        ]);

        Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 200
        ]);

        $this->assertEquals(300, $user->getTotalExpenses());
    }

    public function test_user_can_calculate_net_savings()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        
        Income::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 1000
        ]);

        Expense::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'amount' => 600
        ]);

        $this->assertEquals(400, $user->getNetSavings());
    }
} 