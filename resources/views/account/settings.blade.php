@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-2xl mx-auto">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Account Settings</h1>

    {{-- Profile --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
        <h2 class="font-bold text-gray-900 mb-4">Profile</h2>
        <form method="POST" action="{{ route('account.settings.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Language</label>
                <select name="locale" class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
                    <option value="en" {{ $user->profile?->locale === 'en' ? 'selected' : '' }}>English</option>
                    <option value="ar" {{ $user->profile?->locale === 'ar' ? 'selected' : '' }}>العربية (Arabic)</option>
                </select>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-500">
                <strong>Email:</strong> {{ $user->email }} <span class="text-xs">(cannot be changed)</span>
            </div>
            <button type="submit" class="bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold px-6 py-2.5 rounded-xl text-sm">
                Save Changes
            </button>
        </form>
    </div>

    {{-- Password --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
        <h2 class="font-bold text-gray-900 mb-4">Change Password</h2>
        <form method="POST" action="{{ route('account.password.change') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Current Password</label>
                <input type="password" name="current_password" required
                       class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition @error('current_password') border-red-400 @enderror">
                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
                <input type="password" name="password" required
                       class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition"
                       placeholder="Min. 8 chars, letters + numbers">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
            </div>
            <button type="submit" class="bg-[#1B4332] text-white font-bold px-6 py-2.5 rounded-xl text-sm hover:bg-[#2D6A4F] transition">
                Update Password
            </button>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-white rounded-2xl border-2 border-red-100 shadow-sm p-6" x-data="{ showDelete: false }">
        <h2 class="font-bold text-red-700 mb-1">⚠️ Danger Zone</h2>
        <p class="text-sm text-gray-500 mb-4">These actions are permanent and cannot be undone.</p>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('account.export') }}"
               class="bg-[#1B4332] text-white font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-[#2D6A4F] transition">
                📦 Export My Data (GDPR)
            </a>
            <button @click="showDelete = true"
                    class="bg-red-50 border-2 border-red-200 text-red-700 font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-red-100 transition">
                🗑 Delete Account
            </button>
        </div>

        <div x-show="showDelete" x-transition class="mt-5 border-t border-red-100 pt-4">
            <p class="text-sm text-red-700 font-semibold mb-3">Type DELETE to confirm permanent account deletion:</p>
            <form method="POST" action="{{ route('account.delete') }}" class="flex gap-2">
                @csrf @method('DELETE')
                <input type="text" name="confirm_delete" required pattern="DELETE"
                       class="border-2 border-red-300 focus:border-red-500 rounded-xl px-4 py-2.5 text-sm outline-none flex-1"
                       placeholder='Type "DELETE"'>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition">
                    Confirm Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
