<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RessourceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $candidature = $user->candidature;
        
        // Vérifier que la candidature est validée
        if (!$candidature || $candidature->statut !== 'admis') {
            return redirect()->route('etudiant.dashboard')
                ->with('error', 'Vous n\'avez pas accès à cette section. Votre candidature n\'est pas encore validée.');
        }
        
        $classe = $user->classe;
        
        if (!$classe) {
            return view('etudiant.ressources.index', [
                'ressources' => collect(),
                'classe' => null,
                'message' => 'Vous n\'êtes pas encore affecté à une classe.'
            ]);
        }
        
        $ressources = \App\Models\Ressource::where('classe_id', $classe->id)
            ->with(['cours', 'enseignant'])
            ->latest()
            ->get();
        
        // Grouper par cours
        $ressourcesParCours = $ressources->groupBy('cours_id');
        
        // Grouper par type
        $ressourcesParType = $ressources->groupBy('type');
        
        return view('etudiant.ressources.index', compact('ressources', 'ressourcesParCours', 'ressourcesParType', 'classe'));
    }

    public function show(Request $request, $ressourceId): View
    {
        $user = $request->user();
        $candidature = $user->candidature;
        
        // Vérifier que la candidature est validée
        if (!$candidature || $candidature->statut !== 'admis') {
            return redirect()->route('etudiant.dashboard')
                ->with('error', 'Vous n\'avez pas accès à cette section. Votre candidature n\'est pas encore validée.');
        }
        
        $classe = $user->classe;
        
        if (!$classe) {
            abort(404, 'Classe non trouvée');
        }
        
        $ressource = \App\Models\Ressource::where('classe_id', $classe->id)
            ->with(['cours', 'enseignant'])
            ->findOrFail($ressourceId);
        
        return view('etudiant.ressources.show', compact('ressource', 'classe'));
    }

    public function download(Request $request, $ressourceId): BinaryFileResponse
    {
        $user = $request->user();
        $candidature = $user->candidature;
        
        // Vérifier que la candidature est validée
        if (!$candidature || $candidature->statut !== 'admis') {
            abort(403, 'Vous n\'avez pas accès à cette section. Votre candidature n\'est pas encore validée.');
        }
        
        $classe = $user->classe;
        
        if (!$classe) {
            abort(404, 'Classe non trouvée');
        }
        
        $ressource = \App\Models\Ressource::where('classe_id', $classe->id)
            ->findOrFail($ressourceId);
        
        if ($ressource->contenu && Storage::disk('public')->exists($ressource->contenu)) {
            return Storage::disk('public')->download($ressource->contenu, $ressource->titre . '.' . pathinfo($ressource->contenu, PATHINFO_EXTENSION));
        }
        
        abort(404, 'Fichier non trouvé');
    }
}
