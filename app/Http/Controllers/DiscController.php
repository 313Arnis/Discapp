<?php

namespace App\Http\Controllers;

use App\Models\Disc;
use Illuminate\Http\Request;

class DiscController extends Controller
{
    public function index()
    {
        $discs = auth()->user()->discs()->latest()->get();

        return view('profile.discs.index', compact('discs'));
    }

    public function create()
    {
        return view('profile.discs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|max:255',
        ]);

        auth()->user()->discs()->create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()
            ->route('profile.discs.index')
            ->with('success', 'Disks veiksmīgi pievienots!');
    }

    public function destroy(Disc $disc)
    {
        if ($disc->user_id !== auth()->id()) {
            abort(403);
        }

        $disc->delete();

        return redirect()
            ->route('profile.discs.index')
            ->with('success', 'Disks izdzēsts!');
    }
}