<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\CurrencyRatesService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $user = Auth::user();
        $accounts = $user->accounts;

        return view('accounts', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('accounts.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:255'],
            'balance' => ['required', 'numeric', 'min:0'],
        ]);

        $account = Account::create([
            'type' => $request->type,
            'number' => $this->generateAccountNumber(),
            'currency' => $request->currency,
            'balance' => $request->balance,
            'user_id' => auth()->id(),
        ]);

        $account->save();

        Session::flash('success', 'Account created successfully.');

        // Optionally, you can redirect the user to a specific page or perform additional actions
        return redirect()->route('accounts')->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id): View
    {
        $account = Account::find($id);

        if (!$account) {
            // Account not found, handle the appropriate response (e.g., redirect, error message)
        }

        return view('accounts.show', ['account' => $account]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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
        $account = Account::findOrFail($request->id);

        // Perform any additional authorization checks if needed

        // Delete the investment
        $account->delete();

        return redirect()->route('accounts')->with('success', 'Account deleted successfully.');
    }

    public function generateAccountNumber(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $accountNumber = '';

        // Generate 21 random characters
        for ($i = 0; $i < 21; $i++) {
            $accountNumber .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $accountNumber;
    }

}
