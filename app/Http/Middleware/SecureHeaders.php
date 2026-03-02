<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureHeaders
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        return $response
            ->header('X-Frame-Options',       'SAMEORIGIN')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-XSS-Protection',       '1; mode=block')
            ->header('Referrer-Policy',        'strict-origin-when-cross-origin')
            ->header('Permissions-Policy',     'geolocation=(), camera=(), microphone=()')
            ->header('Content-Security-Policy',
                "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' https://js.stripe.com; " .
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
                "font-src 'self' https://fonts.gstatic.com; " .
                "frame-src https://js.stripe.com https://www.youtube-nocookie.com; " .
                "img-src 'self' data: https:; " .
                "connect-src 'self' https://api.stripe.com;"
            );
    }
}
