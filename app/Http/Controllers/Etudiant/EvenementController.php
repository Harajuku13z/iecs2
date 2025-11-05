<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvenementController extends Controller
{
    public function index(Request $request): View
    {
        $evenementsAVenir = \App\Models\Evenement::where('publie', true)
            ->where('date_debut', '>=', now())
            ->orderBy('date_debut', 'asc')
            ->paginate(12);
        
        $evenementsPasses = \App\Models\Evenement::where('publie', true)
            ->where('date_debut', '<', now())
            ->orderBy('date_debut', 'desc')
            ->paginate(12);
        
        return view('etudiant.evenements.index', compact('evenementsAVenir', 'evenementsPasses'));
    }

    public function show(Request $request, $evenementId): View
    {
        $evenement = \App\Models\Evenement::where('publie', true)
            ->findOrFail($evenementId);
        
        // Événements similaires
        $evenementsSimilaires = \App\Models\Evenement::where('publie', true)
            ->where('id', '!=', $evenement->id)
            ->where('date_debut', '>=', now())
            ->orderBy('date_debut', 'asc')
            ->take(3)
            ->get();
        
        return view('etudiant.evenements.show', compact('evenement', 'evenementsSimilaires'));
    }
}
