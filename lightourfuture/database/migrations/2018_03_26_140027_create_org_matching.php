<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgMatching extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_matchings', function (Blueprint $table) {
            
            $table->increments('org_matchings_id');

            $table->string('org_agent_id', 45);
            $table->foreign('org_agent_id')->references('org_agent_id')->on('org_agents')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('org_college_id', 45);
            $table->foreign('org_college_id')->references('org_college_id')->on('agent_college_matchings')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('org_company_id', 45);
            $table->foreign('org_company_id')->references('org_company_id')->on('agent_company_matchings')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('matching_date', 10);
            $table->char('employment_decision_ox', 1)->nullable();       //채용 확정 여부
            $table->timestamp('data_entry_time')->useCurrent();
            
            //인덱스 유니크키
            $table->unique(array('org_agent_id', 'org_college_id', 'org_company_id', 'matching_date'), 'org_matching_index');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_matchings');
    }
}
