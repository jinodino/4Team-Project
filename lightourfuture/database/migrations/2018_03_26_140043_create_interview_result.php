<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterviewResult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_results', function (Blueprint $table) {
            
            $table->increments('no');
            $table->integer('interview_id')->unsigned();
            $table->foreign('interview_id')->references('interview_id')->on('interview_schedules')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('student_login_id', 45); //학생 아이디
            $table->foreign('student_login_id')->references('login_id')->on('users')->onDelete('Restrict')->onUpdate('cascade'); 
            $table->dateTime('interview_start_time')->nullable(); //학생 면접 시작 시간
            $table->dateTime('interview_end_time')->nullable(); //학생 면접 끝 시간
            $table->string('interview_result', 12)->nullable();// 면접 결과 ( o / x / null )
            $table->text('interview_feedback')->nullable();     //면접 피드백
            $table->timestamp('data_entry_time')->useCurrent();
            $table->unique(array('interview_id', 'student_login_id'), 'interview_result_index');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview_results');
    }
}
