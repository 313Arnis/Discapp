<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discs', function (Blueprint $table) {
            $table->decimal('speed', 3, 1)->nullable();
            $table->decimal('glide', 3, 1)->nullable();
            $table->decimal('turn', 3, 1)->nullable();
            $table->decimal('fade', 3, 1)->nullable();
            $table->string('image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('discs', function (Blueprint $table) {
            $table->dropColumn([
                'speed',
                'glide',
                'turn',
                'fade',
                'image',
            ]);
        });
    }
};
