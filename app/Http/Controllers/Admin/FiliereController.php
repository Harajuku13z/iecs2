<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Specialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FiliereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filieres = Filiere::with('specialites', 'classes')->orderBy('nom')->paginate(15);
        return view('admin.filieres.index', compact('filieres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.filieres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'filiere-' . time() . '-' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            $validated['image'] = $filename;
        }

        $filiere = Filiere::create($validated);

        // Gérer les spécialités
        if ($request->has('specialites')) {
            foreach ($request->specialites as $specialiteData) {
                if (!empty($specialiteData['nom'])) {
                    $filiere->specialites()->create([
                        'nom' => $specialiteData['nom'],
                        'description' => $specialiteData['description'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Filiere $filiere)
    {
        return view('admin.filieres.show', compact('filiere'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filiere $filiere)
    {
        $filiere->load('specialites');
        return view('admin.filieres.edit', compact('filiere'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Filiere $filiere)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($filiere->image && Storage::disk('public')->exists($filiere->image)) {
                Storage::disk('public')->delete($filiere->image);
            }
            
            $file = $request->file('image');
            $filename = 'filiere-' . time() . '-' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            $validated['image'] = $filename;
        }

        $filiere->update($validated);

        // Gérer les spécialités
        // Supprimer les spécialités existantes
        $filiere->specialites()->delete();
        
        // Créer les nouvelles spécialités
        if ($request->has('specialites')) {
            foreach ($request->specialites as $specialiteData) {
                if (!empty($specialiteData['nom'])) {
                    $filiere->specialites()->create([
                        'nom' => $specialiteData['nom'],
                        'description' => $specialiteData['description'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filiere $filiere)
    {
        $filiere->delete();

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière supprimée avec succès.');
    }
}
