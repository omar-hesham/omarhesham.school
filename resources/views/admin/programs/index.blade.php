@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Programs</h1>
        <a href="{{ route('admin.programs.create') }}" class="bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold px-5 py-2.5 rounded-xl text-sm">+ New Program</a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Title</th>
                    <th class="px-5 py-3 text-left">Level</th>
                    <th class="px-5 py-3 text-left">Students</th>
                    <th class="px-5 py-3 text-left">Lessons</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($programs as $program)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <div class="font-semibold text-gray-900">{{ $program->title }}</div>
                            <div class="text-xs text-gray-400" style="direction:rtl">{{ $program->title_ar }}</div>
                        </td>
                        <td class="px-5 py-4"><x-badge :color="$program->access_level === 'free' ? 'green' : 'gold'">{{ $program->access_level }}</x-badge></td>
                        <td class="px-5 py-4 font-semibold text-[#1B4332]">{{ $program->enrollments()->count() }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $program->lessons()->count() }}</td>
                        <td class="px-5 py-4"><x-badge :color="$program->is_published ? 'green' : 'red'">{{ $program->is_published ? 'live' : 'draft' }}</x-badge></td>
                        <td class="px-5 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.programs.edit', $program) }}"
                                   class="text-xs border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">Edit</a>
                                <form method="POST" action="{{ route('admin.programs.toggle', $program) }}">
                                    @csrf
                                    <button class="text-xs px-3 py-1.5 rounded-lg border transition {{ $program->is_published ? 'bg-red-50 border-red-200 text-red-600 hover:bg-red-100' : 'bg-green-50 border-green-200 text-green-700 hover:bg-green-100' }}">
                                        {{ $program->is_published ? 'Unpublish' : 'Publish' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No programs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $programs->links() }}</div>
    </div>
</div>
@endsection
