<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Auth::user()->expenses()
            ->with('category')
            ->latest()
            ->paginate(10);
            
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = Auth::user()->categories()
            ->where('type', 'expense')
            ->get();

        if ($categories->isEmpty()) {
            $defaultCategories = [
                ['name' => 'Food & Dining', 'type' => 'expense'],
                ['name' => 'Transportation', 'type' => 'expense'],
                ['name' => 'Housing', 'type' => 'expense'],
                ['name' => 'Utilities', 'type' => 'expense'],
                ['name' => 'Entertainment', 'type' => 'expense'],
            ];

            foreach ($defaultCategories as $category) {
                Auth::user()->categories()->create($category);
            }

            $categories = Auth::user()->categories()
                ->where('type', 'expense')
                ->get();
        }

        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:need,want,saving',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $expense = Auth::user()->expenses()->create($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Expense created successfully.');
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        
        $categories = Auth::user()->categories()
            ->where('type', 'expense')
            ->get();
            
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:need,want,saving',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
