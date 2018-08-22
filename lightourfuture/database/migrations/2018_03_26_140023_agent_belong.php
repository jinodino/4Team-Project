<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgentBelong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_belongs', function (Blueprint $table) {
            //에이전트가 소속된 에이전트 회사 및 그룹
            $table->increments('no');
            $table->string('agent_id', 45);
            $table->foreign('agent_id')->references('login_id')->on('agents')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('org_agent_id', 45);
            $table->foreign('org_agent_id')->references('org_agent_id')->on('org_agents')->onDelete('Restrict')->onUpdate('cascade');
            $table->unique(array('agent_id', 'org_agent_id'), 'agent_belongs_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_belongs');
    }
}
