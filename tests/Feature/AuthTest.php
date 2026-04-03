<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Facades\Password;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_register_page()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_register_as_member()
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Rafii Member',
            'email' => 'rafii@umm.ac.id',
            'phone' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Sesuai kodinganmu, setelah register redirect ke login
        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'rafii@umm.ac.id',
            'role' => 'member'
        ]);

        // Memastikan user langsung login otomatis setelah register
        $this->assertAuthenticated();
    }

    /** @test */
    public function member_login_redirects_to_home()
    {
        $user = User::factory()->create([
            'email' => 'member@test.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
        ]);

        $response = $this->post(route('login.process'), [
            'email' => 'member@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function admin_login_redirects_to_dashboard()
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('login.process'), [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'wrong@test.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login.process'), [
            'email' => 'wrong@test.com',
            'password' => 'salah-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_see_forgot_password_page()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
    }



    public function test_user_can_request_password_reset_link()
    {
        $user = User::factory()->create([
            'email' => 'reset@test.com'
        ]);

        Password::shouldReceive('sendResetLink')
            ->once()
            ->andReturn(Password::RESET_LINK_SENT);

        $response = $this->post(route('password.email'), [
            'email' => 'reset@test.com'
        ]);

        $response->assertSessionHas('status');
    }

    public function test_user_cannot_request_reset_with_invalid_email()
    {
        Password::shouldReceive('sendResetLink')
            ->once()
            ->andReturn(Password::INVALID_USER);

        $response = $this->post(route('password.email'), [
            'email' => 'tidakada@test.com'
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_see_reset_password_form()
    {
        $token = 'dummy-token';

        $response = $this->get(route('password.reset', [
            'token' => $token,
            'email' => 'reset@test.com'
        ]));

        $response->assertStatus(200);
    }

    public function test_user_can_reset_password()
    {
        $user = User::factory()->create([
            'email' => 'reset@test.com'
        ]);

        Password::shouldReceive('reset')
            ->once()
            ->andReturn(Password::PASSWORD_RESET);

        $response = $this->post(route('password.update'), [
            'token' => 'valid-token',
            'email' => 'reset@test.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_user_cannot_reset_password_with_invalid_token()
    {
        Password::shouldReceive('reset')
            ->once()
            ->andReturn(Password::INVALID_TOKEN);

        $response = $this->post(route('password.update'), [
            'token' => 'invalid-token',
            'email' => 'reset@test.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}