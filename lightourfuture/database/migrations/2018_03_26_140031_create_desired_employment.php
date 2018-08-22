<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesiredEmployment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desired_employments', function (Blueprint $table) {
            
            $table->increments('no');
            $table->integer('desired_employment_id')->unsigned();
            $table->foreign('desired_employment_id')->references('id')->on('desired_employment_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->integer('employment_id')->unsigned();
            $table->foreign('employment_id')->references('employment_id')->on('employment_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->timestamp('data_entry_time')->useCurrent();
            $table->unique(array('desired_employment_id', 'employment_id'), 'desired_employments_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('desired_employments');
    }
}
