<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeHoleResult extends Model
{
    protected $fillable = [
        'practice_round_id',
        'course_hole_id',
        'score',
    ];

    protected $casts = [
        'practice_round_id' => 'integer',
        'course_hole_id' => 'integer',
        'score' => 'integer',
    ];

    public function practiceRound()
    {
        return $this->belongsTo(PracticeRound::class);
    }

    public function courseHole()
    {
        return $this->belongsTo(CourseHole::class);
    }
}