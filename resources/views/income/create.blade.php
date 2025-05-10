<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Income - XPENSE</title>
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
            height: calc(100vh - 180px);
            overflow: hidden;
        }

        nav a {
            display: block;
            background-color: #4f5ebd;
            color: white;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 12px;
            text-decoration: none;
        }

        nav a:nth-child(1) {
            background-color: #7e74f1;
        }

        nav a:nth-child(3) {
            border: 2px solid #7e74f1;
            background-color: transparent;
            color: #7e74f1;
        }

        nav div {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        nav div a {
            margin-bottom: 15px;
        }

        main {
            flex: 1;
            padding: 40px;
            margin-left: 260px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            background-color: #e6eaf5;
        }

        .form-container {
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            gap: 40px;
            padding: 20px;
        }

        .form-section {
            flex: 2;
            background-color: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .info-section {
            flex: 1;
            background-color: #f8f9fe;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h2 {
            font-size: 24px;
            color: #1f2b53;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1f2b53;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4f5ebd;
        }

        .amount-input {
            position: relative;
        }

        .amount-input span {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        .amount-input input {
            padding-left: 30px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-secondary {
            background-color: white;
            border: 1px solid #e2e8f0;
            color: #1f2b53;
        }

        .btn-primary {
            background-color: #4f5ebd;
            border: none;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .info-section h3 {
            font-size: 18px;
            color: #1f2b53;
            margin-bottom: 20px;
        }

        .info-tips {
            margin-bottom: 30px;
        }

        .info-tips h4 {
            font-size: 16px;
            color: #1f2b53;
            margin-bottom: 10px;
        }

        .info-tips ul {
            list-style: none;
        }

        .info-tips li {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }

        .info-tips li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #4f5ebd;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside>
        <img src="{{ asset('images/XPENSELosgz (2).png') }}" alt="XPENSE Logo">
        <h1>XPENSE</h1>
        <nav>
            <a href="{{ route('dashboard') }}">Home</a>
            <a href="{{ route('expenses.index') }}">Expenses</a>
            <a href="{{ route('incomes.index') }}">Income</a>
            <a href="{{ route('financial-goals.index') }}">Goals</a>
            <a href="{{ route('insights.index') }}">Reports</a>
            <div style="margin-top: auto; padding-top: 20px;">
                <a href="{{ route('profile.edit') }}">My Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width: 100%; text-align: left; background-color: transparent; border: 2px solid #7e74f1; color: #7e74f1; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;">
                        Logout
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main>
        <div class="form-container">
            <div class="form-section">
                <div class="form-header">
                    <h2>Add New Income</h2>
                    <p>Record your income to track your financial progress</p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-md">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('income.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <div class="amount-input">
                            <span>₱</span>
                            <input type="number" name="amount" id="amount" step="0.01" required
                                value="{{ old('amount') }}"
                                placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" name="description" id="description" required
                            value="{{ old('description') }}"
                            placeholder="Enter description">
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" required
                            value="{{ old('date', date('Y-m-d')) }}">
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add Income</button>
                    </div>
                </form>
            </div>

            <div class="info-section">
                <h3>Tips for Recording Income</h3>
                <div class="info-tips">
                    <h4>Best Practices</h4>
                    <ul>
                        <li>Record income as soon as you receive it</li>
                        <li>Use clear, descriptive labels</li>
                        <li>Include all sources of income</li>
                        <li>Be consistent with your recording</li>
                    </ul>
                </div>
                <div class="info-tips">
                    <h4>Common Income Sources</h4>
                    <ul>
                        <li>Regular salary</li>
                        <li>Freelance work</li>
                        <li>Investments</li>
                        <li>Side businesses</li>
                        <li>Gifts and bonuses</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</body>
</html> 