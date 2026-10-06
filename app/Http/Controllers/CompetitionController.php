<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Course;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SACENSĪBU SARAKSTS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $competitions =
            Competition::with([
                'course',
                'creator',
                'users',
            ])
                ->latest('date')
                ->get();

        return view(
            'competitions.index',
            compact(
                'competitions'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SACENSĪBU IZVEIDE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $courses =
            Course::orderBy('name')
                ->get();

        return view(
            'competitions.create',
            compact(
                'courses'
            )
        );
    }


    public function store(
        Request $request
    ) {
        $validated =
            $request->validate([
                'name' => [
                    'required',
                    'max:255',
                ],

                'description' => [
                    'nullable',
                ],

                'date' => [
                    'required',
                    'date',
                ],

                'course_id' => [
                    'required',
                    'exists:courses,id',
                ],

                'max_players' => [
                    'nullable',
                    'integer',
                    'min:1',
                ],

                'status' => [
                    'required',
                    'in:planned,active,finished,cancelled',
                ],
            ]);

        Competition::create([
            'name' =>
                $validated['name'],

            'description' =>
                $validated['description']
                ?? null,

            'date' =>
                $validated['date'],

            'course_id' =>
                $validated['course_id'],

            'max_players' =>
                $validated['max_players']
                ?? null,

            'status' =>
                $validated['status'],

            'user_id' =>
                auth()->id(),
        ]);

        return redirect()
            ->route(
                'competitions.index'
            )
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

    public function show(
        Competition $competition
    ) {
        $competition->load([
            'users',
            'creator',
            'course.courseHoles',
            'holeResults.user',
            'holeResults.courseHole',
        ]);

        $players =
            $competition
                ->users
                ->map(
                    function ($user) use (
                        $competition
                    ) {
                        $results =
                            $competition
                                ->holeResults
                                ->where(
                                    'user_id',
                                    $user->id
                                );

                        $totalScore =
                            $results->sum(
                                'score'
                            );

                        $totalPar = 0;

                        foreach (
                            $results as $result
                        ) {
                            if (
                                $result->courseHole
                            ) {
                                $totalPar +=
                                    $result
                                        ->courseHole
                                        ->par;
                            }
                        }

                        $relative =
                            $totalScore -
                            $totalPar;

                        return [
                            'user' =>
                                $user,

                            'division' =>
                                $user
                                    ->pivot
                                    ->division
                                ?? '-',

                            'played' =>
                                $results
                                    ->count(),

                            'total_score' =>
                                $totalScore,

                            'total_par' =>
                                $totalPar,

                            'relative' =>
                                $relative,
                        ];
                    }
                )
                ->filter(
                    function ($player) {
                        return
                            $player['user']
                            !== null;
                    }
                )
                ->sort(
                    function ($a, $b) {

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
                                $a['relative']
                                <=>
                                $b['relative'];
                        }

                        if (
                            $a['total_score'] !==
                            $b['total_score']
                        ) {
                            return
                                $a['total_score']
                                <=>
                                $b['total_score'];
                        }

                        return strcasecmp(
                            $a['user']->name,
                            $b['user']->name
                        );
                    }
                )
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
    | SACENSĪBU REDIĢĒŠANA
    |--------------------------------------------------------------------------
    */

    public function edit(
        Competition $competition
    ) {
        $this->checkOwner(
            $competition
        );

        $courses =
            Course::orderBy('name')
                ->get();

        return view(
            'competitions.edit',
            compact(
                'competition',
                'courses'
            )
        );
    }


    public function update(
        Request $request,
        Competition $competition
    ) {
        $this->checkOwner(
            $competition
        );

        $validated =
            $request->validate([
                'name' => [
                    'required',
                    'max:255',
                ],

                'description' => [
                    'nullable',
                ],

                'date' => [
                    'required',
                    'date',
                ],

                'course_id' => [
                    'required',
                    'exists:courses,id',
                ],

                'max_players' => [
                    'nullable',
                    'integer',
                    'min:1',
                ],

                'status' => [
                    'required',
                    'in:planned,active,finished,cancelled',
                ],
            ]);

        $competition->update([
            'name' =>
                $validated['name'],

            'description' =>
                $validated['description']
                ?? null,

            'date' =>
                $validated['date'],

            'course_id' =>
                $validated['course_id'],

            'max_players' =>
                $validated['max_players']
                ?? null,

            'status' =>
                $validated['status'],
        ]);

        return redirect()
            ->route(
                'competitions.show',
                $competition
            )
            ->with(
                'success',
                'Sacensības veiksmīgi atjauninātas!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SACENSĪBU DZĒŠANA
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Competition $competition
    ) {
        $this->checkOwner(
            $competition
        );

        $competition->delete();

        return redirect()
            ->route(
                'competitions.index'
            )
            ->with(
                'success',
                'Sacensības izdzēstas!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SACENSĪBU ĪPAŠNIEKA PĀRBAUDE
    |--------------------------------------------------------------------------
    */

    private function checkOwner(
        Competition $competition
    ): void {
        if (
            $competition->user_id !==
            auth()->id()
        ) {
            abort(
                403,
                'Tev nav tiesību veikt šo darbību.'
            );
        }
    }
}