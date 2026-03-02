@extends('layouts.auth')
@section('title', 'Consent Link Expired')

@section('content')
<div class="text-center space-y-4">
    <div class="text-5xl">⏰</div>
    <h1 class="text-2xl font-extrabold text-gray-900">Consent Link Expired</h1>
    <p class="text-sm text-gray-600 leading-relaxed">
        This parental consent link has expired (links are valid for 7 days). The child's account access has been denied automatically.
    </p>
    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 text-xs text-left space-y-1">
        <p>• If the child still wants access, they must register again</p>
        <p>• Contact <a href="mailto:support@omarhesham.school" class="underline">support@omarhesham.school</a> for assistance</p>
    </div>
    <a href="{{ route('home') }}"
       class="block w-full text-center bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm hover:shadow-lg transition">
        Return to Homepage
    </a>
</div>
@endsection
