<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Student Progress Report</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f0; margin: 0; padding: 0; color: #1a1a1a; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
        .header { background: linear-gradient(135deg, #1B4332, #2D6A4F); padding: 32px 28px; text-align: center; }
        .header h1 { color: #D4AF37; font-size: 22px; margin: 0 0 4px; }
        .header p { color: rgba(255,255,255,.75); font-size: 14px; margin: 0; }
        .body { padding: 28px; }
        .greeting { font-size: 16px; margin-bottom: 16px; }
        .intro { color: #4B5563; font-size: 14px; line-height: 1.6; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th { background: #1B4332; color: #fff; padding: 10px 14px; text-align: left; font-size: 13px; font-weight: 600; }
        td { padding: 12px 14px; border-bottom: 1px solid #F3F4F6; font-size: 14px; }
        tr:last-child td { border-bottom: none; }
        tr:nth-child(even) td { background: #F9FAFB; }
        .quality-badge { display: inline-block; background: #DCFCE7; color: #166534; padding: 2px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .no-logs { color: #9CA3AF; font-style: italic; }
        .cta { text-align: center; margin: 28px 0; }
        .cta a { background: linear-gradient(135deg, #D4AF37, #F0C040); color: #0A1F14; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-weight: 700; font-size: 15px; }
        .footer { background: #F9FAFB; padding: 20px 28px; text-align: center; font-size: 12px; color: #9CA3AF; border-top: 1px solid #E5E7EB; }
        .arabic { font-family: 'Amiri', 'Times New Roman', serif; direction: rtl; color: #D4AF37; font-size: 16px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="arabic">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</div>
            <h1>📊 Weekly Progress Report</h1>
            <p>Week ending {{ $week_ending }}</p>
        </div>

        <div class="body">
            <p class="greeting">As-salamu alaykum, <strong>{{ $teacher->name }}</strong>,</p>

            <p class="intro">
                Here is a summary of your students' Hifz activity for the past 7 days.
                Please review and approve any pending progress logs at your earliest convenience.
            </p>

            @if ($students->isEmpty())
                <p class="no-logs">No active students were found for your account this week.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Sessions</th>
                            <th>Ayahs Covered</th>
                            <th>Avg Quality</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                        <tr>
                            <td><strong>{{ $student['name'] }}</strong></td>
                            <td>{{ $student['log_count'] }}</td>
                            <td>{{ $student['ayahs_total'] }}</td>
                            <td>
                                @if ($student['avg_quality'] !== '—')
                                    <span class="quality-badge">{{ $student['avg_quality'] }} / 5</span>
                                @else
                                    <span class="no-logs">No logs</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="cta">
                <a href="{{ config('app.url') }}/teacher/dashboard">Review Pending Logs →</a>
            </div>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} omarhesham.school · You are receiving this as a registered teacher.</p>
            <p>Questions? <a href="mailto:support@omarhesham.school">support@omarhesham.school</a></p>
        </div>
    </div>
</body>
</html>
