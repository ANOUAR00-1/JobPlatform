<?php

<<<<<<< HEAD
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
=======
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
       Schema::create('offres', function (Blueprint $table) {
>>>>>>> origin/feature/dev
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('description');
<<<<<<< HEAD
            $table->json('competences_requises')->nullable();
            $table->string('localisation')->nullable();
            $table->string('salaire', 100)->nullable();
=======
            $table->json('competences_requises')->nullable(); // Required skills as JSON array
            $table->foreignId('ville_id')->constrained('villes')->onDelete('cascade'); // Foreign key to villes table
            $table->string('salaire')->nullable(); // Salary range or amount
>>>>>>> origin/feature/dev
            $table->enum('type_contrat', ['CDI', 'CDD', 'Stage', 'Freelance'])->default('CDI');
            $table->date('date_expiration')->nullable();
            $table->enum('statut', ['ouverte', 'fermee', 'pourvue'])->default('ouverte');
            $table->timestamps();
        });
<<<<<<< HEAD
    }

=======

    }

    /**
     * Reverse the migrations.
     */
>>>>>>> origin/feature/dev
    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
