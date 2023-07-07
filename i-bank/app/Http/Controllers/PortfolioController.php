<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $investments = $user->investments;

        $totalProfit = 0;

        foreach ($investments as $investment) {
            $profit = $investment->price - $investment->user_price;
            $totalProfit += $profit;
        }

        return view('portfolio', compact('investments', 'totalProfit'));

    }

    public function destroy(): View
    {
        $accounts = Account::all();

        return view('investments.destroy', compact('accounts'));

    }
}
