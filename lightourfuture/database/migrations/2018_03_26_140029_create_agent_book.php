<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_books', function (Blueprint $table) {
            
            $table->increments('no');
            $table->string('org_agent_id', 45);
            $table->foreign('org_agent_id')->references('org_agent_id')->on('org_agents')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('name', 30);
            $table->string('email', 90);
            $table->string('org_id', 45)->nullable();
            $table->foreign('org_id')->references('org_id')->on('orgs')->onDelete('Restrict')->onUpdate('cascade');
            $table->integer('faculty_id')->unsigned()->nullable();
            $table->foreign('faculty_id')->references('faculty_id')->on('faculties')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('join_ox', 1)->default('x');
            $table->timestamp('data_entry_time')->useCurrent();
            
            //인덱스 유니크키
            $table->unique(array('org_agent_id', 'email'), 'agent_books_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_books');
    }
}
