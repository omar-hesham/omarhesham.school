<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeacherStudentLink;
use App\Models\ProgressLog;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = TeacherStudentLink::where('teacher_id', auth()->id())
            ->with('student.profile')
            ->get();

        return view('teacher.students.index', compact('students'));
    }

    public function assign(Request $request, User $student)
    {
        abort_unless($student->role === 'student', 422, 'Can only assign students.');

        TeacherStudentLink::firstOrCreate([
            'teacher_id' => auth()->id(),
            'student_id' => $student->id,
        ], ['is_active' => true]);

        AuditLog::record(auth()->id(), 'student_assigned', 'User', $student->id);

        return back()->with('status', __('teacher.student_assigned', ['name' => $student->name]));
    }

    public function remove(User $student)
    {
        TeacherStudentLink::where('teacher_id', auth()->id())
            ->where('student_id', $student->id)
            ->update(['is_active' => false]);

        AuditLog::record(auth()->id(), 'student_removed', 'User', $student->id);

        return back()->with('status', __('teacher.student_removed'));
    }

    public function viewProgress(User $student)
    {
        // Ensure teacher owns this link
        $link = TeacherStudentLink::where('teacher_id', auth()->id())
            ->where('student_id', $student->id)
            ->where('is_active', true)
            ->firstOrFail();

        $logs = ProgressLog::where('user_id', $student->id)
            ->orderByDesc('logged_at')
            ->paginate(30);

        return view('teacher.students.progress', compact('student', 'logs'));
    }

    public function approveLog(Request $request, ProgressLog $log)
    {
        // Confirm this student belongs to this teacher
        $this->authorizeStudentLog($log);

        $log->update(['approved_by' => auth()->id()]);

        AuditLog::record(auth()->id(), 'progress_approved', 'ProgressLog', $log->id);

        return back()->with('status', __('teacher.log_approved'));
    }

    public function rejectLog(Request $request, ProgressLog $log)
    {
        $this->authorizeStudentLog($log);

        // Soft-delete or just un-approve
        $log->delete();

        AuditLog::record(auth()->id(), 'progress_rejected', 'ProgressLog', $log->id);

        return back()->with('status', __('teacher.log_rejected'));
    }

    protected function authorizeStudentLog(ProgressLog $log): void
    {
        $isMyStudent = TeacherStudentLink::where('teacher_id', auth()->id())
            ->where('student_id', $log->user_id)
            ->where('is_active', true)
            ->exists();

        abort_unless($isMyStudent, 403);
    }
}
