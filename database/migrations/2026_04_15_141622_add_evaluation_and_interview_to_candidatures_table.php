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
      Schema::table('candidatures', function (Blueprint $table) {
    
    $table->integer('note_evaluation')->nullable()->after('statut');
    $table->text('commentaire_recruteur')->nullable()->after('note_evaluation');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
     Schema::table('candidatures', function (Blueprint $table) {
    $table->dropColumn(['note_evaluation', 'commentaire_recruteur']);
});
    }
};
