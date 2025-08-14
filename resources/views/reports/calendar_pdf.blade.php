<!DOCTYPE html>
<html>
<head>
    <title>Calendar Report</title>
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
        
        .section-title {
            color: #000033;
            border-bottom: 2px solid #000033;
            padding-bottom: 8px;
            margin: 30px 0 20px 0;
            font-size: 18px;
            font-weight: 600;
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
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
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
            color: #000033;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Calendar Report</h1>
        <div class="report-info">
            Generated on {{ formatUserTimezone(now()) }} ({{ getUserTimezone() }})
            @if(isset($type))
                | Type: {{ ucfirst($type) }}
            @endif
        </div>
    </div>
    
    <!-- Summary Section -->
    <div class="summary-box">
        <div class="summary-title">Report Summary</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-value">{{ $events->count() + $visitations->count() }}</div>
                <div class="summary-label">Total Events</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ $events->count() }}</div>
                <div class="summary-label">Custom Events</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ $visitations->count() }}</div>
                <div class="summary-label">Visitations</div>
            </div>
            @if(isset($filters))
                <div class="summary-item">
                    <div class="summary-value">{{ $filters['child'] ?? 'All' }}</div>
                    <div class="summary-label">Child Filter</div>
                </div>
            @endif
        </div>
    </div>

    @if($type === 'event' || $type === 'both')
        <h2 class="section-title">Events</h2>
        @if($events->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Child</th>
                        <th>Assigned To</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Created By</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->child->name ?? 'N/A' }}</td>
                            <td>
                                @if($event->assignee)
                                    {{ $event->assignee->name }} 
                                    @if($event->assignee->roles->count() > 0)
                                        ({{ $event->assignee->roles->first()->name }})
                                    @endif
                                @else
                                    Unassigned
                                @endif
                            </td>
                            <td>{{ formatUserTimezone($event->start) }}</td>
                            <td>{{ $event->end ? formatUserTimezone($event->end) : 'N/A' }}</td>
                            <td>{{ $event->user->name ?? 'N/A' }}</td>
                            <td>{{ formatUserTimezone($event->created_at) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="no-data">No events found for the selected criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <div class="no-data">No events found for the selected criteria.</div>
        @endif
    @endif

    @if($type === 'both')
        <div class="page-break"></div>
    @endif

    @if($type === 'visitation' || $type === 'both')
        <h2 class="section-title">Visitations</h2>
        @if($visitations->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Child</th>
                        <th>Assigned To</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Recurring</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visitations as $visitation)
                        <tr>
                            <td>{{ $visitation->child->name }}</td>
                            <td>
                                {{ $visitation->parent->name }}
                                @if($visitation->parent->roles->count() > 0)
                                    ({{ $visitation->parent->roles->first()->name }})
                                @endif
                            </td>
                            <td>{{ formatUserTimezone($visitation->date_start) }}</td>
                            <td>{{ formatUserTimezone($visitation->date_end) }}</td>
                            <td>
                                @if($visitation->is_recurring)
                                    <span class="recurring-icon">🔁</span>
                                @else
                                    No
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($visitation->status ?? 'scheduled') }}">
                                    {{ ucfirst($visitation->status ?? 'N/A') }}
                                </span>
                            </td>
                            <td>{{ formatUserTimezone($visitation->created_at) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="no-data">No visitations found for the selected criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <div class="no-data">No visitations found for the selected criteria.</div>
        @endif
    @endif
    
    <div class="footer">
        <div class="page-number"></div>
        | Generated by Parent Planner
    </div>
</body>
</html>