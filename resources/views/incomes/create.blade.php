<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Income') }}
        </h2>
    </x-slot>

    <style>
        .form-container {
            background: linear-gradient(to bottom right, #ffffff, #f8fafc);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .form-input {
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .form-input:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.1), 0 2px 4px -1px rgba(79, 70, 229, 0.06);
        }

        .form-label {
            color: #4b5563;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-group:hover .form-label {
            color: #4f46e5;
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
            border-top: 5px solid #6b7280;
            pointer-events: none;
        }

        .btn-primary {
            background: linear-gradient(to right, #4f46e5, #6366f1);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.1);
        }

        .btn-secondary {
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #f3f4f6;
            transform: translateY(-1px);
        }

        .error-message {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="form-container">
                <div class="p-6">
                    <form method="POST" action="{{ route('incomes.store') }}" class="space-y-6">
                        @csrf

                        <!-- Amount Input -->
                        <div class="form-group">
                            <label for="amount" class="form-label block text-sm font-medium">{{ __('Amount') }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 w-12 flex items-center justify-start pl-4 pointer-events-none">
                                    <span class="text-gray-700 font-medium text-base">₱&nbsp;</span>
                                </div>
                                <input type="number" 
                                    name="amount" 
                                    id="amount" 
                                    step="0.01" 
                                    value="{{ old('amount') }}"
                                    class="form-input block w-full pl-12 pr-12 sm:text-sm border-gray-300 rounded-md" 
                                    placeholder="0.00"
                                    required>
                            </div>
                            @error('amount')
                                <p class="error-message mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description Input -->
                        <div class="form-group">
                            <label for="description" class="form-label block text-sm font-medium">{{ __('Description') }}</label>
                            <input type="text" 
                                name="description" 
                                id="description" 
                                value="{{ old('description') }}"
                                class="form-input block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                placeholder="Enter income description"
                                required>
                            @error('description')
                                <p class="error-message mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date Input -->
                        <div class="form-group">
                            <label for="date" class="form-label block text-sm font-medium">{{ __('Date') }}</label>
                            <input type="date" 
                                name="date" 
                                id="date" 
                                value="{{ old('date', date('Y-m-d')) }}"
                                class="form-input block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                required>
                            @error('date')
                                <p class="error-message mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <a href="{{ route('incomes.index') }}" 
                               class="btn-secondary inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                    class="btn-primary inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Add Income') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 