<!DOCTYPE html>
<html>

<head>
    <title>Expense Report</title>
    <style>
        /* Using a common font stack for compatibility */
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding: 15px 20px;
            background-color: #000033;
            color: white;
        }

        .header h1 {
            margin: 0 0 5px 0;
            font-size: 22px;
        }

        .report-info {
            font-size: 11px;
        }

        .summary-box {
            background-color: #f8f9fa;
            border-left: 4px solid #000033;
            padding: 15px;
            margin-bottom: 25px;
        }

        .summary-title {
            font-weight: bold;
            color: #000033;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .summary-grid {
            /* Using tables for PDF layout consistency */
            width: 100%;
        }

        .summary-item {
            text-align: center;
            width: 25%;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #000033;
        }

        .summary-label {
            font-size: 10px;
            color: #6c757d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th {
            background-color: #000033;
            color: white;
            font-weight: bold;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        td {
            border-bottom: 1px solid #e9ecef;
            padding: 8px;
            font-size: 10px;
            vertical-align: top;
        }

        /* Master row for each expense */
        .expense-master-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        /* Sub-row for split details */
        .split-detail-row td {
            padding-left: 25px;
            border-bottom: 1px dotted #ccc;
        }

        .no-data {
            text-align: center;
            padding: 25px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-disputed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .amount {
            text-align: right;
            font-weight: bold;
        }

        tfoot {
            font-weight: bold;
            background-color: #e9ecef;
        }

        .footer {
            position: fixed;
            bottom: -30px;
            /* Adjust if footer is not showing */
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 9px;
            color: #6c757d;
        }

        .page-number:before {
            content: "Page " counter(page);
        }
    </style>
</head>

<body>
    @php
        // --- PRE-CALCULATE ALL SUMMARY VALUES ---
        // These calculations are based on the unique expenses, ensuring no double-counting.
        $totalAmount = $expenses->sum('amount');
        $paidAmount = $expenses->where('status', 'paid')->sum('amount');
        $pendingAmount = $expenses->where('status', 'pending')->sum('amount');
        $totalTransactions = $expenses->count();
    @endphp

    <div class="header">
        <img src="<?php echo public_path('assets/images/light-logo.png'); ?>" alt="Parenting Planner Logo" style="height: 40px; margin-bottom: 10px;">
        <h1>Expense Report</h1>
        <p class="report-info">Generated on {{ now()->format('M d, Y H:i A') }}</p>
    </div>

    <div class="summary-box">
        <div class="summary-title">Report Summary</div>
        <table class="summary-grid">
            <tr>
                <td class="summary-item">
                    <div class="summary-value">${{ number_format($totalAmount, 2) }}</div>
                    <div class="summary-label">Total Expenses</div>
                </td>
                <td class="summary-item">
                    <div class="summary-value">${{ number_format($paidAmount, 2) }}</div>
                    <div class="summary-label">Paid</div>
                </td>
                <td class="summary-item">
                    <div class="summary-value">${{ number_format($pendingAmount, 2) }}</div>
                    <div class="summary-label">Pending</div>
                </td>
                <td class="summary-item">
                    <div class="summary-value">{{ $totalTransactions }}</div>
                    <div class="summary-label">Transactions</div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:12%;">Date</th>
                <th style="width:38%;">Child & Description</th>
                <th style="width:18%;">Created By</th>
                <th style="width:12%;" class="amount">Total</th>
                <th style="width:15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($expenses as $expense)
                {{-- THE MASTER ROW for each expense --}}
                <tr class="expense-master-row">
                    <td>{{ $expense->created_at->format('M d, Y') }}</td>
                    <td>
                        <strong>{{ $expense->child->name ?? 'N/A' }}</strong><br>
                        {{ $expense->description }}
                    </td>
                    <td>{{ $expense->payer->name ?? 'N/A' }}</td>
                    <td class="amount">${{ number_format($expense->amount, 2) }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($expense->status) }}">
                            {{ ucfirst($expense->status) }}
                        </span>
                    </td>
                </tr>

                {{-- THE SUB-ROWS for each person's share (the split) --}}
                @foreach ($expense->splits as $split)
                    <tr class="split-detail-row">
                        <td></td> {{-- Indent --}}
                        <td colspan="4">
                            <strong>↳ Responsible:</strong> {{ $split->user->name ?? 'N/A' }} |
                            <strong>Share:</strong>
                            ${{ number_format($expense->amount * ($split->percentage / 100), 2) }}
                            ({{ $split->percentage }}%) |
                            <strong>Confirmed:</strong>
                            @if ($split->user_id === $expense->payer_id)
                                N/A (Payer)
                            @else
                                {{ $expense->confirmations->contains('user_id', $split->user_id) ? 'Yes' : 'No' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" class="no-data">No expenses found for the selected criteria.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Grand Total:</strong></td>
                <td class="amount">${{ number_format($totalAmount, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generated by Parent Planner
    </div>
</body>

</html>
