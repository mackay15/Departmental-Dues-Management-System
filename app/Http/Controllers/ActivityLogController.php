<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = \App\Models\ActivityLog::with(['user', 'subject'])
            ->latest()
            ->paginate(15);

        return view('auditor.logs', compact('logs'));
    }

    public function print()
    {
        // For printing, we might want to get more records without pagination, or a specific filtered set.
        // We'll get the latest 100 for now.
        $logs = \App\Models\ActivityLog::with(['user', 'subject'])
            ->latest()
            ->take(100)
            ->get();

        return view('auditor.logs-print', compact('logs'));
    }
}
