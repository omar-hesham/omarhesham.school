<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('profile')->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($b) => $b->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"));
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('age_group')) {
            $query->whereHas('profile', fn($b) => $b->where('age_group', $request->age_group));
        }

        $users = $query->paginate(30)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('profile', 'progressLogs', 'enrollments', 'consentRecords');
        return view('admin.users.show', compact('user'));
    }

    public function ban(Request $request, User $user)
    {
        abort_if($user->role === 'admin', 403, 'Cannot ban another admin.');

        $data = $request->validate([
            'ban_reason' => ['required', 'string', 'max:500'],
        ]);

        $user->update([
            'is_banned'  => true,
            'ban_reason' => $data['ban_reason'],
        ]);

        AuditLog::record(auth()->id(), 'user_banned', 'User', $user->id, [
            'reason' => $data['ban_reason'],
        ]);

        return back()->with('status', __('admin.user_banned', ['name' => $user->name]));
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false, 'ban_reason' => null]);

        AuditLog::record(auth()->id(), 'user_unbanned', 'User', $user->id);

        return back()->with('status', __('admin.user_unbanned', ['name' => $user->name]));
    }

    public function changeRole(Request $request, User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Cannot change your own role.');

        $data = $request->validate([
            'role' => ['required', 'in:student,teacher,admin,center_admin'],
        ]);

        $user->update(['role' => $data['role']]);

        AuditLog::record(auth()->id(), 'role_changed', 'User', $user->id, [
            'new_role' => $data['role'],
        ]);

        return back()->with('status', __('admin.role_updated'));
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Cannot delete yourself.');
        abort_if($user->role === 'admin', 403, 'Cannot delete another admin.');

        AuditLog::record(auth()->id(), 'user_hard_deleted', 'User', $user->id, [
            'email' => $user->email,
        ]);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', __('admin.user_deleted'));
    }
}
