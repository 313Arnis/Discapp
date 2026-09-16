<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionResult;
use Illuminate\Http\Request;
use App\Models\Course;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::latest()->get();

        return view('competitions.index', compact('competitions'));
    }


    public function create()
    {
    $courses = Course::orderBy('name')->get();

    return view('competitions.create', compact('courses'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|max:255',
        'description' => 'nullable',
        'date' => 'required|date',
        'course_id' => 'required|exists:courses,id',
        'max_players' => 'nullable|integer|min:1',
        'status' => 'required|in:planned,active,finished,cancelled',
    ]);

    $course = Course::findOrFail($request->course_id);

    Competition::create([
        'name' => $request->name,
        'description' => $request->description,
        'date' => $request->date,

        // Saglabā arī trases nosaukumu vecajā location laukā
        'location' => $course->name,

        'max_players' => $request->max_players,
        'status' => $request->status,
        'user_id' => auth()->id(),
        'course_id' => $course->id,
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
            'creator',
        ]);

        return view('competitions.show', compact('competition'));
    }


    public function edit(Competition $competition)
    {
        if ($competition->user_id !== auth()->id()) {
            abort(403, 'Tev nav tiesību rediģēt šīs sacensības.');
        }

        return view('competitions.edit', compact('competition'));
    }


    public function update(Request $request, Competition $competition)
    {
        if ($competition->user_id !== auth()->id()) {
            abort(403, 'Tev nav tiesību rediģēt šīs sacensības.');
        }

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'date' => 'required|date',
            'location' => 'required|max:255',
            'max_players' => 'nullable|integer|min:1',
            'status' => 'required|in:planned,active,finished,cancelled',
        ]);

        $competition->update([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'max_players' => $request->max_players,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('competitions.show', $competition)
            ->with('success', 'Sacensības veiksmīgi atjauninātas!');
    }


    public function destroy(Competition $competition)
    {
        if ($competition->user_id !== auth()->id()) {
            abort(403, 'Tev nav tiesību dzēst šīs sacensības.');
        }

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


        // Pārbauda, vai spēlētājs jau ir pieteicies
        if (
            $competition->users()
                ->where('user_id', auth()->id())
                ->exists()
        ) {
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


        // Pievieno spēlētāju ar izvēlēto divīziju
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

        // MA1 pieejama visiem
        $divisions['MA1'] = 'MA1 - Mixed Amateur 1';

        // MA2
        if ($rating <= 934) {
            $divisions['MA2'] = 'MA2 - Mixed Amateur 2';
        }

        // MA3
        if ($rating <= 899) {
            $divisions['MA3'] = 'MA3 - Mixed Amateur 3';
        }

        // MA4
        if ($rating <= 849) {
            $divisions['MA4'] = 'MA4 - Mixed Amateur 4';
        }

        return $divisions;
    }


    // =========================
    // IZSTĀŠANĀS NO SACENSĪBĀM
    // =========================

    public function leave(Competition $competition)
    {
        // Sacensību veidotājs nevar izstāties no savas sacensības
        if ($competition->user_id === auth()->id()) {
            return back()->with(
                'error',
                'Sacensību veidotājs nevar izstāties no savām sacensībām.'
            );
        }

        $competition->users()->detach(auth()->id());

        // Izdzēš arī spēlētāja rezultātu
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


        // Tikai sacensību dalībnieks drīkst ievadīt rezultātu
        if (
            !$competition->users()
                ->where('user_id', auth()->id())
                ->exists()
        ) {
            abort(403, 'Tev nav tiesību iesniegt rezultātu šajās sacensībās.');
        }


        // Izveido vai atjaunina rezultātu
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
    private function getLatvianCourses()
{
    return [
        'Laumu Dabas Parks',
        'Discgolfpark Ērgļi',
        'Deviņkalnu Disku Golfa Parks',
        'airBaltic Disc Golf Park',
        'DiscGolfPark Vilce',
        'Riekstukalns',
        'Priekuļu Disku Golfa Laukums',
        'Ikšķiles Disku Golfa Parks',
        'Disc Golf Park "Pauku Priedes"',
        'Inčukalna Medību Pils Laukums',
        'Lūša Ķepa',
        'Rezidence Kurzeme',
        'PROPARK Disku golfa parks Baložos',
        'airBaltic Training Disc Golf Park',
        'LIMBO disc golf course',
        'Disku golfa parks ZIBEŅI',
        'Ziedoņi',
        'Tukuma Disku Golfa parks',
        'Ventspils Disku Golfa Parks',
        'Zaķusala Disc Golf Park',
        'Zaķumuižas Disku Golfa Parks',
        'Sējas Disku Golf Parks',
        'Disku golfa parks "Palsa"',
        'Lēdurgas Disku Golfa Parks',
        'Ķeguma Skolas Disc Golf Park',
        'Garozas Disku golfa laukums',
        'Salaspils Ako',
        'Allendorf Discgolf course',
        'Līgatnes disku golfa parks',
        'Viesturskolas Disku Golfa Parks',
        'Ceļa Ēzeļi',
        'Piņķu ūdenskrātuves disku golfa parks',
        'Sīmaņi',
        'Līvbērze Disc Golf Park',
        '"ZĪDŪŅS" - disku golfa parks "Malnova"',
        '"MUIŽA" - disku golfa parks "Malnova"',
        'Jumpravas Disku Golfa trase',
        'Disku golfa parks "Vaidava"',
        'DiscGolfPark BALVI',
        'Kandavas Diksu Golfs',
        'Reiņa Trase',
        'Mežinieki',
        'Upes iela park',
        'Nrc "Vaivari" Disku Golfa Parks',
        'Esena',
        'Turlavas Disku Golfa Parks',
        'Disku golfa parks "ABULS"',
        'Pabažu disku golfa parks',
        'Ļaudona_GRIEZE',
        'Disc Golf Park "Lettes"',
        'Aiviekstes Ozoli 6',
        'Sesiles disku golfs',
    ];
}
}