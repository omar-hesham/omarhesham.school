@extends('layouts.auth')
@section('title', 'Awaiting Parental Consent')

@section('content')
<div class="text-center space-y-4">
    <div class="text-5xl">🕐</div>
    <h1 class="text-2xl font-extrabold text-gray-900">Waiting for Parental Approval</h1>
    <p class="text-sm text-gray-600 leading-relaxed">
        We've sent a consent request to your parent or guardian at
        <strong>{{ auth()->user()->profile?->guardian_email }}</strong>.
        Your account will be activated once they approve.
    </p>
    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-4 py-3 text-xs text-left space-y-1">
        <p>• Check your guardian's inbox (and spam folder)</p>
        <p>• The link expires after 7 days</p>
        <p>• Contact <a href="mailto:support@omarhesham.school" class="underline">support@omarhesham.school</a> if you need help</p>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="w-full border-2 border-gray-200 text-gray-600 font-semibold py-3 rounded-xl text-sm hover:bg-gray-50 transition">
            Sign Out
        </button>
    </form>
</div>
@endsection
