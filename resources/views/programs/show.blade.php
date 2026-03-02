@extends('layouts.app')
@section('title', $program->title)

@section('content')
<div class="max-w-5xl mx-auto px-6 py-12">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-[#1B4332] to-[#2D6A4F] rounded-2xl p-8 mb-8 flex items-end justify-between">
        <div>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full mb-3 inline-block {{ $program->access_level === 'premium' ? 'bg-amber-300/20 text-amber-200' : 'bg-white/20 text-white/80' }}">
                {{ $program->access_level === 'premium' ? '⭐ Premium' : '✓ Free' }}
            </span>
            <h1 class="text-3xl font-extrabold text-white">{{ $program->title }}</h1>
            @if ($program->title_ar)
                <p class="text-[#D4AF37] font-arabic text-xl mt-1 dir-rtl">{{ $program->title_ar }}</p>
            @endif
            @if ($program->description)
                <p class="text-white/70 mt-3 max-w-xl">{{ $program->description }}</p>
            @endif
        </div>
        <div>
            @auth
                @if (!$enrolled)
                    <form method="POST" action="{{ route('student.enrollments.store', $program) }}">
                        @csrf
                        <button class="btn-gold">Enroll Now →</button>
                    </form>
                @else
                    <span class="bg-white/20 text-white font-semibold px-5 py-2.5 rounded-xl text-sm">✓ Enrolled</span>
                @endif
            @else
                <a href="{{ route('register') }}" class="btn-gold">Sign Up to Enroll →</a>
            @endauth
        </div>
    </div>

    {{-- Lessons --}}
    <h2 class="text-xl font-extrabold text-gray-900 mb-4">Lessons ({{ $program->lessons->count() }})</h2>
    <div class="space-y-3">
        @forelse ($program->lessons->sortBy('sort_order') as $lesson)
            <a href="{{ route('lessons.show', $lesson) }}"
               class="flex items-center gap-4 bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition group">
                <div class="w-10 h-10 rounded-xl bg-[#1B4332]/10 flex items-center justify-center text-[#1B4332] font-extrabold">
                    {{ $loop->iteration }}
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-gray-900 group-hover:text-[#1B4332] transition">{{ $lesson->title }}</div>
                    @if ($lesson->description)
                        <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($lesson->description, 80) }}</div>
                    @endif
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-[#1B4332] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @empty
            <p class="text-gray-400 text-sm py-8 text-center">No lessons added yet.</p>
        @endforelse
    </div>
</div>
@endsection
