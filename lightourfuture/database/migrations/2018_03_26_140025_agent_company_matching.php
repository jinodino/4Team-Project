<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgentCompanyMatching extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_company_matchings', function (Blueprint $table) {
            $table->increments('no');
            $table->string('org_agent_id', 45);
            $table->foreign('org_agent_id')->references('org_agent_id')->on('org_agents')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('org_company_id', 45);
            $table->foreign('org_company_id')->references('org_company_id')->on('org_companies')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('matching_date', 10);
            //복합키
            $table->unique(['org_agent_id', 'org_company_id', 'matching_date'], 'agent_company_matching_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_company_matchings');
    }
}
