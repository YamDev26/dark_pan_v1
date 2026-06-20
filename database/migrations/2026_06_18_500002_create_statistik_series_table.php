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
        Schema::create('statistik_series', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('nbre_t');
            $table->integer('nbre_g');
            $table->integer('nbre_f');
            $table->integer('admis');
            $table->integer('admis_g');
            $table->integer('admis_f');
            $table->string('taux_a');
            $table->string('taux_g');
            $table->string('taux_f');
            $table->integer('classee');
            $table->integer('no_classe');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('serie_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('cutting_school_year_id');
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade');
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('cutting_school_year_id')->references('id')->on('cutting_school_years')->onDelete('cascade');
            $table->unique(['level_id', 'serie_id', 'school_id', 'cutting_school_year_id'], 'lssc_unique'); // Contrainte unique sur les deux colonnes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistik_series');
    }
};
