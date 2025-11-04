<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActualiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $actualites = Actualite::orderBy('date_publication', 'desc')->paginate(15);
        return view('admin.actualites.index', compact('actualites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.actualites.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categorie' => 'required|string|max:255',
            'publie' => 'boolean',
            'date_publication' => 'required|date',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'actualite-' . time() . '-' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            $validated['image'] = $filename;
        }

        $validated['publie'] = $request->has('publie') ? true : false;

        Actualite::create($validated);

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualité créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Actualite $actualite)
    {
        return view('admin.actualites.show', compact('actualite'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Actualite $actualite)
    {
        return view('admin.actualites.edit', compact('actualite'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Actualite $actualite)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categorie' => 'required|string|max:255',
            'publie' => 'boolean',
            'date_publication' => 'required|date',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($actualite->image && Storage::exists('public/' . $actualite->image)) {
                Storage::delete('public/' . $actualite->image);
            }
            
            $file = $request->file('image');
            $filename = 'actualite-' . time() . '-' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            $validated['image'] = $filename;
        }

        $validated['publie'] = $request->has('publie') ? true : false;

        $actualite->update($validated);

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualité mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Actualite $actualite)
    {
        // Supprimer l'image si elle existe
        if ($actualite->image && Storage::exists('public/' . $actualite->image)) {
            Storage::delete('public/' . $actualite->image);
        }

        $actualite->delete();

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualité supprimée avec succès.');
    }
}
