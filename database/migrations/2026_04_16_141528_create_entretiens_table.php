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
        Schema::create('entretiens', function (Blueprint $table) {
           $table->id(); 
        $table->date('date'); 
        $table->time('horaire'); 
        $table->string('lieu'); 
        $table->string('lien')->nullable(); 
        $table->string('langue'); 
        $table->boolean('confirmerPresence')->default(false); 
        
        $table->foreignId('candidature_id')->constrained()->onDelete('cascade');
        
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entretiens');
    }
};
