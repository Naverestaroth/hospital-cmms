<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortableColumns = ['part_code', 'part_name', 'stock', 'unit', 'location'];
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        if (!in_array($sortField, $sortableColumns) && $sortField !== 'created_at') {
            $sortField = 'created_at';
        }

        $spareparts = Sparepart::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('part_code', 'like', "%{$search}%")
                        ->orWhere('part_name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate(15)
            ->withQueryString();

        return view('spareparts.index', compact('spareparts', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('spareparts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'part_code' => 'required|unique:spareparts',

            'part_name' => 'required',

            'stock' => 'required|integer',

            'unit' => 'required',

            'location' => 'nullable',

        ]);

        Sparepart::create($request->all());

        return redirect()

            ->route('spareparts.index')

            ->with('success', 'Sparepart created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sparepart $sparepart)
    {
        return view('spareparts.show', compact('sparepart'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sparepart $sparepart)
    {
        return view('spareparts.edit', compact('sparepart'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sparepart $sparepart)
    {
        $validated = $request->validate([
            'part_code' => 'required|unique:spareparts,part_code,' . $sparepart->id,
            'part_name' => 'required',
            'stock' => 'required|integer',
            'unit' => 'required',
            'location' => 'nullable',
        ]);

        $sparepart->update($validated);

        return redirect()
            ->route('spareparts.index')
            ->with('success', 'Sparepart updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sparepart $sparepart)
    {
        $sparepart->delete();

        return redirect()
            ->route('spareparts.index')
            ->with('success', 'Sparepart deleted successfully.');
    }
}
