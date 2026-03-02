@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<h1 class="text-2xl font-extrabold text-gray-900 mb-1">Set a new password</h1>
<p class="text-sm text-gray-500 mb-6">Must be at least 8 characters with letters and numbers.</p>

<form method="POST" action="{{ route('password.update') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
        <input type="email" name="email" value="{{ old('email', $email ?? '') }}" required
               class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
        <input type="password" name="password" required
               class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition"
               placeholder="••••••••">
        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
        <input type="password" name="password_confirmation" required
               class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition"
               placeholder="••••••••">
    </div>

    <button type="submit"
            class="w-full bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm">
        Reset Password
    </button>
</form>
@endsection
