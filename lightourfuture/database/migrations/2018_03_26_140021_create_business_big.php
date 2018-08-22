<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessBig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_bigs', function (Blueprint $table) {
            
            $table->increments('no');
            $table->integer('business_big_id')->unsigned();
            $table->foreign('business_big_id')->references('id')->on('business_big_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('org_company_id', 45);
            $table->foreign('org_company_id')->references('org_company_id')->on('org_companies')->onDelete('Restrict')->onUpdate('cascade');
            $table->timestamp('data_entry_time')->useCurrent();
            $table->unique(array('business_big_id', 'org_company_id'), 'business_bigs_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_bigs');
    }
}
