<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Set New Financial Goal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('goals.store') }}" class="space-y-6">
                        @csrf

                        <!-- Title Input -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">{{ __('Goal Title') }}</label>
                            <div class="mt-2">
                                <input type="text" 
                                    name="title" 
                                    id="title" 
                                    value="{{ old('title') }}"
                                    class="block w-full sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500" 
                                    placeholder="Enter your goal title"
                                    required>
                            </div>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Amount Input -->
                        <div>
                            <label for="target_amount" class="block text-sm font-medium text-gray-700">{{ __('Target Amount') }}</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" 
                                    name="target_amount" 
                                    id="target_amount" 
                                    step="0.01" 
                                    value="{{ old('target_amount') }}"
                                    class="block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500" 
                                    placeholder="0.00"
                                    required>
                            </div>
                            @error('target_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Amount Input -->
                        <div>
                            <label for="current_amount" class="block text-sm font-medium text-gray-700">{{ __('Current Amount') }}</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" 
                                    name="current_amount" 
                                    id="current_amount" 
                                    step="0.01" 
                                    value="{{ old('current_amount', 0) }}"
                                    class="block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500" 
                                    placeholder="0.00"
                                    required>
                            </div>
                            @error('current_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Date Input -->
                        <div>
                            <label for="target_date" class="block text-sm font-medium text-gray-700">{{ __('Target Date') }}</label>
                            <div class="mt-2">
                                <input type="date" 
                                    name="target_date" 
                                    id="target_date" 
                                    value="{{ old('target_date') }}"
                                    class="block w-full sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500" 
                                    required
                                    min="{{ date('Y-m-d') }}">
                            </div>
                            @error('target_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description Input -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                            <div class="mt-2">
                                <textarea
                                    name="description" 
                                    id="description" 
                                    rows="3"
                                    class="block w-full sm:text-sm border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500" 
                                    placeholder="Describe your financial goal">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <a href="{{ route('financial-goals.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Set Goal') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 