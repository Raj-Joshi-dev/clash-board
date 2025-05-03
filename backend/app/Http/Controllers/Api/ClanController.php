<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clan;
use App\Services\ClashApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ClanController extends Controller
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
     * Display a listing of clans.
     */
    public function index(): JsonResponse
    {
        $clans = Clan::all();
        return response()->json([
            'success' => true,
            'data' => $clans
        ]);
    }

    /**
     * Store a newly created clan in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tag' => 'required|string|max:255|unique:clans',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location_name' => 'nullable|string|max:255',
            'is_family_friendly' => 'boolean',
            'badge_urls' => 'required|array',
            'clan_level' => 'required|integer|min:1',
            'clan_points' => 'integer|min:0',
            'clan_builder_base_points' => 'integer|min:0',
            'clan_capital_points' => 'integer|min:0',
            'capital_league_name' => 'nullable|string|max:255',
            'required_trophies' => 'integer|min:0',
            'war_frequency' => 'nullable|string|max:255',
            'war_win_streak' => 'integer|min:0',
            'war_wins' => 'integer|min:0',
            'war_ties' => 'integer|min:0',
            'war_losses' => 'integer|min:0',
            'is_war_log_public' => 'boolean',
            'war_league_name' => 'nullable|string|max:255',
            'members' => 'integer|min:0',
            'member_list' => 'nullable|array',
            'clan_capital' => 'nullable|array',
            'chat_language' => 'nullable|string|max:255',
        ]);

        $clan = Clan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Clan created successfully',
            'data' => $clan
        ], 201);
    }

    /**
     * Display the specified clan by tag.
     */
    public function show(string $tag): JsonResponse
    {
        // Remove # if it's part of the tag
        $tag = ltrim($tag, '#');

        $clan = Clan::where('tag', $tag)->first();

        if (!$clan) {
            return response()->json([
                'success' => false,
                'message' => 'Clan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $clan
        ]);
    }

    /**
     * Update the specified clan in storage.
     */
    public function update(Request $request, string $tag): JsonResponse
    {
        $tag = ltrim($tag, '#');

        $clan = Clan::where('tag', $tag)->first();

        if (!$clan) {
            return response()->json([
                'success' => false,
                'message' => 'Clan not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'type' => 'string|max:255',
            'description' => 'nullable|string',
            'location_name' => 'nullable|string|max:255',
            'is_family_friendly' => 'boolean',
            'badge_urls' => 'array',
            'clan_level' => 'integer|min:1',
            'clan_points' => 'integer|min:0',
            'clan_builder_base_points' => 'integer|min:0',
            'clan_capital_points' => 'integer|min:0',
            'capital_league_name' => 'nullable|string|max:255',
            'required_trophies' => 'integer|min:0',
            'war_frequency' => 'nullable|string|max:255',
            'war_win_streak' => 'integer|min:0',
            'war_wins' => 'integer|min:0',
            'war_ties' => 'integer|min:0',
            'war_losses' => 'integer|min:0',
            'is_war_log_public' => 'boolean',
            'war_league_name' => 'nullable|string|max:255',
            'members' => 'integer|min:0',
            'member_list' => 'nullable|array',
            'clan_capital' => 'nullable|array',
            'chat_language' => 'nullable|string|max:255',
        ]);

        $clan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Clan updated successfully',
            'data' => $clan
        ]);
    }

    /**
     * Remove the specified clan from storage.
     */
    public function destroy(string $tag): JsonResponse
    {
        $tag = ltrim($tag, '#');

        $clan = Clan::where('tag', $tag)->first();

        if (!$clan) {
            return response()->json([
                'success' => false,
                'message' => 'Clan not found'
            ], 404);
        }

        $clan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Clan deleted successfully'
        ]);
    }

    /**
     * Fetch a clan from the Clash of Clans API and store/update in our database.
     */
    public function fetchFromApi(string $tag): JsonResponse
    {
        $tag = ltrim($tag, '#');

        // Get clan data from the Clash API
        $clanData = $this->clashApiService->getClan($tag);

        if (!$clanData) {
            return response()->json([
                'success' => false,
                'message' => 'Clan not found in Clash of Clans API'
            ], 404);
        }

        // Check if clan already exists in our database
        $clan = Clan::where('tag', $tag)->first();

        // Prepare clan data - avoid using direct boolean values
        $isFamilyFriendly = isset($clanData['isFamilyFriendly']) ? $clanData['isFamilyFriendly'] : false;
        $isWarLogPublic = isset($clanData['isWarLogPublic']) ? $clanData['isWarLogPublic'] : true;

        // Ensure proper strings are used for PostgreSQL boolean values
        $isFamilyFriendlyValue = $isFamilyFriendly ? 'true' : 'false';
        $isWarLogPublicValue = $isWarLogPublic ? 'true' : 'false';

        // Prepare clan data
        $clanAttributes = [
            'tag' => ltrim($clanData['tag'], '#'),
            'name' => $clanData['name'],
            'type' => $clanData['type'],
            'description' => $clanData['description'] ?? null,
            'location_name' => $clanData['location']['name'] ?? null,
            'is_family_friendly' => DB::raw($isFamilyFriendlyValue),
            'badge_urls' => $clanData['badgeUrls'],
            'clan_level' => $clanData['clanLevel'],
            'clan_points' => $clanData['clanPoints'] ?? 0,
            'clan_builder_base_points' => $clanData['clanBuilderBasePoints'] ?? 0,
            'clan_capital_points' => $clanData['clanCapitalPoints'] ?? 0,
            'capital_league_name' => $clanData['capitalLeague']['name'] ?? null,
            'required_trophies' => $clanData['requiredTrophies'] ?? 0,
            'war_frequency' => $clanData['warFrequency'] ?? null,
            'war_win_streak' => $clanData['warWinStreak'] ?? 0,
            'war_wins' => $clanData['warWins'] ?? 0,
            'war_ties' => $clanData['warTies'] ?? 0,
            'war_losses' => $clanData['warLosses'] ?? 0,
            'is_war_log_public' => DB::raw($isWarLogPublicValue),
            'war_league_name' => $clanData['warLeague']['name'] ?? null,
            'members' => $clanData['members'] ?? 0,
            'member_list' => $clanData['memberList'] ?? null,
            'clan_capital' => isset($clanData['clanCapital']) ? $clanData['clanCapital'] : null,
            'chat_language' => isset($clanData['chatLanguage']) ? $clanData['chatLanguage']['name'] : null,
        ];

        // Create or update the clan
        if ($clan) {
            $clan->update($clanAttributes);
            $message = 'Clan updated successfully from Clash of Clans API';
        } else {
            $clan = Clan::create($clanAttributes);
            $message = 'Clan fetched successfully from Clash of Clans API';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $clan,
            'fetched_at' => now()->toIso8601String()
        ], $clan->wasRecentlyCreated ? 201 : 200);
    }
}
