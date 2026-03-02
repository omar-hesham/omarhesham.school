@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-7xl mx-auto">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Admin Dashboard</h1>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        @foreach ([
            ['👥', number_format($stats['total_users'] ?? 0), 'Total Users'],
            ['👶', number_format($stats['total_children'] ?? 0), 'Children'],
            ['⏳', $stats['pending_consents'] ?? 0, 'Pending Consents'],
            ['📤', $stats['pending_content'] ?? 0, 'Pending Content'],
            ['💰', '$'.number_format(($stats['total_donations'] ?? 0) / 100, 0), 'Donations'],
            ['📊', number_format($stats['logs_this_week'] ?? 0), 'Logs/Week'],
        ] as [$e, $v, $l])
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                <div class="text-xl mb-1">{{ $e }}</div>
                <div class="text-2xl font-extrabold text-[#1B4332]">{{ $v }}</div>
                <div class="text-[10px] text-gray-400 mt-0.5 leading-tight">{{ $l }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Audit Log --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-900">Recent Audit Log</h2>
                <a href="{{ route('admin.reports.audit') }}" class="text-xs text-[#1B4332] font-semibold hover:underline">View all →</a>
            </div>
            <div class="space-y-2">
                @forelse ($recentAuditLogs ?? [] as $log)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-2 min-w-0">
                            <x-badge :color="str_contains($log->action, 'ban') ? 'red' : (str_contains($log->action, 'approv') ? 'green' : 'blue')">
                                {{ str_replace('_', ' ', $log->action) }}
                            </x-badge>
                            <span class="text-xs text-gray-500 truncate">{{ $log->user?->name ?? 'System' }}</span>
                        </div>
                        <span class="text-xs text-gray-300 shrink-0 ml-2">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 py-4 text-center">No audit logs yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Pending Consents --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-900">Pending Parent Consents</h2>
                <a href="{{ route('admin.reports.consents') }}" class="text-xs text-[#1B4332] font-semibold hover:underline">View all →</a>
            </div>
            @forelse ($pendingConsents ?? [] as $record)
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                    <div>
                        <div class="text-sm font-semibold text-gray-800">{{ $record->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $record->guardian_email }} · {{ $record->created_at->format('M j') }}</div>
                    </div>
                    <x-badge color="gold">{{ $record->created_at->diffInDays(now()) }}d old</x-badge>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-4 text-center">No pending consents 🎉</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
