<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.dashboard', function ($view) {
            $user = auth()->user();
            if (!$user) return;

            $svgBook   = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>';
            $svgHome   = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>';
            $svgUsers  = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>';
            $svgChart  = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>';
            $svgUpload = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>';
            $svgShield = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>';
            $svgGear   = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>';

            $links = match ($user->role) {
                'admin', 'center_admin' => [
                    ['label' => 'Dashboard',  'route' => 'admin.dashboard',           'icon' => $svgHome],
                    ['label' => 'Users',      'route' => 'admin.users.index',         'icon' => $svgUsers],
                    ['label' => 'Moderation', 'route' => 'admin.moderation.index',    'icon' => $svgShield],
                    ['label' => 'Programs',   'route' => 'admin.programs.index',      'icon' => $svgBook],
                    ['label' => 'Reports',    'route' => 'admin.reports.audit',       'icon' => $svgChart],
                    ['label' => 'Settings',   'route' => 'account.settings',          'icon' => $svgGear],
                ],
                'teacher' => [
                    ['label' => 'Dashboard', 'route' => 'teacher.dashboard',          'icon' => $svgHome],
                    ['label' => 'Students',  'route' => 'teacher.students.index',     'icon' => $svgUsers],
                    ['label' => 'Upload',    'route' => 'teacher.content.upload.form','icon' => $svgUpload],
                    ['label' => 'Settings',  'route' => 'account.settings',           'icon' => $svgGear],
                ],
                default => [
                    ['label' => 'Dashboard', 'route' => 'student.dashboard',          'icon' => $svgHome],
                    ['label' => 'Progress',  'route' => 'student.progress.index',     'icon' => $svgChart],
                    ['label' => 'Programs',  'route' => 'programs.index',             'icon' => $svgBook],
                    ['label' => 'Settings',  'route' => 'account.settings',           'icon' => $svgGear],
                ],
            };

            $streak = 0;
            if ($user->role === 'student') {
                $date = now()->toDateString();
                while (\App\Models\ProgressLog::where('user_id', $user->id)
                    ->where('logged_at', $date)->exists()) {
                    $streak++;
                    $date = now()->subDays($streak)->toDateString();
                }
            }

            $view->with(['sidebarLinks' => $links, 'streak' => $streak]);
        });
    }
}
