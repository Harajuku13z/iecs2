<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classe_cours', function (Blueprint $table) {
            if (!Schema::hasColumn('classe_cours', 'semestre')) {
                $table->string('semestre')->nullable()->after('cours_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('classe_cours', function (Blueprint $table) {
            if (Schema::hasColumn('classe_cours', 'semestre')) {
                $table->dropColumn('semestre');
            }
        });
    }
};


