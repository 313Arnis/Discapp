<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionHoleResult;
use App\Models\CompetitionResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CompetitionRegistrationController extends Controller
{
    // PIETEIKŠANĀS FORMA
    public function create(Competition $competition)
    {
        $error = $this->getJoinStatusError($competition);
        if ($error) {
            return redirect()->route('competitions.show', $competition)->with('error', $error);
        }

        $user = auth()->user();

        if ($competition->users()->where('users.id', $user->id)->exists()) {
            return redirect()->route('competitions.show', $competition)
                ->with('error', 'Tu jau esi pieteicies šīm sacensībām.');
        }

        if ($competition->max_players !== null &&
            $competition->users()->count() >= $competition->max_players) {
            return redirect()->route('competitions.show', $competition)
                ->with('error', 'Šīs sacensības jau ir pilnas.');
        }

        $rating = $user->rating;
        $divisions = $this->getAvailableDivisions($rating);

        return view('competitions.join', compact('competition', 'divisions', 'rating'));
    }

    // PIETEIKŠANĀS SAGLABĀŠANA
    public function store(Request $request, Competition $competition)
    {
        $user = $request->user();
        $divisions = $this->getAvailableDivisions($user->rating);

        $validated = $request->validate([
            'division' => ['required', Rule::in(array_keys($divisions))],
        ]);

        return DB::transaction(function () use ($competition, $user, $validated) {
            $competition = Competition::whereKey($competition->id)
                ->lockForUpdate()
                ->firstOrFail();

            $error = $this->getJoinStatusError($competition);
            if ($error) {
                return redirect()->route('competitions.show', $competition)
                    ->with('error', $error);
            }

            $alreadyJoined = $competition->users()
                ->where('users.id', $user->id)
                ->exists();

            if ($alreadyJoined) {
                return redirect()->route('competitions.show', $competition)
                    ->with('error', 'Tu jau esi pieteicies šīm sacensībām.');
            }

            $participantsCount = $competition->users()->count();

            if ($competition->max_players !== null &&
                $participantsCount >= $competition->max_players) {
                return redirect()->route('competitions.show', $competition)
                    ->with('error', 'Šīs sacensības jau ir pilnas.');
            }

            $competition->users()->attach($user->id, [
                'division' => $validated['division'],
            ]);

            return redirect()->route('competitions.show', $competition)
                ->with('success', 'Tu veiksmīgi pieteicies sacensībām!');
        }, 3);
    }

    // IZSTĀŠANĀS NO SACENSĪBĀM
    public function destroy(Competition $competition)
    {
        $user = auth()->user();

        return DB::transaction(function () use ($competition, $user) {
            $competition = Competition::whereKey($competition->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($user->role === 'admin') {
                return back()->with('error', 'Administratoram šī darbība nav pieejama.');
            }

            if ($competition->user_id === $user->id) {
                return back()->with('error', 'Sacensību veidotājs nevar izstāties no savām sacensībām.');
            }

            if ($competition->status !== 'planned') {
                return back()->with('error', 'Izstāties var tikai no plānotām sacensībām.');
            }

            $isParticipant = $competition->users()
                ->where('users.id', $user->id)
                ->exists();

            if (!$isParticipant) {
                return back()->with('error', 'Tu nepiedalies šajās sacensībās.');
            }

            $hasResults = CompetitionResult::where('competition_id', $competition->id)
                ->where('user_id', $user->id)
                ->exists();

            $hasHoleResults = CompetitionHoleResult::where('competition_id', $competition->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($hasResults || $hasHoleResults) {
                return back()->with('error', 'Izstāties nevar, jo sacensībās jau ir saglabāti tavi rezultāti.');
            }

            $competition->users()->detach($user->id);

            return redirect()->route('competitions.show', $competition)
                ->with('success', 'Tu vairs nepiedalies šajās sacensībās.');
        }, 3);
    }

    // PIEEJAMĀS DIVĪZIJAS
    private function getAvailableDivisions($rating): array
    {
        $divisions = ['MA1' => 'MA1 - Mixed Amateur 1'];

        if ($rating !== null && $rating <= 934) {
            $divisions['MA2'] = 'MA2 - Mixed Amateur 2';
        }

        if ($rating !== null && $rating <= 899) {
            $divisions['MA3'] = 'MA3 - Mixed Amateur 3';
        }

        if ($rating !== null && $rating <= 849) {
            $divisions['MA4'] = 'MA4 - Mixed Amateur 4';
        }

        return $divisions;
    }

    // PIETEIKŠANĀS STATUSA PĀRBAUDE
    private function getJoinStatusError(Competition $competition): ?string
    {
        if (!auth()->check()) {
            return 'Lai pieteiktos sacensībām, nepieciešams pieslēgties.';
        }

        if (auth()->user()->role === 'admin') {
            return 'Administrators nevar pieteikties sacensībām kā spēlētājs.';
        }

        if ($competition->status === 'finished') {
            return 'Pabeigtām sacensībām vairs nevar pieteikties.';
        }

        if ($competition->status === 'cancelled') {
            return 'Atceltām sacensībām nevar pieteikties.';
        }

        if (!$competition->isRegistrationOpen()) {
            return 'Pieteikšanās šīm sacensībām pašlaik nav atvērta.';
        }

        return null;
    }
}
