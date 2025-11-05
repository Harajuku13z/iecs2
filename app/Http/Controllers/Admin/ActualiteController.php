<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'categorie' => 'required|string|max:255',
            'publie' => 'nullable|in:1,0,on',
            'date_publication' => 'nullable|date',
        ]);
        
        // Utiliser la date actuelle si non fournie
        if (empty($validated['date_publication'])) {
            $validated['date_publication'] = now()->format('Y-m-d');
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'actualite-' . time() . '-' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            $validated['image'] = $filename;
        }

        $validated['publie'] = $request->has('publie') ? true : false;

        $actualite = Actualite::create($validated);

        // Créer une notification pour tous les étudiants si l'article est publié
        if ($actualite->publie) {
            $etudiants = \App\Models\User::where('role', 'etudiant')->get();
            foreach ($etudiants as $etudiant) {
                \App\Models\Notification::create([
                    'titre' => 'Nouvel article : ' . $actualite->titre,
                    'contenu' => 'Un nouvel article a été publié : ' . $actualite->titre . '. ' . Str::limit($actualite->description, 100),
                    'type' => 'info',
                    'destinataire_type' => 'user',
                    'user_id' => $etudiant->id,
                    'sender_id' => auth()->id(),
                    'envoye_email' => false,
                ]);
            }
        }

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
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'required|string',
                'contenu' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'categorie' => 'required|string|max:255',
                'publie' => 'nullable|in:1,0,on',
                'date_publication' => 'nullable|date',
            ]);
            
            // Utiliser la date actuelle si non fournie
            if (empty($validated['date_publication'])) {
                $validated['date_publication'] = now()->format('Y-m-d');
            }

            // Gérer l'upload de l'image
            if ($request->hasFile('image')) {
                try {
                    // Supprimer l'ancienne image si elle existe
                    if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                        Storage::disk('public')->delete($actualite->image);
                    }
                    
                    $file = $request->file('image');
                    $filename = 'actualite-' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('', $filename, 'public');
                    
                    if ($path) {
                        $validated['image'] = $filename;
                    } else {
                        return back()->with('error', 'Erreur lors de l\'upload de l\'image. Le fichier n\'a pas pu être sauvegardé.')->withInput();
                    }
                } catch (\Exception $e) {
                    \Log::error('Erreur upload image actualité: ' . $e->getMessage());
                    return back()->with('error', 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage())->withInput();
                }
            } else {
                // Conserver l'image existante si aucune nouvelle image n'est fournie
                $validated['image'] = $actualite->image;
            }

            $validated['publie'] = $request->has('publie') ? true : false;

            $actualite->update($validated);

            return redirect()->route('admin.actualites.index')
                ->with('success', 'Actualité mise à jour avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Erreur modification actualité: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Une erreur est survenue lors de la modification: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Actualite $actualite)
    {
        // Supprimer l'image si elle existe
        if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
            Storage::disk('public')->delete($actualite->image);
        }

        $actualite->delete();

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualité supprimée avec succès.');
    }
}
