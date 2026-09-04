<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_registration_is_blocked_for_emails_outside_the_allowed_list(): void
    {
        config(['app.admin_allowed_emails' => ['ana@juari.com']]);

        $response = $this->post('/register', [
            'name' => 'Estranho',
            'email' => 'estranho@fora.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'estranho@fora.com']);
    }

    public function test_registration_is_allowed_for_emails_in_the_allowed_list(): void
    {
        config(['app.admin_allowed_emails' => ['ana@juari.com']]);

        $response = $this->post('/register', [
            'name' => 'Ana',
            'email' => 'ana@juari.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
