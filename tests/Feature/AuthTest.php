<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\GoogleTwoFactorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Welcome back');
        $response->assertDontSee('Register now');
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'author@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'author@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_with_2fa_enabled_is_redirected_to_challenge_upon_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'author@example.com',
            'password' => bcrypt('password123'),
            'role' => 'editor',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'author@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login.two-factor'));
        $response->assertSessionHas('auth.2fa.user_id', $user->id);
        $this->assertGuest();
    }

    public function test_user_can_authenticate_with_valid_totp_code(): void
    {
        $service = app(GoogleTwoFactorService::class);
        $secret = 'JBSWY3DPEHPK3PXP';

        $user = User::factory()->create([
            'email' => 'author@example.com',
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ]);

        $validCode = $service->calculateCode($secret);

        $response = $this->withSession(['auth.2fa.user_id' => $user->id])
            ->post('/login/two-factor', [
                'code' => $validCode,
            ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_authenticate_with_invalid_totp_code(): void
    {
        $user = User::factory()->create([
            'email' => 'author@example.com',
            'two_factor_secret' => 'JBSWY3DPEHPK3PXP',
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->withSession(['auth.2fa.user_id' => $user->id])
            ->from('/login/two-factor')
            ->post('/login/two-factor', [
                'code' => '000000',
            ]);

        $response->assertRedirect('/login/two-factor');
        $response->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_user_can_authenticate_with_emergency_recovery_code_and_code_is_consumed(): void
    {
        $user = User::factory()->create([
            'email' => 'author@example.com',
            'two_factor_secret' => 'JBSWY3DPEHPK3PXP',
            'two_factor_recovery_codes' => ['recov-1111', 'recov-2222', 'recov-3333'],
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->withSession(['auth.2fa.user_id' => $user->id])
            ->post('/login/two-factor', [
                'recovery_code' => 'recov-1111',
            ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);

        // Verify recovery code was consumed and removed
        $freshUser = $user->fresh();
        $this->assertNotContains('recov-1111', $freshUser->two_factor_recovery_codes);
        $this->assertContains('recov-2222', $freshUser->two_factor_recovery_codes);
    }

    public function test_user_cannot_reuse_consumed_recovery_code(): void
    {
        $user = User::factory()->create([
            'email' => 'author@example.com',
            'two_factor_secret' => 'JBSWY3DPEHPK3PXP',
            'two_factor_recovery_codes' => ['recov-2222'],
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->withSession(['auth.2fa.user_id' => $user->id])
            ->from('/login/two-factor')
            ->post('/login/two-factor', [
                'recovery_code' => 'already-used-code',
            ]);

        $response->assertRedirect('/login/two-factor');
        $response->assertSessionHasErrors('recovery_code');
        $this->assertGuest();
    }

    public function test_user_with_unconfirmed_2fa_is_redirected_to_setup_upon_login(): void
    {
        $user = User::factory()->unconfirmedTwoFactor()->create([
            'email' => 'newuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'newuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login.two-factor.setup'));
        $response->assertSessionHas('auth.2fa.user_id', $user->id);
        $this->assertGuest();
    }

    public function test_user_can_complete_two_factor_setup_with_valid_code(): void
    {
        $service = app(GoogleTwoFactorService::class);
        $secret = 'JBSWY3DPEHPK3PXP';
        $recoveryCodes = ['code-1', 'code-2'];

        $user = User::factory()->unconfirmedTwoFactor()->create([
            'email' => 'setup@example.com',
        ]);

        $validCode = $service->calculateCode($secret);

        $response = $this->withSession([
            'auth.2fa.user_id' => $user->id,
            'auth.2fa.setup_secret' => $secret,
            'auth.2fa.setup_recovery_codes' => $recoveryCodes,
        ])->post('/login/two-factor-setup', [
            'code' => $validCode,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);

        $freshUser = $user->fresh();
        $this->assertTrue($freshUser->hasTwoFactorEnabled());
        $this->assertEquals($secret, $freshUser->two_factor_secret);
        $this->assertEquals($recoveryCodes, $freshUser->two_factor_recovery_codes);
    }

    public function test_user_cannot_complete_two_factor_setup_with_invalid_code(): void
    {
        $secret = 'JBSWY3DPEHPK3PXP';
        $user = User::factory()->unconfirmedTwoFactor()->create([
            'email' => 'setup@example.com',
        ]);

        $response = $this->withSession([
            'auth.2fa.user_id' => $user->id,
            'auth.2fa.setup_secret' => $secret,
            'auth.2fa.setup_recovery_codes' => ['code-1'],
        ])->from('/login/two-factor-setup')
            ->post('/login/two-factor-setup', [
                'code' => '999999',
            ]);

        $response->assertRedirect('/login/two-factor-setup');
        $response->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_user_can_cancel_two_factor_and_returns_to_login(): void
    {
        $user = User::factory()->create();

        $response = $this->withSession([
            'auth.2fa.user_id' => $user->id,
            'auth.2fa.setup_secret' => 'SECRET',
        ])->post('/login/two-factor/cancel');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $response->assertSessionMissing('auth.2fa.user_id');
    }

    public function test_public_registration_is_disabled_and_redirects_to_login(): void
    {
        $response = $this->get('/register');
        $response->assertRedirect('/login');

        $postResponse = $this->post('/register', [
            'name' => 'Intruder',
            'email' => 'intruder@example.com',
            'password' => 'Secret123!',
            'password_confirmation' => 'Secret123!',
        ]);

        $postResponse->assertRedirect('/login');
        $this->assertDatabaseMissing('users', [
            'email' => 'intruder@example.com',
        ]);
        $this->assertGuest();
    }

    public function test_unconfirmed_user_cannot_access_admin_dashboard_directly(): void
    {
        $user = User::factory()->unconfirmedTwoFactor()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertRedirect(route('login.two-factor.setup'));
        $response->assertSessionHas('error');
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_user_can_regenerate_recovery_codes_from_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'two_factor_recovery_codes' => ['old-1', 'old-2'],
        ]);

        $response = $this->actingAs($user)->post('/admin/profile/two-factor/recovery-codes');

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('show_recovery_codes');

        $freshUser = $user->fresh();
        $this->assertCount(8, $freshUser->two_factor_recovery_codes);
        $this->assertNotEquals(['old-1', 'old-2'], $freshUser->two_factor_recovery_codes);
    }

    public function test_user_can_reset_two_factor_from_profile_with_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->actingAs($user)->post('/admin/profile/two-factor/reset', [
            'password' => 'correct-password',
        ]);

        $response->assertRedirect(route('login.two-factor.setup'));

        $freshUser = $user->fresh();
        $this->assertFalse($freshUser->hasTwoFactorEnabled());
        $this->assertNull($freshUser->two_factor_secret);
        $this->assertNull($freshUser->two_factor_confirmed_at);
    }

    public function test_user_cannot_reset_two_factor_with_incorrect_password(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->actingAs($user)->post('/admin/profile/two-factor/reset', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue($user->fresh()->hasTwoFactorEnabled());
    }

    public function test_artisan_command_can_reset_two_factor_for_user(): void
    {
        $user = User::factory()->create([
            'email' => 'lockedout@example.com',
        ]);

        $this->assertTrue($user->hasTwoFactorEnabled());

        $this->artisan('two-factor:reset', ['email' => 'lockedout@example.com'])
            ->expectsOutputToContain('Two-Factor Authentication has been successfully reset')
            ->assertSuccessful();

        $this->assertFalse($user->fresh()->hasTwoFactorEnabled());
    }
}
