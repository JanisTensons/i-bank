<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'verification_code' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();
        $secretKey = $user->two_factor_secret;
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($secretKey, $request->input('verification_code'))) {
            $request->authenticate();
            return redirect()->route('dashboard')->with('success', 'You are logged in!');;
        } else {
            return back()->withErrors(['verification_code' => 'Invalid verification code.'])->withInput();
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
