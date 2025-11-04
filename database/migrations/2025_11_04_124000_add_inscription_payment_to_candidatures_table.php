<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->boolean('inscription_paid')->default(false)->after('classe_id');
            $table->unsignedBigInteger('inscription_paid_by')->nullable()->after('inscription_paid');
            $table->dateTime('inscription_paid_at')->nullable()->after('inscription_paid_by');
        });
    }

    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropColumn(['inscription_paid', 'inscription_paid_by', 'inscription_paid_at']);
        });
    }
};


