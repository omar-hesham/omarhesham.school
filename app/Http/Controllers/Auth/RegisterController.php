<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\ConsentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __construct(private ConsentService $consent) {}

    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'password'       => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role'           => ['required', 'in:student,teacher'],
            'age_group'      => ['required', 'in:adult,child'],
            'guardian_email' => ['required_if:age_group,child', 'nullable', 'email', 'different:email'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        $user->profile->update(['age_group' => $data['age_group']]);

        AuditLog::record($user->id, 'register', 'User', $user->id, [
            'ip' => $request->ip(),
        ]);

        // Child: trigger consent flow instead of logging in directly
        if ($data['age_group'] === 'child') {
            $this->consent->requestConsent($user, $data['guardian_email']);
            Auth::login($user);
            return redirect()->route('consent.pending');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard');
    }
}
