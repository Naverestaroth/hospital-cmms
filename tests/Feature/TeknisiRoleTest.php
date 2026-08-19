<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Asset;
use App\Models\Vendor;
use App\Models\Technician;
use Database\Seeders\TeknisiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeknisiRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_teknisi_seeder_creates_permanent_account()
    {
        $this->seed(TeknisiSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => env('TEKNISI_EMAIL', 'teknisi@hospital.com'),
            'role' => 'teknisi',
        ]);
    }

    public function test_teknisi_can_access_dashboard()
    {
        $user = User::factory()->create([
            'role' => 'teknisi',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_teknisi_has_read_only_access_to_assets()
    {
        $user = User::factory()->create([
            'role' => 'teknisi',
        ]);

        $asset = Asset::create([
            'asset_code' => 'AST-001',
            'asset_name' => 'Test Asset',
            'room' => 'Room 1',
            'status' => 'berfungsi',
        ]);


        // Can view asset index and show
        $this->actingAs($user)->get('/assets')->assertStatus(200);
        $this->actingAs($user)->get("/assets/{$asset->id}")->assertStatus(200);

        // Cannot create, store, edit, update, destroy, import assets
        $this->actingAs($user)->get('/assets/create')->assertStatus(403);
        $this->actingAs($user)->post('/assets', [
            'asset_code' => 'AST-999',
            'asset_name' => 'Test Asset',
            'room' => 'Room 1',
            'status' => 'berfungsi'
        ])->assertStatus(403);
        $this->actingAs($user)->get("/assets/{$asset->id}/edit")->assertStatus(403);
        $this->actingAs($user)->put("/assets/{$asset->id}", ['asset_name' => 'Updated'])->assertStatus(403);
        $this->actingAs($user)->delete("/assets/{$asset->id}")->assertStatus(403);
        $this->actingAs($user)->get('/assets/import')->assertStatus(403);
    }

    public function test_teknisi_has_read_only_access_to_vendors()
    {
        $user = User::factory()->create([
            'role' => 'teknisi',
        ]);

        $vendor = Vendor::create([
            'vendor_code' => 'VND-001',
            'vendor_name' => 'Test Vendor',
            'contact_person' => 'John',
            'phone' => '08123456789',
            'email' => 'vendor@test.com',
        ]);

        // Can view vendor index and show
        $this->actingAs($user)->get('/vendors')->assertStatus(200);
        $this->actingAs($user)->get("/vendors/{$vendor->id}")->assertStatus(200);

        // Cannot create, store, edit, update, destroy vendors
        $this->actingAs($user)->get('/vendors/create')->assertStatus(403);
        $this->actingAs($user)->post('/vendors', [
            'vendor_code' => 'VND-002',
            'vendor_name' => 'New Vendor',
            'contact_person' => 'Jane',
            'phone' => '08123456780',
            'email' => 'vendor2@test.com',
        ])->assertStatus(403);
        $this->actingAs($user)->get("/vendors/{$vendor->id}/edit")->assertStatus(403);
        $this->actingAs($user)->put("/vendors/{$vendor->id}", ['vendor_name' => 'Updated'])->assertStatus(403);
        $this->actingAs($user)->delete("/vendors/{$vendor->id}")->assertStatus(403);
    }

    public function test_teknisi_cannot_perform_admin_wipe()
    {
        $user = User::factory()->create([
            'role' => 'teknisi',
        ]);

        $this->actingAs($user)->post('/settings/wipe', ['targets' => ['assets']])->assertStatus(403);
    }

    public function test_teknisi_sees_correct_navigation_labels()
    {
        $user = User::factory()->create([
            'role' => 'teknisi',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Jadwal');
        $response->assertSee('Laporan Kerja');
    }
}
