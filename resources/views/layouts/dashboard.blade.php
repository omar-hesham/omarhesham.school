@extends('layouts.app')

@section('content')
<div class="flex min-h-[calc(100vh-64px)]">

    {{-- SIDEBAR --}}
    <aside class="w-60 shrink-0 bg-white border-r border-gray-100 flex flex-col py-6 px-3">
        {{-- User card --}}
        <div class="px-3 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D4AF37] to-[#F0C040] flex items-center justify-center font-bold text-[#0A1F14]">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="font-semibold text-gray-900 text-sm truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                </div>
            </div>
            @if(auth()->user()->role === 'student')
                <div class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full w-fit">
                    🔥 {{ $streak ?? 0 }} day streak
                </div>
            @endif
        </div>

        {{-- Nav Links --}}
        <nav class="flex-1 space-y-0.5">
            @foreach ($sidebarLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition
                          {{ request()->routeIs($link['route']) ? 'bg-[#1B4332] text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    {!! $link['icon'] !!}
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Logout --}}
        <div class="px-3 pt-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-2 text-sm text-red-500 hover:text-red-700 font-medium px-3 py-2 rounded-xl hover:bg-red-50 w-full transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 overflow-auto bg-gray-50">
        @yield('dashboard_content')
    </main>
</div>
@endsection
