<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applies', function (Blueprint $table) {
            
            $table->increments('apply_id');
            $table->integer('employment_id')->unsigned(); //채용 id
            $table->foreign('employment_id')->references('employment_id')->on('employment_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('student_login_id', 45); //학생 id
            $table->foreign('student_login_id')->references('login_id')->on('students')->onDelete('Restrict')->onUpdate('cascade');
            $table->string('apply_permission_ox', 1)->nullable(); // 면접 지원을 교수로 부터 승인 받았나 여부 
            $table->string('acceptance_ox', 1)->nullable(); // 내정 여부
            $table->string('student_acceptance_ox')->nullable();    //학생의 내정 승낙 여부
            $table->string('professor_acceptance_ox')->nullable();  //학생의 내정승낙에 대한 교수의 승낙
            $table->text('resign_comment')->nullable();         //학생의 내정 사퇴 이유(기타선택시)
            $table->integer('resign_id')->unsigned()->nullable();           //학생의 내정 사퇴 이유 선택
            $table->foreign('resign_id')->references('id')->on('resign_infos')->onDelete('Restrict')->onUpdate('cascade');
            $table->char('professor_acceptance_check_ox', 1)->nullable();   //학생의 내정결과를 교수가 체크후 공개할지 말지 결정 여부 o : 학생에게 공개, x : 학생에게 공개 안함
            $table->timestamp('data_entry_time')->useCurrent(); 
            $table->unique(array('employment_id', 'student_login_id'), 'applies_index');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applys');
    }
}
