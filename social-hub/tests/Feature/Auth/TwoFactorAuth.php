<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_enable_2fa()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('2fa.enable'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.two-factor.enable');

        $response = $this->post(route('2fa.store'), [
            'code' => '123456'  // Mock code
        ]);
        
        $this->assertTrue($user->fresh()->two_factor_enabled);
    }

    public function test_2fa_verification_required_when_enabled()
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'two_factor_secret' => 'test-secret'
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertRedirect(route('2fa.verify'));
    }
}