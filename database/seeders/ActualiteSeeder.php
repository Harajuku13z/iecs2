<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Actualite;
use Illuminate\Support\Str;

class ActualiteSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['general', 'academique', 'evenement', 'admission', 'vie etudiante'];

        for ($i = 1; $i <= 12; $i++) {
            Actualite::create([
                'titre' => 'Actualité IESCA #' . $i,
                'description' => 'Bref résumé de l\'actualité #' . $i . ' à l\'IESCA. ' . Str::random(20),
                'contenu' => 'Contenu détaillé de l\'actualité #' . $i . '. ' . Str::random(120),
                'image' => null,
                'categorie' => $categories[array_rand($categories)],
                'publie' => true,
                'date_publication' => now()->subDays(rand(1, 90)),
            ]);
        }
    }
}


