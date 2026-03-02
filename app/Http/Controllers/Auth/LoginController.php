<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            AuditLog::record(null, 'login_failed', null, null, [
                'email' => $request->email,
                'ip'    => $request->ip(),
            ]);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // Reject banned users immediately
        if ($user->is_banned) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('auth.account_banned', ['reason' => $user->ban_reason]),
            ]);
        }

        $request->session()->regenerate();

        AuditLog::record($user->id, 'login_success', 'User', $user->id, [
            'ip' => $request->ip(),
        ]);

        return redirect()->intended($this->redirectPath($user));
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        AuditLog::record($userId, 'logout');

        return redirect()->route('home');
    }

    protected function redirectPath($user): string
    {
        return match ($user->role) {
            'admin', 'center_admin' => route('admin.dashboard'),
            'teacher'               => route('teacher.dashboard'),
            default                 => route('student.dashboard'),
        };
    }
}
