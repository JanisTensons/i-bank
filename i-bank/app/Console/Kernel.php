<?php

namespace App\Console;

use App\Jobs\UpdateCurrencyRate;
use App\Jobs\UpdateInvestmentPrice;
use App\Models\CurrencyRate;
use App\Models\Investment;
use App\Services\CurrencyRatesService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $investments = Investment::all();

            foreach ($investments as $investment) {
                UpdateInvestmentPrice::dispatch($investment);
            }
        })->everyMinute();

        $schedule->call(function () {
            if (CurrencyRate::count() === 1) {
                // The table is empty (only EUR rate available), load initial data.
                $currencyRatesService = app(CurrencyRatesService::class);
                $currencyRatesListings = $currencyRatesService->getCurrencyRatesListings();

                foreach ($currencyRatesListings['Currencies']['Currency'] as $listing) {
                    CurrencyRate::create([
                        'currency' => $listing['ID'],
                        'rate' => $listing['Rate']
                    ]);
                }
            } else {
                // The table is not empty, update the rates.
                $currencyRates = CurrencyRate::all();

                foreach ($currencyRates as $currencyRate) {
                    UpdateCurrencyRate::dispatch($currencyRate);
                }
            }
        })->everyMinute();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
