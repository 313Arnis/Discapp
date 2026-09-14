<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionResult;
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

        return redirect()
            ->route('competitions.index')
            ->with('success', 'Sacensības veiksmīgi izveidotas!');
    }


    public function show(Competition $competition)
    {
        $competition->load([
            'users',
            'results.user',
        ]);

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

        return redirect()
            ->route('competitions.show', $competition)
            ->with('success', 'Sacensības veiksmīgi atjauninātas!');
    }


    public function destroy(Competition $competition)
    {
        $competition->delete();

        return redirect()
            ->route('competitions.index')
            ->with('success', 'Sacensības izdzēstas!');
    }


    // =========================
    // PIEVIENOŠANĀS SACENSĪBĀM
    // =========================

    public function join(Competition $competition)
    {
        $rating = auth()->user()->rating;

        $divisions = $this->getAvailableDivisions($rating);

        if (count($divisions) === 0) {
            return back()->with(
                'error',
                'Tev nav pieejama neviena divīzija.'
            );
        }

        return view('competitions.join', compact(
            'competition',
            'divisions',
            'rating'
        ));
    }


    public function storeJoin(Request $request, Competition $competition)
    {
        $rating = auth()->user()->rating;

        $divisions = $this->getAvailableDivisions($rating);

        $request->validate([
            'division' => 'required|in:' . implode(',', array_keys($divisions)),
        ]);


        // Pārbauda vai spēlētājs jau ir pieteicies
        if ($competition->users()->where('user_id', auth()->id())->exists()) {
            return back()->with(
                'error',
                'Tu jau esi pieteicies šīm sacensībām.'
            );
        }


        // Pārbauda maksimālo spēlētāju skaitu
        if (
            $competition->max_players &&
            $competition->users()->count() >= $competition->max_players
        ) {
            return back()->with(
                'error',
                'Šīs sacensības jau ir pilnas.'
            );
        }


        $competition->users()->attach(auth()->id(), [
            'division' => $request->division,
        ]);


        return redirect()
            ->route('competitions.show', $competition)
            ->with(
                'success',
                'Tu veiksmīgi pieteicies sacensībām!'
            );
    }


    private function getAvailableDivisions($rating)
    {
        $divisions = [];

        // MA1 ir pieejama visiem
        $divisions['MA1'] = 'MA1 - Mixed Amateur 1';

        // Zemāka reitinga spēlētāji var izvēlēties arī zemākas divīzijas
        if ($rating <= 934) {
            $divisions['MA2'] = 'MA2 - Mixed Amateur 2';
        }

        if ($rating <= 899) {
            $divisions['MA3'] = 'MA3 - Mixed Amateur 3';
        }

        if ($rating <= 849) {
            $divisions['MA4'] = 'MA4 - Mixed Amateur 4';
        }

        return $divisions;
    }


    public function leave(Competition $competition)
    {
        $competition->users()->detach(auth()->id());

        // Ja spēlētājam bija rezultāts, to arī dzēš
        CompetitionResult::where('competition_id', $competition->id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with(
            'success',
            'Tu vairs nepiedalies šajās sacensībās.'
        );
    }


    // =========================
    // REZULTĀTI
    // =========================

    public function storeResult(
        Request $request,
        Competition $competition
    ) {
        $request->validate([
            'score' => 'required|integer|min:1|max:1000',
        ]);


        // Tikai sacensību dalībnieks drīkst ievadīt savu rezultātu
        if (!$competition->users()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }


        CompetitionResult::updateOrCreate(
            [
                'competition_id' => $competition->id,
                'user_id' => auth()->id(),
            ],
            [
                'score' => $request->score,
            ]
        );


        return back()->with(
            'success',
            'Rezultāts veiksmīgi saglabāts!'
        );
    }
}