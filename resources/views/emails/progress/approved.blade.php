@extends('emails.layout')

@section('content')
<div style="text-align:center;margin-bottom:20px;font-size:44px;">✅</div>
<h1 style="text-align:center;">Session Approved!</h1>

<p class="lead" style="text-align:center;">
    As-salāmu ʿalaykum <strong>{{ $user->name }}</strong>!
    Your teacher <strong>{{ $teacherName }}</strong> has approved your Quran memorization session.
</p>

<div class="info-box info-green">
    <strong>Surah:</strong> {{ $log->surah_number }}<br>
    <strong>Ayahs:</strong> {{ $log->ayah_from }} – {{ $log->ayah_to }}
        ({{ $log->ayah_to - $log->ayah_from + 1 }} ayahs)<br>
    <strong>Quality:</strong>
        <span style="color:#D4AF37;">{{ str_repeat('★', $log->quality_score) }}</span><span style="color:#D1D5DB;">{{ str_repeat('★', 5 - $log->quality_score) }}</span><br>
    <strong>Date:</strong> {{ \Carbon\Carbon::parse($log->logged_at)->format('F j, Y') }}
</div>

@if ($log->notes)
    <p><strong>Your notes:</strong> "{{ $log->notes }}"</p>
@endif

<p style="font-size:14px;color:#4B5563;">
    Every approved session brings you one step closer to completing your Hifz.
    May Allah make it easy for you and accept it. 🤲
</p>

<div class="btn-center">
    <a href="{{ $dashboardUrl }}" class="btn-gold">Log Another Session →</a>
</div>
@endsection
