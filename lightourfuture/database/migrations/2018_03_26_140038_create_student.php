<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            
            //학생이 stops
            //교수가 stops

            $table->string('login_id', 45)->primary();
            $table->foreign('login_id')->references('login_id')->on('users')->onDelete('Restrict')->onUpdate('cascade');
            $table->char('country_code', 2)->nullable();
            $table->foreign('country_code')->references('country_code')->on('country_codes')->onDelete('set null')->onUpdate('cascade');
            $table->string('student_no', 50)->nullable();
            $table->string('student_code');
            $table->string('professor_login_id', 45);
            $table->foreign('professor_login_id')->references('login_id')->on('professors')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('name', 30);
            $table->string('name_eng', 30)->nullable();
            $table->string('name_kanji', 30)->nullable();
            $table->string('name_kana', 30)->nullable();
            $table->date('graduate_date')->nullable();
            //$table->string('ikukaisya');
            $table->char('employ_ox', 1)->default('o'); //구직활동 ox
            $table->string('employ_year', 10);//학생이 수정 불가
            $table->string('email', 90);
            $table->text('profile_photo_url')->nullable();
            $table->date('birth_date');
            // $table->integer('faculty_id')->unsigned();
            // $table->foreign('faculty_id')->references('faculty_id')->on('faculties')->onDelete('cascade');
            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('group_id')->on('groups')->onDelete('Restrict')->onUpdate('cascade'); //소속 정보  group 0 == 조가 없고, 소속만 있는 상태
            $table->text('group_part_content')->nullable();
            $table->string('gender', 6)->nullable();
            $table->date('admission_date')->nullable(); //입학년도
            $table->float('credit')->nullable();
            $table->integer('JPT')->nullable();
            $table->date('JPT_acquisition_date')->nullable();//취득년월
            $table->integer('JLPT')->nullable();
            $table->date('JLPT_acquisition_date')->nullable();//취득년월
            $table->integer('mock_TOEIC')->nullable();          //모의토익
            $table->date('mock_TOEIC_acquisition_date')->nullable();//취득년월
            $table->integer('TOEIC')->nullable();
            $table->date('TOEIC_acquisition_date')->nullable();//취득년월
            $table->integer('TOEFL')->nullable();
            $table->date('TOEFL_acquisition_date')->nullable();//취득년월
            $table->text('skill_contents')->nullable(); //보유 기술 내용
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();      //연락처
            $table->string('sub_phone', 20)->nullable();  //2차연락처
            $table->text('activities')->nullable();    //학업 이외의 활동 
            $table->string('major_grade', 4)->nullable();        //전공실력
            $table->string('sincerity_grade', 4)->nullable();      //성실도
            $table->string('personality_grade', 4)->nullable();    //인성도 
            $table->char('army_ox', 1)->nullable();             //병역 o=> 군필, x=> 미필, null => 해당안함(성별)
            //$table->text('license')->nullable();            //지우기
            $table->text('motivation_content')->nullable();         //일본 취업 동기    //바뀐거
            $table->text('interested_field_content')->nullable();   //관심 분야 //바뀐거
            $table->text('pr_content')->nullable();     //자기 PR 문장  //바뀐거
            $table->text('qualification_content')->nullable();      //자격증    //바뀐거
            $table->string('japanese_speaking_level', 4)->nullable();    //학생의 일본어 회화 능력(교수님 입력)
            $table->text('recommendation_comment')->nullable();
            $table->text('pr_video_url')->nullable();       //pr비디오 url
            $table->text('github_url')->nullable();
            $table->char('employment_end_ox', 1)->nullable();  //학생 채용활동 종료(o일경우 학생의 채용활동이 종료된 것으로 판단.)
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
        Schema::dropIfExists('students');
    }
}
