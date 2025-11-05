<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use Illuminate\Http\Request;

class ActualiteController extends Controller
{
    public function index(Request $request)
    {
        $showAll = $request->has('filter') && $request->filter === 'all';
        $categorie = $request->get('categorie', 'all');
        
        $query = Actualite::where('publie', true);
        
        // Filtrer par catégorie si spécifiée
        if ($categorie && $categorie !== 'all') {
            $query->where('categorie', $categorie);
        }
        
        if ($showAll) {
            // Afficher toutes les actualités avec pagination
            $actualites = $query->orderBy('date_publication', 'desc')->paginate(12);
        } else {
            // Afficher seulement les dernières (limite initiale)
            $actualites = $query->orderBy('date_publication', 'desc')->take(9)->get();
        }

        return view('public.actualites', compact('actualites', 'showAll'));
    }

    public function show(Actualite $actualite)
    {
        if (!$actualite->publie) {
            abort(404);
        }
        
        // Récupérer les actualités similaires (même catégorie)
        $actualitesSimilaires = Actualite::where('publie', true)
            ->where('id', '!=', $actualite->id)
            ->where('categorie', $actualite->categorie)
            ->orderBy('date_publication', 'desc')
            ->take(3)
            ->get();
        
        return view('public.actualite-show', compact('actualite', 'actualitesSimilaires'));
    }
}