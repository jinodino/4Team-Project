<?php

namespace App\Http\Controllers\Student;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentInfoController extends Controller
{
    // profile 출력
    public function StudentProfileShow() {
        // Request -> classification / user_id Value 
        // 임의의 값 학생 아이디
        $login_id = 'st01';

        $students = "students";
        $country_codes = 'country_codes';



        $student_name = DB::table('students')
        ->where('login_id', '=', $login_id)->pluck('name_eng');

        // 학생 전공 스킬 정보
        $skill_info = DB::table('skills as sks')
        ->select('skl.skill_level', 'skf.skill_name')
        ->join('skill_levels as skl', 'sks.language_level_code', '=', 'skl.no')
        ->join('skill_info_codes as skf', 'sks.language_code', '=', 'skf.no')
        ->join('students as std', 'std.login_id', '=', 'sks.student_login_id')
        ->where('std.name_eng', '=', $student_name)->get();

        // 기타 외국어
        $language_info = DB::table('languages as lng')
        ->select('lnl.language_level', 'lni.language')
        ->join('language_levels as lnl', 'lng.language_level_code', '=', 'lnl.no')
        ->join('language_infos as lni', 'lng.language_code', '=', 'lni.no')
        ->join('students as std', 'std.login_id', '=', 'lng.student_login_id')
        ->where('std.name_eng', '=', $student_name)->get();

        // 학생 관심 분야 
        // student_interested_field -> 학생아이디
        $student_interested_info = DB::table('student_interested_field')->where('student_login_id', '=', $login_id)->pluck('business_small_id');
        $std_interesed_arr = [];
        foreach($student_interested_info as $sii) {
            $std_interested = DB::table('business_small_infos')->where('id', '=', $sii)->pluck('content');

            array_push($std_interesed_arr, $std_interested[0]);
        }

        // 학생 -> 총 지원 수 , 면접중, 내정수
        


        // return array('student_info' => $student_info, 'std_skill_info' => $skill_info, 'std_language_info' => $language_info,
        //                 'std_interested_info' => $std_interesed_arr );
       
        return ['student_info'=>$student_info, 'affiliation_info'=>$affiliation_info];
       
    }
    
    /**
     *  모든 기입(즉 수정)은 학생의 PrimaryKey(학생 아이디) Value 필요합니다.
     *  @param String Student_login_id
     */

    // 한글 이름, 영문 이름, 카타카나 이름, 한자 이름, 이메일
    // 성별, 생년월일, 만 나이, 국적, 소속 조, 조에서 맡은 파트, 깃허브 주소
    public function StudentProfileUpdate1(Request $request) {
        
        DB::table('students')
        ->where('login_id', '=', $request->student_id)
        ->update([
            ['name_kor' => $request->name_kor],
            ['name_eng' => $request->name_eng],
            ['name_kana' => $request->name_kana],
            ['name_kanji' => $request->name_kanji],
            ['email' => $request->email],
            ['gender' => $request->gender],
            ['birth_date' => $request->birth],
            ['name_email' => $request->email],
            ['name_email' => $request->email],
            ['name_email' => $request->email],
        ]);
    }

    // JLPT, JPT
    public function StudentProfileUpdate2() {

    }

    // TOEIC, TOEFL
    public function StudentProfileUpdate3() {

    }

    // 기타자격
    public function StudentProfileUpdate4() {

    }

    // 일본 취업을 결심하게 된 동기
    public function StudentProfileUpdate5() {

    }

    // 자기 PR 문장
    public function StudentProfileUpdate6() {

    }
}
