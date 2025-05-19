<?php
  // Get user data from the controller
  $userFullName = Auth::user()->name;
  $loggedUser = Auth::user()->name;
  $income = $totalIncome;
  $expenses = $totalExpenses;
  $netSavings = $netSavings;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>XPENSE Dashboard</title>
  <!-- Add Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      display: flex;
      min-height: 100vh;
      background-color: #e6eaf5;
      color: #0a0a23;
      overflow-x: hidden;
    }

    aside {
      width: 260px;
      background-color: #0a0a23;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 40px;
      position: fixed;
      height: 100vh;
      overflow: hidden;
    }

    aside img {
      width: 80px;
      margin-bottom: 20px;
    }

    aside h1 {
      margin-bottom: 40px;
      font-size: 22px;
      font-weight: bold;
    }

    nav {
      display: flex;
      flex-direction: column;
      width: 100%;
      padding: 0 20px;
      height: calc(100% - 180px);
    }

    nav a {
      display: block;
      background-color: #4f5ebd;
      color: white;
      padding: 12px;
      text-align: center;
      border-radius: 8px;
      margin-bottom: 15px;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    nav a:hover {
      background-color: #7e74f1;
    }

    nav a.active {
      background-color: #7e74f1;
    }

    nav .bottom-section {
      margin-top: auto;
      padding-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    nav form button.btn-secondary {
      width: 100%;
      background-color: transparent;
      border: 2px solid #7e74f1;
      color: #7e74f1;
      padding: 12px;
      text-align: center;
      border-radius: 8px;
      margin-bottom: 15px;
      text-decoration: none;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    nav form button.btn-secondary:hover {
      background-color: rgba(126, 116, 241, 0.1);
    }

    main {
      flex: 1;
      margin-left: 260px;
      padding: 40px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 30px;
    }

    .header h2 {
      color: #1f2b53;
      font-size: 20px;
    }

    .header h1 {
      font-size: 28px;
      font-weight: bold;
      color: #1f2b53;
    }

    .profile {
      font-size: 14px;
      color: #6b7280;
    }

    .cards {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
    }

    .card {
      background-color: #0a0a23;
      color: white;
      padding: 10px;
      border-radius: 12px;
      flex: 1;
      position: relative;
      height: 100px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .card:nth-child(3) {
      background-color: #4a5b91;
    }

    .card-title {
      font-size:20px;
      font-weight: bold;
      margin-bottom: 3px;
    }

    .card-value {
      font-size: 20px;
      font-weight: bold;
    }

    .dots {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 20px;
      color: #999;
    }

    /* Update the tips styles */
    .tips {
      background-color: #f8f9fe;
      border-radius: 20px;
      padding: 25px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 40px auto 20px;
      width: 700px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .tips div {
      flex: 1;
      padding-right: 20px;
    }

    .tips p {
      font-size: 15px;
      line-height: 1.5;
      color: #666;
      max-width: 450px;
    }

    .tips img {
      width: 100px;
      height: auto;
      object-fit: contain;
    }
      margin-top: 20px;
      text-align: center;
    }

    .dots-nav span {
      display: inline-block;
      width: 8px;
      height: 8px;
      background-color: #bbb;
      border-radius: 50%;
      margin: 0 5px;
    }

    .dots-nav span.active {
      background-color: #0a0a23;
    }

    .add-btn {
      position: absolute;
      bottom: 30px;
      left: 50%;
      transform: translateX(-50%);
      width: 40px;
      height: 40px;
      background-color: #0a0a23;
      color: white;
      font-size: 24px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      cursor: pointer;
    }

    .quick-actions {
      position: absolute;
      bottom: 80px;
      left: 50%;
      transform: translateX(-50%);
      background: #0a0a23;
      border-radius: 16px;
      padding: 20px;
      display: none;
      flex-direction: column;
      z-index: 100;
      min-width: 250px;
      gap: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .quick-actions a {
      padding: 16px;
      text-decoration: none;
      color: white;
      font-size: 16px;
      background-color: #4f5ebd;
      border-radius: 12px;
      text-align: center;
      transition: background-color 0.2s;
    }

    .quick-actions a:first-child {
      background-color: #7e74f1;
    }

    .quick-actions a:hover {
      background-color: #6a62d3;
    }

    .quick-actions a:last-child {
      background-color: transparent;
      border: 2px solid #7e74f1;
      color: #7e74f1;
    }

    .quick-actions a:last-child:hover {
      background-color: rgba(126, 116, 241, 0.1);
    }
      border-bottom: 1px solid #eee;
      transition: background 0.2s;
    }

    .quick-actions a:last-child {
      border-bottom: none;
    }

    .quick-actions a:hover {
      background-color: #f0f0f0;
    }
    /* Update layout styles */
        .main-content {
          display: flex;
          gap: 30px;
          margin-top: 40px;
        }
    
        .statistics {
          flex: 2;
          margin: 0;
        }
    
        .chart-container {
          background-color: rgb(36, 36, 188);
          border-radius: 25px;
          padding: 40px;
          color: white;
          height: 400px;
          position: relative;
        }
        .chart-nav {
          display: flex;
          gap: 12px;
          margin-bottom: 20px;
        }

        .chart-btn {
          padding: 10px 18px;
          background-color: white;
          border: 2px solid #4a5b91;
          color: #4a5b91;
          font-weight: 600;
          border-radius: 12px;
          cursor: pointer;
          transition: all 0.3s ease;
        }

        .chart-btn:hover {
          background-color: #4a5b91;
          color: white;
        }

        .chart-btn.active {
          background-color: #4a5b91;
          color: white;
          border-color: #4a5b91;
        }
    
        /* Update tips styles */
        .tips {
          flex: 1;
          background-color: #f8f9fe;
          border-radius: 20px;
          padding: 25px 30px;
          height: fit-content;
          margin: 44px 0 20px;
          box-shadow: 0 2px 6px rgba(0,0,0,0.05);
          width: auto;
          
        }
        .tips img {
          width: 140px;
          height: auto;
          object-fit: contain;
        }

        #expenseChart {
          height: 290px !important;
        }

        .main-content {
  display: flex;
  gap: 30px;
  margin-top: 40px;
  align-items: flex-start;
}

.right-sidebar {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Already defined previously — included for clarity */
.financial-goals {
  background-color: #f8f9fe;
  padding: 25px 30px;
  border-radius: 20px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.goals-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.financial-goals h2 {
  font-size: 20px;
  color: #1f2b53;
}

.set-goals-btn {
  background-color: #4f5ebd;
  color: white;
  padding: 8px 18px;
  border-radius: 12px;
  font-weight: 600;
  text-decoration: none;
}

.goal {
  margin-bottom: 20px;
}

.goal-label {
  display: flex;
  justify-content: space-between;
  font-size: 14px;
  margin-bottom: 6px;
  color: #333;
}

.progress-bar {
  background-color: #ddd;
  border-radius: 10px;
  height: 12px;
  overflow: hidden;
}

.progress {
  background-color: #4f5ebd;
  height: 100%;
  border-radius: 10px 0 0 10px;
}

.no-goals {
  font-size: 14px;
  color: #888;
  text-align: center;
  padding: 15px 0;
}

.recent-transactions {
  background-color: #f8f9fe;
  border-radius: 20px;
  padding: 25px 30px;
  margin-bottom: 30px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.recent-transactions h2 {
  color: #1f2b53;
  font-size: 20px;
  margin-bottom: 20px;
}

.transactions-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.transaction-section {
  background-color: white;
  border-radius: 12px;
  padding: 15px;
}

.transaction-section h3 {
  color: #1f2b53;
  font-size: 16px;
  margin-bottom: 15px;
}

.transaction-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px;
  border-bottom: 1px solid #eee;
}

.transaction-item:last-child {
  border-bottom: none;
}

.transaction-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.transaction-type {
  font-size: 12px;
  padding: 2px 8px;
  border-radius: 12px;
  background-color: #e6eaf5;
  color: #1f2b53;
  display: inline-block;
}

.transaction-description {
  font-size: 14px;
  color: #1f2b53;
}

.transaction-date {
  font-size: 12px;
  color: #6b7280;
}

.transaction-amount {
  font-weight: bold;
  font-size: 14px;
}

.transaction-amount.expense {
  color: #ef4444;
}

.transaction-amount.income {
  color: #10b981;
}

.no-transactions {
  text-align: center;
  color: #6b7280;
  padding: 20px;
}

    
        /* Remove the duplicate style tags and body tags */
        </style>
    </head>
    <body>
    
      <!-- Sidebar -->
      <aside>
        <img src="{{ asset('images/XPENSELosgz (2).png') }}" alt="XPENSE Logo">
        <h1>XPENSE</h1>
        <nav>
          <div>
            <a href="{{ route('dashboard') }}" class="active">Home</a>
            <a href="{{ route('expenses.index') }}">Expenses</a>
            <a href="{{ route('incomes.index') }}">Income</a>
            <a href="{{ route('financial-goals.index') }}">Goals</a>
            <a href="{{ route('insights.index') }}">Report</a>
          </div>
          <div class="bottom-section">
            <a href="{{ route('profile.edit') }}" class="btn-secondary">My Profile</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="btn-secondary">Logout</button>
            </form>
          </div>
        </nav>
      </aside>
    
      <!-- Main Dashboard -->
      <main>
        <style>
            .header-right {
                display: flex;
                align-items: center;
                gap: 20px;
            }
        
            .notifications {
                position: relative;
                cursor: pointer;
            }
        
            .notification-icon {
                color: #1f2b53;
                font-size: 20px;
            }
        
            .notification-badge {
                position: absolute;
                top: -5px;
                right: -5px;
                background-color: #ff4444;
                color: white;
                border-radius: 50%;
                width: 18px;
                height: 18px;
                font-size: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        
            .notification-dropdown {
                position: absolute;
                top: 100%;
                right: 0;
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                width: 300px;
                display: none;
                z-index: 1000;
            }
        
            .notification-dropdown.show {
                display: block;
            }
        
            .notification-header {
                padding: 15px;
                border-bottom: 1px solid #eee;
                font-weight: bold;
                color: #1f2b53;
            }
        
            .notification-list {
                max-height: 300px;
                overflow-y: auto;
            }
        
            .notification-item {
                padding: 12px 15px;
                border-bottom: 1px solid #eee;
                font-size: 14px;
            }
        
            .notification-item:last-child {
                border-bottom: none;
            }
        </style>
        
        <!-- Then, update the header div structure -->
        <div class="header">
            <div>
                <h2>Good day!</h2>
                <h1>{{ Auth::user()->name }}</h1>
            </div>
            <div class="header-right">
                <div class="notifications" id="notificationBell">
                    <div class="notification-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        @if(count($notifications) > 0)
                        <span class="notification-badge">{{ count($notifications) }}</span>
                        @endif
                    </div>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            Notifications
                        </div>
                        <div class="notification-list">
                            @forelse($notifications as $notification)
                            <div class="notification-item {{ $notification['type'] }}">
                                <i class="fas {{ $notification['icon'] }}"></i>
                                <div class="notification-content">
                                    <p class="notification-message">{{ $notification['message'] }}</p>
                                    <span class="notification-time">{{ $notification['time'] }}</span>
                                </div>
                            </div>
                            @empty
                            <div class="notification-item">
                                <p>No new notifications</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add this JavaScript before the closing body tag -->
        <script>
            // Notification dropdown toggle
            const notificationBell = document.getElementById('notificationBell');
            const notificationDropdown = document.getElementById('notificationDropdown');
        
            notificationBell.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationDropdown.classList.toggle('show');
            });
        
            // Close notification dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!notificationBell.contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                }
            });
        </script>

    <div class="cards">
      @if($recentIncomes->isNotEmpty())
      <div class="card">
        <div class="dots">⋮</div>
        <div class="card-title">
          <i class="fas fa-wallet"></i> Total Income
        </div>
        <div class="card-value">₱{{ number_format($income, 2) }}</div>
      </div>
      @endif
      <div class="card">
        <div class="dots">⋮</div>
        <div class="card-title">
          <i class="fas fa-shopping-cart"></i> Total Expenses
        </div>
        <div class="card-value">₱{{ number_format($expenses, 2) }}</div>
      </div>
      <div class="card {{ $netSavings < 0 ? 'negative-savings' : '' }}">
        <div class="dots">⋮</div>
        <div class="card-title">
          <i class="fas fa-piggy-bank"></i> Net Savings
        </div>
        <div class="card-value">₱{{ number_format($netSavings, 2) }}</div>
      </div>
    </div>

    <style>
      .card {
        display: flex;
        flex-direction: column;
        min-height: 100px;
      }

      .recent-list {
        margin-top: 20px;
        overflow-y: auto;
        max-height: 200px;
      }

      .transaction-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      }

      .transaction-item:last-child {
        border-bottom: none;
      }

      .transaction-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
      }

      .transaction-type {
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 10px;
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        display: inline-block;
      }

      .transaction-description {
        font-size: 13px;
        color: white;
      }

      .transaction-date {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.7);
      }

      .transaction-amount {
        font-weight: bold;
        font-size: 13px;
        color: white;
      }

      .transaction-amount.expense {
        color: #ef4444;
      }

      .transaction-amount.income {
        color: #10b981;
      }

      .no-transactions {
        text-align: center;
        color: rgba(255, 255, 255, 0.7);
        padding: 10px;
        font-size: 13px;
      }

      /* Add this to your existing styles */
      .negative-savings {
        background-color: #ef4444 !important;
      }
      
      .negative-savings .card-value,
      .negative-savings .card-title,
      .negative-savings h3,
      .negative-savings .summary-value,
      .negative-savings .summary-period {
        color: white !important;
      }
      
      .negative-savings .summary-icon {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
      }

      /* Add this to your existing styles */
      .card-title i {
        margin-right: 8px;
      }

      .summary-icon i {
        font-size: 24px;
      }
    </style>

    <div class="main-content">
      <div class="statistics">
        <h2>Financial Summary</h2>
        <div class="summary-container">
          <div class="summary-grid">
            <div class="summary-card">
              <div class="summary-icon income">
                <i class="fas fa-wallet"></i>
              </div>
              <div class="summary-info">
                <h3>Monthly Income</h3>
                <p class="summary-value">₱{{ number_format($income, 2) }}</p>
                <p class="summary-period">This Month</p>
                @if(Auth::user()->monthly_income && Auth::user()->monthly_income > 0)
                <div class="progress-container mt-2">
                  <div class="progress-bar">
                    <div class="progress" style="width: {{ $monthlyIncomeProgress }}%"></div>
                  </div>
                  <p class="progress-text text-sm mt-1">
                    {{ number_format($monthlyIncomeProgress, 1) }}% of ₱{{ number_format(Auth::user()->monthly_income, 2) }} target
                  </p>
                </div>
                @else
                <p class="progress-text text-sm mt-1">
                  Set a monthly income target in your profile to track progress
                </p>
                @endif
              </div>
            </div>

            <div class="summary-card">
              <div class="summary-icon expense">
                <i class="fas fa-shopping-cart"></i>
              </div>
              <div class="summary-info">
                <h3>Monthly Expenses</h3>
                <p class="summary-value">₱{{ number_format($expenses, 2) }}</p>
                <p class="summary-period">This Month</p>
              </div>
            </div>

            <div class="summary-card {{ $netSavings < 0 ? 'negative-savings' : '' }}">
              <div class="summary-icon savings">
                <i class="fas fa-piggy-bank"></i>
              </div>
              <div class="summary-info">
                <h3>Net Savings</h3>
                <p class="summary-value">₱{{ number_format($netSavings, 2) }}</p>
                <p class="summary-period">This Month</p>
                @if($netSavings < 0)
                <p class="text-sm text-white mt-1">
                  Warning: Expenses exceed income
                </p>
                @endif
              </div>
            </div>

            <div class="summary-card">
              <div class="summary-icon goal">
                <i class="fas fa-bullseye"></i>
              </div>
              <div class="summary-info">
                <h3>Active Goals</h3>
                <p class="summary-value">{{ $financialGoals->count() }}</p>
                <p class="summary-period">In Progress</p>
              </div>
            </div>
          </div>

          <div class="recent-activity">
            <h3>Recent Activity</h3>
            <div class="activity-list">
              @forelse($recentTransactions as $transaction)
                <div class="activity-item">
                  <div class="activity-icon {{ $transaction->type === 'income' ? 'income' : 'expense' }}">
                    <i class="fas {{ $transaction->type === 'income' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                  </div>
                  <div class="activity-details">
                    <p class="activity-title">{{ $transaction->description }}</p>
                    <p class="activity-date">{{ $transaction->date->format('M d, Y') }}</p>
                  </div>
                  <div class="activity-amount {{ $transaction->type }}">
                    {{ $transaction->type === 'income' ? '+' : '-' }}₱{{ number_format($transaction->amount, 2) }}
                  </div>
                </div>
              @empty
                <p class="no-activity">No recent transactions</p>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <!-- Right Sidebar Content -->
      <div class="right-sidebar">
        <div class="tips">
          <div>
            <h3>Daily tips on <span>Budgeting</span></h3>
            <p>Setting realistic goals helps you stay motivated and focused on your budgeting journey. Break them down into smaller milestones to track your progress and celebrate achievements along the way.</p>
          </div>
          <img src="images/tips2.png" alt="Tips Illustration">
        </div>

        <!-- Financial Goals Section -->
        <div class="financial-goals">
          <div class="goals-header">
            <h2>Financial Goals</h2>
            <a href="{{ route('financial-goals.create') }}" class="set-goals-btn">Set Goals</a>
          </div>

          @if($financialGoals->isEmpty())
            <p class="no-goals">No financial goals set yet.</p>
          @else
            @foreach($financialGoals as $goal)
              <div class="goal">
                <div class="goal-label">
                  <strong>{{ $goal->name }}</strong>
                  <span>₱{{ number_format($goal->current_amount, 2) }} / ₱{{ number_format($goal->target_amount, 2) }}</span>
                </div>
                <div class="progress-bar">
                  <div class="progress" style="width: {{ ($goal->current_amount / $goal->target_amount) * 100 }}%;"></div>
                </div>
                <div class="text-sm text-gray-500 mt-1">
                  Target Date: {{ \Carbon\Carbon::parse($goal->target_date)->format('M d, Y') }}
                </div>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>
  </main>

  <!-- Add Chart.js library -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <script>
    const chartData = {
      income: {
        labels: ['Salary', 'Investments', 'Side Jobs', 'Others'],
        data: [50000, 20000, 15000, 15000],
        colors: ['#0a0a23', '#1a237e', '#4a5b91', '#4caf50']
      },
      expenses: {
        labels: ['Food', 'Rent', 'Utilities', 'Others'],
        data: [1000000, 930345, 630345, 635749],
        colors: ['#0a0a23', '#1a237e', '#4a5b91', '#4caf50']
      },
      savings: {
        labels: ['Emergency Fund', 'Investment', 'Goals'],
        data: [300000, 200000, 100000],
        colors: ['#0a0a23', '#1a237e', '#4a5b91']
      }
    };

    const ctx = document.getElementById('expenseChart');
    let currentChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: chartData.income.labels,
        datasets: [{
          data: chartData.income.data,
          backgroundColor: chartData.income.colors,
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right',
            labels: {
              color: 'white',
              padding: 20,
              font: { size: 14 }
            }
          }
        }
      }
    });

    // Add chart switching functionality
    document.querySelectorAll('.chart-btn').forEach(button => {
      button.addEventListener('click', () => {
        const type = button.dataset.type;
        
        // Update active button
        document.querySelectorAll('.chart-btn').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        // Update chart data
        currentChart.data.labels = chartData[type].labels;
        currentChart.data.datasets[0].data = chartData[type].data;
        currentChart.data.datasets[0].backgroundColor = chartData[type].colors;
        currentChart.update();
      });
    });
  </script>

  <style>
    .summary-container {
      background-color: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .summary-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }

    .summary-card {
      background-color: #f8f9fe;
      border-radius: 15px;
      padding: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .summary-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }

    .summary-icon.income {
      background-color: #e6f7ff;
      color: #1890ff;
    }

    .summary-icon.expense {
      background-color: #fff1f0;
      color: #ff4d4f;
    }

    .summary-icon.savings {
      background-color: #f6ffed;
      color: #52c41a;
    }

    .summary-icon.goal {
      background-color: #f9f0ff;
      color: #722ed1;
    }

    .summary-info h3 {
      font-size: 14px;
      color: #6b7280;
      margin-bottom: 5px;
    }

    .summary-value {
      font-size: 20px;
      font-weight: 600;
      color: #1f2b53;
      margin-bottom: 5px;
    }

    .summary-period {
      color: #6b7280;
      font-size: 14px;
    }

    .progress-container {
      margin-top: 10px;
    }

    .progress-bar {
      width: 100%;
      height: 6px;
      background-color: #e5e7eb;
      border-radius: 3px;
      overflow: hidden;
    }

    .progress {
      height: 100%;
      background-color: #7e74f1;
      border-radius: 3px;
      transition: width 0.3s ease;
    }

    .progress-text {
      color: #6b7280;
      font-size: 12px;
      margin-top: 4px;
    }

    .mt-1 {
      margin-top: 0.25rem;
    }

    .mt-2 {
      margin-top: 0.5rem;
    }

    .text-sm {
      font-size: 0.875rem;
      line-height: 1.25rem;
    }

    .recent-activity {
      background-color: #f8f9fe;
      border-radius: 15px;
      padding: 20px;
    }

    .recent-activity h3 {
      font-size: 16px;
      color: #1f2b53;
      margin-bottom: 15px;
    }

    .activity-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .activity-item {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 10px;
      background-color: white;
      border-radius: 10px;
    }

    .activity-icon {
      width: 35px;
      height: 35px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
    }

    .activity-icon.income {
      background-color: #e6f7ff;
      color: #1890ff;
    }

    .activity-icon.expense {
      background-color: #fff1f0;
      color: #ff4d4f;
    }

    .activity-details {
      flex: 1;
    }

    .activity-title {
      font-size: 14px;
      color: #1f2b53;
      margin-bottom: 3px;
    }

    .activity-date {
      font-size: 12px;
      color: #6b7280;
    }

    .activity-amount {
      font-weight: 600;
      font-size: 14px;
    }

    .activity-amount.income {
      color: #1890ff;
    }

    .activity-amount.expense {
      color: #ff4d4f;
    }

    .no-activity {
      text-align: center;
      color: #6b7280;
      padding: 20px;
    }

    @media (max-width: 768px) {
      .summary-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Add this to your existing styles */
    .text-red-500 {
      color: #ef4444 !important;
    }
    
    .card .card-value.text-red-500,
    .summary-card .summary-value.text-red-500 {
      color: #ef4444 !important;
    }
  </style>
</body>
</html>