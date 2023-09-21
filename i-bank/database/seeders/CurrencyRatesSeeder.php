<?php

namespace Database\Seeders;

use App\Models\CurrencyRate;
use Illuminate\Database\Seeder;

class CurrencyRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Example data for currency rates
        $currencyRates = [
            [
                'currency' => 'EUR',
                'rate' => 1.00,
            ],
        ];

        // Insert the data into the currency_rates table
        foreach ($currencyRates as $rate) {
            CurrencyRate::create($rate);
        }
    }
}
