<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Http\Request;


class Google2FAController extends Controller
{
    // protected $google2fa;

    // public function __construct()
    // {
    //     $this->google2fa = new Google2FA();
    // }

    // public function enable(Request $request)
    // {
    //     $secret = $this->google2fa->generateSecretKey();
        
    //     $user = auth()->user();
    //     $user->two_factor_secret = $secret;
    //     $user->save();

    //     $qrCodeUrl = $this->google2fa->getQRCodeUrl(
    //         config('app.name'),
    //         $user->email,
    //         $secret
    //     );

    //     return view('auth.2fa.enable', compact('qrCodeUrl', 'secret'));
    // }

    // public function confirm(Request $request)
    // {
    //     $request->validate([
    //         'code' => 'required|string|size:6',
    //     ]);

    //     $user = auth()->user();
    //     $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->code);

    //     if ($valid) {
    //         $user->two_factor_enabled = true;
    //         $user->save();
    //         return redirect()->route('dashboard')->with('success', '2FA has been enabled.');
    //     }

    //     return back()->withErrors(['code' => 'Invalid verification code.']);
    // }

    // public function verify(Request $request)
    // {
    //     $request->validate([
    //         'code' => 'required|string|size:6',
    //     ]);

    //     $user = auth()->user();
    //     $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->code);

    //     if ($valid) {
    //         session(['2fa_verified' => true]);
    //         return redirect()->intended('dashboard');
    //     }

    //     return back()->withErrors(['code' => 'Invalid verification code.']);
    // }
}