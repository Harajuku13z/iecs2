<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->unsignedBigInteger('verified_by')->nullable()->after('evaluation_date');
            $table->unsignedBigInteger('evaluated_by')->nullable()->after('verified_by');
            $table->unsignedBigInteger('decided_by')->nullable()->after('evaluated_by');
        });
    }

    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropColumn(['verified_by', 'evaluated_by', 'decided_by']);
        });
    }
};


