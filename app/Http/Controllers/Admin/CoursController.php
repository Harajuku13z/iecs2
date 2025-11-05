<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursController extends Controller
{
    public function index()
    {
        $classes = \App\Models\Classe::with(['filiere', 'niveau', 'cours'])->orderBy('nom')->get();
        return view('admin.cours.index', compact('classes'));
    }

    public function showClasse(\App\Models\Classe $classe)
    {
        $classe->load(['cours' => function($q) {
            $q->withPivot('semestre')->orderBy('pivot_semestre')->orderBy('nom');
        }, 'filiere', 'niveau']);
        
        // Grouper par semestre
        $coursParSemestre = $classe->cours->groupBy(function($cours) {
            return $cours->pivot->semestre ?: 'Non défini';
        });
        
        return view('admin.cours.classe', compact('classe', 'coursParSemestre'));
    }

    public function create()
    {
        $classes = \App\Models\Classe::with(['filiere', 'niveau'])->orderBy('nom')->get();
        $enseignants = \App\Models\User::where('role', 'enseignant')->orderBy('name')->get();
        return view('admin.cours.create', compact('classes', 'enseignants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'semestre' => 'required|in:1,2,3',
            'cours' => 'required|array|min:1',
            'cours.*.nom' => 'required|string|max:255',
            'cours.*.code' => 'required|string|max:50',
            'cours.*.coefficient' => 'required|integer|min:1',
            'cours.*.description' => 'nullable|string',
            'cours.*.enseignant_id' => 'nullable|exists:users,id',
        ]);

        $classe = \App\Models\Classe::findOrFail($validated['classe_id']);
        $semestre = $validated['semestre'];
        $created = 0;
        $skipped = 0;

        foreach ($validated['cours'] as $coursData) {
            // Vérifier si le code existe déjà
            $existing = Cours::where('code', $coursData['code'])->first();
            
            if ($existing) {
                // Utiliser le cours existant
                $cours = $existing;
            } else {
                // Créer un nouveau cours
                $cours = Cours::create([
                    'nom' => $coursData['nom'],
                    'code' => $coursData['code'],
                    'coefficient' => $coursData['coefficient'],
                    'description' => $coursData['description'] ?? null,
                ]);
            }

            // Vérifier si le cours n'est pas déjà associé à cette classe avec ce semestre
            $alreadyAttached = \DB::table('classe_cours')
                ->where('classe_id', $classe->id)
                ->where('cours_id', $cours->id)
                ->where('semestre', $semestre)
                ->exists();

            if (!$alreadyAttached) {
                // Associer le cours à la classe avec le semestre
                $classe->cours()->attach($cours->id, ['semestre' => $semestre]);
                $created++;

                // Si un enseignant est spécifié, l'associer au cours pour cette classe
                if (!empty($coursData['enseignant_id'])) {
                    $enseignantId = $coursData['enseignant_id'];
                    // Vérifier si l'association existe déjà
                    $exists = \DB::table('cours_user')
                        ->where('cours_id', $cours->id)
                        ->where('user_id', $enseignantId)
                        ->where('classe_id', $classe->id)
                        ->exists();

                    if (!$exists) {
                        \DB::table('cours_user')->insert([
                            'cours_id' => $cours->id,
                            'user_id' => $enseignantId,
                            'classe_id' => $classe->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            } else {
                $skipped++;
            }
        }

        $message = "{$created} cours associé(s) à la classe avec succès.";
        if ($skipped > 0) {
            $message .= " {$skipped} cours déjà associé(s) ignoré(s).";
        }

        return redirect()->route('admin.cours.index')->with('success', $message);
    }

    public function edit(Cours $cour)
    {
        return view('admin.cours.edit', compact('cour'));
    }

    public function update(Request $request, Cours $cour)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:cours,code,' . $cour->id,
            'coefficient' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $cour->update($validated);
        return redirect()->route('admin.cours.index')->with('success', 'Cours mis à jour avec succès.');
    }

    public function detachFromClasse(Request $request, Cours $cour)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
        ]);
        
        $classe = \App\Models\Classe::findOrFail($request->classe_id);
        $classe->cours()->detach($cour->id);
        
        return redirect()->route('admin.cours.classe.show', $classe)
            ->with('success', 'Cours retiré de la classe avec succès.');
    }

    public function destroy(Cours $cour)
    {
        $cour->delete();
        return redirect()->route('admin.cours.index')->with('success', 'Cours supprimé avec succès.');
    }
}
