@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Custom Date Range Expenses Report</div>

                <div class="card-body">
                    <div class="mb-4">
                        <h4>Total Expenses: ${{ number_format($report['total'], 2) }}</h4>
                    </div>

                    <div class="mb-4">
                        <h5>Category Breakdown</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($report['categories'] as $category => $amount)
                                    <tr>
                                        <td>{{ $category }}</td>
                                        <td>${{ number_format($amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('reports.expenses.export.pdf') }}" class="btn btn-primary">Export to PDF</a>
                        <a href="{{ route('reports.expenses.export.excel') }}" class="btn btn-success">Export to Excel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 