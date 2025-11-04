<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evenement;

class EvenementSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['conference', 'seminaire', 'atelier', 'formation', 'competition', 'autre'];

        for ($i = 1; $i <= 10; $i++) {
            $start = now()->addDays(rand(-30, 60))->setTime(rand(8, 16), [0, 30][array_rand([0, 30])]);
            $end = (clone $start)->addHours(rand(1, 3));

            Evenement::create([
                'titre' => 'Événement IESCA #' . $i,
                'description' => 'Description de l\'événement #' . $i . ' à l\'IESCA.',
                'lieu' => 'Campus IESCA',
                'date_debut' => $start,
                'date_fin' => $end,
                'image' => null,
                'type' => $types[array_rand($types)],
                'publie' => true,
            ]);
        }
    }
}


