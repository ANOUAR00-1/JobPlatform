<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('description');
            $table->json('competences_requises')->nullable(); // Required skills as JSON array
            $table->foreignId('ville_id')->constrained("villes")->onDelete("cascade");
            $table->string('salaire')->nullable(); // Salary range or amount
            $table->enum('type_contrat', ['CDI', 'CDD', 'Stage', 'Freelance'])->default('CDI');
            $table->date('date_expiration')->nullable();
            $table->enum('statut', ['ouverte', 'fermee', 'pourvue'])->default('ouverte');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
