<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterviewSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_schedules', function (Blueprint $table) {
            
            //인터뷰 스케줄
            $table->increments('interview_id'); 
            $table->integer('employment_id')->nullable()->unsigned();
            $table->foreign('employment_id')->references('employment_id')->on('employment_infos')->onDelete('Restrict')->onUpdate('cascade'); //채용 정보 아이디
            $table->string('writer_id')->nullable();    //갤린더 스케줄에서 스케줄을 쓴 본인 id
            $table->text('schedule_title')->nullable(); //갤린더 스케줄 제목
            $table->integer('interview_count')->nullable(); //현재 인터뷰의 면접 차수
            $table->string('interview_place', 32)->nullable();
            $table->date('interview_date')->nullable(); //날짜
            $table->time('interview_start_time')->nullable(); //면접 시작시간
            $table->time('interview_end_time')->nullable(); //면접 끝시간
            $table->integer('interview_content_choice')->unsigned();
            $table->foreign('interview_content_choice')->references('id')->on('interview_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->text('interview_content')->nullable();
            $table->char('interview_check_ox')->nullable();     //이날 면접이 가능한지에 대한 교수의 수락 o: 가능, x: 불가능
            $table->char('interview_active')->nullable();       //현재 진행중인 면접인가 컬럼
//           $table->string('interview_result_ox', 1)->nullable();
            $table->timestamp('data_entry_time')->useCurrent();
            $table->unique(array('employment_id', 'interview_count'), 'interview_schedules_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview_schedules');
    }
}
