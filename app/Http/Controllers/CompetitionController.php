<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competition;
use App\Models\CompetitionResult;
use App\Models\CompetitionHoleResult;
use App\Models\Course;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::with([
            'course',
            'creator',
            'users',
        ])
            ->latest('date')
            ->get();

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

        Competition::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'course_id' => $request->course_id,
            'max_players' => $request->max_players,
            'status' => $request->status,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('competitions.index')
            ->with(
                'success',
                'Sacensības veiksmīgi izveidotas!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SACENSĪBU SKATS
    |--------------------------------------------------------------------------
    */

    public function show(Competition $competition)
    {
        $competition->load([
            'users',
            'creator',
            'course.courseHoles',
            'holeResults.user',
            'holeResults.courseHole',
        ]);


        $players = $competition->users
            ->map(function ($user) use ($competition) {

                $results = $competition->holeResults
                    ->where('user_id', $user->id);


                $totalScore = $results->sum('score');


                $totalPar = 0;


                foreach ($results as $result) {

                    if ($result->courseHole) {
                        $totalPar += $result->courseHole->par;
                    }

                }


                $relative = $totalScore - $totalPar;


                return [
                    'user' => $user,

                    'division' =>
                        $user->pivot->division ?? '-',

                    'played' =>
                        $results->count(),

                    'total_score' =>
                        $totalScore,

                    'total_par' =>
                        $totalPar,

                    'relative' =>
                        $relative,
                ];
            })


            ->filter(function ($player) {
                return $player['user'] !== null;
            })


            ->sort(function ($a, $b) {

                if (
                    $a['played'] === 0 &&
                    $b['played'] > 0
                ) {
                    return 1;
                }


                if (
                    $a['played'] > 0 &&
                    $b['played'] === 0
                ) {
                    return -1;
                }


                if (
                    $a['relative'] !==
                    $b['relative']
                ) {

                    return
                        $a['relative'] <=>
                        $b['relative'];
                }


                if (
                    $a['total_score'] !==
                    $b['total_score']
                ) {

                    return
                        $a['total_score'] <=>
                        $b['total_score'];
                }


                return strcasecmp(
                    $a['user']->name,
                    $b['user']->name
                );
            })


            ->values();


        return view(
            'competitions.show',
            compact(
                'competition',
                'players'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DZĒŠANA
    |--------------------------------------------------------------------------
    */

    public function destroy(Competition $competition)
    {
        if (
            $competition->user_id !==
            auth()->id()
        ) {

            abort(
                403,
                'Tev nav tiesību dzēst šīs sacensības.'
            );
        }


        $competition->delete();


        return redirect()
            ->route('competitions.index')
            ->with(
                'success',
                'Sacensības izdzēstas!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PIETEIKŠANĀS SACENSĪBĀM
    |--------------------------------------------------------------------------
    */

    public function join(Competition $competition)
    {
        if ($competition->status === 'finished') {

            return back()->with(
                'error',
                'Pabeigtām sacensībām vairs nevar pieteikties.'
            );
        }


        if ($competition->status === 'cancelled') {

            return back()->with(
                'error',
                'Atceltām sacensībām nevar pieteikties.'
            );
        }


        $rating = auth()->user()->rating;

        $divisions =
            $this->getAvailableDivisions($rating);


        if (count($divisions) === 0) {

            return back()->with(
                'error',
                'Tev nav pieejama neviena divīzija.'
            );
        }


        return view(
            'competitions.join',
            compact(
                'competition',
                'divisions',
                'rating'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PIETEIKŠANĀS SAGLABĀŠANA
    |--------------------------------------------------------------------------
    */

    public function storeJoin(
        Request $request,
        Competition $competition
    ) {

        if ($competition->status === 'finished') {

            return back()->with(
                'error',
                'Pabeigtām sacensībām vairs nevar pieteikties.'
            );
        }


        if ($competition->status === 'cancelled') {

            return back()->with(
                'error',
                'Atceltām sacensībām nevar pieteikties.'
            );
        }


        $rating = auth()->user()->rating;

        $divisions =
            $this->getAvailableDivisions($rating);


        $request->validate([
            'division' =>
                'required|in:' .
                implode(
                    ',',
                    array_keys($divisions)
                ),
        ]);


        if (
            $competition
                ->users()
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->exists()
        ) {

            return back()->with(
                'error',
                'Tu jau esi pieteicies šīm sacensībām.'
            );
        }


        if (
            $competition->max_players &&
            $competition->users()->count()
                >= $competition->max_players
        ) {

            return back()->with(
                'error',
                'Šīs sacensības jau ir pilnas.'
            );
        }


        $competition->users()->attach(
            auth()->id(),
            [
                'division' =>
                    $request->division,
            ]
        );


        return redirect()
            ->route(
                'competitions.show',
                $competition
            )
            ->with(
                'success',
                'Tu veiksmīgi pieteicies sacensībām!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PIEEJAMĀS DIVĪZIJAS
    |--------------------------------------------------------------------------
    */

    private function getAvailableDivisions($rating)
    {
        $divisions = [
            'MA1' =>
                'MA1 - Mixed Amateur 1',
        ];


        if ($rating <= 934) {

            $divisions['MA2'] =
                'MA2 - Mixed Amateur 2';
        }


        if ($rating <= 899) {

            $divisions['MA3'] =
                'MA3 - Mixed Amateur 3';
        }


        if ($rating <= 849) {

            $divisions['MA4'] =
                'MA4 - Mixed Amateur 4';
        }


        return $divisions;
    }


    /*
    |--------------------------------------------------------------------------
    | IZSTĀŠANĀS
    |--------------------------------------------------------------------------
    */

    public function leave(Competition $competition)
    {
        if (
            $competition->user_id ===
            auth()->id()
        ) {

            return back()->with(
                'error',
                'Sacensību veidotājs nevar izstāties no savām sacensībām.'
            );
        }


        if ($competition->status === 'finished') {

            return back()->with(
                'error',
                'No pabeigtām sacensībām vairs nevar izstāties.'
            );
        }


        $competition
            ->users()
            ->detach(auth()->id());


        CompetitionResult::where(
            'competition_id',
            $competition->id
        )
            ->where(
                'user_id',
                auth()->id()
            )
            ->delete();


        CompetitionHoleResult::where(
            'competition_id',
            $competition->id
        )
            ->where(
                'user_id',
                auth()->id()
            )
            ->delete();


        return back()->with(
            'success',
            'Tu vairs nepiedalies šajās sacensībās.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SCORECARD
    |--------------------------------------------------------------------------
    */

    public function scorecard(
        Request $request,
        Competition $competition
    ) {

        if (
            !$competition
                ->users()
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->exists()
        ) {

            abort(
                403,
                'Tev nav tiesību apskatīt šo scorecard.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SVARĪGI
        |--------------------------------------------------------------------------
        |
        | Pabeigtām sacensībām scorecard tagad DRĪKST apskatīt.
        | Vienīgais aizliegums ir rezultātu rediģēšana.
        |
        */


        $competition->load([
            'course.courseHoles',
            'users',
        ]);


        if (!$competition->course) {

            abort(
                400,
                'Šīm sacensībām nav piesaistīta trase.'
            );
        }


        $courseHoles =
            $competition
                ->course
                ->courseHoles
                ->sortBy('hole_number')
                ->values();


        if ($courseHoles->count() === 0) {

            abort(
                400,
                'Šai trasei nav pievienoti grozi.'
            );
        }


        $holeNumber = (int) $request->get(
            'hole',
            1
        );


        if ($holeNumber < 1) {
            $holeNumber = 1;
        }


        if (
            $holeNumber >
            $courseHoles->count()
        ) {

            $holeNumber =
                $courseHoles->count();
        }


        $currentHole =
            $courseHoles->firstWhere(
                'hole_number',
                $holeNumber
            );


        $results =
            CompetitionHoleResult::where(
                'competition_id',
                $competition->id
            )
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->get()
                ->keyBy('course_hole_id');


        return view(
            'competitions.scorecard',
            compact(
                'competition',
                'courseHoles',
                'results',
                'currentHole'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VIENA GROZA REZULTĀTA SAGLABĀŠANA
    |--------------------------------------------------------------------------
    */

    public function storeHoleResult(
        Request $request,
        Competition $competition
    ) {

        if (
            !$competition
                ->users()
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->exists()
        ) {

            abort(
                403,
                'Tev nav tiesību ievadīt rezultātus šajās sacensībās.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PABEIGTAS SACENSĪBAS NEVAR REDIĢĒT
        |--------------------------------------------------------------------------
        */

        if ($competition->status === 'finished') {

            return redirect()
                ->route(
                    'competitions.show',
                    $competition
                )
                ->with(
                    'error',
                    'Pabeigtu sacensību rezultātus vairs nevar mainīt.'
                );
        }


        if ($competition->status === 'cancelled') {

            return redirect()
                ->route(
                    'competitions.show',
                    $competition
                )
                ->with(
                    'error',
                    'Atceltām sacensībām rezultātus ievadīt nevar.'
                );
        }


        $request->validate([
            'course_hole_id' =>
                'required|exists:course_holes,id',

            'score' =>
                'required|integer|min:1|max:100',
        ]);


        $competition->load('course');


        if (!$competition->course) {

            abort(
                400,
                'Šīm sacensībām nav piesaistīta trase.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PĀRBAUDĀM GROZU
        |--------------------------------------------------------------------------
        */

        $courseHole =
            $competition
                ->course
                ->courseHoles()
                ->where(
                    'id',
                    $request->course_hole_id
                )
                ->first();


        if (!$courseHole) {

            abort(
                403,
                'Šis grozs nepieder sacensību trasei.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SAGLABĀJAM GROZA REZULTĀTU
        |--------------------------------------------------------------------------
        */

        CompetitionHoleResult::updateOrCreate(
            [
                'competition_id' =>
                    $competition->id,

                'user_id' =>
                    auth()->id(),

                'course_hole_id' =>
                    $courseHole->id,
            ],
            [
                'score' =>
                    $request->score,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | GROZU SKAITS
        |--------------------------------------------------------------------------
        */

        $holeCount =
            $competition
                ->course
                ->courseHoles()
                ->count();


        $playedHoleCount =
            CompetitionHoleResult::where(
                'competition_id',
                $competition->id
            )
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | VISI GROZI PABEIGTI
        |--------------------------------------------------------------------------
        */

        if (
            $playedHoleCount ===
            $holeCount
        ) {

            $totalScore =
                CompetitionHoleResult::where(
                    'competition_id',
                    $competition->id
                )
                    ->where(
                        'user_id',
                        auth()->id()
                    )
                    ->sum('score');


            CompetitionResult::updateOrCreate(
                [
                    'competition_id' =>
                        $competition->id,

                    'user_id' =>
                        auth()->id(),
                ],
                [
                    'score' =>
                        $totalScore,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NĀKAMAIS GROZS
        |--------------------------------------------------------------------------
        */

        $nextHole =
            $courseHole->hole_number + 1;


        /*
        |--------------------------------------------------------------------------
        | JA PABEIGTS PĒDĒJAIS GROZS
        |--------------------------------------------------------------------------
        |
        | Ja scorecard ir pilnībā pabeigts, aizvedam uz rezultātu tabulu.
        |
        */

        if (
            $playedHoleCount ===
            $holeCount
        ) {

            return redirect()
                ->route(
                    'competitions.show',
                    $competition
                )
                ->with(
                    'success',
                    'Scorecard pabeigts! Gala rezultāts saglabāts.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CITĀDI EJAM UZ NĀKAMO GROZU
        |--------------------------------------------------------------------------
        */

        if ($nextHole > $holeCount) {

            $nextHole =
                $courseHole->hole_number;
        }


        return redirect()
            ->route(
                'competitions.scorecard',
                [
                    'competition' =>
                        $competition,

                    'hole' =>
                        $nextHole,
                ]
            )
            ->with(
                'success',
                'Rezultāts saglabāts!'
            );
    }
}