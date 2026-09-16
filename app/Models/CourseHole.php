<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseHole extends Model
{
    protected $fillable = [
        'course_id',
        'hole_number',
        'par',
    ];

    protected $casts = [
        'course_id' => 'integer',
        'hole_number' => 'integer',
        'par' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}