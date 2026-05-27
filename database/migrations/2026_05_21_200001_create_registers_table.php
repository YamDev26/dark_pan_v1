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
        Schema::create('registers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('affecte');
            $table->boolean('redoubant');
            $table->boolean('boursier');
            $table->boolean('interne');
            $table->enum('lv2', ['all', 'esp'])->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('get_classe_id');
            $table->unsignedBigInteger('school_student_id');
            $table->foreign('get_classe_id')->references('id')->on('get_classes')->onDelete('cascade');
            $table->foreign('school_student_id')->references('id')->on('school_students')->onDelete('cascade');
            $table->unique(['get_classe_id', 'school_student_id']); // Contrainte unique sur les deux colonnes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registers');
    }
};
