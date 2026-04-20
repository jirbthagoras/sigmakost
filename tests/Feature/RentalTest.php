<?php

namespace Tests\Feature;

use App\Models\Kost;
use App\Models\Rental;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;
    private Kost $kost;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
        $this->kost = Kost::create([
            'name' => 'Test Kost',
            'description' => 'Description',
            'address' => 'Jl. Test',
            'contact_number' => '08123456',
            'price_per_month' => 1000000,
            'room_count' => 10,
            'available_rooms' => 5,
            'facilities' => json_encode(['WiFi']),
            'rules' => json_encode(['No pets']),
            'status' => 'active',
            'created_by' => $this->admin->id,
        ]);
    }

    // ─── Booking (User) ──────────────────────────────────────

    public function test_authenticated_user_can_book_kost(): void
    {
        $response = $this->actingAs($this->user)->post("/kost/{$this->kost->id}/book", [
            'start_date' => now()->addDays(7)->toDateString(),
            'duration_months' => 3,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('rentals', [
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'status' => 'pending',
            'duration_months' => 3,
        ]);
    }

    public function test_guest_cannot_book_kost(): void
    {
        $response = $this->post("/kost/{$this->kost->id}/book", [
            'start_date' => now()->addDays(7)->toDateString(),
            'duration_months' => 3,
        ]);

        $response->assertRedirect('/');
    }

    public function test_booking_validates_start_date_required(): void
    {
        $response = $this->actingAs($this->user)->post("/kost/{$this->kost->id}/book", [
            'duration_months' => 3,
        ]);

        $response->assertSessionHasErrors('start_date');
    }

    public function test_booking_validates_duration_required(): void
    {
        $response = $this->actingAs($this->user)->post("/kost/{$this->kost->id}/book", [
            'start_date' => now()->addDays(7)->toDateString(),
        ]);

        $response->assertSessionHasErrors('duration_months');
    }

    public function test_booking_calculates_total_price_correctly(): void
    {
        $this->actingAs($this->user)->post("/kost/{$this->kost->id}/book", [
            'start_date' => now()->addDays(7)->toDateString(),
            'duration_months' => 3,
        ]);

        $rental = Rental::first();
        $this->assertEquals(3000000, $rental->total_price);
    }

    public function test_booking_sets_end_date_correctly(): void
    {
        $startDate = now()->addDays(7)->toDateString();

        $this->actingAs($this->user)->post("/kost/{$this->kost->id}/book", [
            'start_date' => $startDate,
            'duration_months' => 3,
        ]);

        $rental = Rental::first();
        $expectedEnd = \Carbon\Carbon::parse($startDate)->addMonths(3)->toDateString();
        $this->assertEquals($expectedEnd, $rental->end_date->toDateString());
    }

    public function test_user_can_view_my_bookings(): void
    {
        Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'duration_months' => 3,
            'total_price' => 3000000,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->user)->get('/my-bookings');

        $response->assertStatus(200);
        $response->assertSee('Test Kost');
    }

    // ─── Rental Scopes ────────────────────────────────────────

    public function test_rental_scope_pending(): void
    {
        Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'pending',
        ]);
        Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'approved',
        ]);

        $this->assertCount(1, Rental::pending()->get());
    }

    public function test_rental_scope_approved(): void
    {
        Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'pending',
        ]);
        Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'approved',
        ]);

        $this->assertCount(1, Rental::approved()->get());
    }

    // ─── Review ───────────────────────────────────────────────

    public function test_user_can_review_approved_rental(): void
    {
        $rental = Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->user)->post("/kost/{$this->kost->id}/review", [
            'rating' => 5,
            'comment' => 'Great kost!',
            'rental_id' => $rental->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'kost_id' => $this->kost->id,
            'rating' => 5,
        ]);
    }

    public function test_user_cannot_review_same_rental_twice(): void
    {
        $rental = Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'approved',
        ]);

        Review::create([
            'user_id' => $this->user->id,
            'kost_id' => $this->kost->id,
            'rental_id' => $rental->id,
            'rating' => 4,
            'comment' => 'Good',
        ]);

        $response = $this->actingAs($this->user)->post("/kost/{$this->kost->id}/review", [
            'rating' => 5,
            'comment' => 'Duplicate!',
            'rental_id' => $rental->id,
        ]);

        $response->assertSessionHas('error');
        $this->assertCount(1, Review::where('rental_id', $rental->id)->get());
    }

    public function test_review_validates_rating_range(): void
    {
        $rental = Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->user)->post("/kost/{$this->kost->id}/review", [
            'rating' => 6,
            'comment' => 'Invalid rating',
            'rental_id' => $rental->id,
        ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_guest_cannot_submit_review(): void
    {
        $response = $this->post("/kost/{$this->kost->id}/review", [
            'rating' => 5,
            'comment' => 'Test',
            'rental_id' => 1,
        ]);

        $response->assertRedirect('/');
    }
}
