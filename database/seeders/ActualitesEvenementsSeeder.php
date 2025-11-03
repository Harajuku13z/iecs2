<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Actualite;
use App\Models\Evenement;
use Carbon\Carbon;

class ActualitesEvenementsSeeder extends Seeder
{
    public function run(): void
    {
        // Actualités
        $actualites = [
            [
                'titre' => 'Rentrée Académique 2025 - Inscriptions Ouvertes',
                'description' => 'L\'IESCA annonce l\'ouverture des inscriptions pour l\'année académique 2025-2026.',
                'contenu' => 'Nous sommes ravis d\'annoncer l\'ouverture des inscriptions pour la nouvelle année académique. Les candidats peuvent soumettre leurs dossiers en ligne dès maintenant.',
                'image' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800',
                'categorie' => 'Académique',
                'date_publication' => Carbon::now()->subDays(2),
                'publie' => true,
            ],
            [
                'titre' => 'Nouveau Partenariat International avec Harvard',
                'description' => 'L\'IESCA signe un accord de collaboration avec l\'Université Harvard.',
                'contenu' => 'Un accord historique vient d\'être signé entre l\'IESCA et l\'Université Harvard pour des programmes d\'échanges académiques.',
                'image' => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800',
                'categorie' => 'Partenariat',
                'date_publication' => Carbon::now()->subDays(5),
                'publie' => true,
            ],
            [
                'titre' => 'Innovation Tech : Laboratoire IA inauguré',
                'description' => 'L\'IESCA inaugure son nouveau laboratoire d\'Intelligence Artificielle.',
                'contenu' => 'Le nouveau laboratoire d\'IA permettra aux étudiants de travailler sur des projets innovants avec les dernières technologies.',
                'image' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800',
                'categorie' => 'Innovation',
                'date_publication' => Carbon::now()->subDays(7),
                'publie' => true,
            ],
        ];

        foreach ($actualites as $actu) {
            Actualite::create($actu);
        }

        // Événements
        $evenements = [
            [
                'titre' => 'Journée Portes Ouvertes',
                'description' => 'Venez découvrir nos formations et rencontrer nos enseignants',
                'lieu' => 'Campus IESCA - Bâtiment Principal',
                'date_debut' => Carbon::now()->addDays(10)->setTime(9, 0),
                'date_fin' => Carbon::now()->addDays(10)->setTime(17, 0),
                'type' => 'Orientation',
                'publie' => true,
            ],
            [
                'titre' => 'Conférence sur l\'Intelligence Artificielle',
                'description' => 'Conférence internationale sur les applications de l\'IA en Afrique',
                'lieu' => 'Amphithéâtre A',
                'date_debut' => Carbon::now()->addDays(15)->setTime(14, 0),
                'date_fin' => Carbon::now()->addDays(15)->setTime(18, 0),
                'type' => 'Conférence',
                'publie' => true,
            ],
            [
                'titre' => 'Forum Entreprises & Carrières',
                'description' => 'Rencontrez les recruteurs des plus grandes entreprises',
                'lieu' => 'Hall d\'exposition',
                'date_debut' => Carbon::now()->addDays(20)->setTime(10, 0),
                'date_fin' => Carbon::now()->addDays(20)->setTime(16, 0),
                'type' => 'Carrière',
                'publie' => true,
            ],
            [
                'titre' => 'Hackathon Innovation 2025',
                'description' => '48h de développement pour créer des solutions innovantes',
                'lieu' => 'Laboratoire Informatique',
                'date_debut' => Carbon::now()->addDays(30)->setTime(8, 0),
                'date_fin' => Carbon::now()->addDays(32)->setTime(18, 0),
                'type' => 'Compétition',
                'publie' => true,
            ],
        ];

        foreach ($evenements as $event) {
            Evenement::create($event);
        }
    }
}
