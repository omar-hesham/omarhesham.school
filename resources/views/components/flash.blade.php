{{-- Usage: <x-flash /> --}}
@if (session('status'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition
         class="fixed top-4 right-4 z-50 bg-green-800 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-semibold max-w-xs">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div x-data="{ show: true }" x-show="show"
         class="fixed top-4 right-4 z-50 bg-red-700 text-white px-5 py-3 rounded-xl shadow-xl text-sm max-w-sm space-y-1">
        <button @click="show = false" class="absolute top-2 right-3 text-white/70 hover:text-white text-lg">&times;</button>
        @foreach ($errors->all() as $error)
            <div>• {{ $error }}</div>
        @endforeach
    </div>
@endif
