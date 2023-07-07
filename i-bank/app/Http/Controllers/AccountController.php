<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $accounts = $user->accounts;

        return view('accounts', compact('accounts'));
    }

    public function create(): View
    {
        return view('accounts.create');
    }

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

        return redirect()->route('accounts')->with('success', 'Account created successfully.');
    }

    public function show(int $id): View
    {
        $account = Account::find($id);

        return view('accounts.show', ['account' => $account]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $account = Account::findOrFail($request->id);

        $account->delete();

        return redirect()->route('accounts')->with('success', 'Account deleted successfully.');
    }

    public function generateAccountNumber(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $accountNumber = '';

        for ($i = 0; $i < 21; $i++) {
            $accountNumber .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $accountNumber;
    }
}
