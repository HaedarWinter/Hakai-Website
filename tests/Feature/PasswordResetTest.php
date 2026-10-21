<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_unknown_email_gets_the_same_generic_response(): void
    {
        $response = $this->post('/forgot-password', [
            'delivery_method' => 'email',
            'email' => 'nobody@example.com',
        ]);

        $response->assertSessionHas('status');
        $response->assertSessionHasNoErrors();
    }

    public function test_password_reset_revokes_existing_tokens(): void
    {
        $user = User::factory()->create();
        $user->createToken('web_session');

        $token = 'reset-token-123456';
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => \Illuminate\Support\Facades\Hash::make($token),
            'created_at' => now(),
        ]);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertRedirect(route('login'));

        $this->assertSame(0, $user->tokens()->count());
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-password-123', $user->fresh()->password));
    }
}
