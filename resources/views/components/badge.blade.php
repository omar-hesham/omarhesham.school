{{-- Usage: <x-badge color="green">approved</x-badge> --}}
{{-- color: green | gold | blue | red --}}
@props(['color' => 'green'])

@php
$classes = match($color) {
    'gold'  => 'bg-amber-100 text-amber-700',
    'blue'  => 'bg-blue-100 text-blue-700',
    'red'   => 'bg-red-100 text-red-700',
    default => 'bg-green-100 text-green-700',
};
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $classes }}">
    {{ $slot }}
</span>
