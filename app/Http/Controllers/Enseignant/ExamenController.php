<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ExamenController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Récupérer tous les examens (notes) créés par cet enseignant
        $examens = \App\Models\Note::whereHas('cours', function($query) use ($user) {
            $query->whereHas('enseignants', function($q) use ($user) {
                $q->where('cours_user.user_id', $user->id);
            });
        })->with(['cours', 'etudiant'])
          ->select('cours_id', 'type_evaluation', 'semestre')
          ->distinct()
          ->get()
          ->groupBy(['cours_id', 'type_evaluation']);
        
        $cours = $user->cours()->with('classes')->get();
        
        return view('enseignant.examens.index', compact('examens', 'cours'));
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $cours = $user->cours()->with('classes')->get();
        
        return view('enseignant.examens.create', compact('cours'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'classe_id' => 'required|exists:classes,id',
            'type_evaluation' => 'required|string|max:255',
            'semestre' => 'nullable|string|max:255',
            'date_examen' => 'nullable|date',
            'etudiants' => 'required|array',
            'etudiants.*.user_id' => 'required|exists:users,id',
            'etudiants.*.note' => 'required|numeric|min:0|max:20',
        ]);

        $user = $request->user();
        
        // Vérifier que l'enseignant est assigné à ce cours
        if (!$user->cours->contains($validated['cours_id'])) {
            return back()->withErrors(['cours_id' => 'Vous n\'êtes pas assigné à ce cours.'])->withInput();
        }

        $cours = Cours::findOrFail($validated['cours_id']);
        
        // Vérifier que la classe est associée au cours
        if (!$cours->classes->contains($validated['classe_id'])) {
            return back()->withErrors(['classe_id' => 'Cette classe n\'est pas associée à ce cours.'])->withInput();
        }

        $created = 0;

        foreach ($validated['etudiants'] as $etudiantData) {
            \App\Models\Note::create([
                'user_id' => $etudiantData['user_id'],
                'cours_id' => $validated['cours_id'],
                'note' => $etudiantData['note'],
                'type_evaluation' => $validated['type_evaluation'],
                'semestre' => $validated['semestre'] ?? null,
            ]);
            $created++;
        }

        return redirect()->route('enseignant.examens.index')->with('success', "$created note(s) d'examen créée(s) avec succès.");
    }

    public function show(Request $request, $coursId, $typeEvaluation): View
    {
        $user = $request->user();
        
        $cours = Cours::findOrFail($coursId);
        
        // Vérifier que l'enseignant est assigné à ce cours
        if (!$user->cours->contains($cours->id)) {
            abort(403, 'Vous n\'êtes pas assigné à ce cours.');
        }
        
        $notes = \App\Models\Note::where('cours_id', $coursId)
            ->where('type_evaluation', urldecode($typeEvaluation))
            ->with('etudiant')
            ->get();
        
        return view('enseignant.examens.show', compact('cours', 'typeEvaluation', 'notes'));
    }
}
