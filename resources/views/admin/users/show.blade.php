@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-[#1B4332] hover:underline text-sm font-medium">← All Users</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-2xl font-extrabold text-gray-900">{{ $user->name }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#1B4332] to-[#2D6A4F] flex items-center justify-center text-white font-bold text-2xl mx-auto mb-3">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="font-bold text-gray-900 text-lg">{{ $user->name }}</div>
            <div class="text-sm text-gray-500 mb-3">{{ $user->email }}</div>
            <x-badge :color="$user->is_banned ? 'red' : 'green'">{{ $user->is_banned ? 'banned' : 'active' }}</x-badge>
            <div class="mt-3 space-y-1 text-xs text-gray-400">
                <div>Role: <strong class="text-gray-700 capitalize">{{ str_replace('_', ' ', $user->role) }}</strong></div>
                <div>Age Group: <strong class="text-gray-700">{{ $user->profile?->age_group ?? '—' }}</strong></div>
                <div>Joined: <strong class="text-gray-700">{{ $user->created_at->format('M j, Y') }}</strong></div>
            </div>
        </div>

        {{-- Actions + Stats --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-gray-900 mb-4">Actions</h2>
                <div class="flex gap-3 flex-wrap">
                    @if ($user->is_banned)
                        <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                            @csrf
                            <button class="bg-green-100 text-green-700 border border-green-200 font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-green-200 transition">Unban User</button>
                        </form>
                    @else
                        <button onclick="document.getElementById('ban-modal').classList.remove('hidden')"
                                class="bg-red-50 text-red-700 border border-red-200 font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-red-100 transition">Ban User</button>
                    @endif
                    @if ($user->is_banned && $user->ban_reason)
                        <div class="w-full bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-xs">
                            <strong>Ban reason:</strong> {{ $user->ban_reason }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-gray-900 mb-3">Activity Summary</h2>
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div><div class="text-2xl font-extrabold text-[#1B4332]">{{ $user->progressLogs()->count() }}</div><div class="text-xs text-gray-400">Progress Logs</div></div>
                    <div><div class="text-2xl font-extrabold text-[#1B4332]">{{ $user->enrollments()->count() }}</div><div class="text-xs text-gray-400">Enrollments</div></div>
                    <div><div class="text-2xl font-extrabold text-[#1B4332]">{{ $user->donations()->count() }}</div><div class="text-xs text-gray-400">Donations</div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ban Modal --}}
    <div id="ban-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl">
            <h3 class="font-extrabold text-gray-900 mb-1">Ban {{ $user->name }}?</h3>
            <form method="POST" action="{{ route('admin.users.ban', $user) }}" class="space-y-3">
                @csrf
                <textarea name="ban_reason" rows="3" required
                          class="w-full border-2 border-gray-200 focus:border-red-400 rounded-xl px-4 py-3 text-sm outline-none resize-none"
                          placeholder="Reason for ban…"></textarea>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-2.5 rounded-xl text-sm">Confirm Ban</button>
                    <button type="button" onclick="document.getElementById('ban-modal').classList.add('hidden')"
                            class="flex-1 border-2 border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-semibold">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
