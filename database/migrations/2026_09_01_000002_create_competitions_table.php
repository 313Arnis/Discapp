<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'competitions',
            function (Blueprint $table) {
                $table->id();

                $table->string('name');

                $table->text('description')
                    ->nullable();

                $table->date('date');

                $table->unsignedInteger(
                    'max_players'
                )->nullable();

                $table->string('status')
                    ->default('planned');

                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->cascadeOnDelete();

                $table->foreignId('course_id')
                    ->nullable()
                    ->constrained('courses')
                    ->nullOnDelete();

                $table->dateTime(
                    'registration_starts_at'
                )->nullable();

                $table->dateTime(
                    'registration_ends_at'
                )->nullable();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'competitions'
        );
    }
};