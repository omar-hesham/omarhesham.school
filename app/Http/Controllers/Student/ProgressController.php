<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ProgressLog;
use App\Models\AuditLog;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgressController extends Controller
{
    public function __construct(private BadgeService $badgeService) {}

    public function index()
    {
        $logs = ProgressLog::where('user_id', auth()->id())
            ->orderByDesc('logged_at')
            ->paginate(30);

        return view('student.progress', compact('logs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lesson_id'     => ['required', 'exists:lessons,id'],
            'surah_number'  => ['required', 'integer', 'between:1,114'],
            'ayah_from'     => ['required', 'integer', 'min:1', 'max:286'],
            'ayah_to'       => ['required', 'integer', 'min:1', 'max:286', 'gte:ayah_from'],
            'quality_score' => ['required', 'integer', 'between:1,5'],
            'notes'         => ['nullable', 'string', 'max:500'],
        ]);

        $log = ProgressLog::create([
            ...$data,
            'user_id'   => auth()->id(),
            'logged_at' => today()->toDateString(),
        ]);

        // Check if new badges earned
        $newBadges = $this->badgeService->checkAndAward(auth()->id());

        $message = __('student.log_saved');
        if ($newBadges->isNotEmpty()) {
            $message .= ' 🏅 ' . __('student.new_badge', ['badge' => $newBadges->first()->name]);
        }

        return redirect()->route('student.dashboard')->with('status', $message);
    }

    public function destroy(ProgressLog $log)
    {
        abort_unless($log->user_id === auth()->id(), 403);
        abort_if($log->approved_by !== null, 403, __('student.cannot_delete_approved'));

        $log->delete();

        return back()->with('status', __('student.log_deleted'));
    }

    // HTML or PDF weekly report
    public function weeklyReport(Request $request)
    {
        $user = auth()->user();

        $logs = ProgressLog::where('user_id', $user->id)
            ->whereBetween('logged_at', [now()->subDays(7)->toDateString(), today()->toDateString()])
            ->orderBy('logged_at')
            ->get();

        AuditLog::record($user->id, 'progress_report_exported');

        if ($request->query('format') === 'pdf') {
            $pdf = Pdf::loadView('student.report-pdf', compact('user', 'logs'));
            return $pdf->download('hifz-weekly-report.pdf');
        }

        return view('student.report', compact('user', 'logs'));
    }
}
