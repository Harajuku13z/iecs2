<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Affiche le dashboard étudiant avec gestion conditionnelle
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $candidature = $user->candidature;
        
        // Si pas de candidature ou candidature non validée: rediriger vers la page de suivi
        if (!$candidature || $candidature->statut !== 'admis') {
            return redirect()->route('etudiant.candidature.show');
        }
        
        // Si candidature validée (admis), afficher le dashboard complet
        $classe = $user->classe;
        $notes = $user->notes()->with('cours')->latest()->get();
        $cours = $classe ? $classe->cours()->with('enseignants')->get() : collect();
        $calendrier = $classe ? $classe->calendrierCours()->with('cours')->get() : collect();
        $evenements = \App\Models\Evenement::where('publie', true)
            ->where('date_debut', '>=', now())
            ->orderBy('date_debut', 'asc')
            ->take(10)
            ->get();
        $ressources = $classe ? \App\Models\Ressource::where('classe_id', $classe->id)->latest()->get() : collect();
        
        return view('etudiant.dashboard', compact('user', 'candidature', 'classe', 'notes', 'cours', 'calendrier', 'evenements', 'ressources'));
    }
}
