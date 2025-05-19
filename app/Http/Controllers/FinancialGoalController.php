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

        $user = auth()->user();

        // First, create the financial goal
        $financialGoal = $user->financialGoals()->create($validated);

        // Then, create an expense record for the initial amount
        if ($validated['current_amount'] > 0) {
            $user->expenses()->create([
                'amount' => $validated['current_amount'],
                'description' => "Initial allocation for financial goal: {$financialGoal->name}",
                'date' => now(),
                'type' => 'saving',
            ]);
        }

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
        
        // If there's a current amount in the goal, subtract it from expenses
        if ($financialGoal->current_amount > 0) {
            $user = auth()->user();

            // Find and delete all expense records related to this financial goal
            $user->expenses()
                ->where('type', 'saving')
                ->where('description', 'like', "%{$financialGoal->name}%")
                ->delete();
        }
        
        $financialGoal->delete();

        return redirect()->route('financial-goals.index')
            ->with('success', 'Financial goal deleted successfully. The allocated funds have been removed from expenses.');
    }

    public function updateProgress(Request $request, FinancialGoal $goal)
    {
        $this->authorize('update', $goal);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $previousAmount = $goal->current_amount;
        $goal->current_amount += $validated['amount'];
        $goal->updateStatus();
        $goal->save();

        // Create an expense record for the additional amount
        if ($validated['amount'] > 0) {
            auth()->user()->expenses()->create([
                'amount' => $validated['amount'],
                'description' => "Additional allocation for financial goal: {$goal->name}",
                'date' => now(),
                'type' => 'saving',
            ]);
        }

        return redirect()->route('financial-goals.index')
            ->with('success', 'Goal progress updated successfully.');
    }
}
