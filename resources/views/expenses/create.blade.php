@extends('layouts.app')

@section('content')
<div class="insights-container">
    <div class="header-section">
        <h1 class="text-2xl font-bold text-gray-800">Add New Expense</h1>
    </div>

    <div class="form-container">
        <form method="POST" action="{{ route('expenses.store') }}" class="space-y-6">
            @csrf

            <!-- Type Input -->
            <div class="form-group">
                <label for="type" class="form-label">Type</label>
                <div class="select-wrapper">
                    <select name="type" id="type" class="form-input" required>
                        <option value="">Select a type</option>
                        <option value="need" {{ old('type') == 'need' ? 'selected' : '' }}>Need</option>
                        <option value="want" {{ old('type') == 'want' ? 'selected' : '' }}>Want</option>
                    </select>
                </div>
                @error('type')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount Input -->
            <div class="form-group">
                <label for="amount" class="form-label">Amount</label>
                <div class="amount-input-wrapper">
                    <span class="currency-symbol">₱</span>
                    <input type="number" 
                        name="amount" 
                        id="amount" 
                        step="0.01" 
                        value="{{ old('amount') }}"
                        class="form-input" 
                        placeholder="0.00"
                        required>
                </div>
                @error('amount')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description Input -->
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <input type="text" 
                    name="description" 
                    id="description" 
                    value="{{ old('description') }}"
                    class="form-input" 
                    placeholder="Enter expense description"
                    required>
                @error('description')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Input -->
            <div class="form-group">
                <label for="date" class="form-label">Date</label>
                <input type="date" 
                    name="date" 
                    id="date" 
                    value="{{ old('date', date('Y-m-d')) }}"
                    class="form-input" 
                    required>
                @error('date')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('expenses.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    Add Expense
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .insights-container {
        padding: 2rem;
        background-color: #e6eaf5;
        min-height: calc(100vh - 64px);
    }

    .header-section {
        margin-bottom: 2rem;
    }

    .header-section h1 {
        color: #0a0a23;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .form-container {
        background-color: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #0a0a23;
        font-weight: 500;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #4f5ebd;
        box-shadow: 0 0 0 3px rgba(79, 94, 189, 0.1);
    }

    .select-wrapper {
        position: relative;
    }

    .select-wrapper::after {
        content: '';
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 5px solid #64748b;
        pointer-events: none;
    }

    .amount-input-wrapper {
        position: relative;
    }

    .currency-symbol {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-weight: 500;
    }

    .amount-input-wrapper .form-input {
        padding-left: 2.5rem;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-primary {
        background-color: #4f5ebd;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        background-color: #7e74f1;
    }

    .btn-secondary {
        background-color: white;
        color: #64748b;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        background-color: #f8fafc;
    }
</style>
@endpush
@endsection
