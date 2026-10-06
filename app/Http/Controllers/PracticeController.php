<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\PracticeRound;
use App\Models\PracticeHoleResult;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    public function index()
    {
        $courses = Course::with('courseHoles')
            ->orderBy('name')
            ->get();

        $activeRound = PracticeRound::with([
            'course',
            'holeResults'
        ])
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->latest()
            ->first();

        return view(
            'practice.index',
            compact(
                'courses',
                'activeRound'
            )
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => [
                'required',
                'exists:courses,id'
            ],
        ]);

        $course = Course::with('courseHoles')
            ->findOrFail($validated['course_id']);

        if ($course->courseHoles->isEmpty()) {
            return back()->withErrors([
                'course_id' => 'Šai trasei nav pievienoti grozi.'
            ]);
        }

        $activeRound = PracticeRound::where(
            'user_id',
            auth()->id()
        )
            ->where('status', 'active')
            ->first();

        if ($activeRound) {
            return redirect()
                ->route(
                    'practice.scorecard',
                    $activeRound
                )
                ->with(
                    'success',
                    'Tev jau bija iesākts Practice aplis.'
                );
        }

        $practiceRound = PracticeRound::create([
            'user_id' => auth()->id(),
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        return redirect()->route(
            'practice.scorecard',
            $practiceRound
        );
    }


    public function scorecard(PracticeRound $practiceRound)
    {
        $this->authorizeRound($practiceRound);

        $practiceRound->load([
            'course.courseHoles',
            'holeResults.courseHole'
        ]);

        $holes = $practiceRound
            ->course
            ->courseHoles;

        $results = $practiceRound
            ->holeResults
            ->keyBy('course_hole_id');

        $currentHole = $holes->first(
            function ($hole) use ($results) {
                return !$results->has($hole->id);
            }
        );

        if (!$currentHole) {
            $currentHole = $holes->last();
        }

        $totalScore = $practiceRound
            ->holeResults
            ->sum('score');

        $playedPar = $practiceRound
            ->holeResults
            ->sum(function ($result) {
                return $result->courseHole
                    ? $result->courseHole->par
                    : 0;
            });

        $relativeToPar =
            $totalScore - $playedPar;

        $isComplete =
            $practiceRound->holeResults->count()
            >= $holes->count();

        return view(
            'practice.scorecard',
            compact(
                'practiceRound',
                'holes',
                'results',
                'currentHole',
                'totalScore',
                'relativeToPar',
                'isComplete'
            )
        );
    }


    public function storeHoleResult(
        Request $request,
        PracticeRound $practiceRound
    ) {
        $this->authorizeRound($practiceRound);

        if ($practiceRound->status !== 'active') {
            return redirect()->route('profile');
        }

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
                'max:20',
            ],
        ]);

        $practiceRound->load('course.courseHoles');

        $hole = $practiceRound
            ->course
            ->courseHoles
            ->firstWhere(
                'id',
                $validated['course_hole_id']
            );

        if (!$hole) {
            abort(403);
        }

        PracticeHoleResult::updateOrCreate(
            [
                'practice_round_id' =>
                    $practiceRound->id,

                'course_hole_id' =>
                    $hole->id,
            ],
            [
                'score' =>
                    $validated['score'],
            ]
        );

        return redirect()->route(
            'practice.scorecard',
            $practiceRound
        );
    }


    public function finish(PracticeRound $practiceRound)
    {
        $this->authorizeRound($practiceRound);

        $practiceRound->load([
            'course.courseHoles',
            'holeResults.courseHole'
        ]);

        $holes = $practiceRound
            ->course
            ->courseHoles;

        if (
            $practiceRound->holeResults->count()
            < $holes->count()
        ) {
            return redirect()
                ->route(
                    'practice.scorecard',
                    $practiceRound
                )
                ->withErrors([
                    'score' =>
                        'Visiem groziem jābūt aizpildītiem.'
                ]);
        }

        $totalScore = $practiceRound
            ->holeResults
            ->sum('score');

        $coursePar = $holes->sum('par');

        $practiceRound->update([
            'total_score' => $totalScore,

            'relative_to_par' =>
                $totalScore - $coursePar,

            'status' => 'finished',

            'finished_at' => now(),
        ]);

        return redirect()
            ->route('profile')
            ->with(
                'success',
                'Practice aplis veiksmīgi pabeigts!'
            );
    }


    public function destroy(PracticeRound $practiceRound)
    {
        $this->authorizeRound($practiceRound);

        $practiceRound->delete();

        return redirect()
            ->route('profile')
            ->with(
                'success',
                'Practice aplis izdzēsts.'
            );
    }


    private function authorizeRound(
        PracticeRound $practiceRound
    ): void {
        if (
            $practiceRound->user_id
            !== auth()->id()
        ) {
            abort(403);
        }
    }
}