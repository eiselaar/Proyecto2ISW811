<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function enable()
    {
        return view('auth.2fa.enable');
    }

    public function store(Request $request)
    {
        $google2fa = new Google2FA();
        
        if (!$request->session()->has('2fa_secret')) {
            $secret = $google2fa->generateSecretKey();
            $request->session()->put('2fa_secret', $secret);
            
            // Generar QR Code para mostrar
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name'),
                auth()->user()->email,
                $secret
            );
            
            return redirect()->back()->with(['qr_code' => $qrCodeUrl]);
        }

        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $valid = $google2fa->verifyKey(
            $request->session()->get('2fa_secret'), 
            $request->code
        );

        if ($valid) {
            auth()->user()->update([
                'two_factor_enabled' => true,
                'google2fa_secret' => $request->session()->get('2fa_secret'),
            ]);
            
            $request->session()->forget('2fa_secret');
            return redirect()->route('dashboard')->with('status', '2FA has been enabled.');
        }

        return back()->withErrors(['code' => 'The provided code is invalid.']);
    }

    public function verify()
    {
        return view('auth.2fa.verify');
    }

    public function verify2fa(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey(
            auth()->user()->google2fa_secret,
            $request->code
        );

        if ($valid) {
            $request->session()->put('2fa_verified', true);
            
            // Si hay una conexión social pendiente, reanudar el proceso
            if ($request->session()->has('social_callback_platform')) {
                $platform = $request->session()->get('social_callback_platform');
                $code = $request->session()->get('social_callback_code');
                
                // Limpiar las variables de sesión
                $request->session()->forget(['social_callback_platform', 'social_callback_code']);
                
                // Redireccionar al callback de la red social
                return redirect()->route('social.callback', [
                    'platform' => $platform,
                    'code' => $code
                ]);
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['code' => 'The provided code is invalid.']);
    }
}