@extends('layouts.auth')
@section('title', 'Parental Consent Request')

@section('content')
<div class="space-y-5">
    <div class="text-center">
        <div class="text-4xl mb-3">🤲</div>
        <h1 class="text-xl font-extrabold text-gray-900">Parental Consent Request</h1>
        <p class="text-sm text-gray-500 mt-1">omarhesham.school — Quran Memorization Platform</p>
    </div>

    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-gray-700 space-y-2">
        <p><strong>Child's Name:</strong> {{ $child->name }}</p>
        <p><strong>Child's Email:</strong> {{ $child->email }}</p>
        <p><strong>Request Date:</strong> {{ $record->created_at->format('F j, Y') }}</p>
    </div>

    <div class="text-sm text-gray-600 leading-relaxed space-y-2">
        <p>Your child wants to create an account on <strong>omarhesham.school</strong>, an Islamic education platform for Quran memorization.</p>
        <p>By approving, you confirm that you are the parent or legal guardian and consent to your child using this platform under our <a href="{{ route('policies.child-safety') }}" class="text-[#1B4332] underline">Child Safety Policy</a>.</p>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('consent.approve', $record->consent_token) }}"
           class="block text-center bg-gradient-to-r from-[#1B4332] to-[#2D6A4F] text-white font-bold py-3 rounded-xl text-sm hover:shadow-lg transition">
            ✓ I Approve
        </a>
        <a href="{{ route('consent.deny', $record->consent_token) }}"
           class="block text-center bg-red-50 border-2 border-red-200 text-red-700 font-bold py-3 rounded-xl text-sm hover:bg-red-100 transition">
            ✗ Deny Access
        </a>
    </div>
</div>
@endsection
