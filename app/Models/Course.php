<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CourseHole;

class Course extends Model
{
    protected $fillable = [
        'name',
        'holes',
        'rating_1000_score',
        'rating_per_throw',
    ];

    protected $casts = [
        'holes' => 'integer',
        'rating_1000_score' => 'decimal:2',
        'rating_per_throw' => 'decimal:2',
    ];

    /**
     * Visi konkrētās trases grozi.
     */
    public function courseHoles()
    {
        return $this->hasMany(CourseHole::class)
            ->orderBy('hole_number');
    }
}