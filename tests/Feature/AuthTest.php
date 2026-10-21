<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_email(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $response = $this->post('/login', [
            'login' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertSame(1, $user->tokens()->count());
    }

    public function test_wrong_password_does_not_authenticate(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $this->post('/login', [
            'login' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_login_throttle_uses_the_login_field_not_a_shared_key(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'login' => 'someone@example.com',
                'password' => 'wrong-password',
            ]);
        }

        // Different account from the same IP must still be able to log in.
        $this->post('/login', [
            'login' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_api_login_is_throttled(): void
    {
        User::factory()->create(['email' => 'api@example.com']);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', [
                'email' => 'api@example.com',
                'password' => 'wrong-password',
            ])->assertStatus(422);
        }

        $this->postJson('/api/login', [
            'email' => 'api@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(429);
    }
}
