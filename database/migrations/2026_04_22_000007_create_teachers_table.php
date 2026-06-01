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
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('date_naiss');
            $table->string('lieu_naiss');
            $table->enum('piece', ['cni', 'permis', 'passport']);
            $table->string('num_piece')->unique();
            $table->integer('etude')->nullable();
            $table->integer('experiens')->nullable();
            $table->string('diplome')->nullable();
            $table->boolean('autorisate')->nullable();
            $table->enum('type',['permanent', 'vacataire'])->nullable();
            $table->string('num_autorisate')->nullable();
            $table->string('date_autorisate')->nullable();
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
        Schema::dropIfExists('teachers');
    }
};
