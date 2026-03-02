@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Parental Consents</h1>
        <div class="flex gap-2">
            @foreach (['all', 'requested', 'approved', 'denied'] as $s)
                <a href="{{ route('admin.reports.consents', ['status' => $s]) }}"
                   class="px-3 py-2 rounded-xl text-xs font-semibold capitalize border-2 transition
                          {{ request('status', 'all') === $s ? 'bg-[#1B4332] text-white border-[#1B4332]' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    {{ $s }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Child</th>
                    <th class="px-5 py-3 text-left">Guardian Email</th>
                    <th class="px-5 py-3 text-left">Requested</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Responded</th>
                    <th class="px-5 py-3 text-left">Age</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($records as $record)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <div class="font-semibold text-gray-900">{{ $record->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $record->user->email }}</div>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $record->guardian_email }}</td>
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ $record->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ $record->action === 'approved' ? 'bg-green-100 text-green-700'
                                : ($record->action === 'denied' ? 'bg-red-100 text-red-700'
                                : 'bg-amber-100 text-amber-700') }}">
                                {{ $record->action }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs">
                            {{ $record->responded_at?->format('M j, Y') ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs">
                            {{ $record->created_at->diffInDays(now()) }}d old
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No consent records found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $records->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
