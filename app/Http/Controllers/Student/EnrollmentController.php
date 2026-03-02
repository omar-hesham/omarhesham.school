<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Program;

class EnrollmentController extends Controller
{
    public function enroll(Program $program)
    {
        abort_unless($program->is_published, 404);

        // Premium check
        if ($program->access_level === 'premium') {
            $hasSub = auth()->user()->subscriptions()->where('status', 'active')->exists();
            abort_unless($hasSub, 403, __('programs.subscribe_for_premium'));
        }

        Enrollment::firstOrCreate([
            'user_id'    => auth()->id(),
            'program_id' => $program->id,
        ]);

        return redirect()->route('programs.show', $program)
            ->with('status', __('student.enrolled', ['program' => $program->title]));
    }

    public function unenroll(Program $program)
    {
        Enrollment::where('user_id', auth()->id())
            ->where('program_id', $program->id)
            ->delete();

        return redirect()->route('programs.index')
            ->with('status', __('student.unenrolled'));
    }
}
