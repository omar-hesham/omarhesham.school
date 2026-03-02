@extends('layouts.app')
@section('title', 'Programs')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Quran Programs</h1>
            <p class="text-gray-500 mt-1">Choose a program and start your memorization journey.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($programs as $program)
            <a href="{{ route('programs.show', $program->slug) }}"
               class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden group">
                <div class="bg-gradient-to-br from-[#1B4332] to-[#2D6A4F] h-28 flex items-center justify-center">
                    <svg class="w-12 h-12 text-[#D4AF37]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $program->access_level === 'premium' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                            {{ $program->access_level === 'premium' ? '⭐ Premium' : '✓ Free' }}
                        </span>
                    </div>
                    <h2 class="font-bold text-gray-900 group-hover:text-[#1B4332] transition">{{ $program->title }}</h2>
                    @if ($program->description)
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $program->description }}</p>
                    @endif
                    <div class="mt-3 text-xs text-gray-400">
                        {{ $program->lessons_count ?? 0 }} lessons
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-3 py-24 text-center text-gray-400">
                <div class="text-5xl mb-4">📚</div>
                <p class="font-semibold">No programs published yet.</p>
                <p class="text-sm mt-1">Check back soon!</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $programs->links() }}</div>
</div>
@endsection
