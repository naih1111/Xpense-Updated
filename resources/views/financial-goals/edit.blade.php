<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Financial Goal') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="form-header">
                    <h2>{{ __('Edit Financial Goal') }}</h2>
                </div>
                <form method="POST" action="{{ route('financial-goals.update', $goal) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name Input -->
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('Goal Name') }}</label>
                        <input type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name', $goal->name) }}"
                            class="form-input" 
                            placeholder="Enter goal name"
                            required>
                        @error('name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target Amount Input -->
                    <div class="form-group">
                        <label for="target_amount" class="form-label">{{ __('Target Amount') }}</label>
                        <div class="currency-input">
                            <span class="currency-symbol">₱</span>
                            <input type="number" 
                                name="target_amount" 
                                id="target_amount" 
                                step="0.01" 
                                value="{{ old('target_amount', $goal->target_amount) }}"
                                class="form-input" 
                                placeholder="0.00"
                                required>
                        </div>
                        @error('target_amount')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Amount Input -->
                    <div class="form-group">
                        <label for="current_amount" class="form-label">{{ __('Current Amount') }}</label>
                        <div class="currency-input">
                            <span class="currency-symbol">₱</span>
                            <input type="number" 
                                name="current_amount" 
                                id="current_amount" 
                                step="0.01" 
                                value="{{ old('current_amount', $goal->current_amount) }}"
                                class="form-input" 
                                placeholder="0.00"
                                required>
                        </div>
                        @error('current_amount')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target Date Input -->
                    <div class="form-group">
                        <label for="target_date" class="form-label">{{ __('Target Date') }}</label>
                        <input type="date" 
                            name="target_date" 
                            id="target_date" 
                            value="{{ old('target_date', $goal->target_date->format('Y-m-d')) }}"
                            class="form-input" 
                            required>
                        @error('target_date')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description Input -->
                    <div class="form-group">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea 
                            name="description" 
                            id="description" 
                            class="form-input" 
                            rows="3"
                            placeholder="Enter goal description">{{ old('description', $goal->description) }}</textarea>
                        @error('description')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
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
    </div>
</x-app-layout> 