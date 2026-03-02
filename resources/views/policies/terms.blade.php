@extends('layouts.app')
@section('title', 'Terms of Service')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Terms of Service</h1>
    <p class="text-gray-400 text-sm mb-8">Last updated: {{ date('F j, Y') }}</p>

    <div class="space-y-8 text-gray-600 text-sm leading-relaxed">
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">1. Acceptance of Terms</h2>
            <p>By creating an account on omarhesham.school, you agree to these Terms of Service. If you are registering a child, you confirm you are their parent or legal guardian and accept these terms on their behalf.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">2. Platform Use</h2>
            <p>You may use the platform only for lawful, educational purposes. You must not share your account credentials, attempt to circumvent security measures, or use the platform to distribute harmful content.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">3. Content Uploaded by Teachers</h2>
            <p>Teachers uploading content confirm they have the necessary rights to that content and that it complies with Islamic principles and our Community Guidelines. All content is reviewed before publication.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">4. Subscriptions & Payments</h2>
            <p>Premium subscriptions are billed via Stripe. You may cancel at any time. Cancellation takes effect at the end of the current billing period. Refunds are handled on a case-by-case basis — contact support.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">5. Account Termination</h2>
            <p>We reserve the right to suspend or terminate accounts that violate these terms, with or without notice, depending on the severity of the violation.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">6. Contact</h2>
            <p>Legal questions: <a href="mailto:legal@omarhesham.school" class="text-[#1B4332] underline">legal@omarhesham.school</a></p>
        </section>
    </div>
</div>
@endsection
