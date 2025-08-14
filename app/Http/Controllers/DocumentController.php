<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
     public function __construct()
    {
        // This single line connects all controller methods to their corresponding policy methods.
        // e.g., the `store()` method will automatically check the `create()` policy method.
        // the `edit()` method will automatically check the `update()` policy method.
        $this->authorizeResource(Document::class, 'document');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Document::class);
        $user = auth()->user()->load('invitedUsers');
        $familyMemberIds = $user->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        $documents = Document::whereIn('child_id', $children->pluck('id'))->get();
        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Document::class);
        $familyMemberIds = auth()->user()->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        $categories = ['Medical', 'Legal', 'School', 'Financial', 'Other'];
        return view('documents.create', compact('children', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Document::class);
        $validatedData = $request->validate([
            'child_id' => 'required|exists:children,id',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'file_url' => 'required|file|max:10240',
            'notes' => 'nullable|string',
        ]);

        $validatedData['uploaded_by'] = auth()->id();

        if ($request->hasFile('file_url')) {
            $path = $request->file('file_url')->store('documents', 'public');
            $validatedData['file_url'] = $path;
        }

        Document::create($validatedData);

        return redirect()->route('documents.index')->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $this->authorize('view', $document);
        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $this->authorize('update', $document);
        $familyMemberIds = auth()->user()->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        $categories = ['Medical', 'Legal', 'School', 'Financial', 'Other'];
        return view('documents.edit', compact('document', 'children', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validatedData = $request->validate([
            'child_id' => 'required|exists:children,id',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'file_url' => 'nullable|file|max:10240',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('file_url')) {
            // Delete old file if it exists
            if ($document->file_url) {
                Storage::disk('public')->delete($document->file_url);
            }
            $path = $request->file('file_url')->store('documents', 'public');
            $validatedData['file_url'] = $path;
        }

        $document->update($validatedData);

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        // Delete associated file if it exists
        if ($document->file_url) {
            Storage::disk('public')->delete($document->file_url);
        }

        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }
}
