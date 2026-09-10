<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Competition extends Model
{
    protected $fillable = [
        'name',
        'description',
        'date',
        'location',
        'max_players',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('division')
            ->withTimestamps();
    }
}