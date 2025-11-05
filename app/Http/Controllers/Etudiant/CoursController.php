<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoursController extends Controller
{
    public function index(Request $request): View|RedirectResponse
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
            return view('etudiant.cours.index', [
                'cours' => collect(),
                'classe' => null,
                'message' => 'Vous n\'êtes pas encore affecté à une classe.'
            ]);
        }
        
        $cours = $classe->cours()->with(['enseignants', 'notes' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();
        
        return view('etudiant.cours.index', compact('cours', 'classe'));
    }

    public function show(Request $request, $coursId): View|RedirectResponse
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
        
        $cours = $classe->cours()->with(['enseignants', 'notes' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->findOrFail($coursId);
        
        $notes = $user->notes()->where('cours_id', $cours->id)->latest()->get();
        $ressources = \App\Models\Ressource::where('cours_id', $cours->id)
            ->where('classe_id', $classe->id)
            ->latest()
            ->get();
        
        return view('etudiant.cours.show', compact('cours', 'notes', 'ressources', 'classe'));
    }
}
