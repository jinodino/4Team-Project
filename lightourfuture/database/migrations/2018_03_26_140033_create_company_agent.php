<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAgent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_agents', function (Blueprint $table) {
            
            
            $table->string('login_id', 45)->primary();
            $table->foreign('login_id')->references('login_id')->on('users')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('invite_agent_id', 45)->nullable();
            $table->foreign('invite_agent_id')->references('org_agent_id')->on('org_agents')->onDelete('set null')->onUpdate('cascade');
            $table->string('org_company_id', 45)->nullable();
            $table->foreign('org_company_id')->references('org_company_id')->on('org_companies')->onDelete('set null')->onUpdate('cascade');
            $table->string('name', 30)->nullable();
            //$table->string('name_kanji', 30)->nullable();
            $table->string('name_kana', 30)->nullable();
            $table->string('email', 90);
            $table->date('birth_date');
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
        Schema::dropIfExists('company_agents');
    }
}
