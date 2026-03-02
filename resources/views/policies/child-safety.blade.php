@extends('layouts.app')
@section('title', 'Child Safety Policy')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Child Safety Policy</h1>
    <p class="text-gray-400 text-sm mb-8">Last updated: {{ date('F j, Y') }}</p>

    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-4 text-sm mb-8">
        <strong>Our Commitment:</strong> The protection and safety of every child using our platform is our highest priority. We apply strict controls to ensure minors experience a safe, appropriate learning environment.
    </div>

    <div class="space-y-8 text-gray-600 text-sm leading-relaxed">
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">1. Parental Consent (COPPA Compliance)</h2>
            <p>All users who identify as under 18 (children) must receive verified parental consent before their account is activated. We send a consent request email directly to the guardian email provided. The child account remains locked until the parent approves.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">2. Content Moderation</h2>
            <p>All content uploaded to the platform is quarantined and reviewed by a human moderator before being visible to any student. Content that is inappropriate, harmful, or unsuitable for children is permanently rejected.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">3. No Advertising to Minors</h2>
            <p>We do not serve targeted or behavioural advertising to any user identified as a minor. The platform is entirely ad-free for children.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">4. Teacher Vetting</h2>
            <p>Teachers are verified before being granted access to student data or the ability to communicate with students. Any report of inappropriate teacher conduct results in immediate account suspension pending investigation.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">5. Reporting Concerns</h2>
            <p>If you have any concern about a child's safety on our platform, contact us immediately at <a href="mailto:safety@omarhesham.school" class="text-[#1B4332] underline">safety@omarhesham.school</a>. We respond within 24 hours.</p>
        </section>
    </div>
</div>
@endsection
