<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CompetitionResult;
use App\Models\Course;

class Competition extends Model
{
    protected $fillable = [
        'name',
        'description',
        'date',
        'location',
        'max_players',
        'status',
        'user_id',
        'course_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('division')
            ->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(CompetitionResult::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}