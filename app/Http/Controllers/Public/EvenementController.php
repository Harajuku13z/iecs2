<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    public function index(Request $request)
    {
        $showAll = $request->has('filter') && $request->filter === 'all';
        
        if ($showAll) {
            // Afficher tous les événements avec pagination
            $evenementsAVenir = Evenement::where('publie', true)
                ->where('date_debut', '>=', now())
                ->orderBy('date_debut', 'asc')
                ->paginate(12, ['*'], 'avenir');
            
            $evenementsPasses = Evenement::where('publie', true)
                ->where('date_debut', '<', now())
                ->orderBy('date_debut', 'desc')
                ->paginate(12, ['*'], 'passes');
        } else {
            // Afficher seulement les premiers (limite initiale)
            $evenementsAVenir = Evenement::where('publie', true)
                ->where('date_debut', '>=', now())
                ->orderBy('date_debut', 'asc')
                ->take(6)
                ->get();
            
            $evenementsPasses = Evenement::where('publie', true)
                ->where('date_debut', '<', now())
                ->orderBy('date_debut', 'desc')
                ->take(3)
                ->get();
        }

        return view('public.evenements', compact('evenementsAVenir', 'evenementsPasses', 'showAll'));
    }

    public function show(Evenement $evenement)
    {
        if (!$evenement->publie) {
            abort(404);
        }
        
        return view('public.evenement-show', compact('evenement'));
    }
}
