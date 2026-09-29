<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CompetitionResult;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Spēlētāju saraksts un meklēšana
     */
    public function index(Request $request)
    {
        $search = trim(
            (string) $request->input('search', '')
        );

        $players = User::query()
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );
                }
            )
            ->withCount([
                'results as played_competitions_count'
            ])
            ->orderByDesc('rating')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view(
            'players.index',
            compact(
                'players',
                'search'
            )
        );
    }


    /**
     * Publiskais spēlētāja profils
     */
    public function show(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | LIETOTĀJA REZULTĀTI
        |--------------------------------------------------------------------------
        */

        $userResults = CompetitionResult::with([
            'competition.course.courseHoles',
            'competition.users',
            'competition.results',
        ])
            ->where('user_id', $user->id)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | IZSPĒLĒTĀS SACENSĪBAS
        |--------------------------------------------------------------------------
        */

        $playedCompetitions = $userResults->count();


        /*
        |--------------------------------------------------------------------------
        | UZVARAS
        |--------------------------------------------------------------------------
        */

        $wins = 0;

        foreach ($userResults as $userResult) {

            $competition = $userResult->competition;

            if (!$competition) {
                continue;
            }

            $participant = $competition->users
                ->firstWhere(
                    'id',
                    $user->id
                );

            $division =
                $participant?->pivot?->division;

            if (!$division) {
                continue;
            }

            $divisionUserIds = $competition->users
                ->filter(
                    function ($competitionUser) use ($division) {
                        return (
                            $competitionUser->pivot->division
                            ?? null
                        ) === $division;
                    }
                )
                ->pluck('id');

            $bestDivisionScore =
                $competition->results
                    ->whereIn(
                        'user_id',
                        $divisionUserIds
                    )
                    ->min('score');

            if (
                $bestDivisionScore !== null &&
                $userResult->score === $bestDivisionScore
            ) {
                $wins++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SACENSĪBU VĒSTURE
        |--------------------------------------------------------------------------
        */

        $competitionHistory = $userResults
            ->map(function ($result) use ($user) {

                $competition = $result->competition;

                if (!$competition) {
                    return null;
                }


                /*
                |--------------------------------------------------------------------------
                | DIVĪZIJA
                |--------------------------------------------------------------------------
                */

                $participant = $competition->users
                    ->firstWhere(
                        'id',
                        $user->id
                    );

                $division =
                    $participant?->pivot?->division
                    ?? '-';


                /*
                |--------------------------------------------------------------------------
                | TRASES PAR
                |--------------------------------------------------------------------------
                */

                $coursePar = 0;

                if ($competition->course) {

                    $coursePar =
                        $competition
                            ->course
                            ->courseHoles
                            ->sum('par');

                }


                /*
                |--------------------------------------------------------------------------
                | REZULTĀTS PRET PAR
                |--------------------------------------------------------------------------
                */

                $relativeToPar =
                    $coursePar > 0
                        ? $result->score - $coursePar
                        : null;


                /*
                |--------------------------------------------------------------------------
                | DIVĪZIJAS SPĒLĒTĀJI
                |--------------------------------------------------------------------------
                */

                $divisionUserIds =
                    $competition->users
                        ->filter(
                            function (
                                $competitionUser
                            ) use ($division) {

                                return (
                                    $competitionUser
                                        ->pivot
                                        ->division
                                    ?? '-'
                                ) === $division;

                            }
                        )
                        ->pluck('id');


                /*
                |--------------------------------------------------------------------------
                | VIETA DIVĪZIJĀ
                |--------------------------------------------------------------------------
                */

                $betterResults =
                    $competition->results
                        ->whereIn(
                            'user_id',
                            $divisionUserIds
                        )
                        ->where(
                            'score',
                            '<',
                            $result->score
                        )
                        ->count();

                $place =
                    $betterResults + 1;


                return [
                    'competition' =>
                        $competition,

                    'division' =>
                        $division,

                    'score' =>
                        $result->score,

                    'relative_to_par' =>
                        $relativeToPar,

                    'place' =>
                        $place,
                ];
            })
            ->filter()
            ->sortByDesc(
                function ($history) {
                    return
                        $history[
                            'competition'
                        ]->date;
                }
            )
            ->values();


        return view(
            'players.show',
            compact(
                'user',
                'playedCompetitions',
                'wins',
                'competitionHistory'
            )
        );
    }
}