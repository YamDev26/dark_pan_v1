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
        Schema::create('tranche_moyennes', function (Blueprint $table) {
            $table->id();
            $table->integer('moyenne_0_849');
            $table->integer('moyenne_850_999');
            $table->integer('moyenne_10_1199');
            $table->integer('moyenne_12_1399');
            $table->integer('moyenne_14_1599');
            $table->integer('moyenne_16_plus');
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
        Schema::dropIfExists('tranche_moyennes');
    }
};
