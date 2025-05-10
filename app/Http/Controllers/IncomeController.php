<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Auth::user()->incomes()
            ->latest()
            ->paginate(10);
            
        return view('incomes.index', compact('incomes'));
    }

    public function create()
    {
        return view('income.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:0',
                'description' => 'required|string|max:255',
                'date' => 'required|date',
            ]);

            Auth::user()->incomes()->create($validated);

            return redirect()->route('incomes.index')
                ->with('success', 'Income added successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to add income. Please try again.']);
        }
    }

    public function show(Income $income)
    {
        $this->authorize('view', $income);
        return view('incomes.show', compact('income'));
    }

    public function edit(Income $income)
    {
        $this->authorize('update', $income);
        
        $categories = Auth::user()->categories()
            ->where('type', 'income')
            ->get();
            
        return view('incomes.edit', compact('income', 'categories'));
    }

    public function update(Request $request, Income $income)
    {
        $this->authorize('update', $income);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $income->update($validated);

        return redirect()->route('incomes.index')
            ->with('success', 'Income updated successfully.');
    }

    public function destroy(Income $income)
    {
        $this->authorize('delete', $income);

        $income->delete();

        return redirect()->route('incomes.index')
            ->with('success', 'Income deleted successfully.');
    }
}
