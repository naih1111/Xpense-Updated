<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --dark-blue: #0a0a23;
            --dark-blue-light: #112240;
            --primary: #4f5ebd;
            --primary-light: #7e74f1;
            --success: #4caf50;
            --text-primary: #1f2b53;
            --text-secondary: #6b7280;
            --card-bg: #ffffff;
            --border-color: #e5e7eb;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        body {
            background-color: #e6eaf5;
            font-family: 'Figtree', sans-serif;
        }

        .app-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 260px;
            background-color: var(--dark-blue);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 40px;
            height: 100%;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .sidebar-logo {
            width: 80px;
            margin-bottom: 20px;
        }

        .sidebar-title {
            margin-bottom: 40px;
            font-size: 22px;
            font-weight: bold;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            width: 100%;
            padding: 0 20px;
            height: calc(100% - 200px);
        }

        .sidebar-nav a {
            display: block;
            background-color: var(--primary);
            color: white;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-nav a:hover {
            background-color: var(--primary-light);
        }

        .sidebar-nav a.active {
            background-color: var(--primary-light);
        }

        .sidebar-nav .btn-secondary {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%;
        }

        .sidebar-nav .btn-secondary:hover {
            background-color: var(--primary-light);
        }

        .sidebar-nav form button.btn-secondary {
            background-color: transparent;
            border: 2px solid var(--primary-light);
            color: var(--primary-light);
        }

        .sidebar-nav form button.btn-secondary:hover {
            background-color: rgba(126, 116, 241, 0.1);
        }

        .sidebar-nav .bottom-section {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .main-content {
            margin-left: 260px;
            padding: 40px;
            width: calc(100% - 260px);
            height: 100vh;
            overflow-y: auto;
            position: relative;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            color: var(--text-primary);
            font-size: 24px;
            font-weight: 600;
        }

        .card {
            background: linear-gradient(135deg, var(--card-bg) 0%, #f8f9fe 100%);
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 94, 189, 0.2);
        }

        .btn-secondary {
            background: white;
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: #f8f9fe;
            border-color: var(--primary);
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 94, 189, 0.1);
            outline: none;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .form-header h2 {
            color: var(--text-primary);
            font-size: 1.25rem;
            font-weight: 600;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .currency-input {
            position: relative;
            display: flex;
            align-items: center;
        }

        .currency-symbol {
            position: absolute;
            left: 1rem;
            color: var(--text-secondary);
        }

        .currency-input input {
            padding-left: 2rem;
        }

        @media (max-width: 768px) {
            .app-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 20px;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
                height: auto;
            }

            .sidebar-nav {
                height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <img src="{{ asset('images/XPENSELosgz (2).png') }}" alt="XPENSE Logo" class="sidebar-logo">
            <div class="sidebar-title">XPENSE</div>
            <nav class="sidebar-nav">
                <div>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}">Expenses</a>
                    <a href="{{ route('incomes.index') }}" class="{{ request()->routeIs('incomes.*') ? 'active' : '' }}">Income</a>
                    <a href="{{ route('financial-goals.index') }}" class="{{ request()->routeIs('financial-goals.*') ? 'active' : '' }}">Goals</a>
                    <a href="{{ route('insights.index') }}" class="{{ request()->routeIs('insights.*') ? 'active' : '' }}">Report</a>
                </div>
                <div class="bottom-section">
                    <a href="{{ route('profile.edit') }}" class="btn-secondary">My Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="btn-secondary">Logout</button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            @if (isset($header))
                <header class="page-header">
                    {{ $header }}
                </header>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('styles')
    @stack('scripts')
</body>
</html> 