<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->foreignId('ville_id')->nullable()->constrained()->onDelete('set null');
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
