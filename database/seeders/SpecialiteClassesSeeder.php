<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialite;
use App\Models\Classe;
use App\Models\Niveau;

class SpecialiteClassesSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure niveaux exist
        $niveaux = [
            ['nom' => 'L1 (Licence 1)', 'ordre' => 1],
            ['nom' => 'L2 (Licence 2)', 'ordre' => 2],
            ['nom' => 'L3 (Licence 3)', 'ordre' => 3],
        ];
        foreach ($niveaux as $i => $n) {
            Niveau::firstOrCreate(['nom' => $n['nom']], ['ordre' => $n['ordre']]);
        }

        $nivL1 = Niveau::where('nom', 'L1 (Licence 1)')->first();
        $nivL2 = Niveau::where('nom', 'L2 (Licence 2)')->first();
        $nivL3 = Niveau::where('nom', 'L3 (Licence 3)')->first();

        foreach (Specialite::with('filiere')->get() as $specialite) {
            if (!$specialite->filiere) { continue; }
            $base = $specialite->nom;
            // L1
            Classe::firstOrCreate(
                ['nom' => 'L1 - ' . $base, 'filiere_id' => $specialite->filiere_id, 'niveau_id' => $nivL1?->id],
                []
            );
            // L2
            Classe::firstOrCreate(
                ['nom' => 'L2 - ' . $base, 'filiere_id' => $specialite->filiere_id, 'niveau_id' => $nivL2?->id],
                []
            );
            // L3
            Classe::firstOrCreate(
                ['nom' => 'L3 - ' . $base, 'filiere_id' => $specialite->filiere_id, 'niveau_id' => $nivL3?->id],
                []
            );
        }
    }
}


