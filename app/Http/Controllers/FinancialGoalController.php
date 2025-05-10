<?php

namespace App\Http\Controllers;

use App\Models\FinancialGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialGoalController extends Controller
{
    public function index()
    {
        $financialGoals = auth()->user()->financialGoals()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('financial-goals.index', compact('financialGoals'));
    }

    public function create()
    {
        return view('financial-goals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'required|numeric|min:0',
            'target_date' => 'required|date|after:today',
        ]);

        $financialGoal = auth()->user()->financialGoals()->create($validated);

        return redirect()->route('financial-goals.index')
            ->with('success', 'Financial goal created successfully.');
    }

    public function edit(FinancialGoal $financialGoal)
    {
        $this->authorize('update', $financialGoal);
        return view('financial-goals.edit', compact('financialGoal'));
    }

    public function update(Request $request, FinancialGoal $financialGoal)
    {
        $this->authorize('update', $financialGoal);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'required|numeric|min:0',
            'target_date' => 'required|date|after:today',
        ]);

        $financialGoal->update($validated);

        return redirect()->route('financial-goals.index')
            ->with('success', 'Financial goal updated successfully.');
    }

    public function destroy(FinancialGoal $financialGoal)
    {
        $this->authorize('delete', $financialGoal);
        
        $financialGoal->delete();

        return redirect()->route('financial-goals.index')
            ->with('success', 'Financial goal deleted successfully.');
    }

    public function updateProgress(Request $request, FinancialGoal $goal)
    {
        $this->authorize('update', $goal);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $goal->current_amount += $validated['amount'];
        $goal->updateStatus();
        $goal->save();

        return redirect()->route('financial-goals.index')
            ->with('success', 'Goal progress updated successfully.');
    }
}
