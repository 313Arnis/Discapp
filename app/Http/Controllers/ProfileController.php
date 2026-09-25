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
        | IZSPĒLĒTĀS SACENSĪBAS
        |--------------------------------------------------------------------------
        */

        $playedCompetitions = CompetitionResult::where(
            'user_id',
            $user->id
        )->count();


        /*
        |--------------------------------------------------------------------------
        | UZVARAS
        |--------------------------------------------------------------------------
        */

        $wins = 0;

        $userResults = CompetitionResult::where(
            'user_id',
            $user->id
        )->get();


        foreach ($userResults as $userResult) {

            $bestScore = CompetitionResult::where(
                'competition_id',
                $userResult->competition_id
            )->min('score');


            if ($userResult->score === $bestScore) {
                $wins++;
            }
        }


        return view(
            'profile',
            compact(
                'user',
                'playedCompetitions',
                'wins'
            )
        );
    }


    /**
     * Profila bildes atjaunošana
     */
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' =>
                'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);


        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | IZDZĒŠAM VECO BILDI
        |--------------------------------------------------------------------------
        */

        if (
            $user->profile_picture &&
            Storage::disk('public')->exists($user->profile_picture)
        ) {
            Storage::disk('public')->delete(
                $user->profile_picture
            );
        }


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
        | SAGLABĀJAM CEĻU LIETOTĀJAM
        |--------------------------------------------------------------------------
        */

        $user->profile_picture = $path;

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


        if (
            $user->profile_picture &&
            Storage::disk('public')->exists($user->profile_picture)
        ) {
            Storage::disk('public')->delete(
                $user->profile_picture
            );
        }


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