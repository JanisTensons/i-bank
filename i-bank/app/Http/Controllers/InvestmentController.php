<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateInvestmentPrice;
use App\Models\Account;
use App\Models\Investment;
use App\Services\CoinMarketCapService;
use Doctrine\DBAL\Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected CoinMarketCapService $coinMarketCapService;

    public function __construct(CoinMarketCapService $coinMarketCapService)
    {
        $this->coinMarketCapService = $coinMarketCapService;
    }

    /**
     * @throws GuzzleException
     */
    public function index()
    {
        $cryptocurrencyListings = $this->coinMarketCapService->getCryptocurrencyListings();

        $cryptocurrencyCollection = [];
        foreach ($cryptocurrencyListings['data'] as $listing) {
            $cryptocurrencyCollection[] = [
                'name' => $listing['name'],
                'price' => $listing['quote']['EUR']['price'],
                'percent_change_1h' => $listing['quote']['EUR']['percent_change_1h'],
                'percent_change_7d' => $listing['quote']['EUR']['percent_change_7d'],
            ];
        }

        return view('investments', compact('cryptocurrencyCollection'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $user = Auth::user();
        $accounts = $user->accounts()->where('type', 'Investing Account')->get();

        return view('investments.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $account = Account::findOrFail($request->account_id);
        $investmentPrice = $request->price;
        $totalCost = $investmentPrice * $request->amount;

        if ($account->balance < $totalCost) {
            // The account does not have enough balance
            return redirect()->back()->withErrors(['insufficient_balance' => 'Insufficient account balance.']);
        }

        // Subtract the total cost from the account balance
        $account->balance -= $totalCost;
        $account->save();

        // Create the investment without the price
        $investment = Investment::create([
            'name' => $request->name,
            'amount' => $request->amount,
            'user_price' => $request->price,
            'user_id' => auth()->id(),
        ]);

        // Dispatch the job to update the price asynchronously
        UpdateInvestmentPrice::dispatch($investment);

        Session::flash('success', 'Investment created successfully.');

        return redirect()->route('investments')->with('success', 'Investment created successfully.');
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
    public function destroy(Request $request): RedirectResponse
    {
        $investment = Investment::findOrFail($request->id);

        // Perform any additional authorization checks if needed

        // Delete the investment
        $investment->delete();

        $account = Account::findOrFail($request->account_id);
        $account->balance += $request->profit;
        $account->save();

        // Optionally, perform any additional logic or redirect as needed

        return redirect()->route('portfolio')->with('success', 'Investment sold successfully.');
    }




}
