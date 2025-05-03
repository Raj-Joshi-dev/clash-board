<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class ClashApiService
{
    /**
     * The Clash of Clans API base URL.
     */
    protected string $baseUrl = 'https://api.clashofclans.com/v1';

    /**
     * The Clash of Clans API token.
     */
    protected string $apiToken;

    /**
     * Create a new ClashApiService instance.
     */
    public function __construct()
    {
        $this->apiToken = config('services.clash_of_clans.api_token');
    }

    /**
     * Make a request to the Clash of Clans API.
     *
     * @param string $endpoint The API endpoint.
     * @param array $query Optional query parameters.
     * @return Response The HTTP response.
     */
    protected function makeRequest(string $endpoint, array $query = []): Response
    {
        return Http::withToken($this->apiToken)
            ->get("{$this->baseUrl}{$endpoint}", $query);
    }

    /**
     * Fetch a player by player tag.
     *
     * @param string $playerTag The player tag (with or without #).
     * @return array|null The player data or null if not found.
     */
    public function getPlayer(string $playerTag): ?array
    {
        // Ensure the player tag starts with a #
        $playerTag = '#' . ltrim($playerTag, '#');
        
        // URL encode the player tag
        $encodedTag = urlencode($playerTag);
        
        $response = $this->makeRequest("/players/{$encodedTag}");
        
        if ($response->successful()) {
            return $response->json();
        }
        
        return null;
    }

    /**
     * Fetch a clan by clan tag.
     *
     * @param string $clanTag The clan tag (with or without #).
     * @return array|null The clan data or null if not found.
     */
    public function getClan(string $clanTag): ?array
    {
        // Ensure the clan tag starts with a #
        $clanTag = '#' . ltrim($clanTag, '#');
        
        // URL encode the clan tag
        $encodedTag = urlencode($clanTag);
        
        $response = $this->makeRequest("/clans/{$encodedTag}");
        
        if ($response->successful()) {
            return $response->json();
        }
        
        return null;
    }
}