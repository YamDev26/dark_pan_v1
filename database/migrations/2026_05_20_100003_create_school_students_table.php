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
        Schema::create('school_students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('residence')->nullable();
            $table->enum('type',['parent', 'tuteur']);
            $table->enum('status', [0, 1])->default(1);
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('tuteur_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('school_year_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('tuteur_id')->references('id')->on('tuteurs')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('cascade');
            $table->unique(['student_id', 'school_id']); // Contrainte unique sur les deux colonnes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_students');
    }
};
