<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaculty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
            
            
            $table->increments('faculty_id');
            $table->string('org_college_id', 45);
            $table->foreign('org_college_id')->references('org_college_id')->on('org_colleges')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('department_name', 40)->nullable();
            $table->integer('major_id')->unsigned();//전공분류 ID
            $table->foreign('major_id')->references('id')->on('major_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('major_name', 40)->nullable();
            $table->string('class_name', 45)->nullable();
            //college>department>major>class_name
            $table->integer('college_category_sub')->nullable();
            $table->timestamp('data_entry_time')->useCurrent();
            $table->unique(array('org_college_id', 'department_name', 'major_name', 'class_name'), 'faculty_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facultys');
    }
}
