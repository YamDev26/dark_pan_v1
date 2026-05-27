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
        Schema::create('schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('autorisation')->unique();
            $table->string('name_school')->unique();
            $table->string('slug_school')->nullable();
            $table->string('email_school')->unique()->nullable();
            $table->string('phon_school')->unique()->nullable();
            $table->string('ville_school')->unique()->nullable();
            $table->string('addres_postal')->unique()->nullable();
            $table->date('created')->unique()->nullable();
            $table->date('opening')->unique()->nullable();
            $table->enum('cycle1',[0,1])->default(0);
            $table->enum('cycle2',[0,1])->default(0);
            $table->date('date1')->unique()->nullable();
            $table->date('date2')->unique()->nullable();
            $table->boolean('param')->default(false);
            $table->enum('status',[0,1])->default(1);
            $table->string('logo')->unique()->nullable();
            $table->unsignedBigInteger('dren_school_id')->nullable();
            $table->foreign('dren_school_id')->references('id')->on('dren_schools')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};