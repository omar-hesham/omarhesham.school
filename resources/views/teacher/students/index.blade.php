@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-5xl mx-auto">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">My Students</h1>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Student</th>
                    <th class="px-5 py-3 text-left">Age Group</th>
                    <th class="px-5 py-3 text-left">This Week</th>
                    <th class="px-5 py-3 text-left">Streak</th>
                    <th class="px-5 py-3 text-left">Consent</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($students ?? [] as $link)
                    @php $student = $link->student; @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-[#1B4332]/10 flex items-center justify-center text-[#1B4332] font-bold text-xs">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $student->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <x-badge :color="$student->profile?->age_group === 'child' ? 'gold' : 'blue'">
                                {{ $student->profile?->age_group ?? 'adult' }}
                            </x-badge>
                        </td>
                        <td class="px-5 py-4 font-semibold text-[#1B4332]">
                            {{ $student->progressLogs()->where('logged_at', '>=', now()->subDays(7))->sum(\DB::raw('ayah_to - ayah_from + 1')) }} ayahs
                        </td>
                        <td class="px-5 py-4 text-gray-600">🔥 —</td>
                        <td class="px-5 py-4">
                            @if ($student->profile?->age_group === 'child')
                                <x-badge :color="$student->profile?->consent_status === 'approved' ? 'green' : 'gold'">
                                    {{ $student->profile?->consent_status ?? 'n/a' }}
                                </x-badge>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 flex items-center gap-2">
                            <a href="{{ route('teacher.students.progress', $student) }}"
                               class="text-xs border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">View Progress</a>
                            <form method="POST" action="{{ route('teacher.students.unlink', $link) }}" onsubmit="return confirm('Remove this student?')">
                                @csrf @method('DELETE')
                                <button class="text-xs bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No students assigned yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
