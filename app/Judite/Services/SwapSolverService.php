<?php

namespace App\Judite\Services;

use GuzzleHttp\Client;

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
        // form_params
        return $this->client->post('/', ['exchange_requests' => $exchanges]);
    }
}
