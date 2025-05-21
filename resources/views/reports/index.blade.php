@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Reports</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('reports.expenses.monthly') }}" class="bg-white p-4 rounded shadow hover:bg-gray-100">
            <h2 class="text-lg font-semibold">Monthly Expenses</h2>
            <p class="text-gray-600">View your monthly expense report.</p>
        </a>
        <a href="{{ route('reports.incomes.monthly') }}" class="bg-white p-4 rounded shadow hover:bg-gray-100">
            <h2 class="text-lg font-semibold">Monthly Incomes</h2>
            <p class="text-gray-600">View your monthly income report.</p>
        </a>
        <a href="{{ route('reports.goals.progress') }}" class="bg-white p-4 rounded shadow hover:bg-gray-100">
            <h2 class="text-lg font-semibold">Goal Progress</h2>
            <p class="text-gray-600">Track your financial goals progress.</p>
        </a>
    </div>
</div>
@endsection 