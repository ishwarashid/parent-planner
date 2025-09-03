<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $transaction['id'] }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
            color: #333;
        }
        
        .header p {
            margin: 0;
            color: #666;
        }
        
        .company-info {
            float: left;
            width: 50%;
        }
        
        .customer-info {
            float: right;
            width: 50%;
            text-align: right;
        }
        
        .clear {
            clear: both;
        }
        
        .invoice-details {
            margin: 30px 0;
        }
        
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .invoice-details th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .invoice-details td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .totals {
            width: 300px;
            margin-left: auto;
            margin-top: 20px;
        }
        
        .totals table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        .totals .total-row td {
            font-weight: bold;
            border-top: 2px solid #333;
            border-bottom: none;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-info">
                <h1>INVOICE</h1>
                <p>{{ config('app.name') }}</p>
                <p>{{ config('app.url') }}</p>
            </div>
            <div class="customer-info">
                <p><strong>Invoice #:</strong> {{ $transaction['id'] }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($transaction['billed_at'])->format('F j, Y') }}</p>
                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $transaction['status'])) }}</p>
            </div>
            <div class="clear"></div>
        </div>
        
        <div class="customer-info-section">
            <h3>Bill To:</h3>
            <p>{{ $user->name }}</p>
            <p>{{ $user->email }}</p>
            @if($user->customer)
                <p>Customer ID: {{ $user->customer->paddle_id }}</p>
            @endif
        </div>
        
        <div class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction['details']['line_items'] as $item)
                        <tr>
                            <td>
                                {{ $item['product']['name'] ?? 'Unnamed Product' }}
                                @if(isset($item['price']['description']) && $item['price']['description'] != ($item['product']['name'] ?? ''))
                                    <br><small>{{ $item['price']['description'] }}</small>
                                @endif
                            </td>
                            <td>{{ $item['quantity'] ?? 1 }}</td>
                            <td>{{ Laravel\Paddle\Cashier::formatAmount($item['unit_totals']['subtotal'] ?? 0, $transaction['details']['totals']['currency_code'] ?? 'USD') }}</td>
                            <td class="text-right">{{ Laravel\Paddle\Cashier::formatAmount($item['totals']['total'] ?? ($item['unit_totals']['total'] ?? 0), $transaction['details']['totals']['currency_code'] ?? 'USD') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">{{ Laravel\Paddle\Cashier::formatAmount($transaction['details']['totals']['subtotal'] ?? 0, $transaction['details']['totals']['currency_code'] ?? 'USD') }}</td>
                </tr>
                @if(isset($transaction['details']['totals']['discount']) && $transaction['details']['totals']['discount'] > 0)
                    <tr>
                        <td>Discount:</td>
                        <td class="text-right">-{{ Laravel\Paddle\Cashier::formatAmount($transaction['details']['totals']['discount'], $transaction['details']['totals']['currency_code'] ?? 'USD') }}</td>
                    </tr>
                @endif
                @if(isset($transaction['details']['totals']['tax']) && $transaction['details']['totals']['tax'] > 0)
                    <tr>
                        <td>Tax:</td>
                        <td class="text-right">{{ Laravel\Paddle\Cashier::formatAmount($transaction['details']['totals']['tax'], $transaction['details']['totals']['currency_code'] ?? 'USD') }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td><strong>Total:</strong></td>
                    <td class="text-right"><strong>{{ Laravel\Paddle\Cashier::formatAmount($transaction['details']['totals']['total'] ?? 0, $transaction['details']['totals']['currency_code'] ?? 'USD') }}</strong></td>
                </tr>
            </table>
        </div>
        
        <div class="footer">
            <p>Invoice Number: {{ $transaction['invoice_number'] ?? 'N/A' }}</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>