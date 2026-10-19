<?php

namespace App\Console\Commands;

use App\Models\Competition;
use App\Services\RatingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateCompetitionStatuses extends Command
{
    protected $signature = 'competitions:update-statuses';

    protected $description = 'Automātiski atjaunina sacensību statusus';

    public function handle(RatingService $ratingService): int
    {
        $now = now();

        // SĀK SACENSĪBAS
        $activated = Competition::query()
            ->where('status', 'planned')
            ->whereNotNull('starts_at')
            ->where('starts_at', '<=', $now)
            ->where(function ($query) use ($now) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', $now);
            })
            ->update(['status' => 'active']);

        $this->info("Aktivizētas sacensības: {$activated}");

        // PABEIDZ SACENSĪBAS
        $competitions = Competition::query()
            ->whereIn('status', ['planned', 'active'])
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', $now)
            ->get();

        foreach ($competitions as $competition) {
            try {
                DB::transaction(function () use (
                    $competition,
                    $ratingService
                ) {
                    $competition = Competition::query()
                        ->whereKey($competition->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if (!in_array(
                        $competition->status,
                        ['planned', 'active'],
                        true
                    )) {
                        return;
                    }

                    if (
                        !$competition->ends_at ||
                        now()->lt($competition->ends_at)
                    ) {
                        return;
                    }

                    $holeIds = $competition->course
                        ?->courseHoles()
                        ->pluck('id');

                    if (!$holeIds || $holeIds->isEmpty()) {
                        $this->warn(
                            "Sacensībām #{$competition->id} nav trases grozu."
                        );
                        return;
                    }

                    $participantIds = $competition->users()
                        ->pluck('users.id');

                    if ($participantIds->isEmpty()) {
                        $this->warn(
                            "Sacensībām #{$competition->id} nav dalībnieku."
                        );
                        return;
                    }

                    foreach ($participantIds as $userId) {
                        $holeResults = $competition->holeResults()
                            ->where('user_id', $userId)
                            ->whereIn('course_hole_id', $holeIds);

                        $playedCount = (clone $holeResults)
                            ->distinct()
                            ->count('course_hole_id');

                        if ($playedCount !== $holeIds->count()) {
                            return;
                        }

                        $totalScore = (int) (clone $holeResults)
                            ->sum('score');

                        $savedResult = $competition->results()
                            ->where('user_id', $userId)
                            ->first();

                        if (
                            !$savedResult ||
                            (int) $savedResult->score !== $totalScore
                        ) {
                            return;
                        }
                    }

                    // Visi rezultāti ir saglabāti.
                    // Aprēķinām reitingus pirms statusa maiņas.
                    $ratingService
                        ->finalizeCompetitionRatings($competition);

                    $competition->update([
                        'status' => 'finished',
                    ]);

                    $this->info(
                        "Pabeigtas sacensības #{$competition->id}"
                    );
                }, 3);
            } catch (Throwable $exception) {
                report($exception);

                $this->error(
                    "Neizdevās pabeigt sacensības #{$competition->id}: "
                    . $exception->getMessage()
                );
            }
        }

        return self::SUCCESS;
    }
}
