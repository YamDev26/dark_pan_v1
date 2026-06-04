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
        Schema::create('evaluats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('note')->default('nc');
            $table->unsignedBigInteger('register_id');
            $table->unsignedBigInteger('evaluated_id');
            $table->foreign('register_id')->references('id')->on('registers')->onDelete('cascade');
            $table->foreign('evaluated_id')->references('id')->on('evaluateds')->onDelete('cascade');
            $table->unique(['register_id', 'evaluated_id']); // Contrainte unique sur les deux colonnes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluats');
    }
};
