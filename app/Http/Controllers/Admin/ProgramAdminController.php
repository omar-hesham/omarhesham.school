<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramAdminController extends Controller
{
    public function index()
    {
        $programs = Program::with('creator')->latest()->paginate(20);
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateProgram($request);

        $program = Program::create([
            ...$data,
            'created_by' => auth()->id(),
            'slug'       => Str::slug($data['title']) . '-' . Str::random(4),
        ]);

        AuditLog::record(auth()->id(), 'program_created', 'Program', $program->id);

        return redirect()->route('admin.programs.index')
            ->with('status', __('admin.program_created'));
    }

    public function edit(Program $program)
    {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $data = $this->validateProgram($request);
        $program->update($data);

        AuditLog::record(auth()->id(), 'program_updated', 'Program', $program->id);

        return redirect()->route('admin.programs.index')
            ->with('status', __('admin.program_updated'));
    }

    public function destroy(Program $program)
    {
        AuditLog::record(auth()->id(), 'program_deleted', 'Program', $program->id, [
            'title' => $program->title,
        ]);

        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('status', __('admin.program_deleted'));
    }

    public function publish(Program $program)
    {
        $program->update(['is_published' => !$program->is_published]);

        $action = $program->is_published ? 'program_published' : 'program_unpublished';
        AuditLog::record(auth()->id(), $action, 'Program', $program->id);

        return back()->with('status', $program->is_published
            ? __('admin.program_published')
            : __('admin.program_unpublished')
        );
    }

    protected function validateProgram(Request $request): array
    {
        return $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'title_ar'       => ['nullable', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:5000'],
            'description_ar' => ['nullable', 'string', 'max:5000'],
            'access_level'   => ['required', 'in:free,premium'],
        ]);
    }
}
