<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CalendrierCours;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CalendrierCoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer toutes les classes
        $classes = Classe::with('filiere', 'niveau')->get();
        
        if ($classes->isEmpty()) {
            $this->command->warn('Aucune classe trouvée. Veuillez d\'abord créer des classes.');
            return;
        }

        $joursSemaine = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $semestres = ['Semestre 1', 'Semestre 2', 'Semestre 3'];
        
        // Horaires possibles (8h-18h)
        $creneaux = [
            ['08:00', '09:30'],
            ['09:45', '11:15'],
            ['11:30', '13:00'],
            ['14:00', '15:30'],
            ['15:45', '17:15'],
            ['17:30', '19:00'],
        ];

        $salles = ['Salle 101', 'Salle 102', 'Salle 201', 'Salle 202', 'Salle 301', 'Salle 302', 'Amphi A', 'Amphi B', 'Labo Info 1', 'Labo Info 2'];

        $totalCrees = 0;

        foreach ($classes as $classe) {
            // Récupérer les cours assignés à cette classe
            $coursClasse = $classe->cours()->get();
            
            if ($coursClasse->isEmpty()) {
                continue;
            }

            // Pour chaque semestre
            foreach ($semestres as $semestre) {
                // Générer 15-20 créneaux par semestre pour cette classe
                $nbCreneaux = rand(15, 20);
                
                // Distribuer les cours sur les différents jours
                $coursUtilises = [];
                $joursUtilises = [];
                
                for ($i = 0; $i < $nbCreneaux; $i++) {
                    // Sélectionner un cours aléatoire de la classe
                    $cours = $coursClasse->random();
                    
                    // Trouver un enseignant pour ce cours dans cette classe via cours_user
                    $enseignant = DB::table('cours_user')
                        ->where('cours_user.cours_id', $cours->id)
                        ->where('cours_user.classe_id', $classe->id)
                        ->join('users', 'users.id', '=', 'cours_user.user_id')
                        ->where('users.role', 'enseignant')
                        ->select('users.*')
                        ->first();
                    
                    // Si pas d'enseignant trouvé, prendre un enseignant aléatoire pour ce cours
                    if (!$enseignant) {
                        $enseignant = DB::table('cours_user')
                            ->where('cours_user.cours_id', $cours->id)
                            ->join('users', 'users.id', '=', 'cours_user.user_id')
                            ->where('users.role', 'enseignant')
                            ->select('users.*')
                            ->first();
                    }
                    
                    // Sélectionner un jour et un créneau
                    $jour = $joursSemaine[array_rand($joursSemaine)];
                    $creneau = $creneaux[array_rand($creneaux)];
                    
                    // Vérifier qu'on n'a pas déjà un cours à cette heure pour ce jour dans cette classe
                    $exists = CalendrierCours::where('classe_id', $classe->id)
                        ->where('semestre', $semestre)
                        ->where('jour_semaine', $jour)
                        ->where('heure_debut', $creneau[0])
                        ->exists();
                    
                    if ($exists) {
                        continue; // Skip si conflit
                    }
                    
                    // Créer le calendrier
                    CalendrierCours::create([
                        'classe_id' => $classe->id,
                        'cours_id' => $cours->id,
                        'semestre' => $semestre,
                        'jour_semaine' => $jour,
                        'heure_debut' => $creneau[0],
                        'heure_fin' => $creneau[1],
                        'salle' => $salles[array_rand($salles)],
                        'enseignant' => $enseignant ? $enseignant->name : null,
                        'description' => $cours->description ?? null,
                    ]);
                    
                    $totalCrees++;
                }
            }
        }

        $this->command->info("✓ {$totalCrees} créneaux de calendrier créés pour toutes les classes et semestres");
    }
}

