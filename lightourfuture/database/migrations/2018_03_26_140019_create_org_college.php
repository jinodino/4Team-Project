<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgCollege extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_colleges', function (Blueprint $table) {
            
            
            $table->string('org_college_id', 45)->primary();
            $table->foreign('org_college_id')->references('org_id')->on('orgs')->onDelete('Restrict')->onUpdate('cascade');
            $table->char('country_code', 2);
            $table->foreign('country_code')->references('country_code')->on('country_codes')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('college_alias', 3); //조직 별칭
            //$table->string('org_name_kor', 15)->nullable();
            $table->string('org_name', 30)->nullable();
            $table->string('org_name_kana', 30)->nullable();
            //$table->string('org_name_eng', 30)->nullable();
            $table->text('photo_url')->nullable();
            $table->string('address_city', 20)->nullable();
            $table->string('address', 80)->nullable();
            $table->string('latitude', 25)->nullable();  //학교 위도 좌표
            $table->string('longitude', 25)->nullable();  //학교 경도 좌표
            $table->string('homepage_url', 100)->nullable();
            $table->float('credit_total')->nullable();  //학점 만점
            $table->string('college_category', 15)->nullable();
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
        Schema::dropIfExists('org_colleges');
    }
}
