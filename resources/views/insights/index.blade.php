@extends('layouts.app')

@section('content')
<div class="insights-container">
    <div class="insights-header">
        <div class="header-content">
            <h1>{{ __('Spending Insights') }}</h1>
            <p class="subtitle">{{ __('Track and analyze your financial data') }}</p>
        </div>
        <form action="{{ route('insights.filter') }}" method="GET" class="date-filter">
            <div class="filter-wrapper">
                <i class="fas fa-calendar-alt"></i>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="filter-wrapper">
                <i class="fas fa-calendar-alt"></i>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <button type="submit" class="filter-button">
                <i class="fas fa-filter"></i>
                {{ __('Filter') }}
            </button>
        </form>
    </div>

    <div class="summary-cards">
        <div class="card income">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="card-info">
                    <h3>{{ __('Total Income') }}</h3>
                    <p class="amount">₱{{ number_format($incomeVsExpenses['income'], 2) }}</p>
                    <div class="trend {{ $incomeVsExpenses['incomeTrend'] >= 0 ? 'positive' : 'negative' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ $incomeVsExpenses['incomeTrend'] >= 0 ? '+' : '' }}{{ number_format($incomeVsExpenses['incomeTrend'], 1) }}% from previous period</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card expenses">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-info">
                    <h3>{{ __('Total Expenses') }}</h3>
                    <p class="amount">₱{{ number_format($incomeVsExpenses['expenses'], 2) }}</p>
                    <div class="trend {{ $incomeVsExpenses['expensesTrend'] <= 0 ? 'positive' : 'negative' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ $incomeVsExpenses['expensesTrend'] >= 0 ? '+' : '' }}{{ number_format($incomeVsExpenses['expensesTrend'], 1) }}% from previous period</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card balance {{ $incomeVsExpenses['balance'] >= 0 ? 'positive' : 'negative' }}">
            <div class="card-content">
                <div class="card-icon">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <div class="card-info">
                    <h3>{{ __('Net Balance') }}</h3>
                    <p class="amount">₱{{ number_format($incomeVsExpenses['balance'], 2) }}</p>
                    <div class="trend {{ $incomeVsExpenses['balanceTrend'] >= 0 ? 'positive' : 'negative' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ $incomeVsExpenses['balanceTrend'] >= 0 ? '+' : '' }}{{ number_format($incomeVsExpenses['balanceTrend'], 1) }}% from previous period</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="charts-container">
        <div class="chart-card">
            <div class="chart-header">
                <h3>{{ __('Monthly Spending Trend') }}</h3>
            </div>
            <div class="chart-wrapper">
                <canvas id="monthlySpendingChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <div class="chart-header">
                <h3>{{ __('Expenses by Category') }}</h3>
            </div>
            <div class="chart-content">
                <div class="charts-grid">
                <div class="chart-wrapper" style="height: 300px;">
                    <canvas id="expensesByCategoryChart"></canvas>
                    </div>
                    <div class="chart-wrapper" style="height: 300px;">
                        <canvas id="expensesByCategoryBarChart"></canvas>
                    </div>
                </div>
                <div class="categories-table" style="margin-top: 20px;">
                    <div class="table-header">
                        <h4>{{ __('Top Spending Categories') }}</h4>
                    </div>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Percentage') }}</th>
                                    <th>{{ __('Trend') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalExpenses = $incomeVsExpenses['expenses'];
                                @endphp
                                @foreach($topSpendingCategories as $category)
                                    <tr>
                                        <td>
                                            <div class="category-info">
                                                <div class="category-icon">
                                                    <i class="fas fa-tag"></i>
                                                </div>
                                                <span>{{ $category['name'] }}</span>
                                            </div>
                                        </td>
                                        <td>₱{{ number_format($category['amount'], 2) }}</td>
                                        <td>
                                            @if($totalExpenses > 0)
                                                {{ number_format(($category['amount'] / $totalExpenses) * 100, 1) }}%
                                            @else
                                                0%
                                            @endif
                                        </td>
                                        <td>
                                            <div class="trend-indicator">
                                                <div class="progress-bar">
                                                    <div class="progress" style="width: {{ $totalExpenses > 0 ? ($category['amount'] / $totalExpenses) * 100 : 0 }}%"></div>
                                                </div>
                                                <span class="trend-value {{ $category['trend'] >= 0 ? 'positive' : 'negative' }}">
                                                    <i class="fas fa-arrow-{{ $category['trend'] >= 0 ? 'up' : 'down' }}"></i>
                                                    {{ $category['trend'] >= 0 ? '+' : '' }}{{ number_format($category['trend'], 1) }}%
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="expenses-list" style="margin-top: 30px;">
                    <div class="table-header">
                        <h4>{{ __('Recent Expenses') }}</h4>
                    </div>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentExpenses as $expense)
                                    <tr>
                                        <td>{{ $expense->date->format('M d, Y') }}</td>
                                        <td>{{ $expense->description }}</td>
                                        <td>
                                            <div class="category-info">
                                                <div class="category-icon">
                                                    <i class="fas fa-tag"></i>
                                                </div>
                                                <span>{{ $expense->category->name ?? 'Uncategorized' }}</span>
                                            </div>
                                        </td>
                                        <td>₱{{ number_format($expense->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --dark-blue: #0a0a23;
        --dark-blue-light: #112240;
        --primary: #4f5ebd;
        --primary-light: #7e74f1;
        --success: #4caf50;
        --danger: #f44336;
        --warning: #ff9800;
        --text-primary: #1f2b53;
        --text-secondary: #6b7280;
        --card-bg: #ffffff;
        --border-color: #e5e7eb;
    }

    .insights-container {
        padding: 2rem;
        background: #e6eaf5;
        color: var(--text-primary);
        min-height: 100vh;
    }

    .insights-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
    }

    .header-content h1 {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--dark-blue);
    }

    .subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .date-filter {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .filter-wrapper {
        position: relative;
    }

    .filter-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
    }

    .filter-wrapper input {
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    .filter-button {
        background-color: var(--primary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .filter-button:hover {
        background-color: var(--primary-light);
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .card {
        background-color: var(--card-bg);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .card-content {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .card-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .income .card-icon {
        background-color: rgba(76, 175, 80, 0.1);
        color: var(--success);
    }

    .expenses .card-icon {
        background-color: rgba(244, 67, 54, 0.1);
        color: var(--danger);
    }

    .balance .card-icon {
        background-color: rgba(79, 94, 189, 0.1);
        color: var(--primary);
    }

    .card-info h3 {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .amount {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .trend {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .trend.positive {
        color: var(--success);
    }

    .trend.negative {
        color: var(--danger);
    }

    .charts-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .chart-card {
        background-color: var(--card-bg);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .chart-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .chart-content {
        display: flex;
        flex-direction: column;
    }

    .chart-wrapper {
        height: 300px;
    }

    .categories-table {
        margin-top: 20px;
    }

    .categories-table .table-header {
        margin-bottom: 1rem;
    }

    .categories-table .table-header h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
        padding: 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-color);
    }

    td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .category-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .category-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        background-color: rgba(79, 94, 189, 0.1);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .trend-indicator {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .progress-bar {
        flex: 1;
        height: 0.5rem;
        background-color: #e2e8f0;
        border-radius: 9999px;
        overflow: hidden;
    }

    .progress {
        height: 100%;
        background-color: var(--primary);
        border-radius: 9999px;
        transition: width 0.3s ease;
    }

    .trend-value {
        font-size: 0.875rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .trend-value.positive {
        color: var(--success);
    }

    .trend-value.negative {
        color: var(--danger);
    }

    .expenses-list {
        margin-top: 30px;
        border-top: 1px solid var(--border-color);
        padding-top: 20px;
    }

    .expenses-list .table-header {
        margin-bottom: 1rem;
    }

    .expenses-list .table-header h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .expenses-list table {
        margin-top: 1rem;
    }

    .expenses-list td {
        font-size: 0.875rem;
    }

    .expenses-list .category-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .expenses-list .category-icon {
        width: 1.5rem;
        height: 1.5rem;
        font-size: 0.75rem;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let expensesByCategoryChart;
    let expensesByCategoryBarChart;
    let updateInterval;

    function initializeCharts() {
    // Monthly Spending Trend Chart
    const monthlySpendingCtx = document.getElementById('monthlySpendingChart').getContext('2d');
    new Chart(monthlySpendingCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlySpendingTrend['labels']) !!},
            datasets: [{
                label: 'Monthly Spending',
                data: {!! json_encode($monthlySpendingTrend['data']) !!},
                borderColor: '#4f5ebd',
                backgroundColor: 'rgba(79, 94, 189, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

        // Expenses by Category Doughnut Chart
    const expensesByCategoryCtx = document.getElementById('expensesByCategoryChart').getContext('2d');
        expensesByCategoryChart = new Chart(expensesByCategoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_column($expensesByCategory, 'name')) !!},
            datasets: [{
                data: {!! json_encode(array_column($expensesByCategory, 'amount')) !!},
                backgroundColor: [
                    '#4f5ebd',
                    '#7e74f1',
                    '#4caf50',
                    '#ff9800',
                    '#f44336',
                    '#2196f3',
                    '#9c27b0',
                    '#795548'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        font: {
                            size: 12
                        }
                        },
                        title: {
                            display: true,
                            text: 'Distribution of Expenses',
                            font: {
                                size: 14
                }
            }
        }
                }
            }
        });

        // Expenses by Category Bar Chart
        const expensesByCategoryBarCtx = document.getElementById('expensesByCategoryBarChart').getContext('2d');
        expensesByCategoryBarChart = new Chart(expensesByCategoryBarCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($expensesByCategory, 'name')) !!},
                datasets: [{
                    label: 'Amount',
                    data: {!! json_encode(array_column($expensesByCategory, 'amount')) !!},
                    backgroundColor: 'rgba(79, 94, 189, 0.7)',
                    borderColor: '#4f5ebd',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Expense Amounts by Category',
                        font: {
                            size: 14
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + number_format(value, 0);
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    function updateCategoryData() {
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;

        fetch(`/insights/category-data?start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                // Update doughnut chart
                expensesByCategoryChart.data.labels = data.expensesByCategory.map(category => category.name);
                expensesByCategoryChart.data.datasets[0].data = data.expensesByCategory.map(category => category.amount);
                expensesByCategoryChart.update();

                // Update bar chart
                expensesByCategoryBarChart.data.labels = data.expensesByCategory.map(category => category.name);
                expensesByCategoryBarChart.data.datasets[0].data = data.expensesByCategory.map(category => category.amount);
                expensesByCategoryBarChart.update();

                // Update table
                const tableBody = document.querySelector('.categories-table tbody');
                const totalExpenses = data.topSpendingCategories.reduce((sum, category) => sum + category.amount, 0);
                
                tableBody.innerHTML = data.topSpendingCategories.map(category => `
                    <tr>
                        <td>
                            <div class="category-info">
                                <div class="category-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <span>${category.name}</span>
                            </div>
                        </td>
                        <td>₱${number_format(category.amount, 2)}</td>
                        <td>${totalExpenses > 0 ? number_format((category.amount / totalExpenses) * 100, 1) : 0}%</td>
                        <td>
                            <div class="trend-indicator">
                                <div class="progress-bar">
                                    <div class="progress" style="width: ${totalExpenses > 0 ? (category.amount / totalExpenses) * 100 : 0}%"></div>
                                </div>
                                <span class="trend-value ${category.trend >= 0 ? 'positive' : 'negative'}">
                                    <i class="fas fa-arrow-${category.trend >= 0 ? 'up' : 'down'}"></i>
                                    ${category.trend >= 0 ? '+' : ''}${number_format(category.trend, 1)}%
                                </span>
                            </div>
                        </td>
                    </tr>
                `).join('');
            })
            .catch(error => console.error('Error updating category data:', error));
    }

    function number_format(number, decimals) {
        return new Intl.NumberFormat('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }).format(number);
    }

    // Initialize charts when the page loads
    initializeCharts();

    // Update data every 30 seconds
    updateInterval = setInterval(updateCategoryData, 30000);

    // Update when date filters change
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.addEventListener('change', () => {
            clearInterval(updateInterval);
            updateCategoryData();
            updateInterval = setInterval(updateCategoryData, 30000);
        });
    });

    // Clean up interval when leaving the page
    window.addEventListener('beforeunload', () => {
        clearInterval(updateInterval);
    });
</script>
@endpush
@endsection 