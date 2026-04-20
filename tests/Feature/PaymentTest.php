<?php

namespace Tests\Feature;

use App\Models\Kost;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;
    private Rental $rental;
    private Payment $payment;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);

        $kost = Kost::create([
            'name' => 'Test Kost',
            'description' => 'Desc',
            'address' => 'Addr',
            'contact_number' => '08123',
            'price_per_month' => 1000000,
            'room_count' => 10,
            'available_rooms' => 5,
            'facilities' => json_encode(['WiFi']),
            'rules' => json_encode([]),
            'status' => 'active',
            'created_by' => $this->admin->id,
        ]);

        $this->rental = Rental::create([
            'kost_id' => $kost->id,
            'user_id' => $this->user->id,
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'duration_months' => 3,
            'total_price' => 3000000,
            'status' => 'approved',
        ]);

        $this->payment = Payment::create([
            'rental_id' => $this->rental->id,
            'user_id' => $this->user->id,
            'amount' => 1000000,
            'due_date' => now()->addDays(7),
            'period_month' => now()->month,
            'period_year' => now()->year,
            'status' => 'unpaid',
        ]);
    }

    // ─── User Payment ─────────────────────────────────────────

    public function test_user_can_view_payments_page(): void
    {
        $response = $this->actingAs($this->user)->get('/my-payments');

        $response->assertStatus(200);
    }

    public function test_user_can_upload_payment_proof(): void
    {
        $response = $this->actingAs($this->user)->post("/payments/{$this->payment->id}/pay", [
            'payment_proof' => UploadedFile::fake()->image('proof.jpg', 800, 600),
            'payment_method' => 'transfer_bank',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->payment->refresh();
        $this->assertEquals('paid', $this->payment->status);
        $this->assertNotNull($this->payment->payment_proof);
        $this->assertEquals('transfer_bank', $this->payment->payment_method);
    }

    public function test_payment_proof_file_is_stored(): void
    {
        $this->actingAs($this->user)->post("/payments/{$this->payment->id}/pay", [
            'payment_proof' => UploadedFile::fake()->image('proof.png'),
            'payment_method' => 'e_wallet',
        ]);

        $this->payment->refresh();
        Storage::disk('public')->assertExists($this->payment->payment_proof);
    }

    public function test_payment_rejects_invalid_file_type(): void
    {
        $response = $this->actingAs($this->user)->post("/payments/{$this->payment->id}/pay", [
            'payment_proof' => UploadedFile::fake()->create('document.docx', 100),
            'payment_method' => 'transfer_bank',
        ]);

        $response->assertSessionHasErrors('payment_proof');
    }

    public function test_payment_rejects_oversized_file(): void
    {
        $response = $this->actingAs($this->user)->post("/payments/{$this->payment->id}/pay", [
            'payment_proof' => UploadedFile::fake()->image('huge.jpg')->size(6000),
            'payment_method' => 'transfer_bank',
        ]);

        $response->assertSessionHasErrors('payment_proof');
    }

    public function test_payment_requires_payment_method(): void
    {
        $response = $this->actingAs($this->user)->post("/payments/{$this->payment->id}/pay", [
            'payment_proof' => UploadedFile::fake()->image('proof.jpg'),
        ]);

        $response->assertSessionHasErrors('payment_method');
    }

    public function test_payment_rejects_invalid_method(): void
    {
        $response = $this->actingAs($this->user)->post("/payments/{$this->payment->id}/pay", [
            'payment_proof' => UploadedFile::fake()->image('proof.jpg'),
            'payment_method' => 'bitcoin',
        ]);

        $response->assertSessionHasErrors('payment_method');
    }

    public function test_user_cannot_pay_already_paid_payment(): void
    {
        $this->payment->update(['status' => 'paid']);

        $response = $this->actingAs($this->user)->post("/payments/{$this->payment->id}/pay", [
            'payment_proof' => UploadedFile::fake()->image('proof.jpg'),
            'payment_method' => 'transfer_bank',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_user_cannot_pay_other_users_payment(): void
    {
        $otherUser = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($otherUser)->post("/payments/{$this->payment->id}/pay", [
            'payment_proof' => UploadedFile::fake()->image('proof.jpg'),
            'payment_method' => 'transfer_bank',
        ]);

        $response->assertStatus(403);
    }

    // ─── Admin Payment Verification ───────────────────────────

    public function test_admin_can_view_payments_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/payments');

        $response->assertStatus(200);
    }

    public function test_admin_can_verify_paid_payment(): void
    {
        $this->payment->update(['status' => 'paid']);

        $response = $this->actingAs($this->admin)->patch("/admin/payments/{$this->payment->id}/verify");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->payment->refresh();
        $this->assertEquals('verified', $this->payment->status);
        $this->assertEquals($this->admin->id, $this->payment->verified_by);
        $this->assertNotNull($this->payment->verified_at);
    }

    public function test_admin_cannot_verify_unpaid_payment(): void
    {
        $response = $this->actingAs($this->admin)->patch("/admin/payments/{$this->payment->id}/verify");

        $response->assertSessionHas('error');
        $this->assertEquals('unpaid', $this->payment->fresh()->status);
    }

    // ─── Payment Model ────────────────────────────────────────

    public function test_payment_is_paid_helper(): void
    {
        $this->assertFalse($this->payment->isPaid());

        $this->payment->status = 'paid';
        $this->assertTrue($this->payment->isPaid());

        $this->payment->status = 'verified';
        $this->assertTrue($this->payment->isPaid());
    }

    public function test_payment_is_overdue_helper(): void
    {
        $this->payment->due_date = now()->subDays(5);
        $this->payment->status = 'unpaid';
        $this->assertTrue($this->payment->isOverdue());

        $this->payment->due_date = now()->addDays(5);
        $this->assertFalse($this->payment->isOverdue());

        $this->payment->due_date = now()->subDays(5);
        $this->payment->status = 'paid';
        $this->assertFalse($this->payment->isOverdue());
    }

    public function test_payment_period_label_attribute(): void
    {
        $this->payment->period_month = 4;
        $this->payment->period_year = 2026;

        $this->assertEquals('April 2026', $this->payment->period_label);
    }

    public function test_payment_scopes(): void
    {
        $paid = Payment::create([
            'rental_id' => $this->rental->id,
            'user_id' => $this->user->id,
            'amount' => 1000000,
            'due_date' => now(),
            'period_month' => 1,
            'period_year' => 2026,
            'status' => 'paid',
        ]);
        $verified = Payment::create([
            'rental_id' => $this->rental->id,
            'user_id' => $this->user->id,
            'amount' => 1000000,
            'due_date' => now(),
            'period_month' => 2,
            'period_year' => 2026,
            'status' => 'verified',
        ]);

        $this->assertCount(1, Payment::unpaid()->get());
        $this->assertCount(1, Payment::paid()->get());
        $this->assertCount(1, Payment::verified()->get());
    }
}
