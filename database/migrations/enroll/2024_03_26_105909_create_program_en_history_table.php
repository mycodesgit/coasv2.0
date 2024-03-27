<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_en_history', function (Blueprint $table) {
            $table->id();
            $table->string('studentID')->nullable();
            $table->string('schlyear')->nullable();
            $table->integer('semester')->nullable();
            $table->string('campus')->nullable();
            $table->string('course')->nullable();
            $table->string('progCod')->nullable();
            $table->string('studMajor')->nullable();
            $table->string('studMinor')->nullable();
            $table->string('studLevel')->nullable();
            $table->string('studYear')->nullable();
            $table->string('studSec')->nullable();
            $table->string('studUnit')->nullable();
            $table->string('studStatus')->nullable();
            $table->integer('studSch')->nullable();
            $table->integer('studClassID')->nullable();
            $table->string('postedBy')->nullable();
            $table->string('confirmBy')->nullable();
            $table->string('postedDate')->nullable();
            $table->string('studType')->nullable();
            $table->string('transferee')->nullable();
            $table->integer('fourPs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_en_history');
    }
};
