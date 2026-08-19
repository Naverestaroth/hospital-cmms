<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    public function test_kepala_ipsrs_can_authenticate_and_redirect_to_dashboard(): void
    {
        $password = 'Password123!';
        $user = User::factory()->create([
            'email' => 'kepala.ipsrs@hospital.com',
            'role' => 'kepala_ipsrs',
            'password' => bcrypt($password),
        ]);

        $response = $this->post('/login', [
            'email' => 'kepala.ipsrs@hospital.com',
            'password' => $password,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertEquals('kepala_ipsrs', auth()->user()->role);
    }

    public function test_kepala_ipsrs_logout_queues_remembered_email_cookie(): void
    {
        $user = User::factory()->create([
            'email' => 'kepala.ipsrs@hospital.com',
            'role' => 'kepala_ipsrs',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertCookie('remembered_email', 'kepala.ipsrs@hospital.com');
    }

    public function test_kepala_ipsrs_can_access_settings_but_cannot_wipe_data(): void
    {
        $user = User::factory()->create([
            'role' => 'kepala_ipsrs',
        ]);

        $response = $this->actingAs($user)->get('/settings');
        $response->assertStatus(200);
        $response->assertSee('Profile');
        $response->assertSee('User & Role', false);


        $response->assertDontSee('Admin Tools');
        $response->assertDontSee('System Notification Preferences');

        $wipeResponse = $this->actingAs($user)->post('/settings/wipe', ['targets' => ['tickets']]);
        $wipeResponse->assertStatus(403);
    }


    public function test_kepala_ipsrs_can_update_password(): void
    {
        $user = User::factory()->create([
            'role' => 'kepala_ipsrs',
            'password' => bcrypt('OldPassword123!'),
        ]);

        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'OldPassword123!',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('NewPassword123!', $user->fresh()->password));
    }

    public function test_kepala_ipsrs_can_update_profile_info(): void
    {
        $user = User::factory()->create([
            'role' => 'kepala_ipsrs',
            'email' => 'kepala.ipsrs@hospital.com',
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Kepala IPSRS Baru',
            'google_email' => 'kepala.ipsrs.google@gmail.com',
            'phone' => '081234567890',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $user->refresh();
        $this->assertEquals('Kepala IPSRS Baru', $user->name);
        $this->assertEquals('kepala.ipsrs.google@gmail.com', $user->google_email);
        $this->assertEquals('081234567890', $user->phone);
        $this->assertEquals('kepala.ipsrs@hospital.com', $user->email);
        $this->assertEquals('kepala_ipsrs', $user->role);
    }




    public function test_users_can_authenticate_in_developer_mode(): void
    {
        $user = User::factory()->create([
            'role' => 'developer',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'login_type' => 'developer',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('settings', absolute: false));
    }

    public function test_direct_quick_developer_login_without_credentials(): void
    {
        $response = $this->post('/login/developer-quick');

        $this->assertAuthenticated();
        $response->assertRedirect(route('settings', absolute: false));
    }
}

