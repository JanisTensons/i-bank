<?php

namespace App\Jobs;

use App\Http\Controllers\InvestmentController;
use App\Models\Investment;
use App\Services\CoinMarketCapService;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateInvestmentPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Investment $investment;

    /**
     * Create a new job instance.
     *
     * @param Investment $investment
     */
    public function __construct(Investment $investment)
    {
        $this->investment = $investment;
    }

    /**
     * Execute the job.
     *
     * @param CoinMarketCapService $coinMarketCapService
     * @return void
     */
    public function handle(CoinMarketCapService $coinMarketCapService)
    {
        // Fetch data from CoinMarketCap or any other source
        $cryptocurrencyCollection = $coinMarketCapService->getCryptocurrencyListings();

        // Process the fetched data and update the investment price
        $price = $this->getPriceForInvestment($cryptocurrencyCollection, $this->investment->name);
        $this->investment->price = $price;

        // Save the updated investment
        $this->investment->save();
    }

    /**
     * Get the price for the investment based on the fetched data.
     *
     * @param array $cryptocurrencyCollection
     * @param string $investmentName
     * @return float|null
     */
    private function getPriceForInvestment(array $cryptocurrencyCollection, string $investmentName): ?float
    {
        foreach ($cryptocurrencyCollection['data'] as $listing) {
            if ($listing['name'] === $investmentName) {
                return $listing['quote']['EUR']['price'];
            }
        }

        return null; // If the investment price is not found in the fetched data
    }
}
