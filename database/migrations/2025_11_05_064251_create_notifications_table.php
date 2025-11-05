<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu');
            $table->enum('type', ['info', 'warning', 'success', 'danger', 'message'])->default('info');
            $table->enum('destinataire_type', ['all', 'classe', 'user', 'role'])->default('all');
            $table->foreignId('classe_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Destinataire spécifique
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null'); // Expéditeur
            $table->string('role')->nullable(); // Pour notifier par rôle
            $table->boolean('envoye_email')->default(false);
            $table->boolean('lu')->default(false);
            $table->timestamp('lu_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
