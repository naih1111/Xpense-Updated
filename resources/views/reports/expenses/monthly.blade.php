@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Monthly Expenses Report</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Total Expenses</h5>
            <p class="card-text">${{ number_format($report['total'], 2) }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Category Breakdown</h5>
            <ul class="list-group">
                @foreach($report['categories'] as $category => $amount)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $category }}
                        <span class="badge bg-primary rounded-pill">${{ number_format($amount, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection 