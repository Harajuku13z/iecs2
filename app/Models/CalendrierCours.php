<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalendrierCours extends Model
{
    protected $fillable = [
        'classe_id',
        'cours_id',
        'semestre',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'salle',
        'enseignant',
        'description',
    ];

    protected $casts = [
        // Ne pas caster les heures en datetime car elles sont stockées en time
        // 'heure_debut' => 'datetime',
        // 'heure_fin' => 'datetime',
    ];

    /**
     * Récupérer les calendriers d'un enseignant
     */
    public static function getByEnseignant(User $enseignant)
    {
        // Récupérer les cours de l'enseignant via cours_user
        $coursIds = DB::table('cours_user')
            ->where('user_id', $enseignant->id)
            ->pluck('cours_id')
            ->unique();
        
        // Récupérer les classes de l'enseignant
        $classeIds = DB::table('cours_user')
            ->where('user_id', $enseignant->id)
            ->pluck('classe_id')
            ->unique()
            ->filter();
        
        // Retourner les calendriers pour ces cours et classes
        if ($coursIds->isEmpty() && $classeIds->isEmpty()) {
            // Si aucun cours ou classe assigné, chercher par nom d'enseignant
            return self::where('enseignant', $enseignant->name)
                ->with(['classe.filiere', 'classe.niveau', 'cours'])
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
        }
        
        return self::where(function($query) use ($coursIds, $classeIds, $enseignant) {
                if ($coursIds->isNotEmpty() && $classeIds->isNotEmpty()) {
                    $query->whereIn('cours_id', $coursIds)
                          ->whereIn('classe_id', $classeIds);
                } elseif ($coursIds->isNotEmpty()) {
                    $query->whereIn('cours_id', $coursIds);
                } elseif ($classeIds->isNotEmpty()) {
                    $query->whereIn('classe_id', $classeIds);
                }
            })
            ->orWhere('enseignant', $enseignant->name)
            ->with(['classe.filiere', 'classe.niveau', 'cours'])
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
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
}
