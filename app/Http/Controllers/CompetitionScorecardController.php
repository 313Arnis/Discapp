<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionHoleResult;
use App\Models\CompetitionResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompetitionScorecardController extends Controller
{
    // ==========================================
    // REZULTĀTU IEVADES LAPA
    // ==========================================

    public function show(
        Request $request,
        Competition $competition
    ) {
        $this->checkParticipant($competition, $request->user()->id);

        if (!$competition->canEnterResults()) {
            return redirect()
                ->route('competitions.show', $competition)
                ->with(
                    'error',
                    'Rezultātu ievade nav pieejama. Pārbaudi sacensību sākuma un beigu laiku.'
                );
        }

        $competition->load([
            'course.courseHoles',
            'users',
        ]);

        if (!$competition->course) {
            abort(400, 'Sacensībām nav piesaistīta trase.');
        }

        $courseHoles = $competition->course
            ->courseHoles
            ->sortBy('hole_number')
            ->values();

        if ($courseHoles->isEmpty()) {
            abort(400, 'Trasei nav pievienoti grozi.');
        }

        $holeNumber = (int) $request->query(
            'hole',
            $courseHoles->first()->hole_number
        );

        $currentHole = $courseHoles->firstWhere(
            'hole_number',
            $holeNumber
        );

        if (!$currentHole) {
            $currentHole = $courseHoles->first();
        }

        $results = CompetitionHoleResult::where(
            'competition_id',
            $competition->id
        )
            ->where('user_id', $request->user()->id)
            ->get()
            ->keyBy('course_hole_id');

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

    // ==========================================
    // GROZA REZULTĀTA SAGLABĀŠANA
    // ==========================================

    public function store(
        Request $request,
        Competition $competition
    ) {
        $validated = $request->validate([
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

        return DB::transaction(function () use (
            $competition,
            $validated,
            $request
        ) {
            // Bloķē sacensību ierakstu,
            // kamēr tiek saglabāts rezultāts.
            $competition = Competition::whereKey(
                $competition->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $userId = $request->user()->id;

            // Pārbauda dalībnieka tiesības.
            $this->checkParticipant($competition, $userId);

            // Pārbauda sacensību statusu un laiku.
            if (!$competition->canEnterResults()) {
                return redirect()
                    ->route('competitions.show', $competition)
                    ->with(
                        'error',
                        'Rezultātus var ievadīt tikai sacensību norises laikā.'
                    );
            }

            $competition->load('course');

            if (!$competition->course) {
                abort(
                    400,
                    'Sacensībām nav piesaistīta trase.'
                );
            }

            // Pārbauda, vai grozs pieder
            // sacensību trasei.
            $courseHole = $competition->course
                ->courseHoles()
                ->where('id', $validated['course_hole_id'])
                ->first();

            if (!$courseHole) {
                abort(
                    403,
                    'Šis grozs nepieder sacensību trasei.'
                );
            }

            // Saglabā vai atjaunina groza rezultātu.
            CompetitionHoleResult::updateOrCreate(
                [
                    'competition_id' => $competition->id,
                    'user_id' => $userId,
                    'course_hole_id' => $courseHole->id,
                ],
                [
                    'score' => $validated['score'],
                ]
            );

            // Iegūst visus trases grozus.
            $courseHoles = $competition->course
                ->courseHoles()
                ->orderBy('hole_number')
                ->get();

            $holeIds = $courseHoles->pluck('id');
            $holeCount = $holeIds->count();

            // Iegūst aizpildītos grozus.
            $playedHoleIds = CompetitionHoleResult::where(
                'competition_id',
                $competition->id
            )
                ->where('user_id', $userId)
                ->whereIn('course_hole_id', $holeIds)
                ->distinct()
                ->pluck('course_hole_id');

            // ======================================
            // VISI GROZI AIZPILDĪTI
            // ======================================

            if (
                $holeCount > 0 &&
                $playedHoleIds->count() === $holeCount
            ) {
                $this->finishScorecard(
                    $competition,
                    $userId,
                    $holeIds
                );

                return redirect()
                    ->route('competitions.show', $competition)
                    ->with(
                        'success',
                        'Scorecard pabeigts! Gala rezultāts saglabāts.'
                    );
            }

            // ======================================
            // NĀKAMAIS NEAIZPILDĪTAIS GROZS
            // ======================================

            $nextHole = $courseHoles->first(
                function ($hole) use ($playedHoleIds) {
                    return !$playedHoleIds->contains(
                        $hole->id
                    );
                }
            );

            return redirect()
                ->route(
                    'competitions.scorecard',
                    [
                        'competition' => $competition,
                        'hole' => $nextHole?->hole_number
                            ?? $courseHole->hole_number,
                    ]
                )
                ->with(
                    'success',
                    'Rezultāts saglabāts!'
                );
        }, 3);
    }

    // ==========================================
    // SCORECARD PABEIGŠANA
    // ==========================================

    private function finishScorecard(
        Competition $competition,
        int $userId,
        $holeIds
    ): void {
        // Saskaita tikai sacensību trases
        // grozu rezultātus.
        $totalScore = CompetitionHoleResult::where(
            'competition_id',
            $competition->id
        )
            ->where('user_id', $userId)
            ->whereIn('course_hole_id', $holeIds)
            ->sum('score');

        // Saglabā gala rezultātu.
        CompetitionResult::updateOrCreate(
            [
                'competition_id' => $competition->id,
                'user_id' => $userId,
            ],
            [
                'score' => (int) $totalScore,

                // Reitingu aprēķinās tikai
                // pēc sacensību pabeigšanas.
                'round_rating' => null,
            ]
        );
    }

    // ==========================================
    // DALĪBNIEKA PĀRBAUDE
    // ==========================================

    private function checkParticipant(
        Competition $competition,
        int $userId
    ): void {
        $isParticipant = $competition
            ->users()
            ->where('users.id', $userId)
            ->exists();

        if (!$isParticipant) {
            abort(
                403,
                'Tev nav tiesību ievadīt šo sacensību rezultātus.'
            );
        }
    }
}
