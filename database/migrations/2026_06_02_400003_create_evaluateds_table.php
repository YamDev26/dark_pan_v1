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
        Schema::create('evaluateds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('created');
            $table->enum('value',[0.5, 1, 2])->default(1);
            $table->enum('actif', [0,1])->default(1);
            $table->unsignedBigInteger('sub_matter_id')->nullable();
            $table->unsignedBigInteger('get_classe_id');
            $table->unsignedBigInteger('level_matter_id');
            $table->unsignedBigInteger('evaluated_type_id');
            $table->unsignedBigInteger('cutting_school_year_id');
            $table->foreign('get_classe_id')->references('id')->on('get_classes')->onDelete('cascade');
            $table->foreign('sub_matter_id')->references('id')->on('sub_matters')->onDelete('cascade');
            $table->foreign('level_matter_id')->references('id')->on('level_matters')->onDelete('cascade');
            $table->foreign('evaluated_type_id')->references('id')->on('evaluated_types')->onDelete('cascade');
            $table->foreign('cutting_school_year_id')->references('id')->on('cutting_school_years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluateds');
    }
};
