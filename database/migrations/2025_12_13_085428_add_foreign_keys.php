<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professeurs', function (Blueprint $table) {
            $table->foreign('equipe_id', 'fk_professeurs_equipe_id')->references('id')->on('equipes')->onDelete('set null');
        });

        Schema::table('equipes', function (Blueprint $table) {
            $table->foreign('id_chef_equipe', 'fk_equipes_id_chef_equipe')->references('id')->on('professeurs')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('equipes', function (Blueprint $table) {
            $table->dropForeign(['id_chef_equipe']);
        });

        Schema::table('professeurs', function (Blueprint $table) {
            $table->dropForeign(['equipe_id']);
        });
    }
};
