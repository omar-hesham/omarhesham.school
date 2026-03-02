@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Audit Log</h1>
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search action or user…"
                   class="border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-2 text-sm outline-none w-56 transition">
            <input type="date" name="from" value="{{ request('from') }}"
                   class="border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-3 py-2 text-sm outline-none transition">
            <input type="date" name="to" value="{{ request('to') }}"
                   class="border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-3 py-2 text-sm outline-none transition">
            <button class="bg-[#1B4332] text-white px-4 py-2 rounded-xl text-sm font-semibold">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Action</th>
                    <th class="px-5 py-3 text-left">User</th>
                    <th class="px-5 py-3 text-left">Subject</th>
                    <th class="px-5 py-3 text-left">IP Address</th>
                    <th class="px-5 py-3 text-left">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ str_contains($log->action, 'ban') ? 'bg-red-100 text-red-700'
                                : (str_contains($log->action, 'approv') ? 'bg-green-100 text-green-700'
                                : (str_contains($log->action, 'delete') ? 'bg-orange-100 text-orange-700'
                                : 'bg-blue-100 text-blue-700')) }}">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $log->user?->name ?? 'System' }}</td>
                        <td class="px-5 py-3 text-gray-400 text-xs">
                            @if ($log->auditable_type)
                                {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs font-mono">{{ $log->ip_address ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ $log->created_at->format('M j, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">No audit logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $logs->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
