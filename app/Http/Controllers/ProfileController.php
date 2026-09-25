<?php

namespace App\Http\Controllers;

use App\Models\CompetitionResult;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $playedCompetitions = CompetitionResult::where(
            'user_id',
            $user->id
        )->count();

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

        return view('profile', compact(
            'user',
            'playedCompetitions',
            'wins'
        ));
    }
}