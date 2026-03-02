<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Donation;
use App\Models\ConsentRecord;
use App\Models\User;
use App\Models\ContentItem;
use App\Models\ProgressLog;

class ReportController extends Controller
{
    public function adminDashboard()
    {
        $stats = [
            'total_users'      => User::count(),
            'total_children'   => User::whereHas('profile', fn($q) => $q->where('age_group', 'child'))->count(),
            'pending_consents' => ConsentRecord::where('action', 'requested')->count(),
            'pending_content'  => ContentItem::where('is_quarantined', true)->count(),
            'total_donations'  => Donation::where('status', 'completed')->sum('amount'),
            'logs_this_week'   => ProgressLog::where('logged_at', '>=', now()->subDays(7)->toDateString())->count(),
        ];

        $recentAuditLogs = AuditLog::with('user')
            ->latest()
            ->take(15)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAuditLogs'));
    }

    public function auditLog()
    {
        $logs = AuditLog::with('user')
            ->latest()
            ->paginate(50);

        return view('admin.reports.audit', compact('logs'));
    }

    public function donations()
    {
        $donations = Donation::with('user')
            ->latest()
            ->paginate(30);

        $totals = [
            'one_time'  => Donation::where('type', 'one_time')->where('status', 'completed')->sum('amount'),
            'recurring' => Donation::where('type', 'recurring')->where('status', 'completed')->sum('amount'),
        ];

        return view('admin.reports.donations', compact('donations', 'totals'));
    }

    public function consents()
    {
        $records = ConsentRecord::with('user')
            ->latest()
            ->paginate(30);

        return view('admin.reports.consents', compact('records'));
    }
}
