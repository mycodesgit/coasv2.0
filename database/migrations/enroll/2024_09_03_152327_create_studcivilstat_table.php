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
        Schema::create('studcivilstat', function (Blueprint $table) {
            $table->id();
            $table->string('cvlstat_name')->nullable();
            $table->timestamps();
        });

        DB::table('studcivilstat')->insert([
            ['cvlstat_name' => 'SINGLE', 'created_at' => now(), 'updated_at' => now()],
            ['cvlstat_name' => 'MARRIED', 'created_at' => now(), 'updated_at' => now()],
            ['cvlstat_name' => 'DIVORCED', 'created_at' => now(), 'updated_at' => now()],
            ['cvlstat_name' => 'WIDOWED', 'created_at' => now(), 'updated_at' => now()],
            ['cvlstat_name' => 'SEPERATED', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('studcivilstat');
    }
};
