<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function settings()
    {
        return view('account.settings', ['user' => auth()->user()->load('profile')]);
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'   => ['required', 'string', 'max:100'],
            'locale' => ['required', 'in:en,ar'],
        ]);

        $user->update(['name' => $data['name']]);
        $user->profile->update(['locale' => $data['locale']]);

        // Update app locale for this session
        session(['locale' => $data['locale']]);

        AuditLog::record($user->id, 'account_settings_updated');

        return back()->with('status', __('account.settings_saved'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('auth.wrong_current_password')]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        AuditLog::record($user->id, 'password_changed', 'User', $user->id, [
            'ip' => $request->ip(),
        ]);

        return back()->with('status', __('account.password_changed'));
    }

    // GDPR Article 20 — Data portability
    public function exportData()
    {
        $user = auth()->user()->load('profile', 'progressLogs', 'enrollments', 'donations');

        $export = [
            'exported_at' => now()->toIso8601String(),
            'user' => [
                'name'       => $user->name,
                'email'      => $user->email,
                'created_at' => $user->created_at,
            ],
            'profile'       => $user->profile->only('age_group', 'locale', 'consent_status'),
            'progress_logs' => $user->progressLogs->toArray(),
            'enrollments'   => $user->enrollments->toArray(),
            'donations'     => $user->donations->map(fn($d) => $d->only('amount', 'currency', 'type', 'status', 'created_at')),
        ];

        AuditLog::record($user->id, 'data_exported');

        return response()->json($export)
            ->header('Content-Disposition', 'attachment; filename="my-data.json"');
    }

    // GDPR Article 17 — Right to erasure
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm_delete' => ['required', 'in:DELETE'],
        ]);

        $user   = auth()->user();
        $userId = $user->id;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Anonymize rather than hard-delete to preserve audit trail integrity
        $user->update([
            'name'      => 'Deleted User',
            'email'     => "deleted_{$userId}@removed.invalid",
            'password'  => Hash::make(\Str::random(32)),
            'is_banned' => true,
        ]);
        $user->profile->update([
            'guardian_email' => null,
            'bio'            => null,
            'avatar_path'    => null,
        ]);

        AuditLog::record($userId, 'account_deleted_self');

        return redirect()->route('home')->with('status', __('account.deleted'));
    }
}
