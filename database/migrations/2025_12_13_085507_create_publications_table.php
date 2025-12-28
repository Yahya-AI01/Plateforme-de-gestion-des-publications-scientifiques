<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->enum('type', ['Article', 'Conférence', 'Chapitre', 'Thèse']);
            $table->integer('annee');
            $table->string('lien_pdf')->nullable();
            $table->string('domaine');
            $table->text('resume');
            $table->foreignId('auteur_principal_id')->constrained('professeurs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};