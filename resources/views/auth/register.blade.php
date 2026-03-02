@extends('layouts.auth')
@section('title', 'Create Account')

@section('content')
<h1 class="text-2xl font-extrabold text-gray-900 mb-1">Create your account</h1>
<p class="text-sm text-gray-500 mb-6">Begin your Hifz journey on omarhesham.school</p>

<form method="POST" action="{{ route('register') }}" class="space-y-4"
      x-data="{ ageGroup: '{{ old('age_group', 'adult') }}' }">
    @csrf

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required autofocus
               class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition @error('name') border-red-400 @enderror"
               placeholder="Your full name">
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition @error('email') border-red-400 @enderror"
               placeholder="you@example.com">
        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
        <input type="password" name="password" required
               class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition"
               placeholder="Min. 8 characters, letters + numbers">
        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
        <input type="password" name="password_confirmation" required
               class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition"
               placeholder="••••••••">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">I am a</label>
        <select name="role" class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
            <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
            <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Age Group</label>
        <div class="grid grid-cols-2 gap-2">
            <label class="flex items-center gap-2 border-2 rounded-xl px-3 py-2.5 cursor-pointer transition"
                   :class="ageGroup === 'adult' ? 'border-[#1B4332] bg-green-50' : 'border-gray-200'">
                <input type="radio" name="age_group" value="adult" x-model="ageGroup" class="text-[#1B4332]" {{ old('age_group', 'adult') === 'adult' ? 'checked' : '' }}>
                <span class="text-sm font-medium text-gray-700">Adult (18+)</span>
            </label>
            <label class="flex items-center gap-2 border-2 rounded-xl px-3 py-2.5 cursor-pointer transition"
                   :class="ageGroup === 'child' ? 'border-amber-500 bg-amber-50' : 'border-gray-200'">
                <input type="radio" name="age_group" value="child" x-model="ageGroup" class="text-amber-500" {{ old('age_group') === 'child' ? 'checked' : '' }}>
                <span class="text-sm font-medium text-gray-700">Child / Minor</span>
            </label>
        </div>
    </div>

    {{-- Guardian email — shown only for children --}}
    <div x-show="ageGroup === 'child'" x-transition class="space-y-3">
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 text-xs leading-relaxed">
            ⚠️ <strong>Parental Consent Required:</strong> Because you selected "Child / Minor", we will email your parent or guardian for approval before activating your account (COPPA compliance).
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Parent / Guardian Email</label>
            <input type="email" name="guardian_email" value="{{ old('guardian_email') }}"
                   class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition @error('guardian_email') border-red-400 @enderror"
                   placeholder="parent@example.com">
            @error('guardian_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <button type="submit"
            class="w-full bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm hover:shadow-lg hover:shadow-amber-200 transition mt-2">
        Create Account
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-5">
    Already registered?
    <a href="{{ route('login') }}" class="text-[#1B4332] font-semibold hover:underline">Sign in</a>
</p>
<p class="text-center text-xs text-gray-400 mt-3">
    By creating an account you agree to our
    <a href="{{ route('policies.terms') }}" class="underline">Terms</a> and
    <a href="{{ route('policies.privacy') }}" class="underline">Privacy Policy</a>.
</p>
@endsection
