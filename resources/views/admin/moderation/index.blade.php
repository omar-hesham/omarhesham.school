@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-7xl mx-auto">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Content Moderation</h1>

    {{-- Status filter tabs --}}
    <div class="flex gap-2 mb-6">
        @foreach (['pending', 'approved', 'rejected'] as $s)
            <a href="{{ route('admin.moderation.index', ['status' => $s]) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold capitalize border-2 transition
                      {{ ($status ?? 'pending') === $s ? 'bg-[#1B4332] text-white border-[#1B4332]' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ $s }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Content</th>
                    <th class="px-5 py-3 text-left">Type</th>
                    <th class="px-5 py-3 text-left">Uploader</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($items as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 font-semibold text-gray-900">{{ $item->title }}</td>
                        <td class="px-5 py-4"><x-badge color="blue">{{ $item->type }}</x-badge></td>
                        <td class="px-5 py-4 text-gray-500">{{ $item->uploader->name }}</td>
                        <td class="px-5 py-4">
                            <x-badge :color="match($item->moderationStatus?->status) { 'approved' => 'green', 'rejected' => 'red', default => 'gold' }">
                                {{ $item->moderationStatus?->status ?? 'pending' }}
                            </x-badge>
                        </td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $item->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.moderation.preview', $item) }}"
                                   class="text-xs border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">Preview</a>
                                @if ($item->moderationStatus?->status === 'pending')
                                    <form method="POST" action="{{ route('admin.moderation.approve', $item) }}" class="inline">
                                        @csrf
                                        <button class="text-xs bg-green-100 text-green-700 hover:bg-green-200 font-semibold px-3 py-1.5 rounded-lg transition">✓ Approve</button>
                                    </form>
                                    <button onclick="document.getElementById('reject-modal-{{ $item->id }}').classList.remove('hidden')"
                                            class="text-xs bg-red-50 border border-red-200 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">✗ Reject</button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- Reject Modal --}}
                    <div id="reject-modal-{{ $item->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl">
                            <h3 class="font-extrabold text-gray-900 mb-4">Reject "{{ Str::limit($item->title, 30) }}"?</h3>
                            <form method="POST" action="{{ route('admin.moderation.reject', $item) }}" class="space-y-3">
                                @csrf
                                <textarea name="rejection_note" rows="3" required
                                          class="w-full border-2 border-gray-200 focus:border-red-400 rounded-xl px-4 py-3 text-sm outline-none resize-none"
                                          placeholder="Reason for rejection…"></textarea>
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-2.5 rounded-xl text-sm">Confirm Reject</button>
                                    <button type="button" onclick="document.getElementById('reject-modal-{{ $item->id }}').classList.add('hidden')"
                                            class="flex-1 border-2 border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-semibold">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No {{ $status ?? 'pending' }} content.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $items->links() }}</div>
    </div>
</div>
@endsection
