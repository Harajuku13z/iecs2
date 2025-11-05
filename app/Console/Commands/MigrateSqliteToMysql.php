<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class MigrateSqliteToMysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:sqlite-to-mysql {--force : Force la migration même si des données existent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migre les données de SQLite vers MySQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sqlitePath = database_path('database.sqlite');
        
        if (!File::exists($sqlitePath)) {
            $this->error('Le fichier SQLite n\'existe pas à: ' . $sqlitePath);
            return 1;
        }

        $this->info('Début de la migration SQLite vers MySQL...');
        
        // Configurer temporairement la connexion SQLite
        config(['database.connections.sqlite.database' => $sqlitePath]);
        DB::purge('sqlite');
        
        // Vérifier que MySQL est bien configuré
        try {
            DB::connection('mysql')->getPdo();
        } catch (\Exception $e) {
            $this->error('Impossible de se connecter à MySQL: ' . $e->getMessage());
            return 1;
        }

        // Liste des tables à migrer (dans l'ordre pour respecter les clés étrangères)
        $tables = [
            'users',
            'settings',
            'filieres',
            'niveaux',
            'specialites',
            'classes',
            'cours',
            'candidatures',
            'actualites',
            'evenements',
            'calendrier_cours',
            'notes',
            'ressources',
            'notifications',
            'static_pages',
        ];

        foreach ($tables as $table) {
            $this->info("Migration de la table: {$table}");
            
            try {
                // Vérifier si la table existe dans SQLite
                if (!Schema::connection('sqlite')->hasTable($table)) {
                    $this->warn("  Table {$table} n'existe pas dans SQLite, ignorée.");
                    continue;
                }

                // Vérifier si la table existe dans MySQL
                if (!Schema::connection('mysql')->hasTable($table)) {
                    $this->warn("  Table {$table} n'existe pas dans MySQL, ignorée.");
                    continue;
                }

                // Récupérer les données de SQLite
                $data = DB::connection('sqlite')->table($table)->get();
                
                if ($data->isEmpty()) {
                    $this->info("  Aucune donnée à migrer pour {$table}");
                    continue;
                }

                // Vérifier si des données existent déjà dans MySQL
                $existingCount = DB::connection('mysql')->table($table)->count();
                
                if ($existingCount > 0 && !$this->option('force')) {
                    if (!$this->confirm("  La table {$table} contient déjà {$existingCount} enregistrements. Continuer ?", false)) {
                        $this->warn("  Migration de {$table} ignorée.");
                        continue;
                    }
                }

                // Vider la table MySQL si --force
                if ($this->option('force') && $existingCount > 0) {
                    DB::connection('mysql')->table($table)->truncate();
                    $this->info("  Table {$table} vidée.");
                }

                // Insérer les données par batch
                $chunks = $data->chunk(100);
                $bar = $this->output->createProgressBar($data->count());
                $bar->start();

                foreach ($chunks as $chunk) {
                    $insertData = $chunk->map(function ($item) {
                        return (array) $item;
                    })->toArray();
                    
                    try {
                        DB::connection('mysql')->table($table)->insert($insertData);
                        $bar->advance($chunk->count());
                    } catch (\Exception $e) {
                        $this->newLine();
                        $this->error("  Erreur lors de l'insertion dans {$table}: " . $e->getMessage());
                        $bar->finish();
                        continue 2;
                    }
                }

                $bar->finish();
                $this->newLine();
                $this->info("  ✓ {$data->count()} enregistrements migrés pour {$table}");
                
            } catch (\Exception $e) {
                $this->error("  Erreur lors de la migration de {$table}: " . $e->getMessage());
                continue;
            }
        }

        $this->newLine();
        $this->info('Migration terminée avec succès !');
        
        return 0;
    }
}

