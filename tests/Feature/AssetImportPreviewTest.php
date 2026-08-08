<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AssetImportPreviewTest extends TestCase
{
    public function test_preview_page_renders_rows_from_session_payload(): void
    {
        $user = new User([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'assets_import_preview' => [
                    'previewRows' => [
                        [
                            'asset_code' => 'AST-0001',
                            'asset_name' => 'Patient Monitor',
                            'brand' => 'Mindray',
                            'type' => 'MEC-1000',
                            'serial_number' => 'AQ-45206980',
                            'room' => 'Ambulance dan Unit 119',
                            'procurement_year' => '2014-01-01',
                            'status' => 'berfungsi',
                            'description' => 'Butuh aksesoris, batrai',
                        ],
                    ],
                    'summary' => [
                        'imported' => 0,
                        'skipped' => 0,
                        'duplicates' => 0,
                        'failed' => 0,
                    ],
                    'errors' => [],
                ],
            ])
            ->get(route('assets.import.preview'));

        $response->assertOk();
        $response->assertSee('Patient Monitor');
        $response->assertSee('AQ-45206980');
    }
}
