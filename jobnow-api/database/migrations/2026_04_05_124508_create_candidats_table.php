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
       Schema::create('candidats', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->date('dateNaissance')->nullable();
            $table->string('telephone');
            $table->string('email');                  
            $table->string('cv')->nullable();          
            $table->string('photo')->nullable();       
            $table->text('experience')->nullable();    
            
            // (Foreign Keys)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ville_id')->constrained('villes'); 
            
            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};
