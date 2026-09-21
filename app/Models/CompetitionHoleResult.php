<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionHoleResult extends Model
{
    protected $fillable = [
        'competition_id',
        'user_id',
        'course_hole_id',
        'throws',
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