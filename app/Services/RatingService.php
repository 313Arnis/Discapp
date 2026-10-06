<?php

namespace App\Services;

use App\Models\CompetitionResult;
use App\Models\Course;
use App\Models\User;

class RatingService
{
    /**
     * Aprēķina viena pabeigta raunda reitingu.
     */
    public function calculateRoundRating(
        Course $course,
        int $totalScore
    ): int {
        $coursePar = (int) $course
            ->courseHoles()
            ->sum('par');

        /*
        |--------------------------------------------------------------------------
        | DISCAPP REITINGA SISTĒMA
        |--------------------------------------------------------------------------
        |
        | 1000-rated rezultāts = trases PAR - 5
        | 1 metiens = 10 reitinga punkti
        |
        */

        $rating1000Score = $coursePar - 5;

        $ratingPerThrow = 10;

        $roundRating =
            1000 +
            (
                ($rating1000Score - $totalScore)
                * $ratingPerThrow
            );

        return (int) round($roundRating);
    }


    /**
     * Pārrēķina lietotāja kopējo reitingu
     * no visiem viņa reitētajiem sacensību raundiem.
     */
    public function updateUserRating(User $user): void
    {
        $ratings = CompetitionResult::where(
            'user_id',
            $user->id
        )
            ->whereNotNull('round_rating')
            ->orderBy('created_at')
            ->pluck('round_rating')
            ->map(
                fn ($rating) => (int) $rating
            )
            ->values();


        /*
        |--------------------------------------------------------------------------
        | NAV REITĒTU RAUNDU
        |--------------------------------------------------------------------------
        */

        if ($ratings->isEmpty()) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | LĪDZ 8 RAUNDIEM
        |--------------------------------------------------------------------------
        |
        | Izmantojam visu raundu vidējo vērtību.
        |
        */

        if ($ratings->count() < 9) {

            $user->rating =
                (int) round(
                    $ratings->avg()
                );

            $user->save();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | 9 VAI VAIRĀK RAUNDI
        |--------------------------------------------------------------------------
        |
        | Jaunākajiem 25% raundu dodam dubultu svaru.
        |
        */

        $recentCount = max(
            1,
            (int) ceil(
                $ratings->count() * 0.25
            )
        );

        $recentRatings =
            $ratings->take(
                -$recentCount
            );

        $weightedSum =
            $ratings->sum()
            + $recentRatings->sum();

        $weightedCount =
            $ratings->count()
            + $recentRatings->count();

        $user->rating =
            (int) round(
                $weightedSum
                / $weightedCount
            );

        $user->save();
    }
}