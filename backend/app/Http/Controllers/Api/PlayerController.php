<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Services\ClashApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlayerController extends Controller
{
    /**
     * The Clash API service instance.
     */
    protected ClashApiService $clashApiService;

    /**
     * Create a new controller instance.
     */
    public function __construct(ClashApiService $clashApiService)
    {
        $this->clashApiService = $clashApiService;
    }

    /**
     * Display a listing of players.
     */
    public function index(): JsonResponse
    {
        $players = Player::all();
        return response()->json([
            'success' => true,
            'data' => $players
        ]);
    }

    /**
     * Store a newly created player.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tag' => 'required|string|max:255|unique:players',
            'town_hall_level' => 'required|integer|min:1|max:15',
            'xp' => 'required|integer|min:1',
            'trophies' => 'integer|min:0',
            'best_trophies' => 'integer|min:0',
            'war_stars' => 'integer|min:0',
            'clan_tag' => 'nullable|string|max:255',
            'clan_name' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'heroes' => 'nullable|array',
        ]);

        $player = Player::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Player created successfully',
            'data' => $player
        ], 201);
    }

    /**
     * Display the specified player by tag.
     */
    public function show(string $tag): JsonResponse
    {
        // In Clash of Clans, player tags often start with #, but in URLs they might be URL encoded
        // Remove # if it's part of the tag
        $tag = ltrim($tag, '#');

        $player = Player::where('tag', $tag)->first();

        if (!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Player not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $player
        ]);
    }

    /**
     * Update the specified player.
     */
    public function update(Request $request, string $tag): JsonResponse
    {
        $tag = ltrim($tag, '#');

        $player = Player::where('tag', $tag)->first();

        if (!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Player not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'town_hall_level' => 'integer|min:1|max:15',
            'xp' => 'integer|min:1',
            'trophies' => 'integer|min:0',
            'best_trophies' => 'integer|min:0',
            'war_stars' => 'integer|min:0',
            'clan_tag' => 'nullable|string|max:255',
            'clan_name' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'heroes' => 'nullable|array',
        ]);

        $player->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Player updated successfully',
            'data' => $player
        ]);
    }

    /**
     * Remove the specified player.
     */
    public function destroy(string $tag): JsonResponse
    {
        $tag = ltrim($tag, '#');

        $player = Player::where('tag', $tag)->first();

        if (!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Player not found'
            ], 404);
        }

        $player->delete();

        return response()->json([
            'success' => true,
            'message' => 'Player deleted successfully'
        ]);
    }

    /**
     * Create a dummy player (for testing and demo purposes).
     */
    public function createDummy(): JsonResponse
    {
        $dummyPlayer = [
            'name' => 'Chief ' . rand(1000, 9999),
            'tag' => 'DUMMY' . rand(100000, 999999),
            'town_hall_level' => rand(8, 15),
            'xp' => rand(50, 300),
            'trophies' => rand(1000, 6000),
            'best_trophies' => rand(1500, 7000),
            'war_stars' => rand(100, 2000),
            'clan_tag' => 'CLAN' . rand(10000, 99999),
            'clan_name' => 'Dummy Clan',
            'role' => ['Member', 'Elder', 'Co-leader', 'Leader'][rand(0, 3)],
            'heroes' => [
                ['name' => 'Barbarian King', 'level' => rand(1, 90), 'maxLevel' => 90],
                ['name' => 'Archer Queen', 'level' => rand(1, 90), 'maxLevel' => 90],
                ['name' => 'Grand Warden', 'level' => rand(1, 65), 'maxLevel' => 65],
            ],
        ];

        $player = Player::create($dummyPlayer);

        return response()->json([
            'success' => true,
            'message' => 'Dummy player created successfully',
            'data' => $player
        ], 201);
    }

    /**
     * Fetch a player from the Clash of Clans API and store/update in our database.
     */
    public function fetchFromApi(string $tag): JsonResponse
    {
        $tag = ltrim($tag, '#');

        // Get player data from the Clash API
        $playerData = $this->clashApiService->getPlayer($tag);

        if (!$playerData) {
            return response()->json([
                'success' => false,
                'message' => 'Player not found in Clash of Clans API'
            ], 404);
        }

        // Check if player already exists in our database
        $player = Player::where('tag', $tag)->first();

        // Prepare player data
        $playerAttributes = [
            'name' => $playerData['name'],
            'tag' => ltrim($playerData['tag'], '#'),
            'town_hall_level' => $playerData['townHallLevel'],
            'xp' => $playerData['expLevel'],
            'trophies' => $playerData['trophies'],
            'best_trophies' => $playerData['bestTrophies'],
            'war_stars' => $playerData['warStars'],
        ];

        // Add clan information if available
        if (isset($playerData['clan'])) {
            $playerAttributes['clan_tag'] = ltrim($playerData['clan']['tag'], '#');
            $playerAttributes['clan_name'] = $playerData['clan']['name'];
            $playerAttributes['role'] = $playerData['role'] ?? 'Member';
        }

        // Add heroes if available
        if (isset($playerData['heroes']) && is_array($playerData['heroes'])) {
            $heroes = [];
            foreach ($playerData['heroes'] as $hero) {
                $heroes[] = [
                    'name' => $hero['name'],
                    'level' => $hero['level'],
                    'maxLevel' => $hero['maxLevel'],
                ];
            }
            $playerAttributes['heroes'] = $heroes;
        }

        // Create or update the player
        if ($player) {
            $player->update($playerAttributes);
            $message = 'Player updated successfully from Clash of Clans API';
        } else {
            $player = Player::create($playerAttributes);
            $message = 'Player fetched successfully from Clash of Clans API';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $player
        ], $player->wasRecentlyCreated ? 201 : 200);
    }
}
