<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_create_category()
    {
        $categoryData = [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'type' => 'expense',
        ];

        $category = Category::create($categoryData);

        $this->assertDatabaseHas('categories', [
            'name' => $categoryData['name'],
            'type' => $categoryData['type'],
        ]);
    }

    public function test_can_assign_category_to_expense()
    {
        // Create a category
        $category = Category::create([
            'name' => 'Food & Dining',
            'description' => 'Food and dining expenses',
            'type' => 'expense',
        ]);

        // Create an expense with the category
        $expense = $this->user->expenses()->create([
            'amount' => 50.00,
            'description' => 'Lunch',
            'type' => 'expense',
            'category_id' => $category->id,
            'date' => now(),
        ]);

        $this->assertEquals($category->id, $expense->category_id);
        $this->assertEquals('Food & Dining', $expense->category->name);
    }

    public function test_can_get_expenses_by_category()
    {
        // Create a category
        $category = Category::create([
            'name' => 'Transportation',
            'description' => 'Transportation expenses',
            'type' => 'expense',
        ]);

        // Create multiple expenses in this category
        $this->user->expenses()->createMany([
            [
                'amount' => 30.00,
                'description' => 'Bus fare',
                'type' => 'expense',
                'category_id' => $category->id,
                'date' => now(),
            ],
            [
                'amount' => 50.00,
                'description' => 'Taxi fare',
                'type' => 'expense',
                'category_id' => $category->id,
                'date' => now(),
            ],
        ]);

        // Create an expense in a different category
        $otherCategory = Category::create([
            'name' => 'Other',
            'description' => 'Other expenses',
            'type' => 'expense',
        ]);

        $this->user->expenses()->create([
            'amount' => 100.00,
            'description' => 'Misc expense',
            'type' => 'expense',
            'category_id' => $otherCategory->id,
            'date' => now(),
        ]);

        // Get expenses for transportation category
        $transportationExpenses = $this->user->expenses()
            ->where('category_id', $category->id)
            ->get();

        $this->assertEquals(2, $transportationExpenses->count());
        $this->assertEquals(80.00, $transportationExpenses->sum('amount'));
    }

    public function test_expense_categories_are_separate_from_income_categories()
    {
        // Create expense category
        $expenseCategory = Category::create([
            'name' => 'Bills',
            'description' => 'Monthly bills',
            'type' => 'expense',
        ]);

        // Create income category
        $incomeCategory = Category::create([
            'name' => 'Salary',
            'description' => 'Monthly salary',
            'type' => 'income',
        ]);

        // Get only expense categories
        $expenseCategories = Category::where('type', 'expense')->get();
        
        // Get only income categories
        $incomeCategories = Category::where('type', 'income')->get();

        $this->assertTrue($expenseCategories->contains($expenseCategory));
        $this->assertFalse($expenseCategories->contains($incomeCategory));
        
        $this->assertTrue($incomeCategories->contains($incomeCategory));
        $this->assertFalse($incomeCategories->contains($expenseCategory));
    }

    public function test_category_validation()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        Category::create([
            'name' => '', // Empty name should fail validation
            'type' => 'invalid_type', // Invalid type should fail validation
        ]);
    }
} 