<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Auth::user()->budgets()
            ->with('category')
            ->get()
            ->map(function ($budget) {
                return [
                    'id' => $budget->id,
                    'category' => $budget->category->name,
                    'amount' => $budget->amount,
                    'period' => $budget->period,
                    'start_date' => $budget->start_date,
                    'end_date' => $budget->end_date,
                    'spent' => $budget->getSpentAmount(),
                    'remaining' => $budget->getRemainingAmount(),
                    'percentage' => $budget->getSpentPercentage(),
                ];
            });
            
        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        $categories = Auth::user()->categories()
            ->where('type', 'expense')
            ->get();
            
        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:monthly,weekly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $budget = Auth::user()->budgets()->create($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Budget created successfully.');
    }

    public function edit(Budget $budget)
    {
        $this->authorize('update', $budget);
        
        $categories = Auth::user()->categories()
            ->where('type', 'expense')
            ->get();
            
        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget)
    {
        $this->authorize('update', $budget);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:monthly,weekly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $budget->update($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);

        $budget->delete();

        return redirect()->route('budgets.index')
            ->with('success', 'Budget deleted successfully.');
    }
}
