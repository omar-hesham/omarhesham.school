@extends('layouts.auth')
@section('title', 'Donation Successful')

@section('content')
<div class="text-center space-y-4">
    <div class="text-5xl">✅</div>
    <h1 class="text-2xl font-extrabold text-gray-900">JazakAllahu Khayran!</h1>
    <p class="text-sm text-gray-600">
        Your donation of <strong>${{ number_format($donation->amount, 2) }}</strong> has been received.
        May Allah reward you immensely for supporting Quran education.
    </p>
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-xs">
        A receipt has been sent to your email address.
    </div>
    <a href="{{ route('home') }}" class="block w-full bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm hover:shadow-lg transition">
        Return Home
    </a>
</div>
@endsection
