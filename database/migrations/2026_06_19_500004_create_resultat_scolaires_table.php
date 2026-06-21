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
        Schema::create('resultat_scolaires', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->integer('nbres_t');
            $table->integer('nbres_g');
            $table->integer('nbres_f');
            $table->integer('admis');
            $table->integer('admis_g');
            $table->integer('admis_f');
            $table->string('taux_a');
            $table->string('taux_g');
            $table->string('taux_f');
            $table->integer('classee');
            $table->integer('non_classe');
            $table->enum('type',['cycle1', 'cycle2', 'total']);
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('cutting_school_year_id');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('cutting_school_year_id')->references('id')->on('cutting_school_years')->onDelete('cascade');
            $table->unique(['type', 'school_id', 'cutting_school_year_id'], 'tsc_unique'); // Contrainte unique sur les deux colonnes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultat_scolaires');
    }
};
