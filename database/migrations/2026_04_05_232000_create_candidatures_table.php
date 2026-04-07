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
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->string('cv_path');
            $table->text('lettre_motivation')->nullable();
            $table->enum('statut', ['en_attente', 'acceptee', 'refusee'])->default('en_attente');
            $table->timestamps();

            $table->unique(['offre_id', 'candidat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
