<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::latest()->get();

        return view('competitions.index', compact('competitions'));
    }

    public function create()
    {
        return view('competitions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'date' => 'required|date',
            'location' => 'required|max:255',
            'max_players' => 'nullable|integer|min:1',
        ]);

        Competition::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'max_players' => $request->max_players,
        ]);

        return redirect('/competitions');
    }

    public function show(Competition $competition)
    {
        return view('competitions.show', compact('competition'));
    }

    public function edit(Competition $competition)
    {
        return view('competitions.edit', compact('competition'));
    }

    public function update(Request $request, Competition $competition)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'date' => 'required|date',
            'location' => 'required|max:255',
            'max_players' => 'nullable|integer|min:1',
            'status' => 'required',
        ]);

        $competition->update($request->all());

        return redirect('/competitions');
    }

    public function destroy(Competition $competition)
    {
        $competition->delete();

        return redirect('/competitions');
    }
}