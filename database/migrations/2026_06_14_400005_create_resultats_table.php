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
        Schema::create('resultats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('effectif');
            $table->string('reussite');
            $table->string('moyenne');
            $table->string('max');
            $table->string('min');
            $table->string('taux_f')->nullable();
            $table->string('taux_m')->nullable();
            $table->string('dificulte')->nullable();
            $table->unsignedBigInteger('get_classe_id');
            $table->unsignedBigInteger('cutting_school_year_id');
            $table->foreign('get_classe_id')->references('id')->on('get_classes')->onDelete('cascade');
            $table->foreign('cutting_school_year_id')->references('id')->on('cutting_school_years')->onDelete('cascade');
            $table->unique(['get_classe_id', 'cutting_school_year_id']); // Contrainte unique sur les deux colonnes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultats');
    }
};
