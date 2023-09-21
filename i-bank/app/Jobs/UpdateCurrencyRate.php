<?php

namespace App\Jobs;

use App\Models\CurrencyRate;
use App\Services\CurrencyRatesService;
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
     */
    public function __construct(CurrencyRate $currencyRate)
    {
        $this->currencyRate = $currencyRate;
    }

    /**
     * Execute the job.
     */
    public function handle(CurrencyRatesService $currencyRatesService)
    {
        $currencyRatesListings = $currencyRatesService->getCurrencyRatesListings();

        $rate = $this->findRateForCurrencyRate($currencyRatesListings, $this->currencyRate->currency);
        $this->currencyRate->rate = $rate;

        $this->currencyRate->save();
    }

    private function findRateForCurrencyRate(array $currencyRatesListings, string $currencyRateId): ?string
    {
        foreach ($currencyRatesListings['Currencies']['Currency'] as $listing) {
            if ($currencyRateId === 'EUR') {
                return '1.00';
            }
            if ((string)$listing['ID'] === $currencyRateId) {
                return $listing['Rate'];
            }
        }
        return null;
    }
}
