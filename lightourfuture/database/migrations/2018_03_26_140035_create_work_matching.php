<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkMatching extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_matchings', function (Blueprint $table) {
            
            $table->increments('no');
            $table->integer('work_id')->unsigned();
            $table->foreign('work_id')->references('id')->on('work_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->integer('employment_id')->unsigned();
            $table->foreign('employment_id')->references('employment_id')->on('employment_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->timestamp('data_entry_time')->useCurrent();
            $table->unique(array('work_id', 'employment_id'), 'work_matchings_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_matchings');
    }
}
