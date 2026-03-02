@extends('layouts.auth')
@section('title', 'Sign In')

@section('content')
<h1 class="text-2xl font-extrabold text-gray-900 mb-1">Welcome back</h1>
<p class="text-sm text-gray-500 mb-6">Sign in to continue your Hifz journey.</p>

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition @error('email') border-red-400 @enderror"
               placeholder="you@example.com">
        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <div class="flex justify-between items-center mb-1.5">
            <label class="text-sm font-semibold text-gray-700">Password</label>
            <a href="{{ route('password.request') }}" class="text-xs text-[#1B4332] hover:underline font-medium">Forgot password?</a>
        </div>
        <input type="password" name="password" required
               class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition @error('password') border-red-400 @enderror"
               placeholder="••••••••">
        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#1B4332] focus:ring-[#1B4332]">
        <span class="text-sm text-gray-600">Remember me</span>
    </label>

    <button type="submit"
            class="w-full bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm hover:shadow-lg hover:shadow-amber-200 transition">
        Sign In
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-6">
    Don't have an account?
    <a href="{{ route('register') }}" class="text-[#1B4332] font-semibold hover:underline">Create one free</a>
</p>
@endsection
