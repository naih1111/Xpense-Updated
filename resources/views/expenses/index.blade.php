@extends('layouts.app')

@section('content')
<div class="insights-container">
    <div class="header-section">
        <h1 class="text-2xl font-bold text-gray-800">Expenses</h1>
        <a href="{{ route('expenses.create') }}" class="action-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add Expense
        </a>
    </div>

    @if($expenses->isEmpty())
        <div class="empty-state">
            <p class="text-gray-500 mb-4">No expenses found.</p>
            <a href="{{ route('expenses.create') }}" class="action-btn">
                Add Your First Expense
            </a>
        </div>
    @else
        <div class="expenses-table">
            <div class="table-container">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                            <tr>
                                <td>{{ $expense->date->format('M d, Y') }}</td>
                                <td>
                                    <span class="category-badge">
                                        {{ $expense->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td>{{ $expense->description }}</td>
                                <td class="amount">₱{{ number_format($expense->amount, 2) }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('expenses.edit', $expense) }}" 
                                           class="edit-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="delete-btn"
                                                    onclick="return confirm('Are you sure you want to delete this expense?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                {{ $expenses->links() }}
            </div>
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

    .expenses-table {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background-color: #f8fafc;
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        color: #1e293b;
    }

    tr:hover {
        background-color: #f8fafc;
    }

    .category-badge {
        display: inline-flex;
        padding: 0.25rem 0.75rem;
        background-color: #e0e7ff;
        color: #4f5ebd;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .amount {
        font-weight: 600;
        color: #0a0a23;
    }

    .action-buttons {
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

    .pagination {
        padding: 1rem;
        border-top: 1px solid #e2e8f0;
    }
</style>
@endpush
@endsection 