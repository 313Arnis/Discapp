<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $fillable = [
        'name',
        'description',
        'date',
        'starts_at',
        'ends_at',
        'registration_starts_at',
        'registration_ends_at',
        'location',
        'max_players',
        'status',
        'user_id',
        'course_id',
    ];

    protected $casts = [
        'date' => 'date',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'registration_starts_at' => 'datetime',
        'registration_ends_at' => 'datetime',
        'max_players' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function holeResults()
    {
        return $this->hasMany(CompetitionHoleResult::class);
    }

    public function isRegistrationOpen(): bool
    {
        return $this->status === 'planned'
            && $this->registration_starts_at !== null
            && $this->registration_ends_at !== null
            && now()->betweenIncluded(
                $this->registration_starts_at,
                $this->registration_ends_at
            );
    }

    public function canEnterResults(): bool
    {
        return $this->status === 'active'
            && $this->starts_at !== null
            && $this->ends_at !== null
            && now()->greaterThanOrEqualTo($this->starts_at)
            && now()->lessThan($this->ends_at);
    }
}
