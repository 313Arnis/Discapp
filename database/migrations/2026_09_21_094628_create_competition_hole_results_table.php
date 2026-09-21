<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_hole_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('competition_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('course_hole_id')
                ->constrained()
                ->onDelete('cascade');

            $table->unsignedTinyInteger('throws');

            $table->timestamps();

            // Viens rezultāts katram spēlētājam uz katru grozu konkrētajās sacensībās
            $table->unique(
                ['competition_id', 'user_id', 'course_hole_id'],
                'chr_comp_user_hole_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_hole_results');
    }
};