@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Users</h1>
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email…"
                   class="border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-2 text-sm outline-none w-64 transition">
            <select name="role" class="border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-3 py-2 text-sm outline-none transition">
                <option value="">All Roles</option>
                @foreach (['student', 'teacher', 'admin', 'center_admin'] as $r)
                    <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $r)) }}</option>
                @endforeach
            </select>
            <button class="bg-[#1B4332] text-white px-4 py-2 rounded-xl text-sm font-semibold">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">User</th>
                    <th class="px-5 py-3 text-left">Role</th>
                    <th class="px-5 py-3 text-left">Age Group</th>
                    <th class="px-5 py-3 text-left">Joined</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-[#1B4332]/10 flex items-center justify-center text-[#1B4332] font-bold text-xs">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4"><x-badge color="blue">{{ str_replace('_', ' ', $user->role) }}</x-badge></td>
                        <td class="px-5 py-4">
                            <x-badge :color="$user->profile?->age_group === 'child' ? 'gold' : 'green'">
                                {{ $user->profile?->age_group ?? '—' }}
                            </x-badge>
                        </td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $user->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-4">
                            <x-badge :color="$user->is_banned ? 'red' : 'green'">
                                {{ $user->is_banned ? 'banned' : 'active' }}
                            </x-badge>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-xs border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition font-medium">View</a>
                                @if (!$user->is_banned)
                                    <button onclick="document.getElementById('ban-modal-{{ $user->id }}').classList.remove('hidden')"
                                            class="text-xs bg-red-50 border border-red-200 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-100 transition font-medium">Ban</button>
                                @else
                                    <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                                        @csrf
                                        <button class="text-xs bg-green-50 border border-green-200 text-green-700 px-3 py-1.5 rounded-lg hover:bg-green-100 transition font-medium">Unban</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- Ban Modal --}}
                    <div id="ban-modal-{{ $user->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl">
                            <h3 class="font-extrabold text-gray-900 mb-1">Ban {{ $user->name }}?</h3>
                            <p class="text-sm text-gray-500 mb-4">They will be immediately logged out and cannot sign in.</p>
                            <form method="POST" action="{{ route('admin.users.ban', $user) }}" class="space-y-3">
                                @csrf
                                <textarea name="ban_reason" rows="3" required
                                          class="w-full border-2 border-gray-200 focus:border-red-400 rounded-xl px-4 py-3 text-sm outline-none resize-none"
                                          placeholder="Reason for ban…"></textarea>
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-2.5 rounded-xl text-sm hover:bg-red-700 transition">Confirm Ban</button>
                                    <button type="button" onclick="document.getElementById('ban-modal-{{ $user->id }}').classList.add('hidden')"
                                            class="flex-1 border-2 border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl text-sm">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $users->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
