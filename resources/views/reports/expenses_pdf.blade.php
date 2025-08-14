<!DOCTYPE html>
<html>
<head>
    <title>Expense Report</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12px;
        }
        
        .header {
            text-align: center; 
            margin-bottom: 30px;
            padding: 20px;
            background-color: #000033;
            color: white;
        }
        
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .report-info {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .summary-box {
            background-color: #f8f9fa;
            border-left: 4px solid #000033;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 0 4px 4px 0;
        }
        
        .summary-title {
            font-weight: 600;
            color: #000033;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
        }
        
        .summary-value {
            font-size: 20px;
            font-weight: 700;
            color: #000033;
        }
        
        .summary-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 25px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        th { 
            background-color: #000033; 
            color: white;
            font-weight: 600;
            padding: 12px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td { 
            border-bottom: 1px solid #e9ecef;
            padding: 10px;
            font-size: 11px;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e9ecef;
        }
        
        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 25px;
            background-color: #f8f9fa;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
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
        
        tfoot {
            font-weight: 600;
            background-color: #e9ecef;
        }
        
        .amount {
            text-align: right;
            font-weight: 600;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        
        .page-number:before {
            content: "Page " counter(page);
        }
        
        @page {
            @bottom-right {
                content: "Page " counter(page);
                font-size: 10px;
                color: #6c757d;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Expense Report</h1>
        <p class="report-info">Generated on {{ formatUserTimezone(now()) }} ({{ getUserTimezone() }})</p>
    </div>
    
    <!-- Summary Section -->
    <div class="summary-box">
        <div class="summary-title">Report Summary</div>
        <div class="summary-grid">
            @php
                $totalAmount = $expenses->sum('amount');
                $paidAmount = $expenses->where('status', 'paid')->sum('amount');
                $pendingAmount = $expenses->where('status', 'pending')->sum('amount');
                $disputedAmount = $expenses->where('status', 'disputed')->sum('amount');
            @endphp
            <div class="summary-item">
                <div class="summary-value">${{ number_format($totalAmount, 2) }}</div>
                <div class="summary-label">Total Expenses</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">${{ number_format($paidAmount, 2) }}</div>
                <div class="summary-label">Paid</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">${{ number_format($pendingAmount, 2) }}</div>
                <div class="summary-label">Pending</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ $expenses->count() }}</div>
                <div class="summary-label">Transactions</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Child</th>
                <th>Payer</th>
                <th>Description</th>
                <th class="amount">Amount</th>
                <th>Category</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0;
                
                function getRoleSuffix($user) {
                    if ($user->hasRole('Admin Co-Parent')) {
                        return ' (Admin Co-Parent)';
                    } elseif ($user->hasRole('Co-Parent')) {
                        return ' (Co-Parent)';
                    } elseif ($user->hasRole('Parent')) {
                        return ' (Parent)';
                    }
                    return '';
                }
            @endphp
            @forelse ($expenses as $expense)
                <tr>
                    <td>{{ $expense->child->name }}</td>
                    <td>{{ $expense->payer->name }}{{ getRoleSuffix($expense->payer) }}</td>
                    <td>{{ $expense->description }}</td>
                    <td class="amount">${{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($expense->status) }}">
                            {{ ucfirst($expense->status) }}
                        </span>
                    </td>
                    <td>{{ formatUserTimezone($expense->created_at, 'M d, Y H:i A') }}</td>
                </tr>
                @php
                    $totalAmount += $expense->amount;
                @endphp
            @empty
                <tr>
                    <td colspan="7" class="no-data">No expenses found for the selected criteria.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;">Total:</td>
                <td class="amount">${{ number_format($totalAmount, 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <div class="page-number"></div>
        | Generated by Parent Planner
    </div>
</body>
</html>
