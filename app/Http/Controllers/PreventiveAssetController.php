<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class PreventiveAssetController extends Controller
{
    public function assetsByRoom(Request $request)
    {
        $request->validate([
            'room' => 'nullable|string|max:255',
        ]);

        $room = $request->query('room');

        if (empty($room)) {
            return response()->json([
                'data' => [],
            ]);
        }

        $assets = Asset::query()
            ->where('room', $room)
            ->orderBy('asset_code')
            ->get([
                'id',
                'asset_code',
                'asset_name',
                'brand',
                'type',
                'serial_number',
                'procurement_year',
            ]);

        $data = $assets->map(function (Asset $asset) {
            $brand = $asset->brand ?? '';
            $serial = $asset->serial_number ?? '';

            return [
                'id' => $asset->id,
                'label' => sprintf(
                    '%s • %s • %s • %s',
                    $asset->asset_code,
                    $asset->asset_name,
                    $brand,
                    $serial
                ),
                'asset_code' => $asset->asset_code,
                'asset_name' => $asset->asset_name,
                'brand' => $asset->brand,
                'type' => $asset->type,
                'serial_number' => $asset->serial_number,
                // simpan di DB sebagai date (YYYY-01-01), tapi UI inputnya tahun saja (YYYY)
                'procurement_year' => $asset->procurement_year,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

    public function assetDetail(Asset $asset)
    {
        return response()->json([
            'data' => [
                'id' => $asset->id,
                'asset_code' => $asset->asset_code,
                'asset_name' => $asset->asset_name,
                'brand' => $asset->brand,
                'type' => $asset->type,
                'serial_number' => $asset->serial_number,
                // tahun saja
                'procurement_year' => $asset->procurement_year,
            ],
        ]);
    }
}

