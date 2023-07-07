<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyRatesService
{
    protected string $cacheKey = 'currency_rates';

    public function getCurrencyRatesListings()
    {
        return Cache::remember($this->cacheKey, 60, function () {
            $response = Http::get('https://www.latvijasbanka.lv/vk/ecb.xml');
            $xmlData = simplexml_load_string($response->getBody(), 'SimpleXMLElement', LIBXML_NOCDATA);
            $jsonData = json_encode($xmlData);
            return json_decode($jsonData, true);
        });
    }
}
