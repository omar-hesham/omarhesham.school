<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ProgressLog;
use App\Models\TeacherStudentLink;
use App\Models\ContentItem;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();

        // All active students assigned to this teacher
        $students = TeacherStudentLink::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->with('student.profile')
            ->get()
            ->pluck('student');

        // Logs awaiting approval
        $pendingLogs = ProgressLog::whereIn('user_id', $students->pluck('id'))
            ->whereNull('approved_by')
            ->with('student', 'lesson.program')
            ->latest()
            ->take(20)
            ->get();

        // Content uploads pending moderation by this teacher
        $pendingContent = ContentItem::where('uploaded_by', $teacher->id)
            ->where('is_quarantined', true)
            ->with('moderationStatus')
            ->latest()
            ->take(10)
            ->get();

        return view('teacher.dashboard', compact('students', 'pendingLogs', 'pendingContent'));
    }
}
