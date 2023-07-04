<?php

use Doctrine\DBAL\Exception;
use Illuminate\Support\Facades\DB;

/**
 * Convert the given price to the specified account currency.
 *
 * @param float $price
 * @param string $accountCurrency
 * @return float
 */
function convertToAccountCurrency(float $price, string $accountCurrency): float
{
    // Get the conversion rate from the database based on the account currency
    $conversionRate = DB::table('currency_rates')
        ->where('currency', $accountCurrency)
        ->value('rate');

    // Validate if a conversion rate is available for the account currency
    if (!$conversionRate) {
        throw new Exception('No conversion rate found for the account currency.');
    }

    // Convert the price based on the conversion rate
    $convertedPrice = $price * $conversionRate;

    return $convertedPrice;
}
