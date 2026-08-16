<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Asset;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortableColumns = ['document_code', 'title', 'document_type', 'issue_date'];
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        if (!in_array($sortField, $sortableColumns) && $sortField !== 'created_at') {
            $sortField = 'created_at';
        }

        $documents = Document::with('asset')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('document_code', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('document_type', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate(15)
            ->withQueryString();

        return view('documents.index', compact('documents', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::orderBy('asset_name')->get();
        return view('documents.create', compact('assets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->filled('issue_date')) {
            $val = $request->input('issue_date');
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $val)) {
                $parts = explode('/', $val);
                $request->merge(['issue_date' => "{$parts[2]}-{$parts[1]}-{$parts[0]}"]);
            }
        }

        if ($request->filled('expiry_date')) {
            $val = $request->input('expiry_date');
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $val)) {
                $parts = explode('/', $val);
                $request->merge(['expiry_date' => "{$parts[2]}-{$parts[1]}-{$parts[0]}"]);
            }
        }

        $request->validate([

            'document_code' => 'required|unique:documents',

            'title' => 'required',

            'document_type' => 'required',

            'asset_id' => 'nullable|exists:assets,id',

            'revision' => 'nullable',

            'expiry_date' => 'nullable|date',

            'expiry_date' => 'nullable|date',

            'description' => 'nullable',

            'file' => 'nullable|file|mimes:pdf|max:10240',

        ]);

        $filePath = null;

        if ($request->hasFile('file')) {

            $filePath = $request
                ->file('file')
                ->store('documents', 'public');
        }

        Document::create([

            'document_code' => $request->document_code,

            'title' => $request->title,

            'document_type' => $request->document_type,

            'asset_id' => $request->asset_id,

            'revision' => $request->revision,

            'issue_date' => $request->issue_date,

            'expiry_date' => $request->expiry_date,

            'description' => $request->description,

            'file_path' => $filePath,

        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $assets = Asset::orderBy('asset_name')->get();
        return view('documents.edit', compact('document', 'assets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $request->validate([

            'document_code' => 'required|unique:documents,document_code,' . $document->id,

            'title' => 'required',

            'document_type' => 'required',

            'asset_id' => 'nullable|exists:assets,id',

            'revision' => 'nullable',

            'issue_date' => 'required|date',

            'expiry_date' => 'nullable|date',

            'description' => 'nullable',

            'file' => 'nullable|file|mimes:pdf|max:10240',

        ]);

        if ($request->hasFile('file')) {

            $document->file_path = $request
                ->file('file')
                ->store('documents', 'public');
        }

        $document->update([

            'document_code' => $request->document_code,

            'title' => $request->title,

            'document_type' => $request->document_type,

            'asset_id' => $request->asset_id,

            'revision' => $request->revision,

            'issue_date' => $request->issue_date,

            'expiry_date' => $request->expiry_date,

            'description' => $request->description,

            'file_path' => $document->file_path,

        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document updated successfully.');
    }

    public function view(Document $document)
    {
        if (!$document->file_path) {
            abort(404);
        }

        return response()->file(
            storage_path('app/public/' . $document->file_path)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }
}
