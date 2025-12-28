<?php

/**
 * Migration pour créer la table des co-auteurs
 *
 * Cette migration crée la table pivot 'co_auteurs' qui établit
 * la relation many-to-many entre les publications et les professeurs
 * (co-auteurs d'une publication).
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter la migration
     *
     * Crée la table 'co_auteurs' avec les clés étrangères vers
     * les tables publications et professeurs, ainsi qu'une contrainte
     * d'unicité pour éviter les doublons.
     */
    public function up(): void
    {
        Schema::create('co_auteurs', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->foreignId('publication_id')->constrained('publications')->onDelete('cascade'); // Clé étrangère vers publications
            $table->foreignId('professeur_id')->constrained('professeurs')->onDelete('cascade'); // Clé étrangère vers professeurs
            $table->timestamps(); // Champs created_at et updated_at

            $table->unique(['publication_id', 'professeur_id']); // Contrainte d'unicité pour éviter les doublons
        });
    }

    /**
     * Annuler la migration
     *
     * Supprime la table 'co_auteurs' si elle existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('co_auteurs');
    }
};
