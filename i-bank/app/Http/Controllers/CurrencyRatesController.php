<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateCurrencyRate;
use App\Services\CurrencyRatesService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurrencyRatesController extends Controller
{
    protected CurrencyRatesService $currencyRatesService;

    public function __construct(CurrencyRatesService $currencyRatesService)
    {
        $this->currencyRatesService = $currencyRatesService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws GuzzleException
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CurrencyRatesService $currencyRatesService)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
