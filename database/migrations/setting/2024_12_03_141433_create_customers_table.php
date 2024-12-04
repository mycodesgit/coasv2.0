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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('queue_number')->unique(); // Unique queue number
            $table->unsignedBigInteger('counter_id')->nullable(); // Assigned counter
            $table->enum('status', ['waiting', 'serving', 'served'])->default('waiting');
            $table->string('campus');
            $table->timestamps();

            $table->foreign('counter_id')->references('id')->on('counters')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
