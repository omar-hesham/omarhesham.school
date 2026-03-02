@extends('emails.layout')

@section('content')
<div style="text-align:center;font-size:44px;margin-bottom:16px;">
    {{ $approved ? '✅' : '❌' }}
</div>

<h1 style="text-align:center;">
    Content {{ $approved ? 'Approved' : 'Rejected' }}
</h1>

<p class="lead" style="text-align:center;">
    Hi <strong>{{ $uploader->name }}</strong>, your uploaded content has been reviewed.
</p>

<div class="info-box {{ $approved ? 'info-green' : 'info-red' }}">
    <strong>Title:</strong> {{ $item->title }}<br>
    <strong>Type:</strong> {{ strtoupper($item->type) }}<br>
    <strong>Uploaded:</strong> {{ $item->created_at->format('F j, Y') }}<br>
    <strong>Decision:</strong> <strong>{{ strtoupper($approved ? 'Approved ✓' : 'Rejected ✗') }}</strong>
</div>

@if ($approved)
    <p>
        Your content is now <strong>live</strong> and visible to enrolled students.
        JazakAllahu Khayran for contributing quality educational material to the platform.
    </p>
    <div class="btn-center">
        <a href="{{ $uploadUrl }}" class="btn-gold">Upload More Content →</a>
    </div>
@else
    <p>
        Unfortunately your content did not meet our moderation standards and has been removed.
        Please review the reason below and feel free to re-upload a revised version.
    </p>
    @if ($rejectionNote)
        <div class="info-box info-amber">
            <strong>Reason:</strong> {{ $rejectionNote }}
        </div>
    @endif
    <p style="font-size:13px;color:#6B7280;">
        If you believe this decision was made in error, please contact
        <a href="mailto:support@omarhesham.school">support@omarhesham.school</a>
        and reference the content title above.
    </p>
    <div class="btn-center">
        <a href="{{ $uploadUrl }}" class="btn-green">Upload Revised Content →</a>
    </div>
@endif
@endsection
