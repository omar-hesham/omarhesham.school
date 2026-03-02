@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-3xl mx-auto">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Upload Content</h1>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6"
         x-data="{ tab: 'file', dragging: false }">

        {{-- Tabs --}}
        <div class="flex gap-2 mb-6">
            <button @click="tab = 'file'" :class="tab === 'file' ? 'bg-[#1B4332] text-white' : 'border-2 border-gray-200 text-gray-600 hover:bg-gray-50'"
                    class="flex-1 py-2.5 rounded-xl font-semibold text-sm transition">📁 Upload File</button>
            <button @click="tab = 'youtube'" :class="tab === 'youtube' ? 'bg-[#1B4332] text-white' : 'border-2 border-gray-200 text-gray-600 hover:bg-gray-50'"
                    class="flex-1 py-2.5 rounded-xl font-semibold text-sm transition">▶ YouTube Link</button>
        </div>

        {{-- File Upload --}}
        <div x-show="tab === 'file'">
            <form method="POST" action="{{ route('teacher.content.upload') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Title</label>
                    <input type="text" name="title" required
                           class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition"
                           placeholder="e.g. Tajweed Rules — Part 1">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">File Type</label>
                    <select name="type" class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
                        <option value="pdf">PDF Document</option>
                        <option value="audio">Audio (MP3/WAV/OGG)</option>
                        <option value="image">Image (JPG/PNG/WebP)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Link to Lesson (optional)</label>
                    <select name="lesson_id" class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
                        <option value="">— None —</option>
                        @foreach ($lessons ?? [] as $lesson)
                            <option value="{{ $lesson->id }}">{{ $lesson->program->title }} — {{ $lesson->title }}</option>
                        @endforeach
                    </select>
                </div>
                <label @dragover.prevent="dragging = true" @dragleave="dragging = false" @drop.prevent="dragging = false"
                       :class="dragging ? 'border-[#1B4332] bg-green-50' : 'border-gray-300 hover:border-gray-400 bg-gray-50'"
                       class="flex flex-col items-center justify-center border-2 border-dashed rounded-2xl p-10 cursor-pointer transition">
                    <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span class="text-sm text-gray-500 font-medium">Click to select or drag & drop</span>
                    <span class="text-xs text-gray-400 mt-1">Max 20MB · PDF, MP3, WAV, OGG, JPG, PNG, WebP</span>
                    <input type="file" name="file" class="sr-only" required>
                </label>
                <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 text-xs">
                    ⚠️ All content is quarantined and must be approved by an admin before students can see it.
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm">
                    Upload Content
                </button>
            </form>
        </div>

        {{-- YouTube --}}
        <div x-show="tab === 'youtube'" x-cloak>
            <form method="POST" action="{{ route('teacher.content.youtube') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Video Title</label>
                    <input type="text" name="title" required
                           class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition"
                           placeholder="e.g. Makhaarij Al-Huruf Explained">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">YouTube URL</label>
                    <input type="url" name="youtube_url" required
                           class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition @error('youtube_url') border-red-400 @enderror"
                           placeholder="https://youtube.com/watch?v=...">
                    @error('youtube_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Link to Lesson (optional)</label>
                    <select name="lesson_id" class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
                        <option value="">— None —</option>
                        @foreach ($lessons ?? [] as $lesson)
                            <option value="{{ $lesson->id }}">{{ $lesson->program->title }} — {{ $lesson->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 text-xs">
                    ⚠️ YouTube videos are embedded using privacy-enhanced mode and require admin approval before appearing to students.
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm">
                    Add YouTube Video
                </button>
            </form>
        </div>
    </div>

    {{-- Existing Uploads --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-bold text-gray-900 mb-4">Your Uploads</h2>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-400 uppercase">
                <tr>
                    <th class="px-4 py-2 text-left">Title</th>
                    <th class="px-4 py-2 text-left">Type</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($myUploads ?? [] as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $item->title }}</td>
                        <td class="px-4 py-3"><x-badge color="blue">{{ $item->type }}</x-badge></td>
                        <td class="px-4 py-3">
                            <x-badge :color="match($item->moderationStatus?->status) { 'approved' => 'green', 'rejected' => 'red', default => 'gold' }">
                                {{ $item->moderationStatus?->status ?? 'pending' }}
                            </x-badge>
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $item->created_at->format('M j, Y') }}</td>
                        <td class="px-4 py-3">
                            @if ($item->moderationStatus?->status !== 'approved')
                                <form method="POST" action="{{ route('teacher.content.destroy', $item) }}" onsubmit="return confirm('Delete this upload?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">No uploads yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
