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
        Schema::create('sub_matters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('libelle');
            $table->string('symbol');
            $table->enum('status', [0,1])->default(1);
            $table->unsignedBigInteger('matter_id')->nullable();
            $table->foreign('matter_id')->references('id')->on('matters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_matters');
    }
};
