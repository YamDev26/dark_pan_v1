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
        Schema::create('level_matters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('value');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('matter_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('serie_id')->nullable();
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade');
            $table->foreign('matter_id')->references('id')->on('matters')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('cascade');
            $table->unique(['level_id', 'matter_id', 'school_id']); // Contrainte unique sur les deux colonnes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_matters');
    }
};
