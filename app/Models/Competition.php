<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'registration_starts_at',
        'registration_ends_at',
    ];

    protected $casts = [
        'date' => 'date',
        'max_players' => 'integer',
        'registration_starts_at' => 'datetime',
        'registration_ends_at' => 'datetime',
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
        if (!in_array($this->status, ['planned', 'active'], true)) {
            return false;
        }

        if (
            $this->registration_starts_at &&
            now()->lt($this->registration_starts_at)
        ) {
            return false;
        }

        if (
            $this->registration_ends_at &&
            now()->gt($this->registration_ends_at)
        ) {
            return false;
        }

        return true;
    }
}