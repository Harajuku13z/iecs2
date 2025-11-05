<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendrierController extends Controller
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
            return view('etudiant.calendrier.index', [
                'calendrier' => collect(),
                'classe' => null,
                'message' => 'Vous n\'êtes pas encore affecté à une classe.',
                'calendrierParJour' => collect(),
                'startOfWeek' => now()->startOfWeek(),
            ]);
        }
        
        // Déterminer la semaine à afficher (par défaut: semaine actuelle)
        $weekParam = $request->query('week');
        if ($weekParam) {
            $startOfWeek = \Carbon\Carbon::parse($weekParam)->startOfWeek();
        } else {
            $startOfWeek = now()->startOfWeek();
        }
        
        // Récupérer tous les calendriers de la classe (tous semestres)
        $calendrier = $classe->calendrierCours()
            ->with(['cours', 'classe.filiere', 'classe.niveau'])
            ->orderByRaw("
                CASE jour_semaine
                    WHEN 'Lundi' THEN 1
                    WHEN 'Mardi' THEN 2
                    WHEN 'Mercredi' THEN 3
                    WHEN 'Jeudi' THEN 4
                    WHEN 'Vendredi' THEN 5
                    WHEN 'Samedi' THEN 6
                    WHEN 'Dimanche' THEN 7
                END
            ")
            ->orderBy('heure_debut')
            ->get();
        
        // Grouper par jour de la semaine
        $joursOrder = ['Lundi' => 1, 'Mardi' => 2, 'Mercredi' => 3, 'Jeudi' => 4, 'Vendredi' => 5, 'Samedi' => 6, 'Dimanche' => 7];
        $calendrierParJour = $calendrier->groupBy('jour_semaine')->sortBy(function($items, $key) use ($joursOrder) {
            return $joursOrder[$key] ?? 999;
        });
        
        // Récupérer les semestres disponibles
        $semestres = $calendrier->pluck('semestre')->filter()->unique()->sort();
        
        return view('etudiant.calendrier.index', compact('calendrier', 'calendrierParJour', 'joursOrder', 'classe', 'startOfWeek', 'semestres'));
    }
}
