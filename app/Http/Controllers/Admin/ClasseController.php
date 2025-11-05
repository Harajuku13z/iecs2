<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::with(['filiere', 'niveau'])->paginate(15);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::orderBy('ordre')->get();
        return view('admin.classes.create', compact('filieres', 'niveaux'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
        ]);

        Classe::create($validated);
        return redirect()->route('admin.classes.index')->with('success', 'Classe créée avec succès.');
    }

    public function edit(Classe $classe)
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::orderBy('ordre')->get();
        return view('admin.classes.edit', compact('classe', 'filieres', 'niveaux'));
    }

    public function update(Request $request, Classe $classe)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
        ]);

        $classe->update($validated);
        return redirect()->route('admin.classes.index')->with('success', 'Classe mise à jour avec succès.');
    }

    public function destroy(Classe $classe)
    {
        $traceId = 'CLS-DEL-' . now()->format('YmdHis') . '-' . $classe->id;
        Log::info("[{$traceId}] Demande de suppression de classe reçue", ['classe_id' => $classe->id, 'classe_nom' => $classe->nom]);
        try {
            // Vérifier s'il y a des étudiants dans cette classe
            $nbEtudiants = $classe->etudiants()->count();
            Log::info("[{$traceId}] Etudiants rattachés", ['count' => $nbEtudiants]);
            // Mettre la classe à null pour les utilisateurs rattachés (sécurité supplémentaire)
            if ($nbEtudiants > 0) {
                $updated = \App\Models\User::where('classe_id', $classe->id)->update(['classe_id' => null]);
                Log::info("[{$traceId}] Etudiants réaffectés (classe_id=null)", ['updated' => $updated]);
            }

            // Vérifier s'il y a des cours associés
            $nbCours = $classe->cours()->count();
            Log::info("[{$traceId}] Cours associés (pivot classe_cours)", ['count' => $nbCours]);
            // Détacher les cours (pivot classe_cours)
            if ($nbCours > 0) {
                $classe->cours()->detach();
                Log::info("[{$traceId}] Détachement des cours effectué");
            }

            // Supprimer les entrées de cours_user liées à cette classe
            $deletedCoursUser = DB::table('cours_user')->where('classe_id', $classe->id)->delete();
            Log::info("[{$traceId}] Liens cours_user supprimés", ['deleted' => $deletedCoursUser]);

            // Supprimer calendrier des cours de cette classe
            $deletedCal = \App\Models\CalendrierCours::where('classe_id', $classe->id)->delete();
            Log::info("[{$traceId}] CalendrierCours supprimés", ['deleted' => $deletedCal]);

            // Mettre à jour les ressources rattachées à cette classe (pivot et colonne directe)
            $deletedResPivot = DB::table('ressource_classe')->where('classe_id', $classe->id)->delete();
            $updatedRes = \App\Models\Ressource::where('classe_id', $classe->id)->update(['classe_id' => null]);
            Log::info("[{$traceId}] Ressources mises à jour", ['pivot_deleted' => $deletedResPivot, 'col_updated' => $updatedRes]);

            // Supprimer notifications liées à la classe
            $deletedNotif = \App\Models\Notification::where('classe_id', $classe->id)->delete();
            Log::info("[{$traceId}] Notifications supprimées", ['deleted' => $deletedNotif]);
            
            // Supprimer la classe (les relations en cascade devraient être gérées automatiquement)
            $deleted = $classe->delete();
            Log::info("[{$traceId}] Classe supprimée", ['deleted' => $deleted]);
            
            $message = 'Classe supprimée avec succès.';
            if ($nbCours > 0) {
                $message .= " ({$nbCours} cours associé(s) ont été retirés)";
            }
            
            return redirect()->route('admin.classes.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error("[{$traceId}] Erreur suppression classe: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'classe_id' => $classe->id,
            ]);
            return redirect()->route('admin.classes.index')
                ->with('error', 'Erreur lors de la suppression de la classe: ' . $e->getMessage());
        }
    }
}
