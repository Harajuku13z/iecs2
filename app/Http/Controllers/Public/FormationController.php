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
            
            // Pour "Je prépare mon bac" ou "Bac", suggérer L1
            $suggestL1 = in_array($niveau->nom, ['Je prépare mon bac', 'Bac']);
            
            // Pour "Licence", permettre L1 et L2 (car on peut avoir L1 d'une autre école et vouloir continuer L2)
            $isLicence = str_contains($niveau->nom, 'Licence') || str_contains($niveau->nom, 'L1') || str_contains($niveau->nom, 'L2');
            $l1Niveau = null;
            $l2Niveau = null;
            $suggestedClasses = collect();
            $suggestedL2Classes = collect();
            
            if ($suggestL1) {
                $l1Niveau = Niveau::where('nom', 'L1 (Licence 1)')->first();
                if ($l1Niveau) {
                    $suggestedClasses = Classe::where('filiere_id', $filiere_id)
                                            ->where('niveau_id', $l1Niveau->id)
                                            ->get();
                }
            }
            
            // Si niveau est Licence, afficher aussi L1 et L2
            if ($isLicence && !$suggestL1) {
                $l1Niveau = Niveau::where('nom', 'L1 (Licence 1)')->first();
                $l2Niveau = Niveau::where('nom', 'L2 (Licence 2)')->first();
                
                if ($l1Niveau) {
                    $suggestedClasses = Classe::where('filiere_id', $filiere_id)
                                            ->where('niveau_id', $l1Niveau->id)
                                            ->get();
                }
                if ($l2Niveau) {
                    $suggestedL2Classes = Classe::where('filiere_id', $filiere_id)
                                            ->where('niveau_id', $l2Niveau->id)
                                            ->get();
                }
            }
            
            return view('public.formations', compact('niveaux', 'filieres', 'niveau', 'filiere', 'classes', 'niveau_id', 'filiere_id', 'suggestL1', 'isLicence', 'l1Niveau', 'l2Niveau', 'suggestedClasses', 'suggestedL2Classes'));
        }
        
        // Sinon afficher toutes les filières
        return view('public.formations', compact('niveaux', 'filieres'));
    }

    public function show(Filiere $filiere)
    {
        $filiere->load('specialites', 'classes.niveau');
        return view('public.formation-show', compact('filiere'));
    }
}
