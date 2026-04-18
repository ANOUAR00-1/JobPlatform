<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('candidatures', function (Blueprint $table) {
    $table->id();
    $table->foreignId('offre_id')->constrained()->onDelete('cascade');
    $table->foreignId('candidat_id')->constrained('candidats')->onDelete('cascade');
    $table->string('cv_path');
    $table->text('lettre_motivation')->nullable();
    
    $table->enum('statut', ['en_attente', 'acceptee', 'refusee', 'convoquée'])->default('en_attente');
    $table->integer('note_evaluation')->nullable(); 
    $table->text('commentaire_recruteur')->nullable(); 
    
    $table->timestamps();
    $table->unique(['offre_id', 'candidat_id']);
});
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
