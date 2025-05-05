<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Player;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Add dummy player data
        Player::create([
            'name' => 'Clasher',
            'tag' => '123',
            'town_hall_level' => 17,
            'xp' => 261,
            'trophies' => 5639,
            'best_trophies' => 6008,
            'war_stars' => 3505,
            'clan_tag' => '9PLULVPC',
            'clan_name' => 'HOUSE OF POWER',
            'role' => 'coLeader',
            'heroes' => [
                [
                    'name' => 'Barbarian King',
                    'level' => 100,
                    'maxLevel' => 100
                ],
                [
                    'name' => 'Archer Queen',
                    'level' => 100,
                    'maxLevel' => 100
                ],
                [
                    'name' => 'Grand Warden',
                    'level' => 75,
                    'maxLevel' => 75
                ],
                [
                    'name' => 'Battle Machine',
                    'level' => 35,
                    'maxLevel' => 35
                ],
                [
                    'name' => 'Royal Champion',
                    'level' => 50,
                    'maxLevel' => 50
                ],
                [
                    'name' => 'Battle Copter',
                    'level' => 35,
                    'maxLevel' => 35
                ],
                [
                    'name' => 'Minion Prince',
                    'level' => 90,
                    'maxLevel' => 90
                ]
            ],
        ]);

        // Add dummy clan data with proper PostgreSQL boolean values using DB::raw
        DB::table('clans')->insert([
            'tag' => '12345',
            'name' => 'Test Clan',
            'type' => 'inviteOnly',
            'description' => 'This is a test clan for development purposes',
            'location_name' => 'International',
            'is_family_friendly' => DB::raw('false'), // Using DB::raw to ensure PostgreSQL gets a proper boolean
            'badge_urls' => json_encode([
                'small' => 'https://api-assets.clashofclans.com/badges/70/test_small.png',
                'medium' => 'https://api-assets.clashofclans.com/badges/200/test_medium.png',
                'large' => 'https://api-assets.clashofclans.com/badges/512/test_large.png'
            ]),
            'clan_level' => 10,
            'clan_points' => 25000,
            'clan_builder_base_points' => 15000,
            'clan_capital_points' => 12000,
            'capital_league_name' => 'Capital League I',
            'required_trophies' => 2000,
            'war_frequency' => 'always',
            'war_win_streak' => 5,
            'war_wins' => 150,
            'war_ties' => 10,
            'war_losses' => 50,
            'is_war_log_public' => DB::raw('false'), // Using DB::raw to ensure PostgreSQL gets a proper boolean
            'war_league_name' => 'Master League I',
            'members' => 35,
            'member_list' => json_encode([
                [
                    'tag' => 'QYPYRQV0',
                    'name' => 'Raj',
                    'role' => 'coLeader',
                    'expLevel' => 261,
                    'trophies' => 5639,
                    'builderBaseTrophies' => 4500,
                    'clanRank' => 1,
                    'previousClanRank' => 1,
                    'donations' => 1500,
                    'donationsReceived' => 1200
                ],
                [
                    'tag' => '98765',
                    'name' => 'TestPlayer',
                    'role' => 'member',
                    'expLevel' => 150,
                    'trophies' => 3200,
                    'builderBaseTrophies' => 2800,
                    'clanRank' => 2,
                    'previousClanRank' => 3,
                    'donations' => 800,
                    'donationsReceived' => 1000
                ]
            ]),
            'clan_capital' => json_encode([
                'capitalHallLevel' => 8,
                'districts' => [
                    [
                        'name' => 'Capital Peak',
                        'id' => 0,
                        'districtHallLevel' => 8
                    ],
                    [
                        'name' => 'Barbarian Camp',
                        'id' => 1,
                        'districtHallLevel' => 7
                    ],
                    [
                        'name' => 'Wizard Valley',
                        'id' => 2,
                        'districtHallLevel' => 7
                    ]
                ]
            ]),
            'chat_language' => 'English',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
