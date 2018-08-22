<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkill extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            
            $table->increments('no');
            $table->string('student_login_id', 45);
            $table->foreign('student_login_id')->references('login_id')->on('students')->onDelete('Restrict')->onUpdate('cascade');
            $table->integer('language_code')->unsigned();
            $table->foreign('language_code')->references('no')->on('skill_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->integer('language_level_code')->unsigned();
            $table->foreign('language_level_code')->references('no')->on('skill_levels')->onDelete('Restrict')->onUpdate('cascade');
            $table->timestamp('data_entry_time')->useCurrent();
            $table->unique(array('student_login_id', 'language_code'));
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skills');
    }
}
