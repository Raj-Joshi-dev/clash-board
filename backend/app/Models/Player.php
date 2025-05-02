<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'tag',
        'town_hall_level',
        'xp',
        'trophies',
        'best_trophies',
        'war_stars',
        'clan_tag',
        'clan_name',
        'role',
        'heroes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'heroes' => 'array',
    ];
}
