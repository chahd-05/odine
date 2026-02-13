<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ActivityLogController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $logs = ActivityLog::with('user')->latest()->paginate(20);

        return view('activity_logs.index', compact('logs'));
    }
}
