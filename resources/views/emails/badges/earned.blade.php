@extends('emails.layout')

@section('content')
<div style="text-align:center;margin-bottom:24px;">
    <div style="font-size:64px;line-height:1;margin-bottom:8px;">{{ $badge->emoji }}</div>
    <div style="display:inline-block;padding:3px 14px;border-radius:20px;font-size:11px;font-weight:700;
                background:{{ $badge->tierColor() }}20;color:{{ $badge->tierColor() }};
                border:1px solid {{ $badge->tierColor() }}40;text-transform:uppercase;letter-spacing:1px;">
        {{ $badge->tier }} badge
    </div>
</div>

<h1 style="text-align:center;">You earned a badge!</h1>
<p class="lead" style="text-align:center;">
    Congratulations <strong>{{ $user->name }}</strong> — you've earned the
    <strong>{{ $badge->name }}</strong> badge!
</p>

<div style="background:linear-gradient(135deg,#0A1F14,#1B4332);border-radius:16px;padding:28px;text-align:center;margin:24px 0;">
    <div style="font-size:52px;margin-bottom:10px;">{{ $badge->emoji }}</div>
    <div style="color:#D4AF37;font-size:20px;font-weight:800;margin-bottom:6px;">{{ $badge->name }}</div>
    @if($badge->name_ar)
        <div style="color:rgba(255,255,255,.7);font-family:Georgia,serif;font-size:16px;direction:rtl;margin-bottom:10px;">
            {{ $badge->name_ar }}
        </div>
    @endif
    <div style="color:rgba(255,255,255,.65);font-size:13px;line-height:1.6;">{{ $badge->description }}</div>
    <div style="margin-top:14px;display:inline-block;background:rgba(212,175,55,.2);
                color:#D4AF37;border:1px solid rgba(212,175,55,.3);
                padding:4px 14px;border-radius:20px;font-size:12px;font-weight:700;">
        +{{ $badge->xp_value }} XP
    </div>
</div>

<p style="text-align:center;font-size:14px;color:#6B7280;margin-bottom:24px;">
    Keep up the excellent work. Every step in your Hifz journey is a reward in itself.
    May Allah bless your memorization. 🤲
</p>

<div style="text-align:center;">
    <a href="{{ $dashboardUrl }}" class="btn-gold">View My Badges →</a>
</div>
@endsection
