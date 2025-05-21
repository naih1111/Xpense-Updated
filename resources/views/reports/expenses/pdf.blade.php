<!DOCTYPE html>
<html>
<head>
    <title>Expenses Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <h1>Expenses Report</h1>
    
    <div class="total">
        Total Expenses: ${{ number_format($report['total'], 2) }}
    </div>

    <table>
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
</body>
</html> 