@extends('emails.layout')

@section('content')
<div style="text-align:center;margin-bottom:24px;">
    <div style="font-size:48px;">✅</div>
</div>

<h1 style="text-align:center;">Account Activated!</h1>
<p class="lead" style="text-align:center;">
    As-salāmu ʿalaykum, <strong>{{ $user->name }}</strong>!
    Your parent has approved your account and you can now begin your Hifz journey.
</p>

<div class="info-box info-green" style="text-align:center;">
    🌟 JazakAllahu Khayran to your parent for supporting your Islamic education.
</div>

<h2>What you can do now</h2>
<ul style="color:#4B5563;font-size:14px;padding-left:20px;line-height:2.2;margin-bottom:16px;">
    <li>📖 Browse and enroll in free Hifz programs</li>
    <li>📝 Log your daily Quran memorization progress</li>
    <li>🔥 Build your memorization streak</li>
    <li>🏅 Earn badges as you reach milestones</li>
    <li>👩‍🏫 Get assigned to a qualified teacher</li>
</ul>

<div class="btn-center">
    <a href="{{ $dashboardUrl }}" class="btn-gold">Go to My Dashboard →</a>
</div>

<p style="text-align:center;font-size:13px;color:#9CA3AF;margin-top:8px;">
    Need help? Reply to this email or contact
    <a href="mailto:support@omarhesham.school">support@omarhesham.school</a>
</p>
@endsection
