<?php

namespace App\Http\Controllers;

use App\Models\CompetitionResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Profila lapa
     */
    public function index()
    {
        $user = auth()->user();

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
        |
        | Uzvara tiek skaitīta savā divīzijā.
        |
        */

        $wins = 0;

        foreach ($userResults as $userResult) {

            $competition = $userResult->competition;

            if (!$competition) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | ATRODAM LIETOTĀJA DIVĪZIJU
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
            | ATRODAM VISUS TĀS PAŠAS DIVĪZIJAS SPĒLĒTĀJUS
            |--------------------------------------------------------------------------
            */

            $divisionUserIds = $competition->users
                ->filter(
                    function ($competitionUser) use ($division) {
                        return (
                            $competitionUser->pivot->division
                            ?? '-'
                        ) === $division;
                    }
                )
                ->pluck('id');

            /*
            |--------------------------------------------------------------------------
            | LABĀKAIS REZULTĀTS DIVĪZIJĀ
            |--------------------------------------------------------------------------
            */

            $bestDivisionScore = $competition->results
                ->whereIn(
                    'user_id',
                    $divisionUserIds
                )
                ->min('score');

            /*
            |--------------------------------------------------------------------------
            | JA LIETOTĀJAM IR LABĀKAIS REZULTĀTS
            |--------------------------------------------------------------------------
            */

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
                | LIETOTĀJA DIVĪZIJA
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
                | TRASES KOPĒJAIS PAR
                |--------------------------------------------------------------------------
                */

                $coursePar = $competition->course
                    ? $competition
                        ->course
                        ->courseHoles
                        ->sum('par')
                    : 0;

                /*
                |--------------------------------------------------------------------------
                | REZULTĀTS PRET PAR
                |--------------------------------------------------------------------------
                */

                $relativeToPar = $coursePar > 0
                    ? $result->score - $coursePar
                    : null;

                /*
                |--------------------------------------------------------------------------
                | SPĒLĒTĀJI TAJĀ PAŠĀ DIVĪZIJĀ
                |--------------------------------------------------------------------------
                */

                $divisionUserIds = $competition->users
                    ->filter(
                        function ($competitionUser) use ($division) {

                            return (
                                $competitionUser->pivot->division
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

                $betterResults = $competition->results
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

                /*
                |--------------------------------------------------------------------------
                | SACENSĪBU VĒSTURES IERAKSTS
                |--------------------------------------------------------------------------
                */

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
                        $place,
                ];
            })
            ->filter()
            ->sortByDesc(
                function ($history) {
                    return
                        $history['competition']->date;
                }
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | PROFILA SKATS
        |--------------------------------------------------------------------------
        */

        return view(
            'profile',
            compact(
                'user',
                'playedCompetitions',
                'wins',
                'competitionHistory'
            )
        );
    }


    /**
     * Profila bildes atjaunošana
     */
    public function updateProfilePicture(
        Request $request
    ) {
        $request->validate([
            'profile_picture' =>
                'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | SAGLABĀJAM JAUNO BILDI
        |--------------------------------------------------------------------------
        */

        $path = $request
            ->file('profile_picture')
            ->store(
                'profile-pictures',
                'public'
            );

        /*
        |--------------------------------------------------------------------------
        | IZDZĒŠAM VECO BILDI
        |--------------------------------------------------------------------------
        */

        if (
            $user->profile_picture &&
            Storage::disk('public')->exists(
                $user->profile_picture
            )
        ) {
            Storage::disk('public')->delete(
                $user->profile_picture
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SAGLABĀJAM CEĻU LIETOTĀJAM
        |--------------------------------------------------------------------------
        */

        $user->profile_picture =
            $path;

        $user->save();

        return redirect()
            ->route('profile')
            ->with(
                'success',
                'Profila bilde veiksmīgi nomainīta!'
            );
    }


    /**
     * Profila bildes dzēšana
     */
    public function deleteProfilePicture()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | IZDZĒŠAM BILDI
        |--------------------------------------------------------------------------
        */

        if (
            $user->profile_picture &&
            Storage::disk('public')->exists(
                $user->profile_picture
            )
        ) {
            Storage::disk('public')->delete(
                $user->profile_picture
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NOŅEMAM BILDI NO LIETOTĀJA
        |--------------------------------------------------------------------------
        */

        $user->profile_picture = null;

        $user->save();

        return redirect()
            ->route('profile')
            ->with(
                'success',
                'Profila bilde noņemta!'
            );
    }
}