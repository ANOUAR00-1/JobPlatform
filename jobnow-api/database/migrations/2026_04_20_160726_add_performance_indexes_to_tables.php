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
        // Offres table indexes
        Schema::table('offres', function (Blueprint $table) {
            $table->index('statut', 'idx_offres_statut');
            $table->index('ville_id', 'idx_offres_ville_id');
            $table->index('entreprise_id', 'idx_offres_entreprise_id');
            $table->index('date_expiration', 'idx_offres_date_expiration');
            $table->index('type_contrat', 'idx_offres_type_contrat');
            $table->index('created_at', 'idx_offres_created_at');
        });

        // Candidatures table indexes
        Schema::table('candidatures', function (Blueprint $table) {
            $table->index('statut', 'idx_candidatures_statut');
            $table->index('candidat_id', 'idx_candidatures_candidat_id');
            $table->index('offre_id', 'idx_candidatures_offre_id');
            $table->index('created_at', 'idx_candidatures_created_at');
        });

        // Candidats table indexes
        Schema::table('candidats', function (Blueprint $table) {
            $table->index('user_id', 'idx_candidats_user_id');
            $table->index('ville_id', 'idx_candidats_ville_id');
            $table->index('email', 'idx_candidats_email');
        });

        // Entreprises table indexes
        Schema::table('entreprises', function (Blueprint $table) {
            $table->index('user_id', 'idx_entreprises_user_id');
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
            $table->index('is_verified', 'idx_users_is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropIndex('idx_offres_statut');
            $table->dropIndex('idx_offres_ville_id');
            $table->dropIndex('idx_offres_entreprise_id');
            $table->dropIndex('idx_offres_date_expiration');
            $table->dropIndex('idx_offres_type_contrat');
            $table->dropIndex('idx_offres_created_at');
        });

        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropIndex('idx_candidatures_statut');
            $table->dropIndex('idx_candidatures_candidat_id');
            $table->dropIndex('idx_candidatures_offre_id');
            $table->dropIndex('idx_candidatures_created_at');
        });

        Schema::table('candidats', function (Blueprint $table) {
            $table->dropIndex('idx_candidats_user_id');
            $table->dropIndex('idx_candidats_ville_id');
            $table->dropIndex('idx_candidats_email');
        });

        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropIndex('idx_entreprises_user_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_is_verified');
        });
    }
};
