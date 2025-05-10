@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ __('Edit Financial Goal') }}</h2>
                    <p class="mt-1 text-sm text-gray-600">{{ __('Update your financial goal details and track your progress.') }}</p>
                </div>

                <form method="POST" action="{{ route('goals.update', $goal->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Goal Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Goal Name') }}</label>
                            <input id="name" type="text" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                name="name" 
                                value="{{ old('name', $goal->name) }}" 
                                required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Date -->
                        <div>
                            <label for="target_date" class="block text-sm font-medium text-gray-700">{{ __('Target Date') }}</label>
                            <input id="target_date" type="date" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('target_date') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                name="target_date" 
                                value="{{ old('target_date', $goal->target_date->format('Y-m-d')) }}" 
                                required>
                            @error('target_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Amount -->
                        <div>
                            <label for="target_amount" class="block text-sm font-medium text-gray-700">{{ __('Target Amount') }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input id="target_amount" type="number" step="0.01" 
                                    class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('target_amount') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                    name="target_amount" 
                                    value="{{ old('target_amount', $goal->target_amount) }}" 
                                    required>
                            </div>
                            @error('target_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Amount -->
                        <div>
                            <label for="current_amount" class="block text-sm font-medium text-gray-700">{{ __('Current Amount') }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input id="current_amount" type="number" step="0.01" 
                                    class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('current_amount') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                    name="current_amount" 
                                    value="{{ old('current_amount', $goal->current_amount) }}" 
                                    required>
                            </div>
                            @error('current_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                        <textarea id="description" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                            name="description" 
                            rows="3">{{ old('description', $goal->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Progress Preview -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">{{ __('Progress Preview') }}</h3>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                            <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ ($goal->current_amount / $goal->target_amount) * 100 }}%"></div>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>{{ number_format(($goal->current_amount / $goal->target_amount) * 100, 1) }}% Complete</span>
                            <span>₱{{ number_format($goal->current_amount, 2) }} of ₱{{ number_format($goal->target_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('financial-goals.index') }}" 
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Update Goal') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 