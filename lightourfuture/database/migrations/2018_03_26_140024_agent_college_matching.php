<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgentCollegeMatching extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_college_matchings', function (Blueprint $table) {
            $table->increments('no');
            $table->string('org_agent_id', 45);
            $table->foreign('org_agent_id')->references('org_agent_id')->on('org_agents')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('org_college_id', 45);
            $table->foreign('org_college_id')->references('org_college_id')->on('org_colleges')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('matching_date', 10);
            //복합키
            $table->unique(['org_agent_id', 'org_college_id', 'matching_date'], 'agent_college_matching_index');

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_college_matchings');
    }
}
