@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('teacher.students.index') }}" class="text-[#1B4332] hover:underline text-sm font-medium">← All Students</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-2xl font-extrabold text-gray-900">{{ $student->name }}'s Progress</h1>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        @foreach ([
            ['📖', $logs->sum(fn($l) => $l->ayah_to - $l->ayah_from + 1), 'Total Ayahs'],
            ['📋', $logs->count(), 'Total Sessions'],
            ['⭐', $logs->count() ? round($logs->avg('quality_score'), 1) : '—', 'Avg Quality'],
        ] as [$e, $v, $l])
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                <div class="text-2xl mb-1">{{ $e }}</div>
                <div class="text-3xl font-extrabold text-[#1B4332]">{{ $v }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $l }}</div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Surah</th>
                    <th class="px-5 py-3 text-left">Ayahs</th>
                    <th class="px-5 py-3 text-left">Quality</th>
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 font-semibold text-gray-900">Surah {{ $log->surah_number }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $log->ayah_from }}–{{ $log->ayah_to }}</td>
                        <td class="px-5 py-4"><x-stars :score="$log->quality_score" /></td>
                        <td class="px-5 py-4 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($log->logged_at)->format('M j, Y') }}</td>
                        <td class="px-5 py-4">
                            <x-badge :color="$log->approved_by ? 'green' : 'gold'">
                                {{ $log->approved_by ? 'approved' : 'pending' }}
                            </x-badge>
                        </td>
                        <td class="px-5 py-4">
                            @if (!$log->approved_by)
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('teacher.progress.approve', $log) }}" class="inline">
                                        @csrf
                                        <button class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-green-200 transition">✓ Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('teacher.progress.reject', $log) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="bg-red-50 text-red-600 border border-red-200 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-100 transition">✗ Reject</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No logs yet for this student.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $logs->links() }}</div>
    </div>
</div>
@endsection
