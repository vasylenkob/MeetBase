<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::orderByDesc('created_at');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:admin,organizer,attendee']);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Не можна змінити власну роль.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Роль оновлено.');
    }

    public function toggleBlock(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Не можна заблокувати себе.');
        }

        $user->update(['is_blocked' => !$user->is_blocked]);

        $msg = $user->is_blocked ? 'Користувача заблоковано.' : 'Користувача розблоковано.';
        return back()->with('success', $msg);
    }
}
