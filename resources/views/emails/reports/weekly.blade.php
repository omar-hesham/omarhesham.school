@extends('emails.layout')

@section('content')

@if ($toTeacher)
    <h1>Weekly Student Report</h1>
    <p class="lead">
        Here is the Hifz progress report for your student
        <strong>{{ $student->name }}</strong> for the week of <strong>{{ $weekRange }}</strong>.
    </p>
@else
    <div class="ayah">
        <div class="ayah-ar">وَلَقَدْ يَسَّرْنَا الْقُرْآنَ لِلذِّكْرِ فَهَلْ مِن مُّدَّكِرٍ</div>
        <div class="ayah-en">And We have certainly made the Quran easy to remember.</div>
        <div class="ayah-ref">— Al-Qamar 54:17</div>
    </div>
    <h1>Your Weekly Hifz Report</h1>
    <p class="lead">
        As-salāmu ʿalaykum <strong>{{ $student->name }}</strong>!
        Here is a summary of your Quran memorization this week
        (<strong>{{ $weekRange }}</strong>).
    </p>
@endif

{{-- Stat grid --}}
<table class="stat-grid" cellpadding="6">
    <tr>
        @foreach ([
            ['📖', $totalAyahs,                'Ayahs Logged'],
            ['🔥', $streak . 'd',              'Day Streak'],
            ['📅', $daysLogged . '/7',         'Days Active'],
            ['⭐', number_format($avgQuality, 1), 'Avg Quality'],
        ] as [$emoji, $value, $label])
            <td style="width:25%;text-align:center;padding:12px 6px;background:#F9FAFB;border-radius:10px;">
                <span class="stat-emoji">{{ $emoji }}</span>
                <span class="stat-val">{{ $value }}</span>
                <span class="stat-lbl">{{ $label }}</span>
            </td>
        @endforeach
    </tr>
</table>

@if ($badges->isNotEmpty())
    <div class="info-box info-green" style="margin-top:20px;">
        🏅 <strong>New badge{{ $badges->count() > 1 ? 's' : '' }} earned this week:</strong>
        {{ $badges->pluck('name')->join(', ') }}
    </div>
@endif

<h2>Daily Activity</h2>
@foreach ($dailyBreakdown as $day => $count)
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
        <div style="width:80px;font-size:11px;color:#6B7280;flex-shrink:0;">{{ $day }}</div>
        <div style="flex:1;">
            <div class="progress-wrap">
                <div class="progress-fill" style="width:{{ $maxAyahs > 0 ? round(($count / $maxAyahs) * 100) : 0 }}%"></div>
            </div>
        </div>
        <div style="width:28px;font-size:12px;font-weight:700;color:#1B4332;text-align:right;">{{ $count }}</div>
    </div>
@endforeach

<h2>Session Details</h2>
@if ($logs->isEmpty())
    <div class="info-box info-amber">No progress was logged this week. Keep going — every ayah counts! 💪</div>
@else
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Surah</th>
                <th>Ayahs</th>
                <th>Count</th>
                <th>Quality</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs->sortByDesc('logged_at') as $log)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($log->logged_at)->format('M j') }}</td>
                    <td style="font-weight:600;">{{ $log->surah_number }}</td>
                    <td>{{ $log->ayah_from }}–{{ $log->ayah_to }}</td>
                    <td style="font-weight:700;color:#1B4332;">{{ $log->ayah_to - $log->ayah_from + 1 }}</td>
                    <td class="stars">{{ str_repeat('★', $log->quality_score) }}<span style="color:#E5E7EB;">{{ str_repeat('★', 5 - $log->quality_score) }}</span></td>
                    <td>
                        <span class="badge {{ $log->approved_by ? 'badge-green' : 'badge-amber' }}">
                            {{ $log->approved_by ? 'approved' : 'pending' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($approvedCnt > 0)
        <p style="font-size:13px;color:#6B7280;margin-top:8px;">
            ✅ {{ $approvedCnt }} of {{ $logs->count() }} session{{ $logs->count() !== 1 ? 's' : '' }}
            approved by your teacher.
        </p>
    @endif
@endif

<hr class="divider">
@if ($totalAyahs >= 50)
    <div class="info-box info-green" style="text-align:center;">
        🌟 <strong>Excellent week!</strong> You logged {{ $totalAyahs }} ayahs.
        May Allah make it easy for you and bless your memorization.
    </div>
@elseif ($totalAyahs >= 20)
    <div class="info-box info-blue" style="text-align:center;">
        💪 <strong>Good effort!</strong> {{ $totalAyahs }} ayahs this week.
        Consistency is the key — keep it up!
    </div>
@else
    <div class="info-box info-amber" style="text-align:center;">
        📚 Every ayah is a reward. Try to log a little every day — even one ayah counts!
    </div>
@endif

<div class="btn-center" style="margin-top:20px;">
    <a href="{{ $dashboardUrl }}" class="btn-gold">View Dashboard</a>
    @if (!$toTeacher)
        <a href="{{ $reportUrl }}" class="btn-green">Download PDF Report</a>
    @endif
</div>

@endsection
