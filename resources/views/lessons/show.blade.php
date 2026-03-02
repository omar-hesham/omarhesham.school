@extends('layouts.app')
@section('title', $lesson->title)

@section('content')
<div class="max-w-5xl mx-auto px-6 py-12">

    {{-- Breadcrumb --}}
    <nav class="text-xs text-gray-400 mb-6">
        <a href="{{ route('programs.index') }}" class="hover:text-[#1B4332]">Programs</a>
        <span class="mx-1">›</span>
        <a href="{{ route('programs.show', $lesson->program->slug) }}" class="hover:text-[#1B4332]">{{ $lesson->program->title }}</a>
        <span class="mx-1">›</span>
        <span class="text-gray-700">{{ $lesson->title }}</span>
    </nav>

    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $lesson->title }}</h1>
    @if ($lesson->title_ar)
        <p class="font-arabic text-[#D4AF37] text-xl dir-rtl mb-4">{{ $lesson->title_ar }}</p>
    @endif
    @if ($lesson->description)
        <p class="text-gray-500 mb-8">{{ $lesson->description }}</p>
    @endif

    {{-- Content Items --}}
    @if ($contentItems->isEmpty())
        <div class="bg-gray-50 rounded-2xl border border-gray-100 p-12 text-center text-gray-400">
            <div class="text-4xl mb-3">📂</div>
            <p>No content has been added to this lesson yet.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($contentItems as $item)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shrink-0
                        {{ $item->type === 'pdf' ? 'bg-red-50' : ($item->type === 'audio' ? 'bg-purple-50' : ($item->type === 'youtube' ? 'bg-red-50' : 'bg-blue-50')) }}">
                        {{ $item->type === 'pdf' ? '📄' : ($item->type === 'audio' ? '🎵' : ($item->type === 'youtube' ? '▶️' : '🖼️')) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $item->title }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ $item->type }}</p>
                    </div>
                    @if ($item->type === 'youtube' && $item->youtube_id)
                        <a href="https://www.youtube-nocookie.com/embed/{{ $item->youtube_id }}" target="_blank" rel="noopener"
                           class="text-xs bg-red-50 border border-red-200 text-red-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                            Watch →
                        </a>
                    @elseif (in_array($item->type, ['pdf', 'audio', 'image']))
                        <a href="{{ route('student.content.stream', $item) }}"
                           class="text-xs bg-[#1B4332]/10 text-[#1B4332] font-semibold px-3 py-1.5 rounded-lg hover:bg-[#1B4332]/20 transition">
                            Open →
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
