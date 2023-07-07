<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CurrencyRatesController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('accounts', [AccountController::class, 'index'])
    ->middleware(['auth'])
    ->name('accounts');

Route::get('create', [AccountController::class, 'create'])
    ->middleware(['auth'])
    ->name('create');

Route::post('create', [AccountController::class, 'store'])
    ->middleware(['auth'])
    ->name('create.store');

Route::get('accounts/{id}', [AccountController::class, 'show'])
    ->middleware(['auth'])
    ->name('accounts.show');

Route::get('transactions', [TransactionController::class, 'index'])
    ->middleware(['auth'])
    ->name('transactions');

Route::get('investments', [InvestmentController::class, 'index'])
    ->middleware(['auth'])
    ->name('investments');

Route::get('investments/create', [InvestmentController::class, 'create'])
    ->middleware(['auth'])
    ->name('investments/create');

Route::post('investments/create', [InvestmentController::class, 'store'])
    ->middleware(['auth'])
    ->name('investments.store');

Route::get('portfolio', [PortfolioController::class, 'index'])
    ->middleware(['auth'])
    ->name('portfolio');

Route::get('portfolio/destroy', [InvestmentController::class, 'createDestroyForm'])
    ->middleware(['auth'])
    ->name('portfolio/destroy');

Route::post('portfolio/destroy', [InvestmentController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('portfolio/destroy');

Route::delete('portfolio/destroy', [InvestmentController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('portfolio/destroy');

Route::get('rates', [CurrencyRatesController::class, 'index'])
    ->middleware(['auth'])
    ->name('rates');

Route::get('transactions/create', [TransactionController::class, 'create'])
    ->middleware(['auth'])
    ->name('transactions/create');

Route::post('transactions/create', [TransactionController::class, 'store'])
    ->middleware(['auth'])
    ->name('transactions.store');

Route::get('destroy', [AccountController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('destroy');

Route::post('destroy', [AccountController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('destroy');

Route::delete('destroy', [AccountController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('destroy');

Route::post('verify-code', [AuthenticatedSessionController::class, 'verifyCode'])
    ->name('verify-code');

Route::get('/run-investment-price-scheduler', function () {
    $investments = \App\Models\Investment::all();

    foreach ($investments as $investment) {
        \App\Jobs\UpdateInvestmentPrice::dispatch($investment);
    }
});

require __DIR__ . '/auth.php';
