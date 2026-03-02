@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-6xl mx-auto">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Teacher Dashboard</h1>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach ([
            ['👥', isset($students) ? $students->count() : 0, 'Students'],
            ['⏳', isset($pendingLogs) ? $pendingLogs->count() : 0, 'Pending Logs'],
            ['📤', isset($pendingContent) ? $pendingContent->count() : 0, 'Pending Content'],
            ['✅', $approvedCount ?? 0, 'Approved Logs']
        ] as [$e, $v, $l])
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                <div class="text-2xl mb-1">{{ $e }}</div>
                <div class="text-3xl font-extrabold text-[#1B4332]">{{ $v }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $l }}</div>
            </div>
        @endforeach
    </div>

    {{-- Pending Approvals --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-gray-900">Pending Progress Approvals</h2>
            <a href="{{ route('teacher.students.index') }}" class="text-xs text-[#1B4332] font-semibold hover:underline">All students →</a>
        </div>
        @forelse ($pendingLogs ?? [] as $log)
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#1B4332]/10 flex items-center justify-center text-[#1B4332] font-bold text-xs">
                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800">{{ $log->user->name ?? 'Student' }}</div>
                        <div class="text-xs text-gray-400">Surah {{ $log->surah_number }}: {{ $log->ayah_from }}–{{ $log->ayah_to }} · {{ \Carbon\Carbon::parse($log->logged_at)->format('M j') }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <x-stars :score="$log->quality_score" />
                    <form method="POST" action="{{ route('teacher.progress.approve', $log) }}" class="inline">
                        @csrf
                        <button class="bg-green-100 text-green-700 hover:bg-green-200 font-semibold text-xs px-3 py-1.5 rounded-lg transition">✓ Approve</button>
                    </form>
                    <form method="POST" action="{{ route('teacher.progress.reject', $log) }}" class="inline" onsubmit="return confirm('Reject this log?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-50 text-red-600 hover:bg-red-100 font-semibold text-xs px-3 py-1.5 rounded-lg border border-red-200 transition">✗ Reject</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-400 text-sm py-4 text-center">No pending approvals. 🎉</p>
        @endforelse
    </div>
</div>
@endsection
