<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CoursController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Récupérer les cours avec leurs notes
        $cours = $user->cours()->with('notes')->get();
        
        // Pour chaque cours, récupérer les classes associées via cours_user
        $cours->each(function($c) use ($user) {
            $classeIds = DB::table('cours_user')
                ->where('cours_id', $c->id)
                ->where('user_id', $user->id)
                ->pluck('classe_id')
                ->unique();
            
            $c->classes = \App\Models\Classe::whereIn('id', $classeIds)
                ->with('filiere', 'niveau')
                ->get();
        });
        
        return view('enseignant.cours.index', compact('cours'));
    }

    public function show(Request $request, Cours $cours): View
    {
        $user = $request->user();
        
        // Vérifier que l'enseignant est assigné à ce cours
        if (!$user->cours->contains($cours->id)) {
            abort(403, 'Vous n\'êtes pas assigné à ce cours.');
        }
        
        $classes = $cours->classes()->with('filiere', 'niveau')->get();
        $notes = $cours->notes()->with('etudiant')->latest()->get();
        $ressources = $cours->ressources()->with('classe')->latest()->get();
        
        return view('enseignant.cours.show', compact('cours', 'classes', 'notes', 'ressources'));
    }

    public function getClasses(Request $request, Cours $cours)
    {
        $user = $request->user();
        
        // Vérifier que l'enseignant est assigné à ce cours
        if (!$user->cours->contains($cours->id)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        
        $classes = $cours->classes()->with('filiere', 'niveau')->get()->map(function($classe) {
            return [
                'id' => $classe->id,
                'nom' => $classe->nom,
                'filiere' => $classe->filiere->nom ?? 'N/A',
                'niveau' => $classe->niveau->nom ?? 'N/A',
            ];
        });
        
        return response()->json($classes);
    }
}
