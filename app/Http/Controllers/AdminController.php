<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Competition;

class AdminController extends Controller
{
    public function index()
    {
        $usersCount = User::count();

        $coursesCount = Course::count();

        $competitionsCount = Competition::count();

        $averageRating = round(User::where('role', 'user')->avg('rating') ?? 0);

        $recentUsers = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.index', compact(
            'usersCount',
            'coursesCount',
            'competitionsCount',
            'averageRating',
            'recentUsers'
        ));
    }

    public function users()
    {
        $users = User::orderBy('name')->get();

        return view('admin.users', compact('users'));
    }

    public function updateUser($user)
    {
        $request = request();

        $validated = $request->validate([
            'role' => 'required|in:user,admin',
            'rating' => 'required|integer|min:0|max:2000',
        ]);

        $user = User::findOrFail($user);

        $user->update([
            'role' => $validated['role'],
            'rating' => $validated['rating'],
        ]);

        return redirect()
            ->route('admin.users')
            ->with('success', 'Lietotāja informācija veiksmīgi atjaunota!');
    }

    public function destroyUser($user)
    {
        $user = User::findOrFail($user);

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