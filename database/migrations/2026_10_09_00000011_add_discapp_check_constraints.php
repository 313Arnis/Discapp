
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $checks = [
        'course_holes' => [
            'chk_course_holes_par' => '`par` >= 1',
            'chk_course_holes_number' => '`hole_number` >= 1',
        ],
        'competitions' => [
            'chk_competitions_max_players' => '`max_players` IS NULL OR `max_players` >= 1',
            'chk_competitions_status' => "`status` IN ('planned', 'active', 'finished', 'cancelled')",
            'chk_competitions_dates' => '`registration_starts_at` IS NULL OR `registration_ends_at` IS NULL OR `registration_ends_at` >= `registration_starts_at`',
        ],
        'competition_hole_results' => [
            'chk_competition_hole_score' => '`score` >= 1',
        ],
        'practice_hole_results' => [
            'chk_practice_hole_score' => '`score` >= 1',
        ],
        'competition_results' => [
            'chk_competition_total_score' => '`score` >= 1',
        ],
        'practice_rounds' => [
            'chk_practice_total_score' => '`total_score` IS NULL OR `total_score` >= 1',
        ],
        'courses' => [
            'chk_courses_holes' => '`holes` >= 1',
            'chk_courses_rating_throw' => '`rating_per_throw` >= 0',
        ],
    ];

    public function up(): void
    {
        foreach ($this->checks as $table => $constraints) {
            foreach ($constraints as $name => $condition) {
                DB::statement(
                    "ALTER TABLE `$table` ADD CONSTRAINT `$name` CHECK ($condition)"
                );
            }
        }
    }

    public function down(): void
    {
        foreach ($this->checks as $table => $constraints) {
            foreach ($constraints as $name => $condition) {
                DB::statement(
                    "ALTER TABLE `$table` DROP CHECK `$name`"
                );
            }
        }
    }
};
