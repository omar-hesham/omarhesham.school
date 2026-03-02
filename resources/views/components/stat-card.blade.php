{{-- Usage: <x-stat-card emoji="🔥" value="7" label="Day Streak" /> --}}
@props(['emoji', 'value', 'label'])

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
    <div class="text-2xl mb-1">{{ $emoji }}</div>
    <div class="text-3xl font-extrabold text-[#1B4332]">{{ $value }}</div>
    <div class="text-xs text-gray-400 mt-0.5">{{ $label }}</div>
</div>
