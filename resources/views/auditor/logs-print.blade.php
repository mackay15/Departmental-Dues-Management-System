<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .action-created { color: #166534; font-weight: bold; }
        .action-updated { color: #854d0e; font-weight: bold; }
        .action-deleted { color: #991b1b; font-weight: bold; }
        
        .no-print {
            display: block;
            margin-bottom: 20px;
            text-align: right;
        }
        .btn-print {
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Print Document</button>
        <a href="{{ route('auditor.logs') }}" class="btn-print" style="background-color: #6c757d; margin-left: 10px;">Back</a>
    </div>

    <div class="header">
        <img src="{{ asset('images/compssa_logo.png') }}" alt="COMPSSA Logo" style="height: 60px; margin-bottom: 10px;">
        <h1>COMPSSA System Activity Logs</h1>
        <p>Generated on {{ now()->format('F j, Y, g:i a') }}</p>
        <p>Showing latest {{ $logs->count() }} records</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>User</th>
                <th>Action</th>
                <th>Subject</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        {{ optional($log->user)->name ?? 'System' }}
                        @if($log->user && $log->user->roles->count() > 0)
                            ({{ $log->user->roles->first()->name }})
                        @endif
                    </td>
                    <td class="action-{{ $log->action }}">
                        {{ ucfirst($log->action) }}
                    </td>
                    <td>
                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                    </td>
                    <td>
                        @if($log->action === 'updated' && isset($log->details['new']))
                            Changed {{ count($log->details['new']) }} fields
                        @elseif($log->action === 'created')
                            New Record
                        @elseif($log->action === 'deleted')
                            Deleted Record
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No activity logs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
