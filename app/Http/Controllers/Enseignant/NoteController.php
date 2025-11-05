<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Cours;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Récupérer les classes de l'enseignant
        $classesIds = \DB::table('cours_user')
            ->where('user_id', $user->id)
            ->distinct()
            ->pluck('classe_id')
            ->filter();
        
        $classes = Classe::whereIn('id', $classesIds)
            ->with('filiere', 'niveau')
            ->orderBy('nom')
            ->get();
        
        // Récupérer les notes avec filtres
        $query = Note::whereHas('cours', function($query) use ($user) {
            $query->whereHas('enseignants', function($q) use ($user) {
                $q->where('cours_user.user_id', $user->id);
            });
        });
        
        // Filtre par classe
        $classeId = $request->get('classe_id');
        if ($classeId) {
            // Récupérer les étudiants de cette classe
            $classe = Classe::find($classeId);
            if ($classe) {
                $etudiantsIds = $classe->etudiants()->pluck('id');
                if ($etudiantsIds->isNotEmpty()) {
                    $query->whereIn('user_id', $etudiantsIds);
                } else {
                    // Aucun étudiant dans cette classe, retourner un résultat vide
                    $query->whereRaw('1 = 0');
                }
            }
        }
        
        // Filtre par cours
        $coursId = $request->get('cours_id');
        if ($coursId) {
            $query->where('cours_id', $coursId);
        }
        
        $notes = $query->with(['cours', 'etudiant'])->latest()->paginate(20)->withQueryString();
        
        // Récupérer les cours pour le filtre (si une classe est sélectionnée)
        $cours = collect();
        if ($classeId) {
            // Récupérer les cours que l'enseignant donne dans cette classe
            $coursIds = \DB::table('cours_user')
                ->where('user_id', $user->id)
                ->where('classe_id', $classeId)
                ->pluck('cours_id');
            
            $cours = Cours::whereIn('id', $coursIds)->orderBy('nom')->get();
        }
        
        return view('enseignant.notes.index', compact('notes', 'classes', 'cours', 'classeId', 'coursId'));
    }
    
    /**
     * Récupérer les cours d'un enseignant pour une classe donnée
     */
    public function getCoursByClasse(Request $request, Classe $classe)
    {
        $user = $request->user();
        
        // Vérifier que l'enseignant enseigne dans cette classe
        $coursIds = \DB::table('cours_user')
            ->where('user_id', $user->id)
            ->where('classe_id', $classe->id)
            ->pluck('cours_id');
        
        $cours = Cours::whereIn('id', $coursIds)
            ->orderBy('nom')
            ->get()
            ->map(function($c) {
                return [
                    'id' => $c->id,
                    'nom' => $c->nom,
                    'code' => $c->code ?? '',
                ];
            });
        
        return response()->json($cours);
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $cours = $user->cours()->with('classes')->get();
        
        return view('enseignant.notes.create', compact('cours'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'classe_id' => 'required|exists:classes,id',
            'etudiants' => 'required|array',
            'etudiants.*.user_id' => 'required|exists:users,id',
            'etudiants.*.note' => 'required|numeric|min:0|max:20',
            'type_evaluation' => 'required|string|max:255',
            'semestre' => 'nullable|string|max:255',
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
        $updated = 0;

        foreach ($validated['etudiants'] as $etudiantData) {
            $note = Note::updateOrCreate(
                [
                    'user_id' => $etudiantData['user_id'],
                    'cours_id' => $validated['cours_id'],
                    'type_evaluation' => $validated['type_evaluation'],
                    'semestre' => $validated['semestre'] ?? null,
                ],
                [
                    'note' => $etudiantData['note'],
                ]
            );

            if ($note->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        $message = "Notes enregistrées avec succès. ";
        $message .= $created > 0 ? "$created nouvelle(s) note(s) créée(s). " : "";
        $message .= $updated > 0 ? "$updated note(s) mise(s) à jour." : "";

        return redirect()->route('enseignant.notes.index')->with('success', $message);
    }

    public function edit(Note $note): View
    {
        $user = request()->user();
        
        // Vérifier que l'enseignant peut modifier cette note
        if (!$user->cours->contains($note->cours_id)) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette note.');
        }
        
        $cours = $user->cours()->with('classes')->get();
        
        return view('enseignant.notes.edit', compact('note', 'cours'));
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        $user = $request->user();
        
        // Vérifier que l'enseignant peut modifier cette note
        if (!$user->cours->contains($note->cours_id)) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette note.');
        }

        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:20',
            'type_evaluation' => 'required|string|max:255',
            'semestre' => 'nullable|string|max:255',
        ]);

        $note->update($validated);

        return redirect()->route('enseignant.notes.index')->with('success', 'Note mise à jour avec succès.');
    }

    public function destroy(Note $note): RedirectResponse
    {
        $user = request()->user();
        
        // Vérifier que l'enseignant peut supprimer cette note
        if (!$user->cours->contains($note->cours_id)) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette note.');
        }

        $note->delete();

        return redirect()->route('enseignant.notes.index')->with('success', 'Note supprimée avec succès.');
    }
}
