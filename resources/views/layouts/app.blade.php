<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Memorize the Quran with purpose — omarhesham.school')">
    <title>@yield('title', 'Omar Hesham School') — omarhesham.school</title>
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col"
      x-data>

    {{-- Flash --}}
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition
             class="fixed top-4 right-4 z-50 bg-green-800 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-semibold max-w-xs">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show"
             class="fixed top-4 right-4 z-50 bg-red-700 text-white px-5 py-3 rounded-xl shadow-xl text-sm max-w-sm space-y-1">
            <button @click="show = false" class="absolute top-2 right-3 text-white/70 hover:text-white text-lg">&times;</button>
            @foreach ($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Navbar --}}
    <nav class="bg-[#0A1F14]/95 backdrop-blur sticky top-0 z-40 border-b border-[#D4AF37]/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#D4AF37] to-[#F0C040] flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#0A1F14]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="leading-none">
                    <div class="text-white font-extrabold text-base">Omar Hesham</div>
                    <div class="text-[#D4AF37] text-[10px] tracking-widest">.school</div>
                </div>
            </a>

            {{-- Desktop Links --}}
            <div class="hidden md:flex items-center gap-1">
                @foreach ([['home', 'Home'], ['programs.index', 'Programs'], ['pricing', 'Pricing'], ['donations.index', 'Donate']] as [$route, $label])
                    <a href="{{ route($route) }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs($route) ? 'text-[#D4AF37] bg-[#D4AF37]/10' : 'text-white/80 hover:text-[#D4AF37] hover:bg-white/5' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Auth --}}
            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="text-white/80 hover:text-white text-sm font-medium px-4 py-2">Login</a>
                    <a href="{{ route('register') }}" class="btn-gold text-sm">Sign Up</a>
                @endguest
                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="flex items-center gap-2 bg-[#D4AF37]/20 hover:bg-[#D4AF37]/30 rounded-lg px-3 py-1.5 transition">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#D4AF37] to-[#F0C040] flex items-center justify-center text-[#0A1F14] font-bold text-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="text-white text-sm font-medium hidden sm:block">{{ auth()->user()->name }}</span>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition
                             class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-xl border border-gray-100 py-1 text-sm">
                            <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="block px-4 py-2 hover:bg-gray-50 text-gray-700">Dashboard</a>
                            <a href="{{ route('account.settings') }}" class="block px-4 py-2 hover:bg-gray-50 text-gray-700">Settings</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600">Logout</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-[#0A1F14] text-white/60 py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-6 text-center space-y-4">
            <div class="flex items-center justify-center gap-2">
                <div class="w-7 h-7 rounded-md bg-[#D4AF37]/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-white font-bold">omarhesham.school</span>
            </div>
            <div class="flex gap-6 justify-center flex-wrap text-xs">
                <a href="{{ route('policies.privacy') }}" class="hover:text-white transition">Privacy Policy</a>
                <a href="{{ route('policies.terms') }}" class="hover:text-white transition">Terms of Service</a>
                <a href="{{ route('policies.child-safety') }}" class="hover:text-white transition">Child Safety</a>
                <a href="{{ route('policies.cookies') }}" class="hover:text-white transition">Cookies</a>
            </div>
            <p class="text-xs">© {{ date('Y') }} omarhesham.school — All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
