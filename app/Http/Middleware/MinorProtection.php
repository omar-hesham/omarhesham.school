<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MinorProtection
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $profile = $user->profile;

        if ($profile?->age_group === 'child' && $profile->consent_status !== 'approved') {
            return redirect()->route('consent.pending');
        }

        return $next($request);
    }
}
