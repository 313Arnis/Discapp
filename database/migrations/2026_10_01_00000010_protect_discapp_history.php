
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $relations = [
        'practice_rounds' => [
            ['user_id', 'users'],
            ['course_id', 'courses'],
        ],
        'competitions' => [
            ['user_id', 'users'],
            ['course_id', 'courses'],
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

            Schema::table($tableName, function (Blueprint $table) use ($relations, $tableName) {
                foreach ($relations as [$column, $parent]) {
                    $foreign = $table->foreign($column)
                        ->references('id')
                        ->on($parent);

                    if ($tableName === 'competitions' && $column === 'course_id') {
                        $foreign->nullOnDelete();
                    } else {
                        $foreign->cascadeOnDelete();
                    }
                }
            });
        }
    }
};
