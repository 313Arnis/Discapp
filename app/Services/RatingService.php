<?php
namespace App\Services;

use App\Models\Competition;
use App\Models\CompetitionResult;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RatingService
{
    // REZERVES FORMULA, JA NAV PIETIEKAMI DAUDZ REITĒTU SPĒLĒTĀJU
    public function calculateRoundRating(Course $course, int $totalScore): int
    {
        $coursePar = (int) $course->courseHoles()->sum('par');
        $rating1000Score = $coursePar - 5;

        return (int) round(1000 + (($rating1000Score - $totalScore) * 10));
    }

    // APRĒĶINA REITINGUS PĒC SACENSĪBU PABEIGŠANAS
    public function finalizeCompetitionRatings(Competition $competition): void
    {
        if (DB::transactionLevel() === 0) {
            throw new \LogicException('Reitingi jāaprēķina datubāzes transakcijā.');
        }

        if ($competition->status !== 'active') {
            throw ValidationException::withMessages([
                'status' => 'Reitingus var aprēķināt tikai aktīvām sacensībām.',
            ]);
        }

        $competition->load('course');

        if (!$competition->course) {
            throw ValidationException::withMessages([
                'status' => 'Sacensībām nav piesaistīta trase.',
            ]);
        }

        $participants = $competition->users()
            ->orderBy('users.id')
            ->lockForUpdate()
            ->get();

        if ($participants->isEmpty()) {
            throw ValidationException::withMessages([
                'status' => 'Sacensībām nav reģistrētu dalībnieku.',
            ]);
        }

        $results = CompetitionResult::where('competition_id', $competition->id)
            ->lockForUpdate()
            ->get()
            ->keyBy('user_id');

        $anchors = [];

        foreach ($participants as $participant) {
            $result = $results->get($participant->id);

            if (!$result) {
                throw ValidationException::withMessages([
                    'status' => 'Ne visiem dalībniekiem ir saglabāts gala rezultāts.',
                ]);
            }

            if ((int) $result->score < 1) {
                throw ValidationException::withMessages([
                    'status' => 'Dalībnieka gala rezultāts nav derīgs.',
                ]);
            }

            if ($participant->rating !== null && (int) $participant->rating > 0) {
                $anchors[] = [
                    'score' => (int) $result->score,
                    'rating' => (int) $participant->rating,
                ];
            }
        }

        $ratingPerThrow = 10;

        if (count($anchors) >= 3) {
            $rating1000Score = collect($anchors)->avg(
                fn ($anchor) => $anchor['score']
                    + (($anchor['rating'] - 1000) / $ratingPerThrow)
            );
        } else {
            $rating1000Score = (int) $competition->course
                ->courseHoles()
                ->sum('par') - 5;
        }

        // VISPIRMS SAGLABĀ VISU DALĪBNIEKU RAUNDA REITINGUS
        foreach ($participants as $participant) {
            $result = $results->get($participant->id);

            $result->round_rating = (int) round(
                1000 + (($rating1000Score - (int) $result->score) * $ratingPerThrow)
            );

            $result->save();
        }

        // TIKAI PĒC TAM ATJAUNINA KOPĒJOS REITINGUS
        foreach ($participants as $participant) {
            $this->updateUserRating($participant);
        }
    }

    // APRĒĶINA LIETOTĀJA KOPĒJO REITINGU
    public function updateUserRating(User $user): void
    {
        $ratings = CompetitionResult::where('user_id', $user->id)
            ->whereNotNull('round_rating')
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('round_rating')
            ->map(fn ($rating) => (int) $rating)
            ->values();

        if ($ratings->isEmpty()) {
            return;
        }

        // LĪDZ 8 RAUNDIEM - VISU REITINGU VIDĒJAIS
        if ($ratings->count() < 9) {
            $user->rating = (int) round($ratings->avg());
            $user->save();
            return;
        }

        // NO 9 RAUNDIEM - JAUNĀKAJIEM 25% DUBULTS SVARS
        $recentCount = max(1, (int) ceil($ratings->count() * 0.25));
        $recentRatings = $ratings->take(-$recentCount);

        $weightedSum = $ratings->sum() + $recentRatings->sum();
        $weightedCount = $ratings->count() + $recentRatings->count();

        $user->rating = (int) round($weightedSum / $weightedCount);
        $user->save();
    }
}
