<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? 'omarhesham.school' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
               background: #F3F4F6; color: #1F2937; line-height: 1.6; }
        a { color: #1B4332; }
        img { border: 0; display: block; }

        .wrapper   { width: 100%; background: #F3F4F6; padding: 40px 16px; }
        .container { max-width: 560px; margin: 0 auto; }

        .header {
            background: linear-gradient(135deg, #0A1F14 0%, #1B4332 60%, #2D6A4F 100%);
            border-radius: 16px 16px 0 0;
            padding: 32px 40px;
            text-align: center;
        }
        .logo-box {
            width: 52px; height: 52px; border-radius: 12px;
            background: linear-gradient(135deg, #D4AF37, #F0C040);
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 12px;
        }
        .header-title { color: #fff; font-size: 20px; font-weight: 800; margin-bottom: 2px; }
        .header-sub   { color: #D4AF37; font-size: 11px; letter-spacing: 2px; }

        .body   { background: #fff; padding: 40px; }
        .ayah   {
            background: linear-gradient(135deg, #1B4332, #2D6A4F);
            border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 24px;
        }
        .ayah-ar  { font-family: 'Amiri', Georgia, serif; color: #fff; font-size: 20px;
                    line-height: 2; direction: rtl; margin-bottom: 6px; }
        .ayah-en  { color: rgba(255,255,255,.75); font-size: 12px; }
        .ayah-ref { color: #D4AF37; font-size: 11px; margin-top: 4px; }

        h1 { font-size: 22px; font-weight: 800; color: #0A1F14; margin-bottom: 8px; }
        h2 { font-size: 16px; font-weight: 700; color: #0A1F14; margin: 24px 0 8px; }
        p  { color: #4B5563; font-size: 14px; margin-bottom: 12px; line-height: 1.7; }
        .lead { font-size: 15px; color: #374151; }

        .btn-gold {
            display: inline-block; background: linear-gradient(135deg, #D4AF37, #F0C040);
            color: #0A1F14 !important; font-weight: 700; font-size: 14px;
            padding: 13px 28px; border-radius: 10px; text-decoration: none; margin: 4px;
        }
        .btn-green {
            display: inline-block; background: #1B4332; color: #fff !important;
            font-weight: 700; font-size: 14px; padding: 13px 28px;
            border-radius: 10px; text-decoration: none; margin: 4px;
        }
        .btn-red {
            display: inline-block; background: #DC2626; color: #fff !important;
            font-weight: 700; font-size: 14px; padding: 13px 28px;
            border-radius: 10px; text-decoration: none; margin: 4px;
        }
        .btn-center { text-align: center; margin: 28px 0; }

        .info-box {
            border-radius: 10px; padding: 16px 20px;
            font-size: 13px; line-height: 1.6; margin: 16px 0;
        }
        .info-green  { background: #F0FDF4; border-left: 4px solid #1B4332; color: #14532D; }
        .info-amber  { background: #FFFBEB; border-left: 4px solid #D97706; color: #78350F; }
        .info-red    { background: #FEF2F2; border-left: 4px solid #DC2626; color: #7F1D1D; }
        .info-blue   { background: #EFF6FF; border-left: 4px solid #2563EB; color: #1E3A5F; }

        .data-table { width: 100%; border-collapse: collapse; margin: 16px 0; font-size: 13px; }
        .data-table th { background: #F9FAFB; padding: 10px 14px; text-align: left;
                         font-weight: 600; color: #6B7280; border-bottom: 1px solid #E5E7EB; }
        .data-table td { padding: 11px 14px; border-bottom: 1px solid #F3F4F6;
                         color: #374151; vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .stars { color: #D4AF37; letter-spacing: 1px; }
        .badge {
            display: inline-block; padding: 2px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 700;
        }
        .badge-green { background: #DCFCE7; color: #166534; }
        .badge-amber { background: #FEF9C3; color: #854D0E; }
        .badge-red   { background: #FEE2E2; color: #991B1B; }

        .divider { border: none; border-top: 1px solid #E5E7EB; margin: 24px 0; }

        .stat-grid { display: table; width: 100%; margin: 20px 0; }
        .stat-cell {
            display: table-cell; width: 25%; text-align: center;
            background: #F9FAFB; border-radius: 10px; padding: 14px 8px;
        }
        .stat-emoji { font-size: 22px; display: block; margin-bottom: 4px; }
        .stat-val   { font-size: 22px; font-weight: 800; color: #1B4332; display: block; }
        .stat-lbl   { font-size: 10px; color: #9CA3AF; display: block; margin-top: 2px; }

        .footer {
            background: #0A1F14; border-radius: 0 0 16px 16px;
            padding: 24px 40px; text-align: center;
        }
        .footer p   { color: rgba(255,255,255,.45); font-size: 11px; margin-bottom: 4px; }
        .footer a   { color: rgba(255,255,255,.5); font-size: 11px; text-decoration: underline; }
        .footer-links { margin-bottom: 12px; }
        .footer-links a { margin: 0 8px; }

        .progress-wrap { background: #E5E7EB; border-radius: 4px; height: 8px; overflow: hidden; margin: 4px 0 12px; }
        .progress-fill { height: 8px; border-radius: 4px;
                         background: linear-gradient(90deg, #1B4332, #D4AF37); }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">

        <div class="header">
            <div class="logo-box">
                <svg width="26" height="26" fill="none" stroke="#0A1F14" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                             C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253
                             m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253
                             v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div class="header-title">Omar Hesham School</div>
            <div class="header-sub">OMARHESHAM.SCHOOL</div>
        </div>

        <div class="body">
            @yield('content')
        </div>

        <div class="footer">
            <div class="footer-links">
                <a href="{{ route('policies.privacy') }}">Privacy</a>
                <a href="{{ route('policies.terms') }}">Terms</a>
                <a href="{{ route('policies.child-safety') }}">Child Safety</a>
                <a href="mailto:support@omarhesham.school">Support</a>
            </div>
            <p>© {{ date('Y') }} omarhesham.school — All rights reserved.</p>
            <p style="margin-top:6px;">
                This email was sent to <strong style="color:rgba(255,255,255,.6)">{{ $recipientEmail ?? '' }}</strong>
            </p>
            @isset($unsubscribeUrl)
                <p style="margin-top:6px;"><a href="{{ $unsubscribeUrl }}">Unsubscribe from these emails</a></p>
            @endisset
        </div>

    </div>
</div>
</body>
</html>
