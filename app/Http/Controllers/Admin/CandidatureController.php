<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Classe;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    public function index()
    {
        $candidatures = Candidature::with('user')->orderBy('created_at', 'desc')->paginate(20);
        $classes = Classe::all();
        return view('admin.candidatures.index', compact('candidatures', 'classes'));
    }

    public function updateStatus(Request $request, Candidature $candidature)
    {
        $validated = $request->validate([
            'statut' => 'required|in:soumis,verifie,admis,rejete',
            'commentaire_admin' => 'nullable|string',
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        $candidature->update([
            'statut' => $validated['statut'],
            'commentaire_admin' => $validated['commentaire_admin'],
        ]);

        // Si admis, changer le rôle de l'utilisateur et affecter à une classe
        if ($validated['statut'] === 'admis' && isset($validated['classe_id'])) {
            $candidature->user->update([
                'role' => 'etudiant',
                'classe_id' => $validated['classe_id'],
            ]);
        }

        return redirect()->route('admin.candidatures.index')
            ->with('success', 'Candidature mise à jour avec succès.');
    }
}
