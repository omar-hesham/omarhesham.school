@extends('layouts.app')
@section('title', 'Privacy Policy')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-16 prose prose-gray">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Privacy Policy</h1>
    <p class="text-gray-400 text-sm mb-8">Last updated: {{ date('F j, Y') }}</p>

    <div class="space-y-8 text-gray-600 text-sm leading-relaxed">
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">1. Information We Collect</h2>
            <p>We collect information you provide directly when you register (name, email, age group) and usage data generated through your interactions with the platform (progress logs, lesson views, session timestamps).</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">2. How We Use Your Information</h2>
            <p>We use your data to operate the platform, provide teacher-student features, process payments via Stripe, send transactional emails via Mailgun, and comply with legal obligations. We do not sell your data to third parties.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">3. Children's Privacy (COPPA)</h2>
            <p>For users under 18 ("children"), we require verified parental consent before activating an account. We do not knowingly collect personal information from children without parental consent. Parents may review, update, or delete their child's data by contacting us.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">4. Data Retention & GDPR</h2>
            <p>We retain audit logs and personal data for up to 12 months unless deletion is requested. EU/EEA users have the right to access, correct, export, and delete their data at any time via Account Settings → Export My Data.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">5. Cookies</h2>
            <p>We use session cookies (required for login) and optional analytics cookies. See our <a href="{{ route('policies.cookies') }}" class="text-[#1B4332] underline">Cookies Policy</a> for details.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">6. Contact</h2>
            <p>Privacy questions: <a href="mailto:privacy@omarhesham.school" class="text-[#1B4332] underline">privacy@omarhesham.school</a></p>
        </section>
    </div>
</div>
@endsection
