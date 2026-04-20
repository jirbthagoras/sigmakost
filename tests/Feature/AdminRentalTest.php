<?php

namespace Tests\Feature;

use App\Models\Kost;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRentalTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;
    private Kost $kost;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
        $this->kost = Kost::create([
            'name' => 'Test Kost',
            'description' => 'Desc',
            'address' => 'Addr',
            'contact_number' => '08123',
            'price_per_month' => 1000000,
            'room_count' => 10,
            'available_rooms' => 5,
            'facilities' => json_encode([]),
            'rules' => json_encode([]),
            'status' => 'active',
            'created_by' => $this->admin->id,
        ]);
    }

    // ─── Index ────────────────────────────────────────────────

    public function test_admin_can_view_rental_requests(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/requests');
        $response->assertStatus(200);
    }

    // ─── Approve ──────────────────────────────────────────────

    public function test_admin_can_approve_pending_rental(): void
    {
        $rental = Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addMonths(3)->addDays(7),
            'duration_months' => 3,
            'total_price' => 3000000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/requests/{$rental->id}/status", [
            'status' => 'approved',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $rental->refresh();
        $this->assertEquals('approved', $rental->status);
        $this->assertEquals($this->admin->id, $rental->approved_by);
        $this->assertNotNull($rental->approved_at);
    }

    public function test_approval_decrements_available_rooms(): void
    {
        $originalAvailable = $this->kost->available_rooms;

        $rental = Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addMonths(1)->addDays(7),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)->patch("/admin/requests/{$rental->id}/status", [
            'status' => 'approved',
        ]);

        $this->kost->refresh();
        $this->assertEquals($originalAvailable - 1, $this->kost->available_rooms);
    }

    public function test_approval_generates_monthly_payments(): void
    {
        $rental = Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addMonths(3)->addDays(7),
            'duration_months' => 3,
            'total_price' => 3000000,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)->patch("/admin/requests/{$rental->id}/status", [
            'status' => 'approved',
        ]);

        $payments = Payment::where('rental_id', $rental->id)->get();
        $this->assertCount(3, $payments);

        foreach ($payments as $payment) {
            $this->assertEquals(1000000, $payment->amount);
            $this->assertEquals('unpaid', $payment->status);
            $this->assertEquals($this->user->id, $payment->user_id);
        }
    }

    // ─── Reject ───────────────────────────────────────────────

    public function test_admin_can_reject_pending_rental(): void
    {
        $rental = Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addMonths(1)->addDays(7),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/requests/{$rental->id}/status", [
            'status' => 'rejected',
            'rejection_reason' => 'No rooms available',
        ]);

        $response->assertRedirect();

        $rental->refresh();
        $this->assertEquals('rejected', $rental->status);
        $this->assertEquals('No rooms available', $rental->rejection_reason);
    }

    public function test_rejection_requires_reason(): void
    {
        $rental = Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addMonths(1)->addDays(7),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/requests/{$rental->id}/status", [
            'status' => 'rejected',
        ]);

        $response->assertSessionHasErrors('rejection_reason');
    }

    public function test_rejection_does_not_decrement_rooms(): void
    {
        $originalAvailable = $this->kost->available_rooms;

        $rental = Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addMonths(1)->addDays(7),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)->patch("/admin/requests/{$rental->id}/status", [
            'status' => 'rejected',
            'rejection_reason' => 'Not available',
        ]);

        $this->kost->refresh();
        $this->assertEquals($originalAvailable, $this->kost->available_rooms);
    }

    public function test_rejection_does_not_generate_payments(): void
    {
        $rental = Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addMonths(1)->addDays(7),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)->patch("/admin/requests/{$rental->id}/status", [
            'status' => 'rejected',
            'rejection_reason' => 'No rooms',
        ]);

        $this->assertCount(0, Payment::where('rental_id', $rental->id)->get());
    }

    // ─── Validation ───────────────────────────────────────────

    public function test_status_must_be_approved_or_rejected(): void
    {
        $rental = Rental::create([
            'kost_id' => $this->kost->id,
            'user_id' => $this->user->id,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addMonths(1)->addDays(7),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/requests/{$rental->id}/status", [
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');
    }
}
