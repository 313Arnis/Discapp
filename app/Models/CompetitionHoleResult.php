<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionHoleResult extends Model
{
    protected $fillable = [
        'competition_id',
        'user_id',
        'course_hole_id',
        'score',
    ];

    protected $casts = [
        'competition_id' => 'integer',
        'user_id' => 'integer',
        'course_hole_id' => 'integer',
        'score' => 'integer',
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courseHole()
    {
        return $this->belongsTo(CourseHole::class);
    }
}