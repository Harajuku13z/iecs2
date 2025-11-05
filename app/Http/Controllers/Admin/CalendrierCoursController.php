<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalendrierCours;
use App\Models\Classe;
use App\Models\Cours;
use Illuminate\Http\Request;

class CalendrierCoursController extends Controller
{
    public function index()
    {
        // Récupérer uniquement les classes qui ont un calendrier
        $classes = Classe::with('filiere', 'niveau')
            ->whereHas('calendrierCours')
            ->orderBy('nom')
            ->get()
            ->map(function($classe) {
                $classe->nb_cours_calendrier = $classe->calendrierCours()->count();
                $classe->nb_semestres = $classe->calendrierCours()->distinct('semestre')->count('semestre');
                return $classe;
            });
        
        return view('admin.calendrier-cours.index', compact('classes'));
    }

    public function show(Classe $classe)
    {
        // Charger les relations
        $classe->load('filiere', 'niveau');
        
        // Vérifier si la classe a des calendriers
        $hasCalendrier = CalendrierCours::where('classe_id', $classe->id)->exists();
        
        if (!$hasCalendrier) {
            return redirect()->route('admin.calendrier-cours.index')
                ->with('info', 'Cette classe n\'a pas encore de calendrier. Vous pouvez en créer un.');
        }
        
        // Récupérer uniquement les semestres distincts pour cette classe
        $semestres = CalendrierCours::where('classe_id', $classe->id)
            ->select('semestre')
            ->distinct()
            ->orderBy('semestre')
            ->pluck('semestre')
            ->filter()
            ->map(function($semestre) use ($classe) {
                return [
                    'nom' => $semestre ?? 'Non spécifié',
                    'nb_cours' => CalendrierCours::where('classe_id', $classe->id)
                        ->where('semestre', $semestre)
                        ->count()
                ];
            });
        
        return view('admin.calendrier-cours.show', compact('classe', 'semestres'));
    }

    public function showSemestre(Request $request, Classe $classe, $semestre)
    {
        // Déterminer la semaine à afficher (par défaut: semaine actuelle)
        $weekParam = $request->query('week');
        if ($weekParam) {
            $startOfWeek = \Carbon\Carbon::parse($weekParam)->startOfWeek();
        } else {
            $startOfWeek = now()->startOfWeek();
        }
        
        $calendrier = CalendrierCours::where('classe_id', $classe->id)
            ->where('semestre', $semestre)
            ->with(['cours', 'classe.filiere', 'classe.niveau'])
            ->orderByRaw("
                CASE jour_semaine
                    WHEN 'Lundi' THEN 1
                    WHEN 'Mardi' THEN 2
                    WHEN 'Mercredi' THEN 3
                    WHEN 'Jeudi' THEN 4
                    WHEN 'Vendredi' THEN 5
                    WHEN 'Samedi' THEN 6
                    WHEN 'Dimanche' THEN 7
                END
            ")
            ->orderBy('heure_debut')
            ->get();
        
        // Grouper par jour de la semaine
        $joursOrder = ['Lundi' => 1, 'Mardi' => 2, 'Mercredi' => 3, 'Jeudi' => 4, 'Vendredi' => 5, 'Samedi' => 6, 'Dimanche' => 7];
        $calendrierParJour = $calendrier->groupBy('jour_semaine')->sortBy(function($items, $key) use ($joursOrder) {
            return $joursOrder[$key] ?? 999;
        });
        
        return view('admin.calendrier-cours.show-semestre', compact('classe', 'semestre', 'calendrier', 'calendrierParJour', 'startOfWeek'));
    }

    public function create()
    {
        $classes = Classe::with('filiere', 'niveau')->orderBy('nom')->get();
        $cours = Cours::orderBy('nom')->get();
        return view('admin.calendrier-cours.create', compact('classes', 'cours'));
    }

    public function store(Request $request)
    {
        // Mode création multiple (entries[])
        if ($request->has('entries')) {
            $validated = $request->validate([
                'classe_id' => 'required|exists:classes,id',
                'semestre' => 'required|in:Semestre 1,Semestre 2,Semestre 3,1,2,3',
                'entries' => 'required|array|min:1',
                'entries.*.cours_id' => 'nullable|exists:cours,id',
                'entries.*.jour_semaine' => 'required|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche',
                'entries.*.heure_debut' => 'required|date_format:H:i',
                'entries.*.heure_fin' => 'required|date_format:H:i|after:entries.*.heure_debut',
                'entries.*.salle' => 'nullable|string|max:255',
                'entries.*.enseignant' => 'nullable|string|max:255',
                'entries.*.description' => 'nullable|string',
            ]);

            $count = 0;
            foreach ($validated['entries'] as $e) {
                // Normaliser le semestre (1 -> Semestre 1, etc.)
                $semestre = $validated['semestre'];
                if (in_array($semestre, ['1', '2', '3'])) {
                    $semestre = 'Semestre ' . $semestre;
                }
                
                CalendrierCours::create([
                    'classe_id' => $validated['classe_id'],
                    'semestre' => $semestre,
                    'cours_id' => $e['cours_id'] ?? null,
                    'jour_semaine' => $e['jour_semaine'],
                    'heure_debut' => $e['heure_debut'],
                    'heure_fin' => $e['heure_fin'],
                    'salle' => $e['salle'] ?? null,
                    'enseignant' => $e['enseignant'] ?? null,
                    'description' => $e['description'] ?? null,
                ]);
                $count++;
            }

            return redirect()->route('admin.calendrier-cours.index')
                ->with('success', "$count élément(s) ajouté(s) au calendrier.");
        }

        // Mode unitaire (compat)
        $validated = $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'semestre' => 'required|in:1,2,3',
            'cours_id' => 'nullable|exists:cours,id',
            'jour_semaine' => 'required|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'salle' => 'nullable|string|max:255',
            'enseignant' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Normaliser le semestre
        $validated['semestre'] = in_array($validated['semestre'], ['1', '2', '3']) 
            ? 'Semestre ' . $validated['semestre'] 
            : $validated['semestre'];
        
        CalendrierCours::create($validated);

        return redirect()->route('admin.calendrier-cours.index')
            ->with('success', 'Cours ajouté au calendrier avec succès.');
    }

    public function edit(CalendrierCours $calendrierCours)
    {
        $classes = Classe::with('filiere', 'niveau')->orderBy('nom')->get();
        $cours = Cours::orderBy('nom')->get();
        return view('admin.calendrier-cours.edit', compact('calendrierCours', 'classes', 'cours'));
    }

    public function update(Request $request, CalendrierCours $calendrierCours)
    {
        $validated = $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'cours_id' => 'nullable|exists:cours,id',
            'jour_semaine' => 'required|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'salle' => 'nullable|string|max:255',
            'enseignant' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $calendrierCours->update($validated);

        return redirect()->route('admin.calendrier-cours.index')
            ->with('success', 'Cours du calendrier mis à jour avec succès.');
    }

    public function destroy(CalendrierCours $calendrierCours)
    {
        $calendrierCours->delete();

        return redirect()->route('admin.calendrier-cours.index')
            ->with('success', 'Cours supprimé du calendrier avec succès.');
    }

    // AJAX: cours disponibles pour une classe donnée
    public function getCoursByClasse(Classe $classe)
    {
        $cours = $classe->cours()->select('cours.id', 'cours.nom', 'cours.code')->orderBy('nom')->get();
        return response()->json($cours);
    }

    // AJAX: enseignants affectés à un cours pour une classe donnée
    public function getEnseignantsByCours(Request $request, Cours $cours)
    {
        $request->validate(['classe_id' => 'required|exists:classes,id']);
        $classeId = (int) $request->classe_id;
        $enseignants = \DB::table('cours_user')
            ->join('users', 'cours_user.user_id', '=', 'users.id')
            ->where('cours_user.cours_id', $cours->id)
            ->where('cours_user.classe_id', $classeId)
            ->orderBy('users.name')
            ->get(['users.id', 'users.name']);
        if ($enseignants->isEmpty()) {
            // Fallback: renvoyer tous les enseignants si aucun n'est encore affecté à ce cours pour cette classe
            $enseignants = \App\Models\User::where('role', 'enseignant')
                ->orderBy('name')
                ->get(['id','name']);
        }
        return response()->json($enseignants);
    }
}
