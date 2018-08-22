<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_companies', function (Blueprint $table) {
            
            
            $table->string('org_company_id', 45)->primary();
            $table->foreign('org_company_id')->references('org_id')->on('orgs')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('manager_login_id', 45)->nullable()->unique();
            $table->foreign('manager_login_id')->references('login_id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('org_name', 30)->nullable();
            $table->string('org_name_kana', 30)->nullable();
            //$table->string('org_name_eng', 30)->nullable();
            $table->char('country_code', 2);
            $table->foreign('country_code')->references('country_code')->on('country_codes')->onDelete('Restrict')->onUpdate('cascade');
            $table->text('photo_url')->nullable();
            $table->integer('address_to_dou_hu_ken')->unsigned()->nullable();
            $table->foreign('address_to_dou_hu_ken')->references('id')->on('japan_prefectures')->onDelete('set null')->onUpdate('cascade');
            $table->string('address', 80)->nullable();
            $table->string('homepage_url', 100)->nullable();
            $table->date('establish_date')->nullable(); //설립년도
            $table->string('ceo_name', 30)->nullable(); 
            $table->string('ceo_name_kana', 30)->nullable(); 
            $table->integer('worker_count')->nullable();    //종업원수
            $table->integer('capital')->nullable();     //자본금
            $table->text('business_content')->nullable();   //사업 내용
            $table->text('company_atmosphere')->nullable(); //사풍, 분위기
            $table->text('recommendation_comment')->nullable(); //에이전트 추천 코멘트
            $table->char('listed_company_ox', 1)->nullable();//상장 여부
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
        Schema::dropIfExists('org_companys');
    }
}
