@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">My Progress</h1>
        <a href="{{ route('student.progress.report') }}?format=pdf"
           class="bg-[#1B4332] text-white font-semibold px-4 py-2.5 rounded-xl text-sm hover:bg-[#2D6A4F] transition flex items-center gap-2">
            📄 Download PDF Report
        </a>
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
                    <th class="px-5 py-3 text-left">Notes</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 font-semibold text-gray-900">Surah {{ $log->surah_number }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $log->ayah_from }}–{{ $log->ayah_to }}</td>
                        <td class="px-5 py-4"><x-stars :score="$log->quality_score" /></td>
                        <td class="px-5 py-4 text-gray-500">{{ \Carbon\Carbon::parse($log->logged_at)->format('M j, Y') }}</td>
                        <td class="px-5 py-4">
                            <x-badge :color="$log->approved_by ? 'green' : 'gold'">
                                {{ $log->approved_by ? 'approved' : 'pending' }}
                            </x-badge>
                        </td>
                        <td class="px-5 py-4 text-gray-400 text-xs max-w-[150px] truncate">{{ $log->notes ?? '—' }}</td>
                        <td class="px-5 py-4">
                            @if (!$log->approved_by)
                                <form method="POST" action="{{ route('student.progress.destroy', $log) }}"
                                      onsubmit="return confirm('Delete this log?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400">No progress logs yet. Start logging from your dashboard!</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
