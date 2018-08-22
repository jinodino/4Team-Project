<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            
            
            $table->increments('group_id');
            $table->integer('faculty_id')->unsigned();
            $table->foreign('faculty_id')->references('faculty_id')->on('faculties')->onDelete('Restrict')->onUpdate('cascade');
            $table->integer('group_num')->nullable();
            $table->string('group_name', 60)->nullable();
            $table->string('project_title', 60)->nullable();
            $table->text('project_content')->nullable();
            $table->text('project_video_url')->nullable();
            $table->string('showcase_year',10)->nullable();
            $table->timestamp('data_entry_time')->useCurrent();
            $table->unique(array('faculty_id', 'group_num', 'showcase_year'), 'group_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
