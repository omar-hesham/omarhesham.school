@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
<h1 class="text-2xl font-extrabold text-gray-900 mb-1">Forgot your password?</h1>
<p class="text-sm text-gray-500 mb-6">Enter your email and we'll send a reset link.</p>

<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition"
               placeholder="you@example.com">
        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <button type="submit"
            class="w-full bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm">
        Send Reset Link
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-5">
    <a href="{{ route('login') }}" class="text-[#1B4332] font-semibold hover:underline">← Back to login</a>
</p>
@endsection
