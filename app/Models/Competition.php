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
    ];
}