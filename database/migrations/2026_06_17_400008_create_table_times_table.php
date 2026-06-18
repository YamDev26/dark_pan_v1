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
        Schema::create('table_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('period');
            $table->unsignedBigInteger('days_week_id');
            $table->unsignedBigInteger('slot_time_id');
            $table->unsignedBigInteger('get_classe_id');
            $table->unsignedBigInteger('level_matter_id');
            $table->foreign('days_week_id')->references('id')->on('days_weeks')->onDelete('cascade');
            $table->foreign('slot_time_id')->references('id')->on('slot_times')->onDelete('cascade');
            $table->foreign('get_classe_id')->references('id')->on('get_classes')->onDelete('cascade');
            $table->foreign('level_matter_id')->references('id')->on('level_matters')->onDelete('cascade');
            $table->unique(['days_week_id', 'slot_time_id', 'get_classe_id', 'level_matter_id'], 'dsgl_unique'); // Contrainte unique sur les deux colonnes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_times');
    }
};
