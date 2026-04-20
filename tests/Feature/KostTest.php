<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Kost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KostTest extends TestCase
{
    use RefreshDatabase;

    private function createKost(array $attrs = []): Kost
    {
        $admin = User::factory()->create(['role' => 'admin']);

        return Kost::create(array_merge([
            'name' => 'Test Kost',
            'description' => 'A test kost description',
            'address' => 'Jl. Test No. 1',
            'contact_number' => '081234567890',
            'price_per_month' => 1500000,
            'room_count' => 10,
            'available_rooms' => 5,
            'facilities' => json_encode(['WiFi', 'AC']),
            'rules' => json_encode(['No pets']),
            'status' => 'active',
            'created_by' => $admin->id,
        ], $attrs));
    }

    // ─── Browse Kosts ─────────────────────────────────────────

    public function test_kost_index_is_publicly_accessible(): void
    {
        $response = $this->get('/kost');

        $response->assertStatus(200);
    }

    public function test_kost_index_shows_active_kosts(): void
    {
        $activeKost = $this->createKost(['name' => 'Active Kost', 'status' => 'active']);
        $inactiveKost = $this->createKost(['name' => 'Inactive Kost', 'status' => 'inactive']);

        $response = $this->get('/kost');

        $response->assertSee('Active Kost');
        $response->assertDontSee('Inactive Kost');
    }

    public function test_kost_detail_is_publicly_accessible(): void
    {
        $kost = $this->createKost();

        $response = $this->get("/kost/{$kost->id}");

        $response->assertStatus(200);
        $response->assertSee('Test Kost');
    }

    public function test_kost_detail_shows_facilities_and_rules(): void
    {
        $kost = $this->createKost([
            'facilities' => json_encode(['WiFi', 'AC', 'Parkir']),
            'rules' => json_encode(['Jam malam 22:00', 'No pets']),
        ]);

        $response = $this->get("/kost/{$kost->id}");

        $response->assertStatus(200);
        $response->assertSee('WiFi');
    }

    // ─── Kost Model ───────────────────────────────────────────

    public function test_kost_scope_active(): void
    {
        $this->createKost(['name' => 'Active', 'status' => 'active']);
        $this->createKost(['name' => 'Inactive', 'status' => 'inactive']);

        $activeKosts = Kost::active()->get();

        $this->assertCount(1, $activeKosts);
        $this->assertEquals('Active', $activeKosts->first()->name);
    }

    public function test_kost_scope_available(): void
    {
        $this->createKost(['name' => 'Available', 'available_rooms' => 3]);
        $this->createKost(['name' => 'Full', 'available_rooms' => 0]);

        $availableKosts = Kost::available()->get();

        $this->assertCount(1, $availableKosts);
        $this->assertEquals('Available', $availableKosts->first()->name);
    }

    public function test_kost_belongs_to_many_categories(): void
    {
        $kost = $this->createKost();
        $cat1 = Category::create(['name' => 'Putra', 'slug' => 'putra']);
        $cat2 = Category::create(['name' => 'Putri', 'slug' => 'putri']);

        $kost->categories()->attach([$cat1->id, $cat2->id]);

        $this->assertCount(2, $kost->categories);
    }

    public function test_kost_average_rating_attribute(): void
    {
        $kost = $this->createKost();
        $user = User::factory()->create();

        // Create rental and reviews
        $rental1 = \App\Models\Rental::create([
            'kost_id' => $kost->id,
            'user_id' => $user->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'duration_months' => 1,
            'total_price' => 1500000,
            'status' => 'approved',
        ]);
        $rental2 = \App\Models\Rental::create([
            'kost_id' => $kost->id,
            'user_id' => $user->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'duration_months' => 1,
            'total_price' => 1500000,
            'status' => 'approved',
        ]);

        \App\Models\Review::create([
            'user_id' => $user->id,
            'kost_id' => $kost->id,
            'rental_id' => $rental1->id,
            'rating' => 5,
            'comment' => 'Great',
        ]);
        \App\Models\Review::create([
            'user_id' => $user->id,
            'kost_id' => $kost->id,
            'rental_id' => $rental2->id,
            'rating' => 3,
            'comment' => 'OK',
        ]);

        $this->assertEquals(4.0, $kost->average_rating);
    }

    public function test_kost_returns_null_average_with_no_reviews(): void
    {
        $kost = $this->createKost();

        $this->assertNull($kost->average_rating);
    }
}
