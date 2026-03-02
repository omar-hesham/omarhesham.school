{{-- Usage: <x-stars :score="4" /> --}}
@props(['score' => 0])

<span class="text-amber-400 tracking-tight text-sm">
    @for ($i = 1; $i <= 5; $i++)
        <span class="{{ $i <= $score ? 'text-amber-400' : 'text-gray-200' }}">★</span>
    @endfor
</span>
