<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calendrier_cours', function (Blueprint $table) {
            if (!Schema::hasColumn('calendrier_cours', 'semestre')) {
                $table->string('semestre')->nullable()->after('classe_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('calendrier_cours', function (Blueprint $table) {
            if (Schema::hasColumn('calendrier_cours', 'semestre')) {
                $table->dropColumn('semestre');
            }
        });
    }
};


