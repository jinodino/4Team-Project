<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillInfoCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skill_infos', function (Blueprint $table) {
            
            
            $table->increments('no');
            $table->integer('skill_field_no')->unsigned();
            $table->foreign('skill_field_no')->references('no')->on('skill_field_info')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('skill_name', 45)->nullable();
            $table->string('skill_name_kana', 45)->nullable();
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
        Schema::dropIfExists('skill_info_codes');
    }
}
