<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Program;
use App\Models\ContentItem;

class LessonController extends Controller
{
    public function show(Program $program, Lesson $lesson)
    {
        abort_unless($lesson->program_id === $program->id, 404);

        // Must be enrolled
        $enrolled = $program->enrollments()->where('user_id', auth()->id())->exists();
        if (!$enrolled) {
            return redirect()->route('programs.show', $program)
                ->with('status', __('lessons.enroll_first'));
        }

        $user = auth()->user();
        $isMinor = $user->profile?->age_group === 'child';

        // Children can only see approved content
        $contentQuery = $lesson->contentItems()->with('moderationStatus');
        if ($isMinor) {
            $contentQuery->whereHas('moderationStatus', fn($q) => $q->where('status', 'approved'));
        }

        $contentItems = $contentQuery->get();

        return view('lessons.show', compact('program', 'lesson', 'contentItems'));
    }
}
