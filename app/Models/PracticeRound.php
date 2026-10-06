<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeRound extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'total_score',
        'relative_to_par',
        'status',
        'finished_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'course_id' => 'integer',
        'total_score' => 'integer',
        'relative_to_par' => 'integer',
        'finished_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function holeResults()
    {
        return $this->hasMany(PracticeHoleResult::class)
            ->orderBy('course_hole_id');
    }
}