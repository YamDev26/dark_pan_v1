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
        Schema::create('matters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('libelle')->unique();
            $table->string('symbol')->nullable();
            $table->integer('order')->nullable();
            $table->enum('officiel',[0,1])->default(1);
            $table->unsignedBigInteger('bilan_matter_id')->nullable();
            $table->enum('status', [0,1])->default(1);
            $table->foreign('bilan_matter_id')->references('id')->on('bilan_matters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matters');
    }
};
