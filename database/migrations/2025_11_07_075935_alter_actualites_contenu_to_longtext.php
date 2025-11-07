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
        // Use raw SQL to change column type from TEXT to LONGTEXT
        // This avoids requiring doctrine/dbal package
        \DB::statement('ALTER TABLE `actualites` MODIFY COLUMN `contenu` LONGTEXT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to TEXT
        \DB::statement('ALTER TABLE `actualites` MODIFY COLUMN `contenu` TEXT');
    }
};
