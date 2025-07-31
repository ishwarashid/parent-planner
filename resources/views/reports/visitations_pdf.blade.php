<!DOCTYPE html>
<html>
<head>
    <title>Visitation Report</title>
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
        <h1>Visitation Report</h1>
        <p>Generated on {{ now()->format('M d, Y H:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Child</th>
                <th>Parent</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Recurring</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($visitations as $visitation)
                <tr>
                    <td>{{ $visitation->child->name }}</td>
                    <td>{{ $visitation->parent->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($visitation->date_start)->format('M d, Y H:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($visitation->date_end)->format('M d, Y H:i A') }}</td>
                    <td>{{ $visitation->is_recurring ? 'Yes' : 'No' }}</td>
                    <td>{{ $visitation->notes ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No visitations found for the selected criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
