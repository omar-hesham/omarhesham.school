@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.moderation.index') }}" class="text-[#1B4332] hover:underline text-sm font-medium">← Moderation Queue</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-2xl font-extrabold text-gray-900">Preview: {{ $item->title }}</h1>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
            <div><span class="text-gray-400">Type:</span> <x-badge color="blue">{{ $item->type }}</x-badge></div>
            <div><span class="text-gray-400">Uploader:</span> <strong>{{ $item->uploader->name }}</strong></div>
            <div><span class="text-gray-400">Status:</span>
                <x-badge :color="match($item->moderationStatus?->status) { 'approved' => 'green', 'rejected' => 'red', default => 'gold' }">
                    {{ $item->moderationStatus?->status ?? 'pending' }}
                </x-badge>
            </div>
            <div><span class="text-gray-400">Uploaded:</span> <strong>{{ $item->created_at->format('M j, Y') }}</strong></div>
        </div>

        {{-- Content preview --}}
        @if ($item->type === 'youtube' && $item->youtube_id)
            <div class="aspect-video rounded-xl overflow-hidden bg-black">
                <iframe src="https://www.youtube-nocookie.com/embed/{{ $item->youtube_id }}"
                        class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
        @elseif ($item->type === 'pdf')
            <div class="bg-gray-50 rounded-xl p-8 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <p class="text-sm">PDF Preview — <a href="{{ route('admin.moderation.download', $item) }}" class="text-[#1B4332] underline">Download to review</a></p>
            </div>
        @elseif ($item->type === 'audio')
            <audio controls class="w-full" src="{{ route('admin.moderation.stream', $item) }}"></audio>
        @elseif ($item->type === 'image')
            <img src="{{ route('admin.moderation.stream', $item) }}" alt="{{ $item->title }}" class="max-w-full rounded-xl">
        @endif
    </div>

    @if ($item->moderationStatus?->status === 'pending')
        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.moderation.approve', $item) }}" class="flex-1">
                @csrf
                <button class="w-full bg-gradient-to-r from-[#1B4332] to-[#2D6A4F] text-white font-bold py-3 rounded-xl text-sm hover:shadow-lg transition">✓ Approve</button>
            </form>
            <button onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                    class="flex-1 bg-red-50 border-2 border-red-200 text-red-700 font-bold py-3 rounded-xl text-sm hover:bg-red-100 transition">✗ Reject</button>
        </div>
    @endif

    {{-- Reject Modal --}}
    <div id="reject-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl">
            <h3 class="font-extrabold text-gray-900 mb-4">Reject this content?</h3>
            <form method="POST" action="{{ route('admin.moderation.reject', $item) }}" class="space-y-3">
                @csrf
                <textarea name="rejection_note" rows="3" required
                          class="w-full border-2 border-gray-200 focus:border-red-400 rounded-xl px-4 py-3 text-sm outline-none resize-none"
                          placeholder="Reason for rejection…"></textarea>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-2.5 rounded-xl text-sm">Confirm Reject</button>
                    <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')"
                            class="flex-1 border-2 border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-semibold">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
