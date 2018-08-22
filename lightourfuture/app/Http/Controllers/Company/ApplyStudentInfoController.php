<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ApplyStudentInfoController extends Controller
{
    // 지정 학생 상세 정보 -> 내정관리 페이지에서 사용
    public function applyStudentInfo() {
        $student_id = request('stdId');
        $arr = []; 
        $student_name = DB::table('students')
        ->where('login_id', '=', $student_id)->pluck('name_eng');
        
        // 빈 배열일 시 더미값 -> 빈배열을 만들기 위해
        if(!isset($student_name[0])) return 0;

        $skill_info = DB::table('skills as sks')
        ->select('skl.skill_level', 'skf.skill_name')
        ->join('skill_levels as skl', 'sks.language_level_code', '=', 'skl.no')
        ->join('skill_infos as skf', 'sks.language_code', '=', 'skf.no')
        ->join('students as std', 'std.login_id', '=', 'sks.student_login_id')
        ->where('std.name_eng', '=', $student_name)->get();

        $student_info = DB::table('students')
          ->select('name', 'name_eng', 'name_kanji', 'name_kana', 'gender', 'email', 'profile_photo_url', 'birth',
          'JLPT', 'JPT',
           DB::raw('Floor((TO_DAYS(now())-(TO_DAYS(birth_date)))/365) as age'))
          ->where('name_eng', '=', $student_name)->get();
          
        return array('skill_info' => $skill_info, 'student_info' => $student_info);
    } 

    public function real_accept() {
        
    }
    
    

}
