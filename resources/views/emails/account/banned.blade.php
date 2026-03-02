@extends('emails.layout')

@section('content')
<h1>Account Suspended</h1>

<p class="lead">
    Hi <strong>{{ $user->name }}</strong>, your account on
    <strong>omarhesham.school</strong> has been suspended by an administrator.
</p>

<div class="info-box info-red">
    <strong>Reason:</strong> {{ $banReason }}
</div>

<p>As a result of this suspension:</p>
<ul style="color:#4B5563;font-size:14px;padding-left:20px;line-height:2.2;">
    <li>You will not be able to log in to your account</li>
    <li>Your progress and data are preserved but inaccessible</li>
    <li>Any active subscriptions have been paused</li>
</ul>

<div class="info-box info-amber">
    If you believe this suspension was made in error or you would like to appeal,
    please email us at
    <a href="mailto:{{ $appealEmail }}">{{ $appealEmail }}</a>
    with your account email and a description of your situation.
    We will respond within 3 business days.
</div>

<p style="font-size:13px;color:#9CA3AF;margin-top:20px;">
    This action was taken in accordance with our
    <a href="{{ route('policies.terms') }}">Terms of Service</a>.
</p>
@endsection
