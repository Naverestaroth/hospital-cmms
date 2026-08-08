<?php

namespace App\Http\Controllers;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortableColumns = ['vendor_code', 'vendor_name', 'contact_person', 'phone', 'email'];
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        if (!in_array($sortField, $sortableColumns) && $sortField !== 'created_at') {
            $sortField = 'created_at';
        }

        $vendors = Vendor::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('vendor_code', 'like', "%{$search}%")
                        ->orWhere('vendor_name', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate(15)
            ->withQueryString();

        return view('vendors.index', compact('vendors', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'vendor_code' => 'required|unique:vendors',

            'vendor_name' => 'required',

            'contact_person' => 'required|string|max:20',

            'phone' => 'required|string|max:99',

            'email' => 'required|email',

        ]);

        Vendor::create($request->all());

        return redirect()

            ->route('vendors.index')

            ->with('success', 'Vendor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        return view('vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'vendor_code' => 'required|unique:vendors,vendor_code,' . $vendor->id,
            'vendor_name' => 'required',
            'contact_person' => 'required|string|max:20',
            'phone' => 'required|string|max:99',
            'email' => 'required|email',
        ]);

        $vendor->update($validated);

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }
}
