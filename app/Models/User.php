<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\CompetitionResult;

use App\Models\Disc;
use App\Models\Competition;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'rating'
])]

#[Hidden([
    'password',
    'remember_token'
])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'rating' => 'integer',
        ];
    }


    /**
     * Lietotāja diski
     */
    public function discs()
    {
        return $this->hasMany(Disc::class);
    }


    /**
     * Lietotāja sacensības
     */
    public function createdCompetitions()
    {
        return $this->hasMany(Competition::class, 'user_id');
    }
    
    public function competitions()
    {  // Pivot: Jo divīzija pieder nevis lietotājam vai sacensībām atsevišķi, bet konkrētā lietotāja dalībai konkrētajās sacensībās.
        return $this->belongsToMany(Competition::class)
            ->withPivot('division')
            ->withTimestamps();
    }
    
    public function results()
    {
        return $this->hasMany(CompetitionResult::class);
    }
}