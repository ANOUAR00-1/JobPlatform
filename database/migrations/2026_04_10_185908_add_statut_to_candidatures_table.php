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
        if (!Schema::hasColumn('candidatures', 'statut')) {
            Schema::table('candidatures', function (Blueprint $table) {
                // [JNV-14] - Task: Add statut to candidatures
                $table->enum('statut', ['en_attente', 'acceptee', 'refusee'])->default('en_attente');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('candidatures', 'statut')) {
            Schema::table('candidatures', function (Blueprint $table) {
                $table->dropColumn('statut');
            });
        }
    }
};
