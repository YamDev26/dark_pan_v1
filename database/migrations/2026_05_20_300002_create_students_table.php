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
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('matricul')->unique();
            $table->string('first');
            $table->string('last');
            $table->enum('genre', ['F','M']);
            $table->string('date');
            $table->string('lieu');
            $table->unsignedBigInteger('notionalitie_id');
            $table->foreign('notionalitie_id')->references('id')->on('notionalities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
