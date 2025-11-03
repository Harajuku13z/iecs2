<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Classe;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    public function index(Request $request)
    {
        $niveau_id = $request->get('niveau_id');
        $filiere_id = $request->get('filiere_id');
        
        $niveaux = Niveau::orderBy('ordre')->get();
        $filieres = Filiere::all();
        
        // Si recherche avec filtres
        if ($niveau_id && $filiere_id) {
            $niveau = Niveau::find($niveau_id);
            $filiere = Filiere::find($filiere_id);
            
            // Trouver les classes correspondantes
            $classes = Classe::where('filiere_id', $filiere_id)
                            ->where('niveau_id', $niveau_id)
                            ->get();
            
            return view('public.formations', compact('niveaux', 'filieres', 'niveau', 'filiere', 'classes', 'niveau_id', 'filiere_id'));
        }
        
        // Sinon afficher toutes les filières
        return view('public.formations', compact('niveaux', 'filieres'));
    }
}
