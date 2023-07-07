<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateInvestmentPrice;
use App\Models\Account;
use App\Models\Investment;
use App\Services\CoinMarketCapService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class InvestmentController extends Controller
{
    protected CoinMarketCapService $coinMarketCapService;

    public function __construct(CoinMarketCapService $coinMarketCapService)
    {
        $this->coinMarketCapService = $coinMarketCapService;
    }

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

    public function create(): View
    {
        $user = Auth::user();
        $accounts = $user->accounts()->where('type', 'Investing Account')->get();

        return view('investments.create', compact('accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'verification_code' => ['required'],
        ]);

        $account = Account::findOrFail($request->account_id);
        $investmentPrice = $request->price;
        $totalCost = $investmentPrice * $request->amount;

        if ($account->balance < $totalCost) {
            return redirect()->back()->withErrors(['insufficient_balance' => 'Insufficient account balance.']);
        }

        $user = Auth::user();
        $secretKey = $user->two_factor_secret;
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($secretKey, $request->input('verification_code'))) {

            $account->balance -= $totalCost;
            $account->save();

            $investment = Investment::create([
                'name' => $request->name,
                'amount' => $request->amount,
                'user_price' => $request->price,
                'user_id' => auth()->id(),
            ]);

            UpdateInvestmentPrice::dispatch($investment);

            Session::flash('success', 'Investment created successfully.');

            return redirect()->route('investments')->with('success', 'Investment created successfully.');
        } else {
            return back()->withErrors(['verification_code' => 'Invalid verification code.'])->withInput();
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $google2fa = new Google2FA();
        $user = Auth::user();
        $secretKey = $user->two_factor_secret;

        if ($google2fa->verifyKey($secretKey, $request->input('verification_code'))) {

            $investment = Investment::findOrFail($request->id);

            $investment->delete();

            $account = Account::findOrFail($request->account_id);
            $account->balance += $request->profit;
            $account->save();

            return redirect()->route('portfolio')->with('success', 'Investment sold successfully.');
        } else {
            return back()->withErrors(['verification_code' => 'Invalid verification code.'])->withInput();
        }
    }

    public function createDestroyForm(): View
    {
        $user = Auth::user();
        $accounts = $user->accounts()->where('type', 'Investing Account')->get();

        return view('investments.destroy', compact('accounts'));
    }
}
