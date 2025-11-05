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
                'calendrierChronologique' => collect(),
                'classe' => null,
                'message' => 'Vous n\'êtes pas encore affecté à une classe.',
            ]);
        }
        
        // Déterminer la semaine à afficher (par défaut: semaine actuelle, peut être étendue)
        $weekParam = $request->query('week');
        $startOfWeek = $weekParam ? \Carbon\Carbon::parse($weekParam)->startOfWeek() : now()->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();
        
        // Récupérer tous les calendriers de la classe (tous semestres)
        $calendrier = $classe->calendrierCours()
            ->with(['cours', 'classe.filiere', 'classe.niveau'])
            ->get();
        
        // Créer une vue chronologique : convertir les jours de la semaine en dates réelles
        $calendrierChronologique = collect();
        $joursOrder = ['Lundi' => 1, 'Mardi' => 2, 'Mercredi' => 3, 'Jeudi' => 4, 'Vendredi' => 5, 'Samedi' => 6, 'Dimanche' => 7];
        
        // Générer les dates pour les 4 prochaines semaines (ou selon le paramètre)
        $currentDate = $startOfWeek->copy();
        $weeksToShow = $request->get('weeks', 4); // Afficher 4 semaines par défaut
        
        for ($week = 0; $week < $weeksToShow; $week++) {
            $weekStart = $currentDate->copy()->addWeeks($week)->startOfWeek();
            
            foreach ($joursOrder as $jourNom => $order) {
                $date = $weekStart->copy()->addDays($order - 1);
                
                // Trouver les cours pour ce jour de la semaine
                $coursDuJour = $calendrier->filter(function($cal) use ($jourNom) {
                    return $cal->jour_semaine === $jourNom;
                });
                
                if ($coursDuJour->isNotEmpty()) {
                    // Trier par heure de début
                    $coursDuJour = $coursDuJour->sortBy(function($cal) {
                        return $cal->heure_debut;
                    });
                    
                    // Ajouter une entrée pour chaque cours avec sa date réelle
                    foreach ($coursDuJour as $cal) {
                        $calendrierChronologique->push([
                            'date' => $date->copy(),
                            'date_str' => $date->format('Y-m-d'),
                            'date_formatted' => $date->format('d/m/Y'),
                            'jour_nom' => $jourNom,
                            'heure_debut' => $cal->heure_debut,
                            'heure_fin' => $cal->heure_fin,
                            'heure_debut_str' => is_string($cal->heure_debut) ? $cal->heure_debut : (is_object($cal->heure_debut) ? $cal->heure_debut->format('H:i') : date('H:i', strtotime($cal->heure_debut))),
                            'heure_fin_str' => is_string($cal->heure_fin) ? $cal->heure_fin : (is_object($cal->heure_fin) ? $cal->heure_fin->format('H:i') : date('H:i', strtotime($cal->heure_fin))),
                            'cours' => $cal->cours,
                            'cours_nom' => $cal->cours ? $cal->cours->nom : ($cal->description ?? 'Cours'),
                            'cours_code' => $cal->cours ? $cal->cours->code : null,
                            'salle' => $cal->salle,
                            'enseignant' => $cal->enseignant,
                            'description' => $cal->description,
                            'semestre' => $cal->semestre,
                            'calendrier' => $cal, // Garder la référence au modèle complet
                        ]);
                    }
                }
            }
        }
        
        // Trier par date puis par heure
        $calendrierChronologique = $calendrierChronologique->sortBy(function($item) {
            return $item['date']->format('Y-m-d') . ' ' . $item['heure_debut_str'];
        })->values();
        
        // Grouper par date pour l'affichage
        $calendrierParDate = $calendrierChronologique->groupBy('date_str');
        
        return view('etudiant.calendrier.index', compact('calendrierChronologique', 'calendrierParDate', 'classe', 'startOfWeek', 'weeksToShow'));
    }
}
