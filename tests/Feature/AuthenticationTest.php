<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can view registration form.
     */
    public function test_user_can_view_registration_form(): void
    {
        $response = $this->get(route('auth.register'));
        $response->assertStatus(200);
        $response->assertSee('Criar nova conta');
    }

    /**
     * Test user can register with valid data.
     */
    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('account.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
        $this->assertAuthenticated();
    }

    /**
     * Test user cannot register with invalid data.
     */
    public function test_user_cannot_register_with_invalid_data(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    /**
     * Test user can view login form.
     */
    public function test_user_can_view_login_form(): void
    {
        $response = $this->get(route('auth.login'));
        $response->assertStatus(200);
        $response->assertSee('Entrar na sua conta');
    }

    /**
     * Test user can login with valid credentials.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('account.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test user cannot login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test authenticated user can logout.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post(route('auth.logout'));
        
        $response->assertRedirect(route('welcome'));
        $this->assertGuest();
    }

    /**
     * Test guest cannot access account dashboard.
     */
    public function test_guest_cannot_access_account_dashboard(): void
    {
        $response = $this->get(route('account.dashboard'));
        $response->assertRedirect(route('auth.login'));
    }

    /**
     * Test authenticated user can access account dashboard.
     */
    public function test_authenticated_user_can_access_account_dashboard(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('account.dashboard'));
        
        $response->assertStatus(200);
        $response->assertSee('Minha Conta');
    }
}
