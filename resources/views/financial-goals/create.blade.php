@extends('layouts.app')

@section('content')
<div class="insights-container">
    <div class="form-container">
        <div class="form-header">
            <h1>{{ __('Create Financial Goal') }}</h1>
            <p class="subtitle">{{ __('Set a new financial goal to track your progress') }}</p>
        </div>

        <form action="{{ route('financial-goals.store') }}" method="POST" class="goal-form">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">{{ __('Goal Name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                    placeholder="{{ __('e.g., Save for Emergency Fund') }}"
                    class="form-input @error('name') error @enderror">
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">{{ __('Description') }}</label>
                <textarea id="description" name="description" rows="3" 
                    placeholder="{{ __('Describe your goal and why it\'s important') }}"
                    class="form-input @error('description') error @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="target_amount" class="form-label">{{ __('Target Amount') }}</label>
                <div class="currency-input">
                    <span class="currency-symbol">₱</span>
                    <input type="number" id="target_amount" name="target_amount" step="0.01" 
                        value="{{ old('target_amount') }}" required 
                        placeholder="0.00"
                        min="0.01"
                        class="form-input @error('target_amount') error @enderror">
                </div>
                @error('target_amount')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="current_amount" class="form-label">{{ __('Initial Amount') }}</label>
                <div class="currency-input">
                    <span class="currency-symbol">₱</span>
                    <input type="number" id="current_amount" name="current_amount" step="0.01" 
                        value="{{ old('current_amount') }}" required 
                        placeholder="0.00"
                        min="0.01"
                        class="form-input @error('current_amount') error @enderror">
                </div>
                @error('current_amount')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="target_date" class="form-label">{{ __('Target Date') }}</label>
                <input type="date" id="target_date" name="target_date" 
                    value="{{ old('target_date') }}" required 
                    class="form-input @error('target_date') error @enderror">
                @error('target_date')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('financial-goals.index') }}" class="btn btn-secondary">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ __('Create Goal') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .insights-container {
        padding: 2rem;
        background-color: #f8fafc;
        min-height: calc(100vh - 64px);
    }

    .form-container {
        max-width: 800px;
        margin: 0 auto;
        background-color: white;
        border-radius: 0.5rem;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-header {
        margin-bottom: 2rem;
    }

    .form-header h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .subtitle {
        color: #718096;
        font-size: 0.875rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    textarea {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }

    .input-with-icon {
        position: relative;
    }

    .currency-symbol {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #718096;
        font-size: 1rem;
        font-weight: bold;
        z-index: 1;
    }

    .currency-input input {
        padding-left: 2rem;
    }

    .error {
        border-color: #e53e3e;
    }

    .error-message {
        color: #e53e3e;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    .cancel-button {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        color: #4a5568;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
    }

    .submit-button {
        padding: 0.5rem 1rem;
        background-color: #4f46e5;
        color: white;
        border: none;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
    }

    .submit-button:hover {
        background-color: #4338ca;
    }
</style>
@endpush
@endsection 