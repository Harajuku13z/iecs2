<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\Candidature;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer toutes les classes existantes
        $classes = Classe::with('filiere', 'niveau')->get();
        
        if ($classes->isEmpty()) {
            $this->command->warn('Aucune classe trouvée. Veuillez d\'abord créer des classes.');
            return;
        }

        // 1. Créer des cours pour différentes matières
        $coursData = [
            // Mathématiques et Sciences
            ['nom' => 'Mathématiques Générales', 'code' => 'MATH101', 'coefficient' => 4, 'description' => 'Algèbre, analyse et géométrie'],
            ['nom' => 'Statistiques', 'code' => 'STAT101', 'coefficient' => 3, 'description' => 'Statistiques descriptives et inférentielles'],
            ['nom' => 'Probabilités', 'code' => 'PROB101', 'coefficient' => 2, 'description' => 'Théorie des probabilités'],
            
            // Informatique
            ['nom' => 'Algorithmique et Programmation', 'code' => 'ALGO101', 'coefficient' => 4, 'description' => 'Bases de l\'algorithmique et programmation'],
            ['nom' => 'Base de Données', 'code' => 'BDD101', 'coefficient' => 3, 'description' => 'Modélisation et gestion des bases de données'],
            ['nom' => 'Réseaux Informatiques', 'code' => 'RES101', 'coefficient' => 3, 'description' => 'Architecture et protocoles réseau'],
            ['nom' => 'Développement Web', 'code' => 'WEB101', 'coefficient' => 3, 'description' => 'HTML, CSS, JavaScript et frameworks'],
            ['nom' => 'Systèmes d\'Exploitation', 'code' => 'SE101', 'coefficient' => 3, 'description' => 'Fonctionnement des OS'],
            
            // Gestion et Commerce
            ['nom' => 'Comptabilité Générale', 'code' => 'COMP101', 'coefficient' => 4, 'description' => 'Principes comptables fondamentaux'],
            ['nom' => 'Management', 'code' => 'MGT101', 'coefficient' => 3, 'description' => 'Gestion et management des organisations'],
            ['nom' => 'Marketing', 'code' => 'MKT101', 'coefficient' => 3, 'description' => 'Stratégies marketing et communication'],
            ['nom' => 'Gestion Financière', 'code' => 'FIN101', 'coefficient' => 3, 'description' => 'Finance d\'entreprise'],
            ['nom' => 'Droit Commercial', 'code' => 'DROIT101', 'coefficient' => 2, 'description' => 'Droit des affaires'],
            
            // Langues et Communication
            ['nom' => 'Anglais', 'code' => 'ANG101', 'coefficient' => 2, 'description' => 'Anglais technique et commercial'],
            ['nom' => 'Communication', 'code' => 'COMM101', 'coefficient' => 2, 'description' => 'Techniques de communication'],
            
            // Général
            ['nom' => 'Économie Générale', 'code' => 'ECO101', 'coefficient' => 3, 'description' => 'Principes économiques fondamentaux'],
            ['nom' => 'Méthodologie de Recherche', 'code' => 'METH101', 'coefficient' => 2, 'description' => 'Méthodes de recherche scientifique'],
        ];

        $cours = [];
        foreach ($coursData as $data) {
            $cours[] = Cours::firstOrCreate(
                ['code' => $data['code']],
                $data
            );
        }

        $this->command->info('✓ ' . count($cours) . ' cours créés');

        // 2. Créer des enseignants
        $enseignantsData = [
            ['prenom' => 'Jean', 'nom' => 'MABIALA', 'specialite' => 'Mathématiques'],
            ['prenom' => 'Marie', 'nom' => 'KOUROUMA', 'specialite' => 'Informatique'],
            ['prenom' => 'Pierre', 'nom' => 'NDOMBA', 'specialite' => 'Programmation'],
            ['prenom' => 'Sophie', 'nom' => 'MOUKALI', 'specialite' => 'Base de données'],
            ['prenom' => 'Paul', 'nom' => 'BOUANGA', 'specialite' => 'Réseaux'],
            ['prenom' => 'Lucie', 'nom' => 'DIABANZA', 'specialite' => 'Comptabilité'],
            ['prenom' => 'Marc', 'nom' => 'KOUNGA', 'specialite' => 'Management'],
            ['prenom' => 'Claire', 'nom' => 'MBOUMBA', 'specialite' => 'Marketing'],
            ['prenom' => 'Thomas', 'nom' => 'MABIALA', 'specialite' => 'Finance'],
            ['prenom' => 'Anne', 'nom' => 'PEMBE', 'specialite' => 'Droit'],
            ['prenom' => 'David', 'nom' => 'MOUKALA', 'specialite' => 'Anglais'],
            ['prenom' => 'Julie', 'nom' => 'KOUROUMA', 'specialite' => 'Communication'],
            ['prenom' => 'Michel', 'nom' => 'NDOMBA', 'specialite' => 'Économie'],
            ['prenom' => 'Céline', 'nom' => 'MOUKALI', 'specialite' => 'Statistiques'],
            ['prenom' => 'Laurent', 'nom' => 'BOUANGA', 'specialite' => 'Développement Web'],
        ];

        $enseignants = [];
        foreach ($enseignantsData as $data) {
            $email = strtolower($data['prenom'] . '.' . $data['nom']) . '@iesc-congo.org';
            $enseignants[] = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $data['prenom'] . ' ' . $data['nom'],
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'enseignant',
                    'phone' => '+242 ' . rand(100000000, 999999999),
                ]
            );
        }

        $this->command->info('✓ ' . count($enseignants) . ' enseignants créés');

        // 3. Assigner des cours aux classes (via classe_cours)
        $coursAssignes = 0;
        foreach ($classes as $classe) {
            // Assigner 8-12 cours par classe
            $coursPourClasse = collect($cours)->random(min(12, count($cours)));
            foreach ($coursPourClasse as $coursObj) {
                $exists = \DB::table('classe_cours')
                    ->where('classe_id', $classe->id)
                    ->where('cours_id', $coursObj->id)
                    ->exists();
                
                if (!$exists) {
                    $classe->cours()->attach($coursObj->id);
                    $coursAssignes++;
                }
            }
        }
        $this->command->info('✓ ' . $coursAssignes . ' cours assignés aux classes');

        // 4. Allouer des cours aux enseignants pour différentes classes
        $coursParEnseignant = [
            // Mathématiques
            ['cours' => ['MATH101', 'STAT101', 'PROB101'], 'enseignant' => 0],
            // Informatique - Programmation
            ['cours' => ['ALGO101', 'WEB101'], 'enseignant' => 1],
            ['cours' => ['ALGO101', 'WEB101'], 'enseignant' => 2],
            // Base de données
            ['cours' => ['BDD101'], 'enseignant' => 3],
            // Réseaux
            ['cours' => ['RES101', 'SE101'], 'enseignant' => 4],
            // Comptabilité
            ['cours' => ['COMP101'], 'enseignant' => 5],
            // Management
            ['cours' => ['MGT101', 'METH101'], 'enseignant' => 6],
            // Marketing
            ['cours' => ['MKT101', 'COMM101'], 'enseignant' => 7],
            // Finance
            ['cours' => ['FIN101', 'ECO101'], 'enseignant' => 8],
            // Droit
            ['cours' => ['DROIT101'], 'enseignant' => 9],
            // Langues
            ['cours' => ['ANG101'], 'enseignant' => 10],
            ['cours' => ['COMM101'], 'enseignant' => 11],
            // Économie
            ['cours' => ['ECO101'], 'enseignant' => 12],
            // Statistiques
            ['cours' => ['STAT101', 'PROB101'], 'enseignant' => 13],
            // Développement Web
            ['cours' => ['WEB101'], 'enseignant' => 14],
        ];

        $assignations = 0;
        foreach ($coursParEnseignant as $assignation) {
            $enseignant = $enseignants[$assignation['enseignant']];
            foreach ($assignation['cours'] as $codeCours) {
                $coursObj = collect($cours)->firstWhere('code', $codeCours);
                if ($coursObj) {
                    // Assigner ce cours à l'enseignant pour 2-3 classes aléatoires
                    $classesPourCours = $classes->random(min(3, $classes->count()));
                    foreach ($classesPourCours as $classe) {
                        // Vérifier si la relation n'existe pas déjà
                        $exists = \DB::table('cours_user')
                            ->where('cours_id', $coursObj->id)
                            ->where('user_id', $enseignant->id)
                            ->where('classe_id', $classe->id)
                            ->exists();
                        
                        if (!$exists) {
                            $enseignant->cours()->attach($coursObj->id, ['classe_id' => $classe->id]);
                            $assignations++;
                        }
                    }
                }
            }
        }

        $this->command->info('✓ ' . $assignations . ' cours assignés aux enseignants');

        // 5. Créer des étudiants pour chaque classe
        $prenoms = [
            'Jean', 'Marie', 'Pierre', 'Sophie', 'Paul', 'Lucie', 'Marc', 'Claire', 'Thomas', 'Anne',
            'David', 'Julie', 'Michel', 'Céline', 'Laurent', 'Nicolas', 'Isabelle', 'Philippe', 'Valérie', 'Sébastien',
            'Caroline', 'Antoine', 'Émilie', 'Guillaume', 'Aurélie', 'Vincent', 'Nathalie', 'François', 'Sandrine', 'Olivier',
            'Kévin', 'Mélanie', 'Julien', 'Stéphanie', 'Romain', 'Audrey', 'Maxime', 'Cécilia', 'Alexandre', 'Élodie',
        ];

        $noms = [
            'MABIALA', 'KOUROUMA', 'NDOMBA', 'MOUKALI', 'BOUANGA', 'DIABANZA', 'KOUNGA', 'MBOUMBA', 'PEMBE', 'MOUKALA',
            'NDINGA', 'MABIKA', 'KOUNZOU', 'MOUKOUNDI', 'BOUENDE', 'DIASSOU', 'KOUNDA', 'MBOUMI', 'PENDA', 'MOUKANI',
            'NDIMBA', 'MABIKA', 'KOUNZOU', 'MOUKOUNDI', 'BOUENDE', 'DIASSOU', 'KOUNDA', 'MBOUMI', 'PENDA', 'MOUKANI',
        ];

        $etudiantsCrees = 0;
        foreach ($classes as $classe) {
            // Créer 15-25 étudiants par classe
            $nbEtudiants = rand(15, 25);
            
            for ($i = 0; $i < $nbEtudiants; $i++) {
                $prenom = $prenoms[array_rand($prenoms)];
                $nom = $noms[array_rand($noms)];
                $email = strtolower($prenom . '.' . $nom) . '@iesc-congo.org';
                
                // Vérifier si l'email existe déjà (pour éviter les doublons)
                $counter = 1;
                $originalEmail = $email;
                while (User::where('email', $email)->exists()) {
                    $email = strtolower($prenom . '.' . $nom . $counter) . '@iesc-congo.org';
                    $counter++;
                }
                
                $etudiant = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $prenom . ' ' . $nom,
                        'email' => $email,
                        'password' => Hash::make('password'),
                        'role' => 'etudiant',
                        'classe_id' => $classe->id,
                        'phone' => '+242 ' . rand(100000000, 999999999),
                        'address' => 'Brazzaville, Congo',
                    ]
                );

                // Créer une candidature validée pour cet étudiant s'il n'en a pas
                if (!$etudiant->candidature && $classe->filiere_id) {
                    // Récupérer une spécialité de la filière si elle existe
                    $specialite = \App\Models\Specialite::where('filiere_id', $classe->filiere_id)->first();
                    
                    Candidature::create([
                        'user_id' => $etudiant->id,
                        'filiere_id' => $classe->filiere_id,
                        'specialite_id' => $specialite?->id,
                        'classe_id' => $classe->id,
                        'statut' => 'admis',
                        'verified_by' => 1, // Admin par défaut
                        'evaluated_by' => 1,
                        'decided_by' => 1,
                    ]);
                }

                $etudiantsCrees++;
            }
        }

        $this->command->info('✓ ' . $etudiantsCrees . ' étudiants créés et assignés aux classes');
        $this->command->info('✓ Données de test générées avec succès !');
        $this->command->info('  Email format: prenom.nom@iesc-congo.org');
        $this->command->info('  Mot de passe par défaut: password');
    }
}



