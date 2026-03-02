<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — omarhesham.school</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0A1F14] via-[#1B4332] to-[#2D6A4F] p-4">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-2">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#D4AF37] to-[#F0C040] flex items-center justify-center shadow-xl">
                    <svg class="w-7 h-7 text-[#0A1F14]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-lg tracking-wide">omarhesham.school</span>
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif
            @if (session('status'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</body>
</html>
