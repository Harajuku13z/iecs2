<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('static_pages', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description')->nullable(); // Description courte
            $table->longText('contenu'); // Contenu HTML ou texte
            $table->enum('type_contenu', ['html', 'texte'])->default('texte'); // Type de contenu
            $table->string('image_principale')->nullable(); // Photo de mise en avant
            $table->string('menu_nom')->nullable(); // Nom dans le menu
            $table->string('menu_parent')->nullable(); // Menu parent (si sous-menu)
            $table->integer('menu_ordre')->default(0); // Ordre dans le menu
            $table->boolean('publie')->default(true);
            $table->boolean('afficher_menu')->default(true); // Afficher dans le menu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('static_pages');
    }
};
