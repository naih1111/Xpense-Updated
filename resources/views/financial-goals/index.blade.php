@extends('layouts.app')

@section('content')
<div class="insights-container">
    <div class="header-section">
        <h1 class="text-2xl font-bold text-gray-800">Financial Goals</h1>
        <a href="{{ route('financial-goals.create') }}" class="action-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add Goal
        </a>
    </div>

    @if($financialGoals->isEmpty())
        <div class="empty-state">
            <p class="text-gray-500 mb-4">No financial goals found.</p>
            <a href="{{ route('financial-goals.create') }}" class="action-btn">
                Set Your First Goal
            </a>
        </div>
    @else
        <div class="goals-grid">
            @foreach($financialGoals as $goal)
                <div class="goal-card">
                    <div class="goal-header">
                        <h3 class="goal-title">{{ $goal->name }}</h3>
                        <div class="goal-actions">
                            <a href="{{ route('financial-goals.edit', $goal) }}" class="edit-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                            <form action="{{ route('financial-goals.destroy', $goal) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this goal?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <p class="goal-description">{{ $goal->description }}</p>
                    <div class="goal-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ ($goal->current_amount / $goal->target_amount) * 100 }}%"></div>
                        </div>
                        <div class="progress-stats">
                            <span class="current-amount">₱{{ number_format($goal->current_amount, 2) }}</span>
                            <span class="target-amount">of ₱{{ number_format($goal->target_amount, 2) }}</span>
                        </div>
                    </div>
                    <div class="goal-footer">
                        <span class="goal-date">Target: {{ $goal->target_date->format('M d, Y') }}</span>
                        <span class="goal-status {{ $goal->status === 'completed' ? 'status-completed' : 'status-in-progress' }}">
                            {{ ucfirst($goal->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('styles')
<style>
    .insights-container {
        padding: 2rem;
        background-color: #e6eaf5;
        min-height: calc(100vh - 64px);
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .header-section h1 {
        color: #0a0a23;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background-color: #4f5ebd;
        color: white;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .action-btn:hover {
        background-color: #7e74f1;
    }

    .empty-state {
        background-color: white;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .goals-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .goal-card {
        background-color: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .goal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .goal-title {
        color: #0a0a23;
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }

    .goal-actions {
        display: flex;
        gap: 0.75rem;
    }

    .edit-btn {
        color: #4f5ebd;
        transition: color 0.2s;
    }

    .edit-btn:hover {
        color: #7e74f1;
    }

    .delete-btn {
        color: #ef4444;
        transition: color 0.2s;
    }

    .delete-btn:hover {
        color: #dc2626;
    }

    .goal-description {
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
    }

    .goal-progress {
        margin-bottom: 1.5rem;
    }

    .progress-bar {
        height: 0.5rem;
        background-color: #e2e8f0;
        border-radius: 9999px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .progress-fill {
        height: 100%;
        background-color: #4f5ebd;
        border-radius: 9999px;
        transition: width 0.3s ease;
    }

    .progress-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.875rem;
    }

    .current-amount {
        color: #0a0a23;
        font-weight: 600;
    }

    .target-amount {
        color: #64748b;
    }

    .goal-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
    }

    .goal-date {
        color: #64748b;
    }

    .goal-status {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-weight: 500;
    }

    .status-completed {
        background-color: #dcfce7;
        color: #16a34a;
    }

    .status-in-progress {
        background-color: #e0e7ff;
        color: #4f5ebd;
    }
</style>
@endpush
@endsection 