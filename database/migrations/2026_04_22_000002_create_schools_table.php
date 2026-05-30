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
            $table->enum('etat',['prive','public']);
            $table->string('autorisation')->unique();
            $table->string('name')->unique();
            $table->string('slug')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phon')->unique()->nullable();
            $table->string('city')->unique()->nullable();
            $table->string('addres')->unique()->nullable();
            $table->date('created')->unique()->nullable();
            $table->date('opening')->unique()->nullable();
            $table->boolean('cycle1')->default(false);
            $table->boolean('cycle2')->default(false);
            $table->date('date1')->unique()->nullable();
            $table->date('date2')->unique()->nullable();
            $table->boolean('caisse')->default(false);
            $table->boolean('notes')->default(false);
            $table->boolean('informatik')->default(false);
            $table->boolean('autres')->default(false);
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