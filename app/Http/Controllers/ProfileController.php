<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileService $profileService
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILA LAPA
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user =
            auth()->user();

        $profileData =
            $this->profileService
                ->getProfileData(
                    $user
                );

        return view(
            'profile',
            [
                'user' =>
                    $user,

                'playedCompetitions' =>
                    $profileData[
                        'playedCompetitions'
                    ],

                'wins' =>
                    $profileData[
                        'wins'
                    ],

                'competitionHistory' =>
                    $profileData[
                        'competitionHistory'
                    ],

                'practiceHistory' =>
                    $profileData[
                        'practiceHistory'
                    ],

                'activePracticeRound' =>
                    $profileData[
                        'activePracticeRound'
                    ],
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILA BILDES ATJAUNOŠANA
    |--------------------------------------------------------------------------
    */

    public function updateProfilePicture(
        Request $request
    ) {
        $validated =
            $request->validate([
                'profile_picture' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120',
                ],
            ]);

        $user =
            auth()->user();

        $newPicture =
            $request
                ->file(
                    'profile_picture'
                )
                ->store(
                    'profile-pictures',
                    'public'
                );

        $oldPicture =
            $user->profile_picture;

        $user->profile_picture =
            $newPicture;

        $user->save();

        if (
            $oldPicture &&
            Storage::disk('public')
                ->exists(
                    $oldPicture
                )
        ) {
            Storage::disk('public')
                ->delete(
                    $oldPicture
                );
        }

        return redirect()
            ->route(
                'profile'
            )
            ->with(
                'success',
                'Profila bilde veiksmīgi nomainīta!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILA BILDES DZĒŠANA
    |--------------------------------------------------------------------------
    */

    public function deleteProfilePicture()
    {
        $user =
            auth()->user();

        $profilePicture =
            $user->profile_picture;

        $user->profile_picture =
            null;

        $user->save();

        if (
            $profilePicture &&
            Storage::disk('public')
                ->exists(
                    $profilePicture
                )
        ) {
            Storage::disk('public')
                ->delete(
                    $profilePicture
                );
        }

        return redirect()
            ->route(
                'profile'
            )
            ->with(
                'success',
                'Profila bilde noņemta!'
            );
    }
}