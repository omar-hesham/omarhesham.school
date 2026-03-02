@extends('layouts.dashboard')

@section('dashboard_content')
<div class="p-8 max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">
                السلام عليكم، {{ explode(' ', auth()->user()->name)[0] }} 👋
            </h1>
            <p class="text-gray-500 text-sm mt-1">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <button onclick="document.getElementById('log-modal').classList.remove('hidden')"
                class="bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold px-5 py-2.5 rounded-xl text-sm hover:shadow-lg transition">
            + Log Progress
        </button>
    </div>

    {{-- Daily Ayah --}}
    <div class="bg-gradient-to-br from-[#1B4332] to-[#2D6A4F] rounded-2xl p-6 text-center mb-6">
        <div class="text-[10px] tracking-[3px] text-[#D4AF37] uppercase mb-3">Ayah of the Day</div>
        <p class="font-serif text-white text-2xl leading-loose mb-3" style="direction:rtl; font-family:'Amiri',serif">
            وَلَقَدْ يَسَّرْنَا الْقُرْآنَ لِلذِّكْرِ فَهَلْ مِن مُّدَّكِرٍ
        </p>
        <p class="text-white/70 text-sm mb-2">And We have certainly made the Quran easy to remember. So is there anyone who will be mindful?</p>
        <p class="text-[#D4AF37] text-xs font-semibold">— Al-Qamar 54:17</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach ([['🔥', $streak ?? 0, 'Day Streak'], ['📖', $weeklyLogs ?? 0, 'Logs This Week'], ['✅', $totalLogs ?? 0, 'Total Logged'], ['🏅', isset($badges) ? count($badges) : 0, 'Badges']] as [$emoji, $value, $label])
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                <div class="text-2xl mb-1">{{ $emoji }}</div>
                <div class="text-3xl font-extrabold text-[#1B4332]">{{ $value }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Weekly Progress --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-gray-900 mb-4">This Week's Progress</h2>
            @foreach ([['Mon',80], ['Tue',55], ['Wed',100], ['Thu',67], ['Fri',47], ['Sat',0], ['Sun',7]] as [$day, $pct])
                <div class="flex items-center gap-3 mb-2.5">
                    <span class="w-8 text-xs text-gray-400">{{ $day }}</span>
                    <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full bg-gradient-to-r from-[#1B4332] to-[#D4AF37] transition-all duration-700"
                             style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="w-6 text-xs font-semibold text-[#1B4332]">{{ (int)($pct * 0.15) }}</span>
                </div>
            @endforeach
        </div>

        {{-- Recent Logs --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-900">Recent Logs</h2>
                <a href="{{ route('student.progress.index') }}" class="text-xs text-[#1B4332] font-semibold hover:underline">View all →</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentLogs ?? [] as $log)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div>
                            <div class="text-sm font-semibold text-gray-800">Surah {{ $log->surah_number }}: {{ $log->ayah_from }}–{{ $log->ayah_to }}</div>
                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($log->logged_at)->format('M j') }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-stars :score="$log->quality_score" />
                            <x-badge :color="$log->approved_by ? 'green' : 'gold'">
                                {{ $log->approved_by ? 'approved' : 'pending' }}
                            </x-badge>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No logs yet. Start logging your progress!</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Assigned Teacher --}}
    @if (!empty($teacher))
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mt-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#1B4332] to-[#2D6A4F] flex items-center justify-center text-white font-bold text-lg">
                {{ strtoupper(substr($teacher->name, 0, 1)) }}
            </div>
            <div>
                <div class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Your Teacher</div>
                <div class="font-bold text-gray-900">{{ $teacher->name }}</div>
                <div class="text-xs text-gray-500">{{ $teacher->email }}</div>
            </div>
        </div>
    @endif
</div>

{{-- LOG PROGRESS MODAL --}}
<div id="log-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-extrabold text-gray-900">Log Progress</h2>
            <button onclick="document.getElementById('log-modal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form method="POST" action="{{ route('student.progress.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Surah Number (1–114)</label>
                <input type="number" name="surah_number" min="1" max="114" required
                       class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition"
                       placeholder="e.g. 2">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ayah From</label>
                    <input type="number" name="ayah_from" min="1" required
                           class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ayah To</label>
                    <input type="number" name="ayah_to" min="1" required
                           class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lesson (optional)</label>
                <select name="lesson_id" class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
                    <option value="">— No lesson —</option>
                    @foreach ($enrolledLessons ?? [] as $lesson)
                        <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Quality</label>
                <div class="flex gap-2">
                    @foreach ([1,2,3,4,5] as $n)
                        <label class="flex-1">
                            <input type="radio" name="quality_score" value="{{ $n }}" class="sr-only peer" {{ $n === 4 ? 'checked' : '' }}>
                            <div class="text-center py-2 border-2 rounded-xl text-sm cursor-pointer peer-checked:border-[#1B4332] peer-checked:bg-green-50 peer-checked:text-[#1B4332] font-semibold border-gray-200 hover:border-gray-300 transition">
                                {{ $n }}★
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Notes (optional)</label>
                <textarea name="notes" rows="2" maxlength="500"
                          class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition resize-none"
                          placeholder="How did the session go?"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm">
                    Save Log
                </button>
                <button type="button" onclick="document.getElementById('log-modal').classList.add('hidden')"
                        class="flex-1 border-2 border-gray-200 text-gray-600 font-semibold py-3 rounded-xl text-sm hover:bg-gray-50 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
