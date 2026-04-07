<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
<<<<<<< HEAD
=======
    /**
     * Run the migrations.
     */
>>>>>>> origin/feature/dev
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
<<<<<<< HEAD
            $table->string('raison_social');
=======
            $table->string('raison_social'); // Company name
>>>>>>> origin/feature/dev
            $table->string('adresse')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->timestamps();
        });
<<<<<<< HEAD
    }

    public function down(): void
    {
        Schema::dropIfExists('entreprises');
=======

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
>>>>>>> origin/feature/dev
    }
};
