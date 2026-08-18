<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetRoomFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_filter_assets_by_unique_room_name()
    {
        $user = User::factory()->create();

        Asset::create([
            'asset_code' => 'AST-ICU-01',
            'asset_name' => 'Patient Monitor ICU',
            'room' => 'ICU',
            'status' => 'berfungsi',
        ]);

        Asset::create([
            'asset_code' => 'AST-RAD-01',
            'asset_name' => 'X-Ray Machine',
            'room' => 'Radiologi',
            'status' => 'berfungsi',
        ]);

        $response = $this->actingAs($user)->get(route('assets.index', ['room' => 'ICU']));

        $response->assertStatus(200);
        $response->assertSee('Patient Monitor ICU');
        $response->assertDontSee('X-Ray Machine');

        $allResponse = $this->actingAs($user)->get(route('assets.index', ['view' => 'room']));
        $allResponse->assertStatus(200);
        $allResponse->assertSee('Semua Ruangan');
        $allResponse->assertSee('ICU');
        $allResponse->assertSee('Radiologi');
    }
}
