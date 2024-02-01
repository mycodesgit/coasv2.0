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
        Schema::create('studgrades', function (Blueprint $table) {
            $table->id();
            $table->string('studID')->nullable();
            $table->string('subjID')->nullable();
            $table->string('subjFgrade')->nullable();
            $table->string('subjComp')->nullable();
            $table->string('creditEarned')->nullable();
            $table->enum('status', array(1, 2))->nullable();
            $table->string('postedBy')->nullable();
            $table->string('postedDate')->nullable();
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
        Schema::dropIfExists('studgrades');
    }
};
