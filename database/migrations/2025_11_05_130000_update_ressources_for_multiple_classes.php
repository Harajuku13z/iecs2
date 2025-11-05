<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter description si elle n'existe pas
        Schema::table('ressources', function (Blueprint $table) {
            if (!Schema::hasColumn('ressources', 'description')) {
                $table->text('description')->nullable()->after('contenu');
            }
            // Permettre que contenu soit un lien ou un chemin de fichier
            if (!Schema::hasColumn('ressources', 'lien')) {
                $table->string('lien')->nullable()->after('contenu');
            }
            // Rendre classe_id nullable car on va utiliser une table pivot
            Schema::table('ressources', function (Blueprint $table) {
                $table->foreignId('classe_id')->nullable()->change();
            });
        });

        // Créer table pivot pour plusieurs classes par ressource
        if (!Schema::hasTable('ressource_classe')) {
            Schema::create('ressource_classe', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ressource_id')->constrained('ressources')->onDelete('cascade');
                $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['ressource_id', 'classe_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ressource_classe');
        
        Schema::table('ressources', function (Blueprint $table) {
            if (Schema::hasColumn('ressources', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('ressources', 'lien')) {
                $table->dropColumn('lien');
            }
        });
    }
};

