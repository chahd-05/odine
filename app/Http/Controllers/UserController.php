<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $users = User::with('roles')->latest()->paginate(20);
        return view('users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'roles' => 'array|exists:roles,id',
        ]);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return back()->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }
}
