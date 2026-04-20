<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_admin_returns_true_for_admin_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($admin->isAdmin());
    }

    public function test_user_is_admin_returns_false_for_user_role(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->assertFalse($user->isAdmin());
    }

    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create(['password' => 'plaintext']);
        $this->assertNotEquals('plaintext', $user->password);
    }

    public function test_user_has_rentals_relationship(): void
    {
        $user = User::factory()->create();
        $this->assertCount(0, $user->rentals);
    }

    public function test_user_has_reviews_relationship(): void
    {
        $user = User::factory()->create();
        $this->assertCount(0, $user->reviews);
    }

    public function test_user_has_kosts_relationship(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->assertCount(0, $user->kosts);
    }

    public function test_user_fillable_attributes(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'role' => 'user',
        ]);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@test.com', $user->email);
        $this->assertEquals('081234567890', $user->phone);
        $this->assertEquals('Jl. Test No. 1', $user->address);
        $this->assertEquals('user', $user->role);
    }

    public function test_user_hidden_attributes(): void
    {
        $user = User::factory()->create();
        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }
}
