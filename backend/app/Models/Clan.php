<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'tag',
        'name',
        'type',
        'description',
        'location_name',
        'is_family_friendly',
        'badge_urls',
        'clan_level',
        'clan_points',
        'clan_builder_base_points',
        'clan_capital_points',
        'capital_league_name',
        'required_trophies',
        'war_frequency',
        'war_win_streak',
        'war_wins',
        'war_ties',
        'war_losses',
        'is_war_log_public',
        'war_league_name',
        'members',
        'member_list',
        'clan_capital',
        'chat_language',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'badge_urls' => 'array',
        'member_list' => 'array',
        'clan_capital' => 'array',
        'is_family_friendly' => 'boolean',
        'is_war_log_public' => 'boolean',
    ];
}
