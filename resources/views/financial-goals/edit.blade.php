@extends('layouts.app')

@section('content')
<div class="insights-container">
    <div class="form-container">
        <div class="form-header">
            <h1>{{ __('Edit Financial Goal') }}</h1>
            <p class="subtitle">{{ __('Update your financial goal details') }}</p>
        </div>

        <form method="POST" action="{{ route('financial-goals.update', $goal) }}" class="goal-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">{{ __('Goal Name') }}</label>
                <input type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $goal->name) }}"
                    class="form-input @error('name') error @enderror" 
                    placeholder="Enter goal name"
                    required>
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">{{ __('Description') }}</label>
                <textarea 
                    name="description" 
                    id="description" 
                    class="form-input @error('description') error @enderror" 
                    rows="3"
                    placeholder="Enter goal description">{{ old('description', $goal->description) }}</textarea>
                @error('description')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="target_amount" class="form-label">{{ __('Target Amount') }}</label>
                <div class="currency-input">
                    <span class="currency-symbol">₱</span>
                    <input type="number" 
                        name="target_amount" 
                        id="target_amount" 
                        step="0.01" 
                        value="{{ old('target_amount', $goal->target_amount) }}"
                        class="form-input @error('target_amount') error @enderror" 
                        placeholder="0.00"
                        required>
                </div>
                @error('target_amount')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="current_amount" class="form-label">{{ __('Current Amount') }}</label>
                <div class="currency-input">
                    <span class="currency-symbol">₱</span>
                    <input type="number" 
                        name="current_amount" 
                        id="current_amount" 
                        step="0.01" 
                        value="{{ old('current_amount', $goal->current_amount) }}"
                        class="form-input @error('current_amount') error @enderror" 
                        placeholder="0.00"
                        required>
                </div>
                @error('current_amount')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="target_date" class="form-label">{{ __('Target Date') }}</label>
                <input type="date" 
                    name="target_date" 
                    id="target_date" 
                    value="{{ old('target_date', $goal->target_date->format('Y-m-d')) }}"
                    class="form-input @error('target_date') error @enderror" 
                    required>
                @error('target_date')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('financial-goals.index') }}" 
                   class="btn btn-secondary">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" 
                        class="btn btn-primary">
                    {{ __('Update Goal') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 