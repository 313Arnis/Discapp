<?php

namespace App\Services;

use App\Models\CompetitionResult;
use App\Models\PracticeRound;
use App\Models\User;
use Illuminate\Support\Collection;

class ProfileService
{
    /*
    |--------------------------------------------------------------------------
    | PROFILA DATI
    |--------------------------------------------------------------------------
    */

    public function getProfileData(
        User $user
    ): array {
        $userResults =
            $this->getCompetitionResults(
                $user
            );

        $competitionHistory =
            $this->buildCompetitionHistory(
                $user,
                $userResults
            );

        return [
            'playedCompetitions' =>
                $userResults->count(),

            'wins' =>
                $this->calculateWins(
                    $user,
                    $userResults
                ),

            'competitionHistory' =>
                $competitionHistory,

            'practiceHistory' =>
                $this->getPracticeHistory(
                    $user
                ),

            'activePracticeRound' =>
                $this->getActivePracticeRound(
                    $user
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | SACENSĪBU REZULTĀTI
    |--------------------------------------------------------------------------
    */

    private function getCompetitionResults(
        User $user
    ): Collection {
        return CompetitionResult::with([
            'competition.course.courseHoles',
            'competition.users',
            'competition.results',
        ])
            ->where(
                'user_id',
                $user->id
            )
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | UZVARU SKAITS
    |--------------------------------------------------------------------------
    */

    private function calculateWins(
        User $user,
        Collection $userResults
    ): int {
        $wins = 0;

        foreach ($userResults as $userResult) {
            $competition =
                $userResult->competition;

            if (!$competition) {
                continue;
            }

            $division =
                $this->getUserDivision(
                    $competition,
                    $user
                );

            $divisionUserIds =
                $this->getDivisionUserIds(
                    $competition,
                    $division
                );

            $bestDivisionScore =
                $competition
                    ->results
                    ->whereIn(
                        'user_id',
                        $divisionUserIds
                    )
                    ->min('score');

            if (
                $bestDivisionScore !== null &&
                $userResult->score ===
                $bestDivisionScore
            ) {
                $wins++;
            }
        }

        return $wins;
    }


    /*
    |--------------------------------------------------------------------------
    | SACENSĪBU VĒSTURE
    |--------------------------------------------------------------------------
    */

    private function buildCompetitionHistory(
        User $user,
        Collection $userResults
    ): Collection {
        return $userResults
            ->map(
                function ($result) use ($user) {
                    $competition =
                        $result->competition;

                    if (!$competition) {
                        return null;
                    }

                    $division =
                        $this->getUserDivision(
                            $competition,
                            $user
                        );

                    $coursePar =
                        $competition->course
                            ? $competition
                                ->course
                                ->courseHoles
                                ->sum('par')
                            : 0;

                    $relativeToPar =
                        $coursePar > 0
                            ? $result->score -
                                $coursePar
                            : null;

                    $divisionUserIds =
                        $this->getDivisionUserIds(
                            $competition,
                            $division
                        );

                    $betterResults =
                        $competition
                            ->results
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

                    return [
                        'competition' =>
                            $competition,

                        'division' =>
                            $division,

                        'score' =>
                            $result->score,

                        'relative_to_par' =>
                            $relativeToPar,

                        'round_rating' =>
                            $result->round_rating,

                        'place' =>
                            $betterResults + 1,
                    ];
                }
            )
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
    }


    /*
    |--------------------------------------------------------------------------
    | LIETOTĀJA DIVĪZIJA
    |--------------------------------------------------------------------------
    */

    private function getUserDivision(
        $competition,
        User $user
    ): string {
        $participant =
            $competition
                ->users
                ->firstWhere(
                    'id',
                    $user->id
                );

        return
            $participant
                ?->pivot
                ?->division
            ?? '-';
    }


    /*
    |--------------------------------------------------------------------------
    | DIVĪZIJAS SPĒLĒTĀJI
    |--------------------------------------------------------------------------
    */

    private function getDivisionUserIds(
        $competition,
        string $division
    ): Collection {
        return $competition
            ->users
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
    }


    /*
    |--------------------------------------------------------------------------
    | PRACTICE VĒSTURE
    |--------------------------------------------------------------------------
    */

    private function getPracticeHistory(
        User $user
    ): Collection {
        return PracticeRound::with(
            'course'
        )
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'status',
                'finished'
            )
            ->orderByDesc(
                'finished_at'
            )
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | AKTĪVAIS PRACTICE APLIS
    |--------------------------------------------------------------------------
    */

    private function getActivePracticeRound(
        User $user
    ): ?PracticeRound {
        return PracticeRound::with(
            'course'
        )
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'status',
                'active'
            )
            ->latest()
            ->first();
    }
}