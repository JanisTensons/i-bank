<?php

namespace App\Console;

use App\Jobs\UpdateCurrencyRate;
use App\Jobs\UpdateInvestmentPrice;
use App\Models\CurrencyRate;
use App\Models\Investment;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
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
            $currencyRates = CurrencyRate::all();

            if ($currencyRates->isEmpty()) {
                // If the currency_rates table is empty, fetch initial data and save it
                $currencyRatesService = app()->make(\App\Services\CurrencyRatesService::class);
                $currencyRatesListings = $currencyRatesService->getCurrencyRatesListings();

                foreach ($currencyRatesListings['Currencies']['Currency'] as $listing) {
                    CurrencyRate::create([
                        'currency' => $listing['ID'],
                        'rate' => $listing['Rate'],
                    ]);
                }
            } else {
                // If the currency_rates table has data, update the rates
                foreach ($currencyRates as $currencyRate) {
                    UpdateCurrencyRate::dispatch($currencyRate);
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
