<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JapanRegion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('japan_regions', function (Blueprint $table) {
            $table->increments('id');
            $table->char('country_code', 2)->default('JP');
            $table->foreign('country_code')->references('country_code')->on('country_codes')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('name_kanji')->nullable();
            $table->string('name_kana')->nullable();
            $table->timestamp('data_entry_time')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('japan_regions');
    }
}
