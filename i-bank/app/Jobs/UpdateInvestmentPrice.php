<?php

namespace App\Jobs;

use App\Models\Investment;
use App\Services\CoinMarketCapService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateInvestmentPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Investment $investment;

    public function __construct(Investment $investment)
    {
        $this->investment = $investment;
    }

    public function handle(CoinMarketCapService $coinMarketCapService)
    {
        $cryptocurrencyCollection = $coinMarketCapService->getCryptocurrencyListings();

        $price = $this->getPriceForInvestment($cryptocurrencyCollection, $this->investment->name);
        $this->investment->price = $price;

        $this->investment->save();
    }

    private function getPriceForInvestment(array $cryptocurrencyCollection, string $investmentName): ?float
    {
        foreach ($cryptocurrencyCollection['data'] as $listing) {
            if ($listing['name'] === $investmentName) {
                return $listing['quote']['EUR']['price'];
            }
        }

        return null;
    }
}
