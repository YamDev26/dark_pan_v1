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
        Schema::create('moyenne_trimestres', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('moyenne');
            $table->string('rang');
            $table->string('values');
            $table->unsignedBigInteger('register_id');
            $table->unsignedBigInteger('cutting_school_year_id');
            $table->foreign('register_id')->references('id')->on('registers')->onDelete('cascade');
            $table->foreign('cutting_school_year_id')->references('id')->on('cutting_school_years')->onDelete('cascade');
            $table->unique(['register_id', 'cutting_school_year_id']); // Contrainte unique sur les deux colonnes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moyenne_trimestres');
    }
};
