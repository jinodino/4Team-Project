<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StudentInterestedField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_interested_field', function (Blueprint $table) {
            $table->increments('no');
            $table->integer('business_small_id')->unsigned();
            $table->foreign('business_small_id')->references('id')->on('business_small_infos')->onDelete('cascade');
            $table->string('student_login_id', 45);
            $table->foreign('student_login_id')->references('login_id')->on('students')->onDelete('cascade');
            $table->timestamp('data_entry_time')->useCurrent();
            $table->unique(array('business_small_id', 'student_login_id'), 'student_interested_field_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_interested_field');
    }
}
