<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    // ─── Guest Middleware ─────────────────────────────────────

    public function test_authenticated_user_cannot_access_login_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/dashboard');
    }

    public function test_authenticated_admin_redirected_to_admin_from_login(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/login');

        $response->assertRedirect('/admin');
    }

    public function test_authenticated_user_cannot_access_register_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/register');

        $response->assertRedirect('/dashboard');
    }

    // ─── Auth Middleware ──────────────────────────────────────

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/');
    }

    public function test_guest_cannot_access_my_bookings(): void
    {
        $response = $this->get('/my-bookings');

        $response->assertRedirect('/');
    }

    public function test_guest_cannot_access_my_payments(): void
    {
        $response = $this->get('/my-payments');

        $response->assertRedirect('/');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    // ─── Admin Middleware ─────────────────────────────────────

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/');
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_categories(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin/categories');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_regular_user_cannot_access_admin_kosts(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin/kosts');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_regular_user_cannot_access_admin_payments(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin/payments');

        $response->assertRedirect(route('dashboard'));
    }
}
