<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionHoleResult;
use App\Models\CompetitionResult;
use App\Services\RatingService;
use Illuminate\Http\Request;

class CompetitionRegistrationController extends Controller
{
    public function __construct(
        private RatingService $ratingService
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | PIETEIKŠANĀS FORMA
    |--------------------------------------------------------------------------
    */

    public function create(
        Competition $competition
    ) {
        $statusError =
            $this->getJoinStatusError(
                $competition
            );

        if ($statusError) {
            return back()->with(
                'error',
                $statusError
            );
        }

        $rating =
            auth()->user()->rating;

        $divisions =
            $this->getAvailableDivisions(
                $rating
            );

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

    public function store(
        Request $request,
        Competition $competition
    ) {
        $statusError =
            $this->getJoinStatusError(
                $competition
            );

        if ($statusError) {
            return back()->with(
                'error',
                $statusError
            );
        }

        $rating =
            auth()->user()->rating;

        $divisions =
            $this->getAvailableDivisions(
                $rating
            );

        $validated =
            $request->validate([
                'division' => [
                    'required',
                    'in:' .
                    implode(
                        ',',
                        array_keys(
                            $divisions
                        )
                    ),
                ],
            ]);

        $alreadyJoined =
            $competition
                ->users()
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->exists();

        if ($alreadyJoined) {
            return back()->with(
                'error',
                'Tu jau esi pieteicies šīm sacensībām.'
            );
        }

        $competitionIsFull =
            $competition->max_players &&
            $competition
                ->users()
                ->count()
            >=
            $competition->max_players;

        if ($competitionIsFull) {
            return back()->with(
                'error',
                'Šīs sacensības jau ir pilnas.'
            );
        }

        $competition
            ->users()
            ->attach(
                auth()->id(),
                [
                    'division' =>
                        $validated['division'],
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
    | IZSTĀŠANĀS
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Competition $competition
    ) {
        if (
            $competition->user_id ===
            auth()->id()
        ) {
            return back()->with(
                'error',
                'Sacensību veidotājs nevar izstāties no savām sacensībām.'
            );
        }

        if (
            $competition->status ===
            'finished'
        ) {
            return back()->with(
                'error',
                'No pabeigtām sacensībām vairs nevar izstāties.'
            );
        }

        $isParticipant =
            $competition
                ->users()
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->exists();

        if (!$isParticipant) {
            return back()->with(
                'error',
                'Tu nepiedalies šajās sacensībās.'
            );
        }

        $competition
            ->users()
            ->detach(
                auth()->id()
            );

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

        $this->ratingService
            ->updateUserRating(
                auth()->user()
            );

        return back()->with(
            'success',
            'Tu vairs nepiedalies šajās sacensībās.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PIEEJAMĀS DIVĪZIJAS
    |--------------------------------------------------------------------------
    */

    private function getAvailableDivisions(
        $rating
    ): array {
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
    | STATUSA PĀRBAUDE
    |--------------------------------------------------------------------------
    */

    private function getJoinStatusError(
        Competition $competition
    ): ?string {
        if (
            $competition->status ===
            'finished'
        ) {
            return
                'Pabeigtām sacensībām vairs nevar pieteikties.';
        }

        if (
            $competition->status ===
            'cancelled'
        ) {
            return
                'Atceltām sacensībām nevar pieteikties.';
        }

        return null;
    }
}