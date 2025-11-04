<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $evenements = Evenement::orderBy('date_debut', 'desc')->paginate(15);
        return view('admin.evenements.index', compact('evenements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.evenements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'lieu' => 'nullable|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|string|max:255',
            'publie' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'evenement-' . time() . '-' . $file->getClientOriginalName();
            $path = $file->storeAs('public', $filename);
            $validated['image'] = $filename;
        }

        $validated['publie'] = $request->has('publie') ? true : false;
        $validated['date_debut'] = $request->date_debut . ' ' . ($request->heure_debut ?? '00:00');
        if ($request->date_fin) {
            $validated['date_fin'] = $request->date_fin . ' ' . ($request->heure_fin ?? '23:59');
        }

        Evenement::create($validated);

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Evenement $evenement)
    {
        return view('admin.evenements.show', compact('evenement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evenement $evenement)
    {
        return view('admin.evenements.edit', compact('evenement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evenement $evenement)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'lieu' => 'nullable|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|string|max:255',
            'publie' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($evenement->image && Storage::exists('public/' . $evenement->image)) {
                Storage::delete('public/' . $evenement->image);
            }
            
            $file = $request->file('image');
            $filename = 'evenement-' . time() . '-' . $file->getClientOriginalName();
            $path = $file->storeAs('public', $filename);
            $validated['image'] = $filename;
        }

        $validated['publie'] = $request->has('publie') ? true : false;
        $validated['date_debut'] = $request->date_debut . ' ' . ($request->heure_debut ?? '00:00');
        if ($request->date_fin) {
            $validated['date_fin'] = $request->date_fin . ' ' . ($request->heure_fin ?? '23:59');
        }

        $evenement->update($validated);

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evenement $evenement)
    {
        // Supprimer l'image si elle existe
        if ($evenement->image && Storage::exists('public/' . $evenement->image)) {
            Storage::delete('public/' . $evenement->image);
        }

        $evenement->delete();

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement supprimé avec succès.');
    }
}
