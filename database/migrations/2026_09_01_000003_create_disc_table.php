<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('type');

            $table->decimal(
                'speed',
                3,
                1
            )->nullable();

            $table->decimal(
                'glide',
                3,
                1
            )->nullable();

            $table->decimal(
                'turn',
                3,
                1
            )->nullable();

            $table->decimal(
                'fade',
                3,
                1
            )->nullable();

            $table->string('image')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discs');
    }
};