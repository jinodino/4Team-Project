<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professors', function (Blueprint $table) {
            
            
            $table->string('login_id', 45)->primary();
            $table->foreign('login_id')->references('login_id')->on('users')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('invite_agent_id', 45);  //초대받은 에이전트 ID
            $table->foreign('invite_agent_id')->references('org_agent_id')->on('org_agents')->onDelete('Restrict')->onUpdate('cascade');
            $table->integer('faculty_id')->unsigned();
            $table->foreign('faculty_id')->references('faculty_id')->on('faculties')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('name', 30)->nullable();
            //$table->string('name_eng', 30)->nullable();
            $table->string('name_kanji', 30)->nullable();
            $table->string('name_kana', 30)->nullable();
            $table->string('email', 90);
            $table->text('profile_photo_url')->nullable();
            $table->date('birth_date');
            $table->string('major', 30)->nullable();
            $table->string('japaness_skill_ox', 1)->nullable();
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
        Schema::dropIfExists('professors');
    }
}
