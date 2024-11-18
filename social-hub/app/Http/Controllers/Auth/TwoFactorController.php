<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Hash;
// Imports que faltan:
use Illuminate\Support\Facades\Auth;  // Para usar auth()
use App\Models\User;  // Si estás trabajando con el modelo User
use Illuminate\Validation\Rules\Password;  // Para la validación de contraseña


class TwoFactorController extends Controller
{
public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = auth()->user();
        
        if (!$user->two_factor_secret) {
            $google2fa = new Google2FA();
            $user->two_factor_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = (new Google2FA())->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        return view('auth.two-factor.enable', compact('qrCodeUrl'));
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = auth()->user();
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            $user->two_factor_enabled = true;
            $user->save();

            return redirect()->route('home')
                ->with('success', '2FA has been enabled successfully.');
        }

        return back()->withErrors(['code' => 'Invalid authentication code.']);
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = auth()->user();
        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->save();

        return redirect()->route('home')
            ->with('success', '2FA has been disabled successfully.');
    }

    public function verify()
    {
        return view('auth.two-factor.verify');
    }

    public function verify2fa(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = auth()->user();
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            session(['2fa_verified' => true]);
            return redirect()->intended('home');
        }

        return back()->withErrors(['code' => 'Invalid authentication code.']);
    }
}