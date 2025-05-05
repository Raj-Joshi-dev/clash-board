<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

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
     * Whether to verify SSL certificates when making API requests.
     */
    protected bool $verifySSL;

    /**
     * Create a new ClashApiService instance.
     */
    public function __construct()
    {
        $this->apiToken = config('services.clash_of_clans.api_token');
        $this->verifySSL = env('CLASH_API_VERIFY_SSL', true);
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
            ->withOptions(['verify' => $this->verifySSL]) // Use environment variable to control SSL verification
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

    /**
     * Fetch capital raid seasons for a clan.
     *
     * @param string $clanTag The clan tag (with or without #).
     * @param array $query Optional query parameters (limit, before, after).
     * @return array|null The capital raid data or null if not found.
     */
    public function getCapitalRaids(string $clanTag, array $query = []): ?array
    {
        // Ensure the clan tag starts with a #
        $clanTag = '#' . ltrim($clanTag, '#');

        // URL encode the clan tag
        $encodedTag = urlencode($clanTag);

        $response = $this->makeRequest("/clans/{$encodedTag}/capitalraidseasons", $query);

        if ($response->successful()) {
            return $response->json();
        }

        // Log error if API call fails
        if ($response->failed()) {
            Log::error('Failed to fetch capital raids', [
                'clan_tag' => $clanTag,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
        }

        return null;
    }
}
