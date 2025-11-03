<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Classe;
use App\Models\Cours;

class IESCADataSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les vraies filières de l'IESCA
        $filieres = [
            [
                'nom' => 'Sciences et Administration des Affaires',
                'description' => 'Management et entrepreneuriat, Gestion des ressources humaines'
            ],
            [
                'nom' => 'Génie Informatique',
                'description' => 'Réseaux et télécommunications, Informatique de gestion'
            ],
            [
                'nom' => 'Sciences Juridiques',
                'description' => 'Droit privé, Droit public, Droit des affaires'
            ],
            [
                'nom' => 'Sciences Commerciales',
                'description' => 'Comptabilité, Management de la chaîne logistique, Banque, Assurance et finances'
            ],
        ];

        foreach ($filieres as $filiereData) {
            $filiere = Filiere::create($filiereData);
            
            // Créer les classes pour chaque niveau de licence
            foreach (Niveau::whereIn('nom', ['L1', 'L2', 'L3'])->get() as $niveau) {
                Classe::create([
                    'nom' => $niveau->nom . ' - ' . substr($filiere->nom, 0, 15),
                    'filiere_id' => $filiere->id,
                    'niveau_id' => $niveau->id,
                ]);
            }
        }

        // Créer quelques cours exemples pour Génie Informatique L1
        $coursGI = [
            ['nom' => 'Algorithmique et Programmation', 'code' => 'GI101', 'coefficient' => 3],
            ['nom' => 'Architecture des Ordinateurs', 'code' => 'GI102', 'coefficient' => 2],
            ['nom' => 'Mathématiques pour l\'Informatique', 'code' => 'GI103', 'coefficient' => 3],
            ['nom' => 'Introduction aux Réseaux', 'code' => 'GI104', 'coefficient' => 2],
            ['nom' => 'Base de Données', 'code' => 'GI105', 'coefficient' => 3],
        ];

        foreach ($coursGI as $coursData) {
            Cours::create($coursData);
        }

        // Créer quelques cours exemples pour Sciences Commerciales
        $coursSC = [
            ['nom' => 'Comptabilité Générale I', 'code' => 'SC101', 'coefficient' => 3],
            ['nom' => 'Économie Générale', 'code' => 'SC102', 'coefficient' => 2],
            ['nom' => 'Mathématiques Financières', 'code' => 'SC103', 'coefficient' => 3],
            ['nom' => 'Introduction au Management', 'code' => 'SC104', 'coefficient' => 2],
        ];

        foreach ($coursSC as $coursData) {
            Cours::create($coursData);
        }
    }
}

