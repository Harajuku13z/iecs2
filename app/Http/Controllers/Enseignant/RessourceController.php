<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Ressource;
use App\Models\Cours;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RessourceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $ressources = $user->ressources()->with(['cours', 'classes'])->latest()->paginate(20);
        
        return view('enseignant.ressources.index', compact('ressources'));
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $cours = $user->cours()->with('classes')->get();
        
        return view('enseignant.ressources.create', compact('cours'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:pdf,word,lien,video,image',
            'cours_id' => 'required|exists:cours,id',
            'classes_ids' => 'required|array|min:1',
            'classes_ids.*' => 'exists:classes,id',
            'contenu' => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar,jpg,jpeg,png,gif,mp4,avi|max:20480',
            'lien' => 'nullable|url|max:500',
            'description' => 'nullable|string',
        ]);

        $user = $request->user();
        
        // Vérifier que l'enseignant est assigné à ce cours
        if (!$user->cours->contains($validated['cours_id'])) {
            return back()->withErrors(['cours_id' => 'Vous n\'êtes pas assigné à ce cours.'])->withInput();
        }

        $cours = Cours::findOrFail($validated['cours_id']);
        
        // Vérifier que les classes sont associées au cours
        $classesIds = $validated['classes_ids'];
        $coursClassesIds = $cours->classes->pluck('id')->toArray();
        foreach ($classesIds as $classeId) {
            if (!in_array($classeId, $coursClassesIds)) {
                return back()->withErrors(['classes_ids' => 'Une ou plusieurs classes ne sont pas associées à ce cours.'])->withInput();
            }
        }

        $contenuPath = null;
        $lien = null;
        
        // Gérer selon le type
        if ($validated['type'] === 'lien') {
            $lien = $validated['lien'];
        } elseif ($request->hasFile('contenu')) {
            $contenuPath = $request->file('contenu')->store('ressources', 'public');
        }

        // Créer la ressource
        $ressource = Ressource::create([
            'titre' => $validated['titre'],
            'type' => $validated['type'],
            'cours_id' => $validated['cours_id'],
            'classe_id' => $classesIds[0], // Garder pour compatibilité
            'user_id' => $user->id,
            'contenu' => $contenuPath,
            'lien' => $lien,
            'description' => $validated['description'] ?? null,
        ]);

        // Attacher à toutes les classes sélectionnées
        $ressource->classes()->sync($classesIds);

        return redirect()->route('enseignant.ressources.index')->with('success', 'Ressource créée avec succès.');
    }

    public function edit(Ressource $ressource): View
    {
        $user = request()->user();
        
        // Vérifier que l'enseignant est le propriétaire de la ressource
        if ($ressource->user_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette ressource.');
        }
        
        $cours = $user->cours()->with('classes')->get();
        $ressource->load('classes');
        
        return view('enseignant.ressources.edit', compact('ressource', 'cours'));
    }

    public function update(Request $request, Ressource $ressource): RedirectResponse
    {
        $user = $request->user();
        
        // Vérifier que l'enseignant est le propriétaire de la ressource
        if ($ressource->user_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette ressource.');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:pdf,word,lien,video,image',
            'cours_id' => 'required|exists:cours,id',
            'classes_ids' => 'required|array|min:1',
            'classes_ids.*' => 'exists:classes,id',
            'contenu' => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar,jpg,jpeg,png,gif,mp4,avi|max:20480',
            'lien' => 'nullable|url|max:500',
            'description' => 'nullable|string',
        ]);

        // Vérifier que l'enseignant est assigné à ce cours
        if (!$user->cours->contains($validated['cours_id'])) {
            return back()->withErrors(['cours_id' => 'Vous n\'êtes pas assigné à ce cours.'])->withInput();
        }

        $cours = Cours::findOrFail($validated['cours_id']);
        $classesIds = $validated['classes_ids'];
        $coursClassesIds = $cours->classes->pluck('id')->toArray();
        foreach ($classesIds as $classeId) {
            if (!in_array($classeId, $coursClassesIds)) {
                return back()->withErrors(['classes_ids' => 'Une ou plusieurs classes ne sont pas associées à ce cours.'])->withInput();
            }
        }

        $data = [
            'titre' => $validated['titre'],
            'type' => $validated['type'],
            'cours_id' => $validated['cours_id'],
            'classe_id' => $classesIds[0],
            'description' => $validated['description'] ?? null,
        ];

        if ($validated['type'] === 'lien') {
            $data['lien'] = $validated['lien'];
            if ($ressource->contenu && Storage::disk('public')->exists($ressource->contenu)) {
                Storage::disk('public')->delete($ressource->contenu);
            }
            $data['contenu'] = null;
        } elseif ($request->hasFile('contenu')) {
            // Supprimer l'ancien fichier
            if ($ressource->contenu && Storage::disk('public')->exists($ressource->contenu)) {
                Storage::disk('public')->delete($ressource->contenu);
            }
            $data['contenu'] = $request->file('contenu')->store('ressources', 'public');
            $data['lien'] = null;
        }

        $ressource->update($data);
        
        // Synchroniser les classes
        $ressource->classes()->sync($classesIds);

        return redirect()->route('enseignant.ressources.index')->with('success', 'Ressource mise à jour avec succès.');
    }

    public function destroy(Ressource $ressource): RedirectResponse
    {
        $user = request()->user();
        
        // Vérifier que l'enseignant est le propriétaire de la ressource
        if ($ressource->user_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette ressource.');
        }

        // Supprimer le fichier
        if ($ressource->contenu && Storage::disk('public')->exists($ressource->contenu)) {
            Storage::disk('public')->delete($ressource->contenu);
        }

        $ressource->delete();

        return redirect()->route('enseignant.ressources.index')->with('success', 'Ressource supprimée avec succès.');
    }
}
