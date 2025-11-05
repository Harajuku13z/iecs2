<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoteController extends Controller
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
        
        $notes = $user->notes()->with('cours')->latest()->get();
        
        // Calculer la moyenne générale
        $totalPoints = 0;
        $totalCoefficients = 0;
        
        foreach ($notes as $note) {
            if ($note->cours) {
                $totalPoints += $note->note * $note->cours->coefficient;
                $totalCoefficients += $note->cours->coefficient;
            }
        }
        
        $moyenneGenerale = $totalCoefficients > 0 ? $totalPoints / $totalCoefficients : 0;
        
        // Grouper par cours
        $notesParCours = $notes->groupBy('cours_id');
        
        // Statistiques par type d'évaluation
        $statistiquesParType = $notes->groupBy('type_evaluation')->map(function($notesGroupe) {
            return [
                'count' => $notesGroupe->count(),
                'moyenne' => $notesGroupe->avg('note'),
                'min' => $notesGroupe->min('note'),
                'max' => $notesGroupe->max('note'),
            ];
        });
        
        return view('etudiant.notes.index', compact('notes', 'moyenneGenerale', 'notesParCours', 'statistiquesParType'));
    }
}
