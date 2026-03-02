<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Enrollment;

class ProgramController extends Controller
{
    public function index()
    {
        $query = Program::where('is_published', true)->with('creator');

        // Guests only see free programs
        if (!auth()->check()) {
            $query->where('access_level', 'free');
        }

        $programs = $query->latest()->paginate(12);

        return view('programs.index', compact('programs'));
    }

    public function show(Program $program)
    {
        // Unpublished programs: only admin/teacher can preview
        if (!$program->is_published) {
            abort_unless(
                auth()->check() && in_array(auth()->user()->role, ['admin', 'center_admin', 'teacher']),
                404
            );
        }

        // Premium programs require subscription
        if ($program->access_level === 'premium' && !auth()->check()) {
            return redirect()->route('pricing')->with('status', __('programs.login_for_premium'));
        }

        if ($program->access_level === 'premium' && auth()->check()) {
            $user = auth()->user();
            $hasSub = $user->subscriptions()->where('status', 'active')->exists();
            if (!$hasSub) {
                return redirect()->route('pricing')->with('status', __('programs.subscribe_for_premium'));
            }
        }

        $lessons = $program->lessons()->orderBy('sort_order')->get();

        $isEnrolled = auth()->check()
            ? Enrollment::where('user_id', auth()->id())
                ->where('program_id', $program->id)
                ->exists()
            : false;

        return view('programs.show', compact('program', 'lessons', 'isEnrolled'));
    }
}
