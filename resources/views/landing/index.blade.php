@extends('layouts.app')
@section('title', 'Memorize the Quran with Purpose')
@section('meta_description', 'omarhesham.school — structured Quran memorization programs guided by certified teachers, for students of all ages.')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-[#0A1F14] via-[#1B4332] to-[#2D6A4F] py-24 px-6 text-center">
    <div class="max-w-3xl mx-auto">
        <div class="inline-block text-xs tracking-[4px] text-[#D4AF37] uppercase mb-4 font-semibold">
            بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-4">
            Memorize the Quran<br>
            <span class="text-[#D4AF37]">with Purpose</span>
        </h1>
        <p class="text-white/70 text-lg mb-8 leading-relaxed">
            Structured Hifz programs, certified teachers, and real-time progress tracking — for every student, at every level.
        </p>
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ route('register') }}" class="btn-gold text-base px-7 py-3">Start for Free →</a>
            <a href="{{ route('programs.index') }}" class="border-2 border-white/30 text-white font-semibold px-7 py-3 rounded-xl text-base hover:bg-white/10 transition">
                Browse Programs
            </a>
        </div>
    </div>
</section>

{{-- Features --}}
<section class="py-20 px-6 bg-white">
    <div class="max-w-5xl mx-auto text-center mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-3">Everything you need to memorize</h2>
        <p class="text-gray-500">From structured lessons to progress analytics — built specifically for Hifz.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
        @foreach ([
            ['📖', 'Structured Programs', 'Follow a clear curriculum with sorted lessons and objectives, not scattered notes.'],
            ['👨‍🏫', 'Certified Teachers', 'Learn under verified teachers who track, approve, and guide your daily progress.'],
            ['📊', 'Real-time Analytics', 'Daily streak tracking, quality scores, and PDF reports to measure your journey.'],
            ['👶', 'Child Safety First', 'Full COPPA compliance with parental consent flows for students under 18.'],
            ['🎖️', 'Badges & Milestones', 'Earn achievement badges as you hit memorization milestones to stay motivated.'],
            ['🌐', 'Arabic & English', 'Full bilingual support — switch between Arabic and English at any time.'],
        ] as [$icon, $title, $desc])
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-md transition">
                <div class="text-3xl mb-3">{{ $icon }}</div>
                <h3 class="font-bold text-gray-900 mb-1">{{ $title }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="bg-gradient-to-r from-[#1B4332] to-[#2D6A4F] py-16 px-6 text-center">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-3xl font-extrabold text-white mb-4">Ready to begin?</h2>
        <p class="text-white/70 mb-8">Join thousands of students on their Hifz journey. Free plan available.</p>
        <a href="{{ route('register') }}" class="btn-gold text-base px-8 py-3">Create Free Account →</a>
    </div>
</section>

@endsection
