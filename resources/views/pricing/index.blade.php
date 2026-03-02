@extends('layouts.app')
@section('title', 'Pricing')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-20 text-center">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-3">Simple, transparent pricing</h1>
    <p class="text-gray-500 text-lg mb-12">Start free. Upgrade when you're ready.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">

        {{-- Free --}}
        <div class="bg-white rounded-2xl border-2 border-gray-100 shadow-sm p-8 text-left">
            <div class="text-sm font-semibold text-green-700 bg-green-50 px-3 py-1 rounded-full inline-block mb-4">Free</div>
            <div class="text-4xl font-extrabold text-gray-900 mb-1">$0</div>
            <p class="text-gray-400 text-sm mb-6">Forever. No credit card required.</p>
            <ul class="space-y-2.5 mb-8 text-sm text-gray-600">
                @foreach (['Access to all free programs', 'Progress logging', 'Streak tracking', 'Badge system', 'PDF progress reports'] as $f)
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ $f }}</li>
                @endforeach
            </ul>
            <a href="{{ route('register') }}" class="block text-center border-2 border-[#1B4332] text-[#1B4332] font-bold py-3 rounded-xl hover:bg-green-50 transition">
                Get Started Free
            </a>
        </div>

        {{-- Premium --}}
        <div class="bg-gradient-to-br from-[#0A1F14] to-[#1B4332] rounded-2xl border-2 border-[#D4AF37]/30 shadow-xl p-8 text-left relative overflow-hidden">
            <div class="absolute top-4 right-4 text-xs font-semibold text-[#0A1F14] bg-[#D4AF37] px-3 py-1 rounded-full">Most Popular</div>
            <div class="text-sm font-semibold text-[#D4AF37] mb-4">Premium</div>
            <div class="text-4xl font-extrabold text-white mb-1">
                ${{ number_format(config('services.stripe.monthly_price_amount', 9), 0) }}
                <span class="text-lg font-normal text-white/60">/mo</span>
            </div>
            <p class="text-white/50 text-sm mb-6">Billed monthly. Cancel anytime.</p>
            <ul class="space-y-2.5 mb-8 text-sm text-white/80">
                @foreach (['Everything in Free', 'All premium programs', 'Priority teacher matching', 'Advanced analytics', 'Unlimited content access', 'Email support'] as $f)
                    <li class="flex items-center gap-2"><span class="text-[#D4AF37]">✓</span> {{ $f }}</li>
                @endforeach
            </ul>
            <a href="{{ route('register') }}" class="block text-center bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl hover:shadow-lg hover:shadow-amber-200/30 transition">
                Start Premium →
            </a>
        </div>
    </div>
</div>
@endsection
