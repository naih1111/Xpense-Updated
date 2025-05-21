@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Goals Progress Report</h1>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total Goals</h5>
                    <p class="card-text">{{ $report['total_goals'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Completed Goals</h5>
                    <p class="card-text">{{ $report['completed_goals'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">In Progress Goals</h5>
                    <p class="card-text">{{ $report['in_progress_goals'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 