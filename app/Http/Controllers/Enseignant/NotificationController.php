<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Récupérer les classes où l'enseignant enseigne
        $classes = Classe::whereHas('cours', function($query) use ($user) {
            $query->where('cours_user.user_id', $user->id);
        })->with('filiere', 'niveau')->get();
        
        // Notifications envoyées par cet enseignant
        $notificationsEnvoyees = Notification::where('sender_id', $user->id)
            ->with('classe')
            ->latest()
            ->paginate(20);
        
        return view('enseignant.notifications.index', compact('classes', 'notificationsEnvoyees'));
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        
        // Récupérer les classes où l'enseignant enseigne
        $classes = Classe::whereHas('cours', function($query) use ($user) {
            $query->where('cours_user.user_id', $user->id);
        })->with('filiere', 'niveau')->get();
        
        return view('enseignant.notifications.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'type' => 'required|in:info,warning,success,danger',
            'destinataire_type' => 'required|in:classe',
            'classe_id' => 'required|exists:classes,id',
        ]);

        $user = $request->user();
        
        // Vérifier que l'enseignant enseigne dans cette classe
        $classe = Classe::findOrFail($validated['classe_id']);
        $coursEnseigne = $user->cours()->whereHas('classes', function($query) use ($classe) {
            $query->where('classe_cours.classe_id', $classe->id);
        })->get();
        
        if ($coursEnseigne->isEmpty()) {
            return back()->withErrors(['classe_id' => 'Vous n\'enseignez pas dans cette classe.'])->withInput();
        }

        // Créer la notification pour tous les étudiants de la classe
        $etudiants = $classe->etudiants()->get();
        
        foreach ($etudiants as $etudiant) {
            Notification::create([
                'titre' => $validated['titre'],
                'contenu' => $validated['contenu'],
                'type' => $validated['type'],
                'destinataire_type' => 'user',
                'user_id' => $etudiant->id,
                'classe_id' => $validated['classe_id'],
                'sender_id' => $user->id,
                'envoye_email' => false,
            ]);
        }

        return redirect()->route('enseignant.notifications.index')
            ->with('success', 'Notification envoyée à ' . $etudiants->count() . ' étudiant(s).');
    }
}
