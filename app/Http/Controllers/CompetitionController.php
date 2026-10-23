<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Course;
use App\Services\RatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CompetitionController extends Controller
{
    public function __construct(
        private RatingService $ratingService
    ) {}

    // SACENSĪBU SARAKSTS
    public function index()
    {
        $competitions = Competition::with([
            'course',
            'creator',
            'users'
        ])
            ->latest('date')
            ->get();

        return view('competitions.index', compact('competitions'));
    }

    // SACENSĪBU IZVEIDES FORMA
    public function create()
    {
        $courses = Course::orderBy('name')->get();

        return view('competitions.create', compact('courses'));
    }

    // SACENSĪBU IZVEIDE
    public function store(Request $request)
    {
        $validated = $this->validateCompetition($request, true);

        // Jaunas sacensības vienmēr ir plānotas.
        // Statusu vēlāk automātiski maina Laravel plānotājs.
        $validated['status'] = 'planned';

        Competition::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('competitions.index')
            ->with('success', 'Sacensības veiksmīgi izveidotas!');
    }

    // SACENSĪBU APSKATE
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

        return view(
            'competitions.show',
            compact('competition', 'players')
        );
    }

    // SACENSĪBU REDIĢĒŠANAS FORMA
    public function edit(Competition $competition)
    {
        $this->checkOwner($competition);

        if ($competition->status === 'finished') {
            return redirect()
                ->route('competitions.show', $competition)
                ->with(
                    'error',
                    'Pabeigtas sacensības vairs nevar rediģēt.'
                );
        }

        $courses = Course::orderBy('name')->get();

        return view(
            'competitions.edit',
            compact('competition', 'courses')
        );
    }

    // SACENSĪBU ATJAUNINĀŠANA
    public function update(
        Request $request,
        Competition $competition
    ) {
        $this->checkOwner($competition);

        $validated = $this->validateCompetition($request);

        return DB::transaction(function () use (
            $competition,
            $validated
        ) {
            $competition = Competition::whereKey($competition->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($competition->status === 'finished') {
                return redirect()
                    ->route('competitions.show', $competition)
                    ->with(
                        'error',
                        'Pabeigtas sacensības vairs nevar rediģēt.'
                    );
            }

            // Saglabājam pašreizējo statusu.
            // Lietotājs to nevar mainīt manuāli.
            $validated['status'] = $competition->status;

            $hasResults = $competition->holeResults()->exists()
                || $competition->results()->exists();

            // Trasi nedrīkst mainīt, ja jau ievadīti rezultāti.
            if (
                $hasResults &&
                (int) $validated['course_id'] !==
                (int) $competition->course_id
            ) {
                throw ValidationException::withMessages([
                    'course_id' =>
                        'Trasi nevar mainīt, jo sacensībās jau ir ievadīti rezultāti.',
                ]);
            }

            $participantCount = $competition->users()->count();

            // Dalībnieku limits nedrīkst būt mazāks
            // par jau pieteikto dalībnieku skaitu.
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

            // Neļaujam pārvietot sākuma laiku uz nākotni,
            // ja sacensības jau ir aktīvas.
            if (
                $competition->status === 'active' &&
                \Carbon\Carbon::parse($validated['starts_at'])->isFuture()
            ) {
                throw ValidationException::withMessages([
                    'starts_at' =>
                        'Aktīvām sacensībām sākuma laiku nevar pārcelt uz nākotni.',
                ]);
            }

            $competition->update($validated);

            return redirect()
                ->route('competitions.show', $competition)
                ->with(
                    'success',
                    'Sacensības veiksmīgi atjauninātas!'
                );
        }, 3);
    }

    // SACENSĪBU DZĒŠANA
    public function destroy(Competition $competition)
    {
        $this->checkOwner($competition);

        return DB::transaction(function () use ($competition) {
            $competition = Competition::whereKey($competition->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($competition->status === 'finished') {
                return back()->with(
                    'error',
                    'Pabeigtas sacensības nevar dzēst.'
                );
            }

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
        }, 3);
    }

    // SACENSĪBU DATU VALIDĀCIJA
    private function validateCompetition(Request $request, bool $creating = false): array
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date_format:Y-m-d'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'max_players' => ['nullable', 'integer', 'min:1'],
            'status' => ['sometimes', Rule::in(['planned', 'active', 'finished', 'cancelled'])],
            'registration_starts_at' => ['required', 'date'],
            'registration_ends_at' => ['required', 'date', 'after:registration_starts_at'],
        ], [
            'date.date_format' => 'Sacensību datumam jābūt formātā GGGG-MM-DD.',
            'ends_at.after' => 'Sacensību beigu laikam jābūt pēc sākuma laika.',
            'registration_ends_at.after' => 'Reģistrācijas beigām jābūt pēc reģistrācijas sākuma.',
        ]);

        $validator->after(function ($validator) use ($creating) {
            $data = $validator->getData();
            $fields = ['date', 'starts_at', 'ends_at', 'registration_starts_at', 'registration_ends_at'];
            foreach ($fields as $field) {
                if ($validator->errors()->has($field) || empty($data[$field])) {
                    return;
                }
            }

            try {
                $date = Carbon::createFromFormat('!Y-m-d', $data['date']);
                $start = Carbon::parse($data['starts_at']);
                $end = Carbon::parse($data['ends_at']);
                $regStart = Carbon::parse($data['registration_starts_at']);
                $regEnd = Carbon::parse($data['registration_ends_at']);
            } catch (\Exception $e) {
                return;
            }

            if (!$date->isSameDay($start)) {
                $validator->errors()->add('date', 'Sacensību datumam jāsakrīt ar sākuma laika datumu.');
            }
            if ($regEnd->greaterThan($start)) {
                $validator->errors()->add('registration_ends_at', 'Reģistrācijai jābeidzas ne vēlāk kā sacensību sākumā.');
            }
            if ($regStart->greaterThanOrEqualTo($start)) {
                $validator->errors()->add('registration_starts_at', 'Reģistrācijai jāsākas pirms sacensībām.');
            }
            if ($creating && $start->lessThanOrEqualTo(now())) {
                $validator->errors()->add('starts_at', 'Jaunām sacensībām sākuma laikam jābūt nākotnē.');
            }
        });

        return $validator->validate();
    }

    // PĀRBAUDA SACENSĪBU ĪPAŠNIEKU
    private function checkOwner(Competition $competition): void
    {
        if (
            (int) $competition->user_id !==
            (int) auth()->id()
        ) {
            abort(
                403,
                'Tev nav tiesību veikt šo darbību.'
            );
        }
    }
}
