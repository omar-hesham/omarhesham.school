@extends('emails.layout')

@section('content')
<h1>Account Access Denied</h1>
<p class="lead">
    Hi <strong>{{ $user->name }}</strong>, your parent or guardian has reviewed your
    account request on <strong>omarhesham.school</strong> and has decided not to
    approve it at this time.
</p>

<div class="info-box info-amber">
    Your account has been blocked and you will not be able to log in.
    No data has been stored beyond what is required to process this consent request.
</div>

<p>
    If you believe this was a mistake, please speak with your parent or guardian.
    They can contact us at
    <a href="mailto:support@omarhesham.school">support@omarhesham.school</a>
    if they would like to reconsider.
</p>

<p>We hope you get the chance to join us in the future. May Allah guide you. 🤲</p>
@endsection
