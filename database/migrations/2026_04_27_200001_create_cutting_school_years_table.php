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
        Schema::create('cutting_school_years', function (Blueprint $table) {
            $table->id();
            $table->string('fin');
            $table->string('debut');
            $table->unsignedBigInteger('cutting_id');
            $table->unsignedBigInteger('school_year_id');
            $table->enum('status', [1,2,3])->comment('En attente, En cours, Terminé');
            $table->foreign('cutting_id')->references('id')->on('cuttings')->onDelete('cascade');
            $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutting_school_years');
    }
};
