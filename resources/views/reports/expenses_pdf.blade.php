<!DOCTYPE html>
<html>
<head>
    <title>Expense Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Expense Report</h1>
        <p>Generated on {{ now()->format('M d, Y H:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Child</th>
                <th>Payer</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Category</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0;
            @endphp
            @forelse ($expenses as $expense)
                <tr>
                    <td>{{ $expense->child->name }}</td>
                    <td>{{ $expense->payer->name }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>${{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>{{ ucfirst($expense->status) }}</td>
                    <td>{{ \Carbon\Carbon::parse($expense->created_at)->format('M d, Y') }}</td>
                </tr>
                @php
                    $totalAmount += $expense->amount;
                @endphp
            @empty
                <tr>
                    <td colspan="7">No expenses found for the selected criteria.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align: right;">Total:</th>
                <th>${{ number_format($totalAmount, 2) }}</th>
                <th colspan="3"></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
