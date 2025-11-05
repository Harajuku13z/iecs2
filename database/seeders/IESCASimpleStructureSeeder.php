<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Specialite;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\CalendrierCours;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class IESCASimpleStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // I. Créer les Niveaux Académiques
        $this->command->info('📚 Création des niveaux académiques...');
        $niveaux = [
            ['nom' => 'L1 (Licence 1)', 'ordre' => 1],
            ['nom' => 'L2 (Licence 2)', 'ordre' => 2],
            ['nom' => 'L3 (Licence 3)', 'ordre' => 3],
        ];

        $niveauxCreated = [];
        foreach ($niveaux as $niveauData) {
            $niveau = Niveau::firstOrCreate(
                ['nom' => $niveauData['nom']],
                ['ordre' => $niveauData['ordre']]
            );
            $niveauxCreated[$niveauData['nom']] = $niveau;
            $this->command->info("   ✅ {$niveau->nom} (ID: {$niveau->id})");
        }

        // II. Créer les Filières
        $this->command->info("\n📖 Création des filières...");
        $filieresData = [
            [
                'nom' => 'Sciences et Administration des Affaires',
                'description' => 'SAA - Management, Entrepreneuriat et Gestion des ressources humaines',
                'code' => 'SAA'
            ],
            [
                'nom' => 'Génie Informatique',
                'description' => 'GI - Réseaux et télécommunications, Informatique de gestion',
                'code' => 'GI'
            ],
            [
                'nom' => 'Sciences Juridiques',
                'description' => 'SJ - Droit privé, Droit public, Droit des affaires',
                'code' => 'SJ'
            ],
            [
                'nom' => 'Sciences Commerciales',
                'description' => 'SC - Comptabilité, Management de la chaîne logistique, Banque Assurance et finances',
                'code' => 'SC'
            ],
        ];

        $filieresCreated = [];
        foreach ($filieresData as $filiereData) {
            $filiere = Filiere::firstOrCreate(
                ['nom' => $filiereData['nom']],
                ['description' => $filiereData['description']]
            );
            $filieresCreated[$filiereData['code']] = $filiere;
            $this->command->info("   ✅ {$filiere->nom} (ID: {$filiere->id})");
        }

        // III. Créer les Spécialités
        $this->command->info("\n🎯 Création des spécialités...");
        $specialitesData = [
            // SAA
            ['nom' => 'Management et entrepreneuriat', 'filiere' => 'SAA', 'code' => 'M'],
            ['nom' => 'Gestion des ressources humaines', 'filiere' => 'SAA', 'code' => 'RH'],
            // GI
            ['nom' => 'Réseaux et télécommunications', 'filiere' => 'GI', 'code' => 'Réseaux'],
            ['nom' => 'Informatique de gestion', 'filiere' => 'GI', 'code' => 'InfoG'],
            // SJ
            ['nom' => 'Droit privé', 'filiere' => 'SJ', 'code' => 'P'],
            ['nom' => 'Droit public', 'filiere' => 'SJ', 'code' => 'Pu'],
            ['nom' => 'Droit des affaires', 'filiere' => 'SJ', 'code' => 'Daff'],
            // SC
            ['nom' => 'Comptabilité', 'filiere' => 'SC', 'code' => 'Cpt'],
            ['nom' => 'Management de la chaîne logistique', 'filiere' => 'SC', 'code' => 'Log'],
            ['nom' => 'Banque, Assurance et finances', 'filiere' => 'SC', 'code' => 'B/A/F'],
        ];

        $specialitesCreated = [];
        foreach ($specialitesData as $specData) {
            $filiere = $filieresCreated[$specData['filiere']];
            $specialite = Specialite::firstOrCreate(
                [
                    'nom' => $specData['nom'],
                    'filiere_id' => $filiere->id
                ],
                ['description' => "Spécialité {$specData['nom']} de la filière {$filiere->nom}"]
            );
            $key = "{$specData['filiere']}-{$specData['code']}";
            $specialitesCreated[$key] = $specialite;
            $this->command->info("   ✅ {$specialite->nom} (ID: {$specialite->id})");
        }

        // IV. Créer les Classes (une classe par spécialité/niveau)
        $this->command->info("\n🏫 Création des classes...");
        $classesData = [
            // L1
            ['nom' => 'L1 SAA-M', 'niveau' => 'L1 (Licence 1)', 'filiere' => 'SAA', 'specialite' => 'SAA-M'],
            ['nom' => 'L1 SAA-RH', 'niveau' => 'L1 (Licence 1)', 'filiere' => 'SAA', 'specialite' => 'SAA-RH'],
            ['nom' => 'L1 GI-Réseaux', 'niveau' => 'L1 (Licence 1)', 'filiere' => 'GI', 'specialite' => 'GI-Réseaux'],
            ['nom' => 'L1 GI-InfoG', 'niveau' => 'L1 (Licence 1)', 'filiere' => 'GI', 'specialite' => 'GI-InfoG'],
            ['nom' => 'L1 SJ-P', 'niveau' => 'L1 (Licence 1)', 'filiere' => 'SJ', 'specialite' => 'SJ-P'],
            ['nom' => 'L1 SJ-Pu', 'niveau' => 'L1 (Licence 1)', 'filiere' => 'SJ', 'specialite' => 'SJ-Pu'],
            ['nom' => 'L1 SC-Cpt', 'niveau' => 'L1 (Licence 1)', 'filiere' => 'SC', 'specialite' => 'SC-Cpt'],
            ['nom' => 'L1 SC-Log', 'niveau' => 'L1 (Licence 1)', 'filiere' => 'SC', 'specialite' => 'SC-Log'],
            ['nom' => 'L1 SC-B/A/F', 'niveau' => 'L1 (Licence 1)', 'filiere' => 'SC', 'specialite' => 'SC-B/A/F'],
            // L2
            ['nom' => 'L2 SAA-M', 'niveau' => 'L2 (Licence 2)', 'filiere' => 'SAA', 'specialite' => 'SAA-M'],
            ['nom' => 'L2 GI-Réseaux', 'niveau' => 'L2 (Licence 2)', 'filiere' => 'GI', 'specialite' => 'GI-Réseaux'],
            ['nom' => 'L2 SC-Cpt', 'niveau' => 'L2 (Licence 2)', 'filiere' => 'SC', 'specialite' => 'SC-Cpt'],
            // L3
            ['nom' => 'L3 SAA-RH', 'niveau' => 'L3 (Licence 3)', 'filiere' => 'SAA', 'specialite' => 'SAA-RH'],
            ['nom' => 'L3 SJ-Daff', 'niveau' => 'L3 (Licence 3)', 'filiere' => 'SJ', 'specialite' => 'SJ-Daff'],
        ];

        $classesCreated = [];
        foreach ($classesData as $classeData) {
            $niveau = $niveauxCreated[$classeData['niveau']];
            $filiere = $filieresCreated[$classeData['filiere']];
            
            $classe = Classe::firstOrCreate(
                ['nom' => $classeData['nom']],
                [
                    'filiere_id' => $filiere->id,
                    'niveau_id' => $niveau->id,
                ]
            );
            $classesCreated[$classeData['nom']] = $classe;
            $this->command->info("   ✅ {$classe->nom} (ID: {$classe->id})");
        }

        // V. Créer quelques enseignants pour les tests
        $this->command->info("\n👨‍🏫 Création des enseignants de test...");
        $enseignants = [
            ['name' => 'Prof. Diallo', 'email' => 'prof.diallo@iesca.com'],
            ['name' => 'Prof. Traoré', 'email' => 'prof.traore@iesca.com'],
            ['name' => 'Prof. Keita', 'email' => 'prof.keita@iesca.com'],
        ];

        $enseignantsCreated = [];
        foreach ($enseignants as $index => $enseignantData) {
            $enseignant = User::firstOrCreate(
                ['email' => $enseignantData['email']],
                [
                    'name' => $enseignantData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'enseignant',
                ]
            );
            $enseignantsCreated[$index + 1] = $enseignant;
            $this->command->info("   ✅ {$enseignant->name} (ID: {$enseignant->id})");
        }

        // VI. Créer des cours pour la classe L1 SAA-M
        $this->command->info("\n📝 Création des cours pour L1 SAA-M...");
        $coursData = [
            ['nom' => 'Introduction au Management', 'code' => 'SAA101', 'coefficient' => 3, 'description' => 'Fondamentaux du management et de la gestion d\'entreprise'],
            ['nom' => 'Comptabilité Générale I', 'code' => 'SAA102', 'coefficient' => 3, 'description' => 'Principes de base de la comptabilité générale'],
            ['nom' => 'Marketing Fondamental', 'code' => 'SAA103', 'coefficient' => 2, 'description' => 'Introduction aux concepts de marketing'],
            ['nom' => 'Gestion des Ressources Humaines', 'code' => 'SAA104', 'coefficient' => 2, 'description' => 'Fondamentaux de la GRH'],
            ['nom' => 'Économie Générale', 'code' => 'SAA105', 'coefficient' => 2, 'description' => 'Principes fondamentaux de l\'économie'],
        ];

        $coursCreated = [];
        foreach ($coursData as $coursInfo) {
            $cours = Cours::firstOrCreate(
                ['code' => $coursInfo['code']],
                [
                    'nom' => $coursInfo['nom'],
                    'coefficient' => $coursInfo['coefficient'],
                    'description' => $coursInfo['description'],
                ]
            );
            $coursCreated[] = $cours;
            $this->command->info("   ✅ {$cours->nom} ({$cours->code})");
        }

        // Associer les cours à la classe L1 SAA-M
        $classeL1SAAM = $classesCreated['L1 SAA-M'];
        foreach ($coursCreated as $cours) {
            if (!$classeL1SAAM->cours()->where('cours_id', $cours->id)->exists()) {
                $classeL1SAAM->cours()->attach($cours->id, ['semestre' => 1]);
            }
        }
        $this->command->info("   ✅ Cours associés à la classe L1 SAA-M");

        // VII. Créer le calendrier des cours pour L1 SAA-M
        $this->command->info("\n📅 Création du calendrier des cours pour L1 SAA-M...");
        $calendrierData = [
            [
                'cours' => 'Introduction au Management',
                'jour_semaine' => 'Lundi',
                'heure_debut' => '08:00:00',
                'heure_fin' => '10:00:00',
                'salle' => 'Salle A101',
                'enseignant' => 'Prof. Diallo',
                'semestre' => 1,
            ],
            [
                'cours' => 'Comptabilité Générale I',
                'jour_semaine' => 'Mardi',
                'heure_debut' => '10:00:00',
                'heure_fin' => '13:00:00',
                'salle' => 'Salle A102',
                'enseignant' => 'Prof. Traoré',
                'semestre' => 1,
            ],
            [
                'cours' => 'Marketing Fondamental',
                'jour_semaine' => 'Jeudi',
                'heure_debut' => '14:00:00',
                'heure_fin' => '16:00:00',
                'salle' => 'Salle A103',
                'enseignant' => 'Prof. Keita',
                'semestre' => 1,
            ],
        ];

        foreach ($calendrierData as $calData) {
            $cours = collect($coursCreated)->firstWhere('nom', $calData['cours']);
            if ($cours) {
                CalendrierCours::firstOrCreate(
                    [
                        'classe_id' => $classeL1SAAM->id,
                        'cours_id' => $cours->id,
                        'semestre' => $calData['semestre'],
                        'jour_semaine' => $calData['jour_semaine'],
                        'heure_debut' => $calData['heure_debut'],
                    ],
                    [
                        'heure_fin' => $calData['heure_fin'],
                        'salle' => $calData['salle'],
                        'enseignant' => $calData['enseignant'],
                        'description' => "Cours de {$cours->nom} pour {$classeL1SAAM->nom}",
                    ]
                );
                $this->command->info("   ✅ {$calData['jour_semaine']} {$calData['heure_debut']} - {$calData['cours']}");
            }
        }

        $this->command->info("\n✅ Seed terminé avec succès !");
        $this->command->info("\n📊 Résumé:");
        $this->command->info("   - Niveaux: " . count($niveauxCreated));
        $this->command->info("   - Filières: " . count($filieresCreated));
        $this->command->info("   - Spécialités: " . count($specialitesCreated));
        $this->command->info("   - Classes: " . count($classesCreated));
        $this->command->info("   - Cours: " . count($coursCreated));
        $this->command->info("   - Enseignants: " . count($enseignantsCreated));
        $this->command->info("\n💡 Pour tester le calendrier, accédez à la classe L1 SAA-M (ID: {$classeL1SAAM->id})");
    }
}

