<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;

class AssetsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $search;
    protected $sortField;
    protected $sortDirection;

    public function __construct($search, $sortField, $sortDirection)
    {
        $this->search = $search;
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return Asset::query()
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('asset_name', 'like', "%{$search}%")
                        ->orWhere('asset_code', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Asset Code',
            'Asset Name',
            'Brand',
            'Type',
            'Serial Number',
            'Room',
            'Procurement Year',
            'Status',
            'Description',
        ];
    }

    /**
     * @param Asset $asset
     * @return array
     */
    public function map($asset): array
    {
        return [
            $asset->asset_code,
            $asset->asset_name,
            $asset->brand,
            $asset->type,
            $asset->serial_number,
            $asset->room,
            $asset->procurement_year ? date('Y', strtotime($asset->procurement_year)) : '',
            $asset->status,
            $asset->description,
        ];
    }
}