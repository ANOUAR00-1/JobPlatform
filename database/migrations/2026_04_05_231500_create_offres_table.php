<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('description');
            $table->json('competences_requises')->nullable();
            $table->string('localisation')->nullable();
            $table->string('salaire', 100)->nullable();
            $table->enum('type_contrat', ['CDI', 'CDD', 'Stage', 'Freelance'])->default('CDI');
            $table->date('date_expiration')->nullable();
            $table->enum('statut', ['ouverte', 'fermee', 'pourvue'])->default('ouverte');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
