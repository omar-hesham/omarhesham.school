@extends('layouts.auth')
@section('title', $action === 'approved' ? 'Access Approved' : 'Access Denied')

@section('content')
<div class="text-center space-y-4">
    @if ($action === 'approved')
        <div class="text-5xl">✅</div>
        <h1 class="text-2xl font-extrabold text-gray-900">Account Approved!</h1>
        <p class="text-sm text-gray-600">Your child's account on omarhesham.school has been activated. They can now log in and start their Quran memorization journey.</p>
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-xs">
            JazakAllahu Khayran for supporting your child's Islamic education. 🌟
        </div>
    @else
        <div class="text-5xl">❌</div>
        <h1 class="text-2xl font-extrabold text-gray-900">Access Denied</h1>
        <p class="text-sm text-gray-600">You have denied access for this account. The child's registration has been blocked. No further action is needed.</p>
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-xs">
            If this was a mistake, contact <a href="mailto:support@omarhesham.school" class="underline">support@omarhesham.school</a>.
        </div>
    @endif
</div>
@endsection
