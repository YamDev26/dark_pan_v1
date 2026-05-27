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
        Schema::create('get_classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('libelle');
            $table->integer('effectif');
            $table->integer('inscrit')->default(0);
            $table->enum('lv2', ['all', 'esp', 'mix'])->nullable();
            $table->enum('invalid', [0, 1])->default(1);
            $table->enum('status', [0, 1])->default(1);
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('serie_id')->nullable();
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade');
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('get_classes');
    }
};
