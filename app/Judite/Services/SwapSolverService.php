<?php

namespace App\Judite\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class SwapSolverService
{
    /**
     * The HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create a new SwapSolverService instance.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the possible matches for requested exchanges.
     *
     * @param array $exchanges
     */
    public function getExchangesMatches($exchanges)
    {
        return $this->client->post('/', [
            'body' => json_encode(['exchange_requests' => $exchanges])
        ]);
    }
}
