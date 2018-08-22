<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmploymentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employment_infos', function (Blueprint $table) {
            
            
            $table->increments('employment_id');
            $table->string('employment_name')->nullable();  //채용건 이름
            //복합키 참조
            //
            $table->integer('org_matching_foreign')->unsigned();

            $table->foreign('org_matching_foreign')->references('org_matchings_id')->on('org_matchings')->onDelete('Restrict')->onUpdate('cascade');
            
            //$table->foreign('matching_date')->references('matching_date')->on('org_matchings')->onDelete('cascade');
            $table->date('apply_open_date')->nullable();
            $table->dateTime('apply_deadline_date')->nullable();
            $table->text('business_type_content')->nullable();
            $table->text('desired_employee_content')->nullable();   //희망 인재상 내용
            $table->string('working_area', 80)->nullable();
            $table->integer('address_to_dou_hu_ken')->unsigned()->nullable();
            $table->foreign('address_to_dou_hu_ken')->references('id')->on('japan_prefectures')->onDelete('set null')->onUpdate('cascade');
            $table->integer('pay')->nullable(); //급여
            $table->text('pay_content')->nullable();    //급여조건1 설명
            $table->text('bonus_pay')->nullable();      //급여조건2 상여금
            $table->text('transport_pay')->nullable();  //급여조건3 교통수단
            $table->text('overtime_pay')->nullable();   //급여조건4 잔업수당
            $table->text('house_pay')->nullable();      //급여조건5 주택보조수당
            $table->text('dormitory_airport_support')->nullable();  //기숙사&항공료 지원
            $table->text('working_sort')->nullable();   //직종
            $table->text('recruit_number')->nullable(); //모집인원
            $table->text('motomeru_major_grade')->nullable();   //자격요건1 전공능력
            $table->text('motomeru_language_grade')->nullable();   //자격요건2 외국어 능력
            $table->text('motomeru_sonohoka')->nullable();  //자격요건3 기타
            $table->text('working_naiyou_content')->nullable(); //주요 업무내용
            $table->text('work_term')->nullable();      //계약기간
            $table->text('insurance_content')->nullable();  //보험가입
            $table->text('other_work_condition')->nullable();   //기타근로조건
            //$table->integer('pay_max')->nullable(); //최고 급여
            $table->string('business_hour', 12)->nullable();
            $table->string('holiday', 12)->nullable();
            $table->text('welfare_content')->nullable();
            $table->date('expected_acceptance_date')->nullable();
            $table->integer('whole_interview_count')->nullable();   
            $table->string('acceptance_fixed_ox', 1)->nullable();   //내정 필수 승낙 여부
            $table->char('employment_owari_ox');    //채용 끝났는가 끝나지 않았는가? 끝났으면:o, 끝나지 않았으면:x
            $table->text('file_url')->nullable();
            $table->timestamp('data_entry_time')->useCurrent();
            $table->string('create_id', 45)->nullable();//정보 작성자 ID

            //$table->unique('org_matching_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employment_infos');
    }
}
