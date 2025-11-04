<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->unsignedBigInteger('filiere_id')->nullable()->after('decided_by');
            $table->unsignedBigInteger('specialite_id')->nullable()->after('filiere_id');
            $table->unsignedBigInteger('classe_id')->nullable()->after('specialite_id');
        });
    }

    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropColumn(['filiere_id', 'specialite_id', 'classe_id']);
        });
    }
};


