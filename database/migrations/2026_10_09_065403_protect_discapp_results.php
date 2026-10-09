
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $relations = [
        'competition_results' => [
            ['competition_id', 'competitions'],
            ['user_id', 'users'],
        ],
        'competition_hole_results' => [
            ['competition_id', 'competitions'],
            ['user_id', 'users'],
            ['course_hole_id', 'course_holes'],
        ],
        'practice_hole_results' => [
            ['course_hole_id', 'course_holes'],
            ['practice_round_id', 'practice_rounds'],
        ],
    ];

    public function up(): void
    {
        foreach ($this->relations as $tableName => $relations) {
            Schema::table($tableName, function (Blueprint $table) use ($relations) {
                foreach ($relations as [$column]) {
                    $table->dropForeign([$column]);
                }
            });

            Schema::table($tableName, function (Blueprint $table) use ($relations) {
                foreach ($relations as [$column, $parent]) {
                    $table->foreign($column)
                        ->references('id')
                        ->on($parent)
                        ->restrictOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->relations as $tableName => $relations) {
            Schema::table($tableName, function (Blueprint $table) use ($relations) {
                foreach ($relations as [$column]) {
                    $table->dropForeign([$column]);
                }
            });

            Schema::table($tableName, function (Blueprint $table) use ($relations) {
                foreach ($relations as [$column, $parent]) {
                    $table->foreign($column)
                        ->references('id')
                        ->on($parent)
                        ->cascadeOnDelete();
                }
            });
        }
    }
};
