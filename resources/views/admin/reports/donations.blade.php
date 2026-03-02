@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Donations Report</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.donations', ['export' => 'csv']) }}"
               class="bg-[#1B4332] text-white font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-[#2D6A4F] transition">
                📥 Export CSV
            </a>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach ([
            ['💰', '$'.number_format(($totals['total'] ?? 0) / 100, 2), 'Total Raised'],
            ['🔁', '$'.number_format(($totals['recurring'] ?? 0) / 100, 2), 'Recurring'],
            ['1️⃣', '$'.number_format(($totals['one_time'] ?? 0) / 100, 2), 'One-Time'],
            ['📊', $totals['count'] ?? 0, 'Donations'],
        ] as [$e, $v, $l])
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                <div class="text-2xl mb-1">{{ $e }}</div>
                <div class="text-2xl font-extrabold text-[#1B4332]">{{ $v }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $l }}</div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Donor</th>
                    <th class="px-5 py-3 text-left">Amount</th>
                    <th class="px-5 py-3 text-left">Type</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3 text-left">Stripe PI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($donations as $donation)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-medium text-gray-800">
                            {{ $donation->user?->name ?? $donation->donor_name ?? 'Anonymous' }}
                        </td>
                        <td class="px-5 py-3 font-semibold text-[#1B4332]">
                            ${{ number_format($donation->amount, 2) }} {{ strtoupper($donation->currency) }}
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ $donation->type === 'recurring' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ str_replace('_', ' ', $donation->type) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ $donation->status === 'completed' ? 'bg-green-100 text-green-700'
                                : ($donation->status === 'failed' ? 'bg-red-100 text-red-700'
                                : 'bg-amber-100 text-amber-700') }}">
                                {{ $donation->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ $donation->created_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3 text-gray-400 text-xs font-mono truncate max-w-[120px]">
                            {{ $donation->stripe_pi_id ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No donations recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $donations->links() }}</div>
    </div>
</div>
@endsection
