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
        Schema::create('classe_teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('checked')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('get_classe_id');
            $table->unsignedBigInteger('level_matter_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('get_classe_id')->references('id')->on('get_classes')->onDelete('cascade');
            $table->foreign('level_matter_id')->references('id')->on('level_matters')->onDelete('cascade');
            $table->unique(['user_id', 'get_classe_id', 'level_matter_id'], 'ugl_unique'); // Contrainte unique sur les deux colonnes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classe_teachers');
    }
};
