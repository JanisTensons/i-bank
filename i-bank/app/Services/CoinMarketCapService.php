<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class CoinMarketCapService
{
    protected string $apiKey;
    protected Client $client;

    public function __construct()
    {
        $this->apiKey = config('app.api_key');
        $this->client = new Client([
            'base_uri' => 'https://pro-api.coinmarketcap.com/v1/',
            'headers' => [
                'Accept' => 'application/json',
                'X-CMC_PRO_API_KEY' => $this->apiKey,
            ],
        ]);
    }

    public function getCryptocurrencyListings()
    {
        $cacheKey = 'crypto_listings';
        $cacheDuration = now()->addSeconds(60);

        return Cache::remember($cacheKey, $cacheDuration, function () {
            $response = $this->client->get('cryptocurrency/listings/latest', [
                'query' => [
                    'start' => 1,
                    'limit' => 100,
                    'convert' => 'EUR'
                ],
            ]);

            return json_decode($response->getBody(), true);
        });
    }
}
