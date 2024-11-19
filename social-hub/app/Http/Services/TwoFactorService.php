<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorService
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function getQRCodeUrl(User $user): string
    {
        return $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );
    }

    public function verify(User $user, string $code): bool
    {
        return $this->google2fa->verifyKey($user->two_factor_secret, $code);
    }
}