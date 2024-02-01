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
        Schema::create('ad_applicant_docs', function (Blueprint $table) {
            $table->id();
            $table->integer('admission_id');
            $table->string('r_card')->nullable();
            $table->string('g_moral')->nullable();
            $table->string('t_record')->nullable();
            $table->string('b_cert')->nullable();
            $table->string('h_dismissal')->nullable();
            $table->string('m_cert')->nullable();
            $table->string('doc_image')->nullable();
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
        Schema::dropIfExists('ad_applicant_docs');
    }
};
