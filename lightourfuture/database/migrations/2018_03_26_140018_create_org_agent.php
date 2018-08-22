<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgAgent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_agents', function (Blueprint $table) {
            
            
            $table->string('org_agent_id', 45)->primary();
            $table->foreign('org_agent_id')->references('org_id')->on('orgs')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('manager_login_id', 45)->nullable();
            $table->foreign('manager_login_id')->references('login_id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('org_name', 30)->nullable();
            $table->string('org_name_kana', 30)->nullable();
            //$table->string('org_name_eng', 30)->nullable();
            $table->char('country_code', 2)->nullable();
            $table->foreign('country_code')->references('country_code')->on('country_codes')->onDelete('Restrict')->onUpdate('cascade');
            $table->text('photo_url')->nullable();
            $table->integer('address_to_dou_hu_ken')->unsigned()->nullable();
            $table->foreign('address_to_dou_hu_ken')->references('id')->on('japan_prefectures')->onDelete('set null')->onUpdate('cascade');
            $table->string('address', 80)->nullable();
            $table->string('homepage_url', 100)->nullable();
            $table->string('create_id', 45)->nullable();//정보 작성자 ID
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
        Schema::dropIfExists('org_agents');
    }
}
