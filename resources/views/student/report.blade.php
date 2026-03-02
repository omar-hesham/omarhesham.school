{{-- PDF report — rendered by barryvdh/laravel-dompdf --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Progress Report — {{ $user->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1a1a1a; font-size: 12px; }
        h1 { color: #1B4332; font-size: 20px; margin-bottom: 4px; }
        .subtitle { color: #6B7280; font-size: 11px; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #1B4332; color: white; padding: 8px 12px; text-align: left; font-size: 11px; }
        td { padding: 8px 12px; border-bottom: 1px solid #F3F4F6; font-size: 11px; }
        tr:nth-child(even) td { background: #F9FAFB; }
        .footer { margin-top: 32px; font-size: 10px; color: #9CA3AF; text-align: center; }
        .badge { padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; }
        .approved { background: #DCFCE7; color: #166534; }
        .pending { background: #FEF9C3; color: #854D0E; }
    </style>
</head>
<body>
    <h1>Progress Report — {{ $user->name }}</h1>
    <div class="subtitle">Generated {{ now()->format('F j, Y') }} · omarhesham.school</div>

    <table>
        <thead>
            <tr>
                <th>Surah</th>
                <th>Ayahs</th>
                <th>Quality</th>
                <th>Date</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td>Surah {{ $log->surah_number }}</td>
                <td>{{ $log->ayah_from }}–{{ $log->ayah_to }}</td>
                <td>{{ str_repeat('★', $log->quality_score) }}{{ str_repeat('☆', 5 - $log->quality_score) }}</td>
                <td>{{ \Carbon\Carbon::parse($log->logged_at)->format('M j, Y') }}</td>
                <td><span class="badge {{ $log->approved_by ? 'approved' : 'pending' }}">{{ $log->approved_by ? 'approved' : 'pending' }}</span></td>
                <td>{{ $log->notes ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">© {{ date('Y') }} omarhesham.school — Confidential progress report</div>
</body>
</html>
