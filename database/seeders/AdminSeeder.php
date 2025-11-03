<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@iesca.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Sample Filieres
        $filieres = [
            ['nom' => 'Génie Informatique', 'description' => 'Formation en développement logiciel et systèmes informatiques'],
            ['nom' => 'Génie Civil', 'description' => 'Formation en construction et travaux publics'],
            ['nom' => 'Gestion des Entreprises', 'description' => 'Formation en management et administration des affaires'],
        ];

        foreach ($filieres as $filiere) {
            Filiere::create($filiere);
        }

        // Create Niveaux
        $niveaux = [
            ['nom' => 'Je prépare mon bac', 'ordre' => 1],
            ['nom' => 'Bac', 'ordre' => 2],
            ['nom' => 'L1 (Licence 1)', 'ordre' => 3],
            ['nom' => 'L2 (Licence 2)', 'ordre' => 4],
            ['nom' => 'L3 (Licence 3)', 'ordre' => 5],
        ];

        foreach ($niveaux as $niveau) {
            Niveau::create($niveau);
        }

        // Create Settings
        $settings = [
            ['cle' => 'homepage_title', 'valeur' => 'Bienvenue à l\'IESCA', 'description' => 'Titre de la page d\'accueil'],
            ['cle' => 'inscription_start_date', 'valeur' => '2025-01-15', 'description' => 'Date de début des inscriptions'],
            ['cle' => 'frais_mensuels', 'valeur' => '50000', 'description' => 'Frais de scolarité mensuels'],
            ['cle' => 'banner_image', 'valeur' => '', 'description' => 'Image de bannière de la page d\'accueil'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
