<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Kost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    // ─── Index ────────────────────────────────────────────────

    public function test_admin_can_view_categories_list(): void
    {
        Category::create(['name' => 'Kost Putra', 'slug' => 'kost-putra']);

        $response = $this->actingAs($this->admin)->get('/admin/categories');

        $response->assertStatus(200);
        $response->assertSee('Kost Putra');
    }

    // ─── Create ───────────────────────────────────────────────

    public function test_admin_can_view_create_category_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/categories/create');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'Kost Putra',
            'description' => 'Kost khusus putra',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Kost Putra',
            'slug' => 'kost-putra',
        ]);
    }

    public function test_category_slug_is_auto_generated(): void
    {
        $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'Studio Room Premium',
        ]);

        $this->assertDatabaseHas('categories', [
            'slug' => 'studio-room-premium',
        ]);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::create(['name' => 'Kost Putra', 'slug' => 'kost-putra']);

        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'Kost Putra',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_category_name_is_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'description' => 'No name provided',
        ]);

        $response->assertSessionHasErrors('name');
    }

    // ─── Update ───────────────────────────────────────────────

    public function test_admin_can_update_category(): void
    {
        $category = Category::create(['name' => 'Old Name', 'slug' => 'old-name']);

        $response = $this->actingAs($this->admin)->put("/admin/categories/{$category->id}", [
            'name' => 'New Name',
            'description' => 'Updated description',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'New Name',
            'slug' => 'new-name',
        ]);
    }

    public function test_admin_can_update_category_keeping_same_name(): void
    {
        $category = Category::create(['name' => 'Same Name', 'slug' => 'same-name']);

        $response = $this->actingAs($this->admin)->put("/admin/categories/{$category->id}", [
            'name' => 'Same Name',
            'description' => 'New desc only',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
    }

    // ─── Delete ───────────────────────────────────────────────

    public function test_admin_can_delete_unused_category(): void
    {
        $category = Category::create(['name' => 'To Delete', 'slug' => 'to-delete']);

        $response = $this->actingAs($this->admin)->delete("/admin/categories/{$category->id}");

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_category_with_kosts(): void
    {
        $category = Category::create(['name' => 'In Use', 'slug' => 'in-use']);
        $kost = Kost::create([
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
        $kost->categories()->attach($category->id);

        $response = $this->actingAs($this->admin)->delete("/admin/categories/{$category->id}");

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    // ─── Show ─────────────────────────────────────────────────

    public function test_admin_can_view_category_detail(): void
    {
        $category = Category::create(['name' => 'Detail Test', 'slug' => 'detail-test']);

        $response = $this->actingAs($this->admin)->get("/admin/categories/{$category->id}");

        $response->assertStatus(200);
        $response->assertSee('Detail Test');
    }
}
