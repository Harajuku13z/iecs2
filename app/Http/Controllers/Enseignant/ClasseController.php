<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClasseController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Récupérer les classes via la relation cours_user
        $classesIds = DB::table('cours_user')
            ->where('user_id', $user->id)
            ->distinct()
            ->pluck('classe_id')
            ->filter();
        
        $classes = Classe::whereIn('id', $classesIds)
            ->with(['filiere', 'niveau', 'etudiants'])
            ->get();
        
        return view('enseignant.classes.index', compact('classes'));
    }

    public function show(Request $request, Classe $classe): View
    {
        $user = $request->user();
        
        // Vérifier que l'enseignant enseigne dans cette classe
        $coursEnseigne = $user->cours()->whereHas('classes', function($query) use ($classe) {
            $query->where('classe_cours.classe_id', $classe->id);
        })->get();
        
        if ($coursEnseigne->isEmpty()) {
            abort(403, 'Vous n\'enseignez pas dans cette classe.');
        }
        
        $etudiants = $classe->etudiants()->with('notes')->get();
        
        return view('enseignant.classes.show', compact('classe', 'coursEnseigne', 'etudiants'));
    }

    public function getEtudiants(Request $request, Classe $classe)
    {
        $user = $request->user();
        
        // Vérifier que l'enseignant enseigne dans cette classe
        $coursEnseigne = $user->cours()->whereHas('classes', function($query) use ($classe) {
            $query->where('classe_cours.classe_id', $classe->id);
        })->get();
        
        if ($coursEnseigne->isEmpty()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        
        $etudiants = $classe->etudiants()->get()->map(function($etudiant) {
            return [
                'id' => $etudiant->id,
                'name' => $etudiant->name,
                'email' => $etudiant->email,
            ];
        });
        
        return response()->json($etudiants);
    }
}
