@extends('layouts.app')
@section('title', 'Support Our Mission')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-16">
    <div class="text-center mb-10">
        <div class="text-5xl mb-4">🤲</div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Support Our Mission</h1>
        <p class="text-gray-500">Help us keep Quran education accessible to everyone.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form method="POST" action="{{ route('donations.checkout') }}" class="space-y-5" id="donation-form">
            @csrf

            {{-- Amount --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Choose Amount</label>
                <div class="grid grid-cols-4 gap-2 mb-3" x-data="{ amount: 20 }">
                    @foreach ([10, 20, 50, 100] as $preset)
                        <label class="text-center">
                            <input type="radio" name="amount" value="{{ $preset }}" class="sr-only peer" {{ $preset === 20 ? 'checked' : '' }}>
                            <div class="py-2.5 border-2 rounded-xl text-sm font-bold cursor-pointer peer-checked:border-[#1B4332] peer-checked:bg-green-50 peer-checked:text-[#1B4332] border-gray-200 hover:border-gray-300 transition">
                                ${{ $preset }}
                            </div>
                        </label>
                    @endforeach
                </div>
                <input type="number" name="custom_amount" min="1" step="0.01" placeholder="Or enter custom amount"
                       class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Donation Type</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 border-2 rounded-xl px-3 py-2.5 cursor-pointer hover:border-gray-300 transition has-[:checked]:border-[#1B4332] has-[:checked]:bg-green-50">
                        <input type="radio" name="type" value="one_time" checked class="text-[#1B4332]">
                        <span class="text-sm font-medium text-gray-700">One-time</span>
                    </label>
                    <label class="flex items-center gap-2 border-2 rounded-xl px-3 py-2.5 cursor-pointer hover:border-gray-300 transition has-[:checked]:border-[#1B4332] has-[:checked]:bg-green-50">
                        <input type="radio" name="type" value="recurring" class="text-[#1B4332]">
                        <span class="text-sm font-medium text-gray-700">Monthly recurring</span>
                    </label>
                </div>
            </div>

            @guest
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Your Name (optional)</label>
                    <input type="text" name="donor_name"
                           class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition"
                           placeholder="Anonymous">
                </div>
            @endguest

            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-xs">
                🔒 Payments are processed securely via Stripe. Your card details never touch our servers.
            </div>

            <button type="submit"
                    class="w-full bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm hover:shadow-lg transition">
                Donate Now →
            </button>
        </form>
    </div>
</div>
@endsection
