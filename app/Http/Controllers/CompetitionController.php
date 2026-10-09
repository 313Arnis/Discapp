<?php
namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Course;
use App\Services\RatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CompetitionController extends Controller
{
    public function __construct(private RatingService $ratingService)
    {
    }

    public function index()
    {
        $competitions = Competition::with(['course', 'creator', 'users'])
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

        if ($validated['status'] === 'finished') {
            throw ValidationException::withMessages([
                'status' => 'Jaunas sacensības nevar izveidot ar statusu Pabeigtas.',
            ]);
        }

        Competition::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('competitions.index')
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

                return strcasecmp($a['user']->name, $b['user']->name);
            })
            ->values();

        return view('competitions.show', compact('competition', 'players'));
    }

    public function edit(Competition $competition)
    {
        $this->checkOwner($competition);

        if ($competition->status === 'finished') {
            return redirect()->route('competitions.show', $competition)
                ->with('error', 'Pabeigtas sacensības vairs nevar rediģēt.');
        }

        $courses = Course::orderBy('name')->get();

        return view('competitions.edit', compact('competition', 'courses'));
    }

    public function update(Request $request, Competition $competition)
    {
        $this->checkOwner($competition);
        $validated = $this->validateCompetition($request);

        return DB::transaction(function () use ($competition, $validated) {
            $competition = Competition::whereKey($competition->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($competition->status === 'finished') {
                return redirect()->route('competitions.show', $competition)
                    ->with('error', 'Pabeigtas sacensības vairs nevar rediģēt.');
            }

            $hasResults = $competition->holeResults()->exists()
                || $competition->results()->exists();

            if ($hasResults && (int) $validated['course_id'] !== (int) $competition->course_id) {
                throw ValidationException::withMessages([
                    'course_id' => 'Trasi nevar mainīt, jo sacensībās jau ir ievadīti rezultāti.',
                ]);
            }

            $participantCount = $competition->users()->count();

            if ($validated['max_players'] !== null && $validated['max_players'] < $participantCount) {
                throw ValidationException::withMessages([
                    'max_players' => 'Maksimālais dalībnieku skaits nevar būt mazāks par ' . $participantCount . '.',
                ]);
            }

            if ($hasResults && $validated['status'] !== 'active' && $validated['status'] !== 'finished') {
                throw ValidationException::withMessages([
                    'status' => 'Sacensībām ar ievadītiem rezultātiem nevar atgriezt statusu uz Plānotas vai Atceltas.',
                ]);
            }

            if ($validated['status'] === 'finished') {
                if ($competition->status !== 'active') {
                    throw ValidationException::withMessages([
                        'status' => 'Pabeigt var tikai aktīvas sacensības.',
                    ]);
                }

                if ((int) $validated['course_id'] !== (int) $competition->course_id) {
                    throw ValidationException::withMessages([
                        'course_id' => 'Pabeidzot sacensības, trasi mainīt nedrīkst.',
                    ]);
                }

                $competition->load('course');

                if (!$competition->course) {
                    throw ValidationException::withMessages([
                        'status' => 'Sacensības nevar pabeigt, jo nav piesaistīta trase.',
                    ]);
                }

                $holeIds = $competition->course->courseHoles()->pluck('id');
                $holeCount = $holeIds->count();
                $participantIds = $competition->users()->pluck('users.id');

                if ($holeCount === 0 || $participantIds->isEmpty()) {
                    throw ValidationException::withMessages([
                        'status' => 'Sacensības nevar pabeigt bez dalībniekiem un trases groziem.',
                    ]);
                }

                foreach ($participantIds as $userId) {
                    $holeResults = $competition->holeResults()
                        ->where('user_id', $userId)
                        ->whereIn('course_hole_id', $holeIds);

                    $playedCount = (clone $holeResults)
                        ->distinct()
                        ->count('course_hole_id');

                    if ($playedCount !== $holeCount) {
                        throw ValidationException::withMessages([
                            'status' => 'Visiem dalībniekiem jāpabeidz visi grozi pirms sacensību pabeigšanas.',
                        ]);
                    }

                    $totalScore = (int) (clone $holeResults)->sum('score');

                    $savedResult = $competition->results()
                        ->where('user_id', $userId)
                        ->first();

                    if (!$savedResult) {
                        throw ValidationException::withMessages([
                            'status' => 'Visiem dalībniekiem jābūt saglabātam gala rezultātam.',
                        ]);
                    }

                    if ((int) $savedResult->score !== $totalScore) {
                        throw ValidationException::withMessages([
                            'status' => 'Dalībnieka gala rezultāts nesakrīt ar grozu rezultātu summu.',
                        ]);
                    }
                }

                // APRĒĶINA VISU DALĪBNIEKU REITINGUS
                $this->ratingService->finalizeCompetitionRatings($competition);
            }

            $competition->update($validated);

            return redirect()->route('competitions.show', $competition)
                ->with('success', $validated['status'] === 'finished'
                    ? 'Sacensības pabeigtas! Visu dalībnieku reitingi aprēķināti.'
                    : 'Sacensības veiksmīgi atjauninātas!');
        }, 3);
    }

    public function destroy(Competition $competition)
    {
        $this->checkOwner($competition);

        return DB::transaction(function () use ($competition) {
            $competition = Competition::whereKey($competition->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($competition->status === 'finished') {
                return back()->with('error', 'Pabeigtas sacensības nevar dzēst.');
            }

            if ($competition->holeResults()->exists() || $competition->results()->exists()) {
                return back()->with('error', 'Sacensības nevar dzēst, jo tajās jau ir ievadīti rezultāti.');
            }

            $competition->delete();

            return redirect()->route('competitions.index')
                ->with('success', 'Sacensības izdzēstas!');
        }, 3);
    }

    private function validateCompetition(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'max_players' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['planned', 'active', 'finished', 'cancelled'])],
            'registration_starts_at' => ['required', 'date', 'before:registration_ends_at'],
            'registration_ends_at' => ['required', 'date', 'after:registration_starts_at'],
        ]);
    }

    private function checkOwner(Competition $competition): void
    {
        if ((int) $competition->user_id !== (int) auth()->id()) {
            abort(403, 'Tev nav tiesību veikt šo darbību.');
        }
    }
}
