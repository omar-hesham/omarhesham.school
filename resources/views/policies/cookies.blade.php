@extends('layouts.app')
@section('title', 'Cookies Policy')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-16">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Cookies Policy</h1>
    <p class="text-gray-400 text-sm mb-8">Last updated: {{ date('F j, Y') }}</p>

    <div class="space-y-8 text-gray-600 text-sm leading-relaxed">
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">1. What Are Cookies?</h2>
            <p>Cookies are small text files stored in your browser. They help us keep you logged in and understand how the platform is used.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">2. Cookies We Use</h2>
            <div class="overflow-hidden rounded-xl border border-gray-200 mt-3">
                <table class="w-full text-xs">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Cookie</th>
                            <th class="px-4 py-2 text-left font-semibold">Purpose</th>
                            <th class="px-4 py-2 text-left font-semibold">Duration</th>
                            <th class="px-4 py-2 text-left font-semibold">Required</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ([
                            ['omarhesham_session', 'Maintains your login session', 'Session', 'Yes'],
                            ['XSRF-TOKEN', 'Security (CSRF protection)', 'Session', 'Yes'],
                            ['remember_me', 'Keeps you logged in across browser restarts', '30 days', 'No'],
                        ] as [$name, $purpose, $dur, $req])
                            <tr>
                                <td class="px-4 py-2.5 font-mono text-gray-700">{{ $name }}</td>
                                <td class="px-4 py-2.5 text-gray-500">{{ $purpose }}</td>
                                <td class="px-4 py-2.5 text-gray-500">{{ $dur }}</td>
                                <td class="px-4 py-2.5">
                                    <span class="font-semibold {{ $req === 'Yes' ? 'text-green-600' : 'text-gray-400' }}">{{ $req }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">3. Managing Cookies</h2>
            <p>You can delete or block cookies via your browser settings. Note that blocking required cookies will prevent you from logging in. We do not use third-party tracking or advertising cookies.</p>
        </section>
        <section>
            <h2 class="text-lg font-bold text-gray-900 mb-2">4. Contact</h2>
            <p>Cookie questions: <a href="mailto:privacy@omarhesham.school" class="text-[#1B4332] underline">privacy@omarhesham.school</a></p>
        </section>
    </div>
</div>
@endsection
