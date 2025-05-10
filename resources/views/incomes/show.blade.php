<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Income Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Income Information') }}</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Amount') }}</p>
                            <p class="mt-1 text-lg text-gray-900">₱{{ number_format($income->amount, 2) }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Date') }}</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $income->date->format('F d, Y') }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">{{ __('Description') }}</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $income->description }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('incomes.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Back to List') }}
                        </a>
                        <a href="{{ route('incomes.edit', $income) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Edit Income') }}
                        </a>
                        <form action="{{ route('incomes.destroy', $income) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    onclick="return confirm('{{ __('Are you sure you want to delete this income record?') }}')">
                                {{ __('Delete Income') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 