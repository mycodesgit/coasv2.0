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
        Schema::create('faculty', function (Blueprint $table) {
            $table->id();
            $table->string('campus');
            $table->string('dept');
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('ext');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('isFaculty');
            $table->string('rank');
            $table->integer('adrID');
            $table->rememberToken();
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
        Schema::dropIfExists('faculty');
    }
};
