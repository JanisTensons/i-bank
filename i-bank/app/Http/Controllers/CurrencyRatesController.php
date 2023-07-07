<?php

namespace App\Http\Controllers;

use App\Services\CurrencyRatesService;
use Illuminate\View\View;

class CurrencyRatesController extends Controller
{
    protected CurrencyRatesService $currencyRatesService;

    public function __construct(CurrencyRatesService $currencyRatesService)
    {
        $this->currencyRatesService = $currencyRatesService;
    }

    public function index(): View
    {
        $currencyRatesListings = $this->currencyRatesService->getCurrencyRatesListings();

        $currencyRatesCollection = [];
        foreach ($currencyRatesListings['Currencies']['Currency'] as $listing) {
            $currencyRatesCollection[] = [
                'currency' => $listing['ID'],
                'rate' => $listing['Rate']
            ];
        }
        return view('rates', compact('currencyRatesCollection'));
    }
}
