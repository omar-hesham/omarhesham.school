@extends('emails.layout')

@section('content')
<h1>Parental Consent Required</h1>
<p class="lead">
    Your child <strong>{{ $child->name }}</strong> has created an account on
    <strong>omarhesham.school</strong>, a Quran memorization and Islamic education
    platform for students of all ages.
</p>

<div class="info-box info-blue">
    <strong>Child's Name:</strong> {{ $child->name }}<br>
    <strong>Child's Email:</strong> {{ $child->email }}<br>
    <strong>Account Created:</strong> {{ $record->created_at->format('F j, Y \a\t g:i A') }}<br>
    <strong>Request Expires:</strong> {{ $expiresAt }}
</div>

<h2>What is omarhesham.school?</h2>
<p>
    omarhesham.school is an Islamic education platform where students memorize the Holy
    Quran (Hifz), learn Tajweed rules, and track their progress under the guidance of
    qualified teachers. Content is moderated before it reaches students, and no
    advertising is shown to minors.
</p>

<h2>What you are consenting to</h2>
<ul style="color:#4B5563;font-size:14px;padding-left:20px;margin-bottom:12px;line-height:2">
    <li>Creating an account for your child on omarhesham.school</li>
    <li>Your child logging Quran memorization progress</li>
    <li>A teacher being assigned to review and approve their progress</li>
    <li>Storing your child's first name, email, and progress data</li>
</ul>

<div class="info-box info-green">
    🔒 <strong>We never sell data, show ads to children, or share data with third parties.</strong>
    Read our <a href="{{ route('policies.child-safety') }}">Child Safety Policy</a> and
    <a href="{{ route('policies.privacy') }}">Privacy Policy</a>.
</div>

<hr class="divider">
<p style="text-align:center;font-weight:700;color:#0A1F14;font-size:15px;">
    Do you approve your child's account?
</p>
<div class="btn-center">
    <a href="{{ $approveUrl }}" class="btn-green">✓ Yes, I Approve</a>
    <a href="{{ $denyUrl }}"    class="btn-red">✗ No, Deny Access</a>
</div>
<p style="text-align:center;font-size:12px;color:#9CA3AF;margin-top:-12px;">
    Or <a href="{{ $infoUrl }}">view full details</a> before deciding.
    This link expires on {{ $expiresAt }}.
</p>

<hr class="divider">
<div class="info-box info-amber">
    ⚠️ If you did not expect this email, your child may have used your email address without
    your knowledge. Simply click "Deny Access" and the account will be blocked immediately.
    Contact <a href="mailto:support@omarhesham.school">support@omarhesham.school</a> if you need help.
</div>
@endsection
