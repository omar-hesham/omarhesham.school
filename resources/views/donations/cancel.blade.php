@extends('layouts.auth')
@section('title', 'Donation Cancelled')

@section('content')
<div class="text-center space-y-4">
    <div class="text-5xl">↩️</div>
    <h1 class="text-2xl font-extrabold text-gray-900">Donation Cancelled</h1>
    <p class="text-sm text-gray-600">No payment was taken. You can try again whenever you're ready.</p>
    <a href="{{ route('donations.index') }}"
       class="block w-full bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm hover:shadow-lg transition">
        Try Again
    </a>
    <a href="{{ route('home') }}" class="block text-sm text-gray-400 hover:text-gray-600 transition">Return Home</a>
</div>
@endsection
