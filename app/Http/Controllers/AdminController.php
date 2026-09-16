<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function users()
    {
        $users = User::orderBy('name')->get();

        return view('admin.users', compact('users'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:user,admin',
            'rating' => 'required|integer|min:0|max:2000',
        ]);

        $user->update([
            'role' => $validated['role'],
            'rating' => $validated['rating'],
        ]);

        return redirect()
            ->route('admin.users')
            ->with('success', 'Lietotāja informācija veiksmīgi atjaunota!');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users')
                ->withErrors([
                    'user' => 'Tu nevari izdzēst pats savu administratora kontu.'
                ]);
        }

        $user->delete();

        return redirect()
            ->route('admin.users')
            ->with('success', 'Lietotājs veiksmīgi izdzēsts!');
    }
}