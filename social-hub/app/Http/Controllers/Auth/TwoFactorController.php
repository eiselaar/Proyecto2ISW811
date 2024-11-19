<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function verify()
    {
        return view('auth.two-factor.verify');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $userId = $request->session()->get('2fa:user_id');
        $user = User::findOrFail($userId);
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            auth()->login($user);
            $request->session()->forget('2fa:user_id');
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['code' => 'Invalid authentication code']);
    }

    public function enable()
    {
        $user = auth()->user();
        $google2fa = new Google2FA();
        
        if (!$user->two_factor_secret) {
            $user->two_factor_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        return view('auth.two-factor.enable', compact('qrCodeUrl'));
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        $user = auth()->user();
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null
        ]);

        return redirect()->route('profile.show')
            ->with('success', '2FA has been disabled.');
    }
}