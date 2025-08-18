<!DOCTYPE html>
<html>

<head>
    <title>Calendar Report</title>
    <style>
        /* Using a common font stack for PDF compatibility */
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
            padding: 20px;
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

        .section-title {
            color: #000033;
            border-bottom: 2px solid #000033;
            padding-bottom: 8px;
            margin: 25px 0 15px 0;
            font-size: 16px;
            font-weight: bold;
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

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .no-data {
            text-align: center;
            padding: 25px;
        }

        .footer {
            position: fixed;
            bottom: -30px;
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

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }

        .status-scheduled {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .recurring-icon {
            font-family: DejaVu Sans, sans-serif;
        }

        /* Ensure icon renders */
    </style>
</head>

<body>
    <div class="header">
        <h1>Calendar Report</h1>
        <div class="report-info">
            Generated on {{ now()->format('M d, Y H:i A') }} | Type: {{ ucfirst($type) }}
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-title">Report Summary</div>
        <table class="summary-grid">
            <tr>
                <td class="summary-item">
                    <div class="summary-value">{{ $events->count() + $visitations->count() }}</div>
                    <div class="summary-label">Total Items</div>
                </td>
                <td class="summary-item">
                    <div class="summary-value">{{ $events->count() }}</div>
                    <div class="summary-label">Custom Events</div>
                </td>
                <td class="summary-item">
                    <div class="summary-value">{{ $visitations->count() }}</div>
                    <div class="summary-label">Visitations</div>
                </td>
            </tr>
        </table>
    </div>

    @if ($type === 'event' || $type === 'both')
        <h2 class="section-title">Events</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Child</th>
                    <th>Assigned To</th>
                    <th>Start Time</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->child->name ?? 'N/A' }}</td>
                        <td>{{ $event->assignee->name ?? 'N/A' }}</td>
                        <td>{{ $event->start->format('M d, Y H:i A') }}</td>
                        <td>{{ $event->user->name ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="no-data">No events found for the selected criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    @if ($type === 'visitation' || $type === 'both')
        <h2 class="section-title">Visitations</h2>
        <table>
            <thead>
                <tr>
                    <th>Child</th>
                    <th>Assigned To</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Recurring</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($visitations as $visitation)
                    <tr>
                        <td>{{ $visitation->child->name ?? 'N/A' }}</td>
                        <td>{{ $visitation->parent->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($visitation->date_start)->format('M d, Y H:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($visitation->date_end)->format('M d, Y H:i A') }}</td>
                        <td>
                            @if ($visitation->is_recurring)
                                <span class="recurring-icon">✔️ Yes</span>
                            @else
                                No
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ strtolower($visitation->status ?? 'scheduled') }}">
                                {{ ucfirst($visitation->status ?? 'Scheduled') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-data">No visitations found for the selected criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">
        Generated by Parent Planner
    </div>
</body>

</html>
