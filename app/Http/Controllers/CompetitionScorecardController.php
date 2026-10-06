<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionHoleResult;
use App\Models\CompetitionResult;
use App\Services\RatingService;
use Illuminate\Http\Request;

class CompetitionScorecardController extends Controller
{
    private RatingService $ratingService;

    public function __construct(
        RatingService $ratingService
    ) {
        $this->ratingService = $ratingService;
    }


    /*
    |--------------------------------------------------------------------------
    | SCORECARD
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        Competition $competition
    ) {
        $this->checkParticipant(
            $competition
        );

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

        if ($courseHoles->isEmpty()) {
            abort(
                400,
                'Šai trasei nav pievienoti grozi.'
            );
        }

        $holeNumber =
            (int) $request->get(
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

        /*
         * Ja hole_number nav secīgs, piemēram,
         * datubāzē ir 1, 2, 4, tad drošības pēc
         * izmantojam pirmo grozu.
         */
        if (!$currentHole) {
            $currentHole =
                $courseHoles->first();
        }

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
                ->keyBy(
                    'course_hole_id'
                );

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
    | GROZA REZULTĀTA SAGLABĀŠANA
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Competition $competition
    ) {
        $this->checkParticipant(
            $competition
        );

        /*
        |--------------------------------------------------------------------------
        | PABEIGTAS SACENSĪBAS
        |--------------------------------------------------------------------------
        */

        if (
            $competition->status ===
            'finished'
        ) {
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


        /*
        |--------------------------------------------------------------------------
        | ATCELTAS SACENSĪBAS
        |--------------------------------------------------------------------------
        */

        if (
            $competition->status ===
            'cancelled'
        ) {
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


        /*
        |--------------------------------------------------------------------------
        | VALIDĀCIJA
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate([
                'course_hole_id' => [
                    'required',
                    'integer',
                    'exists:course_holes,id',
                ],

                'score' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:100',
                ],
            ]);


        /*
        |--------------------------------------------------------------------------
        | TRASE
        |--------------------------------------------------------------------------
        */

        $competition->load(
            'course'
        );

        if (!$competition->course) {
            abort(
                400,
                'Šīm sacensībām nav piesaistīta trase.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PĀRBAUDĀM, VAI GROZS PIEDER TRASEI
        |--------------------------------------------------------------------------
        */

        $courseHole =
            $competition
                ->course
                ->courseHoles()
                ->where(
                    'id',
                    $validated['course_hole_id']
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
                    $validated['score'],
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | PĀRBAUDĀM PROGRESU
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
        | JA VISI GROZI PABEIGTI
        |--------------------------------------------------------------------------
        */

        if (
            $playedHoleCount ===
            $holeCount
        ) {
            $this->finishScorecard(
                $competition
            );

            return redirect()
                ->route(
                    'competitions.show',
                    $competition
                )
                ->with(
                    'success',
                    'Scorecard pabeigts! Gala rezultāts un reitings saglabāts.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | NĀKAMAIS GROZS
        |--------------------------------------------------------------------------
        */

        $nextHole =
            $courseHole->hole_number + 1;

        if (
            $nextHole >
            $holeCount
        ) {
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


    /*
    |--------------------------------------------------------------------------
    | SCORECARD PABEIGŠANA
    |--------------------------------------------------------------------------
    */

    private function finishScorecard(
        Competition $competition
    ): void {
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

        $roundRating =
            $this->ratingService
                ->calculateRoundRating(
                    $competition->course,
                    (int) $totalScore
                );

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

                'round_rating' =>
                    $roundRating,
            ]
        );

        $this->ratingService
            ->updateUserRating(
                auth()->user()
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DALĪBNIEKA PĀRBAUDE
    |--------------------------------------------------------------------------
    */

    private function checkParticipant(
        Competition $competition
    ): void {
        $isParticipant =
            $competition
                ->users()
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->exists();

        if (!$isParticipant) {
            abort(
                403,
                'Tev nav tiesību piekļūt šim scorecard.'
            );
        }
    }
}