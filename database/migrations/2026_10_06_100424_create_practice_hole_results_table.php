<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_hole_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('practice_round_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('course_hole_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('score');

            $table->timestamps();

            $table->unique([
                'practice_round_id',
                'course_hole_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_hole_results');
    }
};