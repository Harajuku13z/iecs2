<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Filiere;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\Candidature;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_etudiants' => User::where('role', 'etudiant')->count(),
            'total_enseignants' => User::where('role', 'enseignant')->count(),
            'total_candidats' => User::where('role', 'candidat')->count(),
            'total_filieres' => Filiere::count(),
            'total_classes' => Classe::count(),
            'total_cours' => Cours::count(),
            'candidatures_en_attente' => Candidature::where('statut', 'soumis')->count(),
        ];

        $recent_candidatures = Candidature::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_candidatures'));
    }
}
