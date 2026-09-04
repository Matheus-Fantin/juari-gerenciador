<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailAllowlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_outside_the_allowed_list_is_kicked_out(): void
    {
        config(['app.admin_allowed_emails' => ['ana@juari.com']]);

        $user = User::factory()->create(['email' => 'estranho@fora.com']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_logged_in_user_in_the_allowed_list_keeps_access(): void
    {
        config(['app.admin_allowed_emails' => ['ana@juari.com']]);

        $user = User::factory()->create(['email' => 'ana@juari.com']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $this->assertAuthenticated();
    }

    public function test_empty_allowed_list_does_not_restrict_anyone(): void
    {
        config(['app.admin_allowed_emails' => []]);

        $user = User::factory()->create(['email' => 'qualquer@coisa.com']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $this->assertAuthenticated();
    }
}
