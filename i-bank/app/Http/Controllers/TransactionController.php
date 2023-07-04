<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CurrencyRate;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $user = Auth::user();
        $transactions = $user->transactions;

        return view('transactions', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $user = Auth::user();
        $accounts = $user->accounts()->where('type', 'Checking Account')->get();

        return view('transactions/create', compact('accounts'));
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
            'from' => ['required', 'integer', 'different:to'],
            'to' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $fromAccount = Account::findOrFail($request->from);
        $toAccount = Account::findOrFail($request->to);

        $fromCurrencyCode = $fromAccount->currency;
        $toCurrencyCode = $toAccount->currency;

        $conversionRate = $this->getConversionRate($fromCurrencyCode, $toCurrencyCode);

        if (!$conversionRate) {
            return redirect()->back()->withErrors(['error' => 'Conversion rate not found.']);
        }

        $amountInEUR = $this->convertToEUR($request->amount, $fromCurrencyCode, $conversionRate);
        $convertedAmount = $this->convertFromEUR($amountInEUR, $toCurrencyCode, $conversionRate);

        if ($fromAccount->balance < $request->amount) {
            return redirect()->back()->withErrors(['amount' => 'Insufficient balance in the From account.']);
        }

        DB::beginTransaction();

        try {
            $this->subtractAmountFromAccount($fromAccount, $request->amount);
            $this->addConvertedAmountToAccount($toAccount, $convertedAmount);
            $this->createTransactionRecord($fromAccount, $toAccount, $convertedAmount, $request->description);

            DB::commit();

            return redirect()->route('transactions')->with('success', 'Transfer completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'An error occurred during the transfer. Please try again.']);
        }
    }

    private function getConversionRate(string $fromCurrencyCode, string $toCurrencyCode): ?float
    {
        if ($fromCurrencyCode === 'EUR') {
            return CurrencyRate::where('currency', $toCurrencyCode)->value('rate');
        }

        $fromCurrencyRate = CurrencyRate::where('currency', $fromCurrencyCode)->value('rate');

        if (!$fromCurrencyRate) {
            return null;
        }

        $toCurrencyRate = CurrencyRate::where('currency', $toCurrencyCode)->value('rate');

        if (!$toCurrencyRate) {
            return null;
        }

        return $toCurrencyRate / $fromCurrencyRate;
    }

    private function convertToEUR(float $amount, string $fromCurrencyCode, float $conversionRate): float
    {
        return $fromCurrencyCode === 'EUR' ? $amount : $amount / $conversionRate;
    }

    private function convertFromEUR(float $amountInEUR, string $toCurrencyCode, float $conversionRate): float
    {
        return $toCurrencyCode === 'EUR' ? $amountInEUR : $amountInEUR * $conversionRate;
    }

    private function subtractAmountFromAccount(Account $account, float $amount): void
    {
        $account->balance -= $amount;
        $account->save();
    }

    private function addConvertedAmountToAccount(Account $account, float $convertedAmount): void
    {
        $account->balance += $convertedAmount;
        $account->save();
    }

    private function createTransactionRecord(Account $fromAccount, Account $toAccount, float $convertedAmount, ?string $description): void
    {
        Transaction::create([
            'from' => $fromAccount->number,
            'to' => $toAccount->number,
            'amount' => $convertedAmount,
            'description' => $description,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function edit($id)
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
    public
    function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}
