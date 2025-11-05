<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidatureController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $candidature = $user->candidature;
        
        if (!$candidature) {
            return view('etudiant.candidature.show', [
                'candidature' => null,
                'message' => 'Vous n\'avez pas encore soumis de candidature.'
            ]);
        }
        
        // Charger les relations
        $candidature->load(['filiere', 'specialite', 'classe.niveau', 'classe.filiere']);
        
        return view('etudiant.candidature.show', compact('candidature'));
    }
}
