<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_holes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->unsignedInteger('hole_number');

            $table->unsignedTinyInteger('par');

            $table->timestamps();

            $table->unique([
                'course_id',
                'hole_number'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_holes');
    }
};