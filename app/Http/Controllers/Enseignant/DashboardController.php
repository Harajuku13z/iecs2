<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        
        $cours = $user->cours()->with('classes')->get();
        
        // Récupérer les classes via la relation cours_user
        $classesIds = \DB::table('cours_user')
            ->where('user_id', $user->id)
            ->distinct()
            ->pluck('classe_id')
            ->filter();
        $classes = \App\Models\Classe::whereIn('id', $classesIds)
            ->with('filiere', 'niveau')
            ->get();
        
        // Récupérer les notes pour les cours de l'enseignant
        $coursIds = $user->cours()->pluck('cours.id');
        $notes = \App\Models\Note::whereIn('cours_id', $coursIds)
            ->latest()
            ->take(10)
            ->get();
        
        $ressources = $user->ressources()->with('cours', 'classe')->latest()->take(5)->get();
        
        $totalEtudiants = 0;
        foreach ($classes as $classe) {
            $totalEtudiants += $classe->etudiants()->count();
        }
        
        // Récupérer le calendrier d'intervention de l'enseignant
        $calendrier = \App\Models\CalendrierCours::getByEnseignant($user);
        
        // Grouper par semestre
        $calendrierParSemestre = $calendrier->groupBy('semestre');
        
        return view('enseignant.dashboard', compact('cours', 'classes', 'notes', 'ressources', 'totalEtudiants', 'calendrier', 'calendrierParSemestre'));
    }
}

