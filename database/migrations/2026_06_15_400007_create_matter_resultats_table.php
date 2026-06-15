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
        Schema::create('matter_resultats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('valeur');
            $table->unsignedBigInteger('get_classe_id');
            $table->unsignedBigInteger('level_matter_id');
            $table->unsignedBigInteger('cutting_school_year_id');
            $table->foreign('get_classe_id')->references('id')->on('get_classes')->onDelete('cascade');
            $table->foreign('level_matter_id')->references('id')->on('level_matters')->onDelete('cascade');
            $table->foreign('cutting_school_year_id')->references('id')->on('cutting_school_years')->onDelete('cascade');
            $table->unique(['get_classe_id', 'level_matter_id', 'cutting_school_year_id'], 'glc_unique'); // Contrainte unique sur les deux colonnes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matter_resultats');
    }
};
