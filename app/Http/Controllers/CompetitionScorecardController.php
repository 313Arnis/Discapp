<?php
namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionHoleResult;
use App\Models\CompetitionResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompetitionScorecardController extends Controller
{
    // SCORECARD APSKATE
    public function show(Request $request, Competition $competition)
    {
        $this->checkParticipant($competition);
        $competition->load(['course.courseHoles', 'users']);

        if (!$competition->course) {
            abort(400, 'Šīm sacensībām nav piesaistīta trase.');
        }

        $courseHoles = $competition->course->courseHoles
            ->sortBy('hole_number')
            ->values();

        if ($courseHoles->isEmpty()) {
            abort(400, 'Šai trasei nav pievienoti grozi.');
        }

        $holeNumber = (int) $request->query('hole', 1);
        $currentHole = $courseHoles->firstWhere('hole_number', $holeNumber);

        if (!$currentHole) {
            $currentHole = $courseHoles->first();
        }

        $results = CompetitionHoleResult::where('competition_id', $competition->id)
            ->where('user_id', auth()->id())
            ->get()
            ->keyBy('course_hole_id');

        return view('competitions.scorecard', compact(
            'competition',
            'courseHoles',
            'results',
            'currentHole'
        ));
    }

    // GROZA REZULTĀTA SAGLABĀŠANA
    public function store(Request $request, Competition $competition)
    {
        $validated = $request->validate([
            'course_hole_id' => ['required', 'integer', 'exists:course_holes,id'],
            'score' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $user = $request->user();

        return DB::transaction(function () use ($competition, $validated, $user) {
            $competition = Competition::whereKey($competition->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->checkParticipant($competition);

            if ($competition->status !== 'active') {
                return redirect()->route('competitions.show', $competition)
                    ->with('error', 'Rezultātus var ievadīt tikai aktīvās sacensībās.');
            }

            $alreadyFinished = CompetitionResult::where('competition_id', $competition->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($alreadyFinished) {
                return redirect()->route('competitions.show', $competition)
                    ->with('error', 'Tavs scorecard jau ir pabeigts. Rezultātus vairs nevar mainīt.');
            }

            $competition->load('course');

            if (!$competition->course) {
                abort(400, 'Šīm sacensībām nav piesaistīta trase.');
            }

            $courseHoles = $competition->course->courseHoles()
                ->orderBy('hole_number')
                ->get();

            if ($courseHoles->isEmpty()) {
                abort(400, 'Šai trasei nav pievienoti grozi.');
            }

            $courseHole = $courseHoles->firstWhere('id', $validated['course_hole_id']);

            if (!$courseHole) {
                abort(403, 'Šis grozs nepieder sacensību trasei.');
            }

            CompetitionHoleResult::updateOrCreate(
                [
                    'competition_id' => $competition->id,
                    'user_id' => $user->id,
                    'course_hole_id' => $courseHole->id,
                ],
                [
                    'score' => $validated['score'],
                ]
            );

            $holeIds = $courseHoles->pluck('id');

            $playedHoleCount = CompetitionHoleResult::where('competition_id', $competition->id)
                ->where('user_id', $user->id)
                ->whereIn('course_hole_id', $holeIds)
                ->distinct()
                ->count('course_hole_id');

            if ($playedHoleCount === $courseHoles->count()) {
                $this->finishScorecard($competition, $user);

                return redirect()->route('competitions.show', $competition)
                    ->with('success', 'Scorecard pabeigts! Gala rezultāts saglabāts. Reitings tiks aprēķināts pēc sacensību pabeigšanas.');
            }

            $playedHoleIds = CompetitionHoleResult::where('competition_id', $competition->id)
                ->where('user_id', $user->id)
                ->whereIn('course_hole_id', $holeIds)
                ->pluck('course_hole_id');

            $nextHole = $courseHoles->first(function ($hole) use ($courseHole, $playedHoleIds) {
                return $hole->hole_number > $courseHole->hole_number
                    && !$playedHoleIds->contains($hole->id);
            });

            if (!$nextHole) {
                $nextHole = $courseHoles->first(function ($hole) use ($playedHoleIds) {
                    return !$playedHoleIds->contains($hole->id);
                });
            }

            return redirect()->route('competitions.scorecard', [
                'competition' => $competition,
                'hole' => $nextHole?->hole_number ?? $courseHole->hole_number,
            ])->with('success', 'Rezultāts saglabāts!');
        }, 3);
    }

    // SCORECARD PABEIGŠANA
    private function finishScorecard(Competition $competition, $user): void
    {
        $courseHoleIds = $competition->course->courseHoles()->pluck('id');

        $totalScore = CompetitionHoleResult::where('competition_id', $competition->id)
            ->where('user_id', $user->id)
            ->whereIn('course_hole_id', $courseHoleIds)
            ->sum('score');

        CompetitionResult::updateOrCreate(
            [
                'competition_id' => $competition->id,
                'user_id' => $user->id,
            ],
            [
                'score' => (int) $totalScore,
                'round_rating' => null,
            ]
        );
    }

    // DALĪBNIEKA PĀRBAUDE
    private function checkParticipant(Competition $competition): void
    {
        if (!auth()->check()) {
            abort(403, 'Lai piekļūtu scorecard, nepieciešams pieslēgties.');
        }

        $isParticipant = $competition->users()
            ->where('users.id', auth()->id())
            ->exists();

        if (!$isParticipant) {
            abort(403, 'Tev nav tiesību piekļūt šim scorecard.');
        }
    }
}
