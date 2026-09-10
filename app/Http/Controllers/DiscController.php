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

            'speed' => 'required|numeric|min:1|max:15',
            'glide' => 'required|numeric|min:1|max:7',
            'turn' => 'required|numeric|min:-5|max:1',
            'fade' => 'required|numeric|min:0|max:5',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('discs', 'public');
        }

        auth()->user()->discs()->create([
            'name' => $request->name,
            'type' => $request->type,

            'speed' => $request->speed,
            'glide' => $request->glide,
            'turn' => $request->turn,
            'fade' => $request->fade,

            'image' => $imagePath,
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
