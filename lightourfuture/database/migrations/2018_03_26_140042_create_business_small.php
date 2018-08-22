<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessSmall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_smalls', function (Blueprint $table) {
            
            $table->increments('no');
            $table->integer('business_small_id')->unsigned();
            $table->foreign('business_small_id')->references('id')->on('business_small_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('org_company_id', 45);
            $table->foreign('org_company_id')->references('org_company_id')->on('org_companies')->onDelete('Restrict')->onUpdate('cascade');
            $table->timestamp('data_entry_time')->useCurrent();
            $table->unique(array('business_small_id', 'org_company_id'), 'business_smalls_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_smalls');
    }
}
