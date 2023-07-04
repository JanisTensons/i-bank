<?php

namespace App\Jobs;

use App\Models\CurrencyRate;
use App\Services\CurrencyRatesService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCurrencyRate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected CurrencyRate $currencyRate;

    /**
     * Create a new job instance.
     *
     * @param CurrencyRate $currencyRate
     */
    public function __construct(CurrencyRate $currencyRate)
    {
        $this->currencyRate = $currencyRate;
    }

    /**
     * Execute the job.
     *
     * @param CurrencyRatesService $currencyRatesService
     * @return void
     * @throws GuzzleException
     */
    public function handle(CurrencyRatesService $currencyRatesService)
    {
        // Fetch data from the currency rates service
        $currencyRatesListings = $currencyRatesService->getCurrencyRatesListings();

        // Find the updated ID and rate for the currency rate
        $id = $this->findIdForCurrencyRate($currencyRatesListings, $this->currencyRate->currency);
        $rate = $this->findRateForCurrencyRate($currencyRatesListings, $this->currencyRate->rate);

        // Update the currency rate
        $this->currencyRate->currency = $id;
        $this->currencyRate->rate = $rate;
        $this->currencyRate->save();
    }

    private function findIdForCurrencyRate(array $currencyRatesListings, string $currencyRateId): ?string
    {
        foreach ($currencyRatesListings['Currencies']['Currency'] as $listing) {
            if ((string) $listing['ID'] === $currencyRateId) {
                return $listing['ID'];
            }
        }
        return null; // If the currency rate ID is not found in the fetched data
    }

    private function findRateForCurrencyRate(array $currencyRatesListings, string $currencyRateRate): ?string
    {
        foreach ($currencyRatesListings['Currencies']['Currency'] as $listing) {
            if ((string) $listing['Rate'] === $currencyRateRate) {
                return $listing['Rate'];
            }
        }
        return null; // If the currency rate rate is not found in the fetched data
    }
}
