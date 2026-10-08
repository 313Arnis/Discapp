<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::with([
            'course',
            'creator',
            'users',
        ])
            ->latest('date')
            ->get();

        return view('competitions.index', compact('competitions'));
    }

    public function create()
    {
        $courses = Course::orderBy('name')->get();

        return view('competitions.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCompetition($request);

        Competition::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('competitions.index')
            ->with('success', 'Sacensības veiksmīgi izveidotas!');
    }

    public function show(Competition $competition)
    {
        $competition->load([
            'users',
            'creator',
            'course.courseHoles',
            'holeResults.user',
            'holeResults.courseHole',
        ]);

        $players = $competition->users
            ->map(function ($user) use ($competition) {
                $results = $competition->holeResults
                    ->where('user_id', $user->id);

                $totalScore = $results->sum('score');
                $totalPar = 0;

                foreach ($results as $result) {
                    if ($result->courseHole) {
                        $totalPar += $result->courseHole->par;
                    }
                }

                return [
                    'user' => $user,
                    'division' => $user->pivot->division ?? '-',
                    'played' => $results->count(),
                    'total_score' => $totalScore,
                    'total_par' => $totalPar,
                    'relative' => $totalScore - $totalPar,
                ];
            })
            ->sort(function ($a, $b) {
                if ($a['played'] === 0 && $b['played'] > 0) {
                    return 1;
                }

                if ($a['played'] > 0 && $b['played'] === 0) {
                    return -1;
                }

                if ($a['relative'] !== $b['relative']) {
                    return $a['relative'] <=> $b['relative'];
                }

                if ($a['total_score'] !== $b['total_score']) {
                    return $a['total_score'] <=> $b['total_score'];
                }

                return strcasecmp(
                    $a['user']->name,
                    $b['user']->name
                );
            })
            ->values();

        return view('competitions.show', compact(
            'competition',
            'players'
        ));
    }

    public function edit(Competition $competition)
    {
        $this->checkOwner($competition);

        $courses = Course::orderBy('name')->get();

        return view('competitions.edit', compact(
            'competition',
            'courses'
        ));
    }

    public function update(
        Request $request,
        Competition $competition
    ) {
        $this->checkOwner($competition);

        $validated = $this->validateCompetition($request);

        // Sacensībām ar ievadītiem rezultātiem
        // trasi vairs nedrīkst mainīt.
        $hasResults = $competition->holeResults()->exists()
            || $competition->results()->exists();

        if (
            $hasResults &&
            (int) $validated['course_id'] !== (int) $competition->course_id
        ) {
            throw ValidationException::withMessages([
                'course_id' =>
                    'Trasi nevar mainīt, jo sacensībās jau ir ievadīti rezultāti.',
            ]);
        }

        // Dalībnieku limits nedrīkst būt mazāks
        // par jau reģistrēto dalībnieku skaitu.
        $participantCount = $competition->users()->count();

        if (
            $validated['max_players'] !== null &&
            $validated['max_players'] < $participantCount
        ) {
            throw ValidationException::withMessages([
                'max_players' =>
                    'Maksimālais dalībnieku skaits nevar būt mazāks par '
                    . $participantCount . '.',
            ]);
        }

        $competition->update($validated);

        return redirect()
            ->route('competitions.show', $competition)
            ->with('success', 'Sacensības veiksmīgi atjauninātas!');
    }

    public function destroy(Competition $competition)
    {
        $this->checkOwner($competition);

        // Neļaujam izdzēst vēsturiskus rezultātus.
        if (
            $competition->holeResults()->exists() ||
            $competition->results()->exists()
        ) {
            return back()->with(
                'error',
                'Sacensības nevar dzēst, jo tajās jau ir ievadīti rezultāti.'
            );
        }

        $competition->delete();

        return redirect()
            ->route('competitions.index')
            ->with('success', 'Sacensības izdzēstas!');
    }

    private function validateCompetition(Request $request): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'date' => [
                'required',
                'date',
            ],
            'course_id' => [
                'required',
                'integer',
                'exists:courses,id',
            ],
            'max_players' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'status' => [
                'required',
                Rule::in([
                    'planned',
                    'active',
                    'finished',
                    'cancelled',
                ]),
            ],
            'registration_starts_at' => [
                'required',
                'date',
                'before:registration_ends_at',
            ],
            'registration_ends_at' => [
                'required',
                'date',
                'after:registration_starts_at',
            ],
        ]);
    }

    private function checkOwner(Competition $competition): void
    {
        if ((int) $competition->user_id !== (int) auth()->id()) {
            abort(403, 'Tev nav tiesību veikt šo darbību.');
        }
    }
}