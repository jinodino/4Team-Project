<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWelfare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfares', function (Blueprint $table) {
            
            $table->increments('no');
            $table->integer('welfare_id')->unsigned();
            $table->foreign('welfare_id')->references('id')->on('welfare_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->integer('employment_id')->unsigned();
            $table->foreign('employment_id')->references('employment_id')->on('employment_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->timestamp('data_entry_time')->useCurrent();
            $table->unique(array('welfare_id', 'employment_id'), 'welfares_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welfares');
    }
}
