<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClashApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CapitalRaidController extends Controller
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
     * Get Capital Raid data for a clan.
     *
     * @param string $clanTag
     * @param Request $request
     * @return JsonResponse
     */
    public function getCapitalRaids(string $clanTag, Request $request): JsonResponse
    {
        // Validate request parameters
        $validated = $request->validate([
            'limit' => 'integer|min:1|max:25|nullable',
            'after' => 'string|nullable',
            'before' => 'string|nullable',
        ]);

        // Set default limit if not provided
        $limit = $validated['limit'] ?? 1;

        // Prepare query parameters
        $query = ['limit' => $limit];

        // Add pagination parameters if provided
        if (isset($validated['after'])) {
            $query['after'] = $validated['after'];
        } elseif (isset($validated['before'])) {
            $query['before'] = $validated['before'];
        }

        // Get response from Clash API
        $response = $this->clashApiService->getCapitalRaids($clanTag, $query);

        if (!$response) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch capital raid data or clan not found'
            ], 404);
        }

        // Process and format the data
        $formattedData = $this->formatCapitalRaidData($response);

        return response()->json([
            'success' => true,
            'data' => $formattedData,
            'paging' => $response['paging'] ?? null,
            'fetched_at' => now()->toIso8601String()
        ]);
    }

    /**
     * Format the capital raid data for frontend.
     *
     * @param array $response
     * @return array
     */
    private function formatCapitalRaidData(array $response): array
    {
        $result = [];

        foreach ($response['items'] as $capitalRaid) {
            $raidData = [
                'state' => $capitalRaid['state'],
                'startTime' => $capitalRaid['startTime'],
                'endTime' => $capitalRaid['endTime'],
                'overview' => [
                    'totalLoot' => $capitalRaid['capitalTotalLoot'],
                    'totalAttacks' => $capitalRaid['totalAttacks'],
                    'raidsCompleted' => $capitalRaid['raidsCompleted'],
                    'enemyDistrictsDestroyed' => $capitalRaid['enemyDistrictsDestroyed'],
                    'avgLootPerRaid' => $capitalRaid['raidsCompleted'] > 0 ? round($capitalRaid['capitalTotalLoot'] / $capitalRaid['raidsCompleted']) : 0,
                    'avgLootPerAttack' => $capitalRaid['totalAttacks'] > 0 ? round($capitalRaid['capitalTotalLoot'] / $capitalRaid['totalAttacks']) : 0,
                    'offensiveReward' => $capitalRaid['offensiveReward'],
                    'defensiveReward' => $capitalRaid['defensiveReward']
                ],
                'members' => []
            ];

            // Process member data
            foreach ($capitalRaid['members'] as $member) {
                $totalAttackLimit = $member['attackLimit'] + $member['bonusAttackLimit'];

                $raidData['members'][] = [
                    'tag' => $member['tag'],
                    'name' => $member['name'],
                    'looted' => $member['capitalResourcesLooted'],
                    'attacksUsed' => $member['attacks'],
                    'attackLimit' => $member['attackLimit'],
                    'bonusAttackLimit' => $member['bonusAttackLimit'],
                    'totalAttackLimit' => $totalAttackLimit,
                    'hits' => $member['attacks'] . '/' . $totalAttackLimit,
                    'attackCompletion' => round(($member['attacks'] / $totalAttackLimit) * 100),
                    'avgLootPerAttack' => $member['attacks'] > 0 ? round($member['capitalResourcesLooted'] / $member['attacks']) : 0
                ];
            }

            // Sort members by resources looted (descending)
            usort($raidData['members'], function ($a, $b) {
                return $b['looted'] - $a['looted'];
            });

            $result[] = $raidData;
        }

        return $result;
    }
}
