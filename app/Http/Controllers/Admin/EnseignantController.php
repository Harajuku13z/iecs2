<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EnseignantController extends Controller
{
    public function index(): View
    {
        $enseignants = User::where('role', 'enseignant')
            ->with(['cours.classes', 'cours' => function($q) {
                $q->with('classes');
            }])
            ->orderBy('name')
            ->paginate(20);
        
        return view('admin.enseignants.index', compact('enseignants'));
    }

    public function show(User $enseignant): View
    {
        if ($enseignant->role !== 'enseignant') {
            abort(404);
        }
        
        $enseignant->load(['cours.classes', 'ressources.cours', 'ressources.classes']);
        
        // Récupérer les classes via cours_user
        $classesIds = DB::table('cours_user')
            ->where('user_id', $enseignant->id)
            ->distinct()
            ->pluck('classe_id')
            ->filter();
        $classes = \App\Models\Classe::whereIn('id', $classesIds)
            ->with('filiere', 'niveau', 'etudiants')
            ->get();
        
        // Calendrier des cours de l'enseignant
        $calendrier = \App\Models\CalendrierCours::whereIn('classe_id', $classesIds)
            ->with('cours', 'classe')
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
        
        return view('admin.enseignants.show', compact('enseignant', 'classes', 'calendrier'));
    }
}



