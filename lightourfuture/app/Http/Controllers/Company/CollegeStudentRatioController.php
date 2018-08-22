<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CollegeStudentRatioController extends Controller
{
    // 각 학교별 학생 -> 인원수, 비율(남, 녀), JLPT
    public function collegeStudentRatio() {
        // 해당 기업 아이디
        //$company_id = request('companyId');
        $company_id = 'co01';

        // 기업 아이디값 추출
        $org_company_id = DB::table('org_companies')
        ->where('manager_login_id', '=', $company_id)
        ->pluck('org_company_id');

        // 매칭되어있는 학교 ID값 추출
        $college_id = DB::table('org_matchings')
        ->where('org_company_id', '=', $org_company_id[0])
        ->pluck('org_college_id');

        $college_info_arr  = [];

        // 매칭된 학교가 많을 수도 있기때문에 돌려야함
        foreach($college_id as $col) {
            // faculty 값 추출
            $faculty_id = DB::table('faculties')
            ->where('org_college_id', '=', $col)
            ->pluck('faculty_id');

            // 대학 학과별 모든 교수 돌려서 교수에 묶인 학생들 수 카운터해야함
            $collegeStdCount = 0;
            $collegeMstdCount = 0;
            $collegeWstdCount = 0;
            $collegeStudentJLPT3 = 0;
            $collegeStudentJLPT2 = 0;
            $collegeStudentJLPT1 = 0;

            // 대학별 모든 계열에 대한 학생 수 구해서 더하기
            foreach($faculty_id as $fac) {
                // 교수아이디 값 추출
                $professor_id = DB::table('professors')
                ->where('faculty_id', '=', $faculty_id[0])
                ->pluck('login_id');    

                foreach($professor_id as $pf) {

                    // 학생 전체 수 추출
                    $studentCount = DB::table('students')
                    ->where('professor_login_id', '=', $pf)
                    ->count();

                    // 여학생 추출
                    $wstudentCount = DB::table('students')
                    ->where([
                        ['professor_login_id', '=', $pf],
                        ['gender', '=', 'w'],
                    ])
                    ->count();

                    // 남학생 추출
                    $mstudentCount = DB::table('students')
                    ->where([
                        ['professor_login_id', '=', $pf],
                        ['gender', '=', 'm'],
                    ])
                    ->count();

                    // JLPT 급수 별 
                    $studentJLPT3 = DB::table('students')
                    ->where([
                        ['professor_login_id', '=', $pf],
                        ['JLPT', '=', 3],
                    ])
                    ->count();

                    // JLPT 급수 별 
                    $studentJLPT2 = DB::table('students')
                    ->where([
                        ['professor_login_id', '=', $pf],
                        ['JLPT', '=', 2],
                    ])
                    ->count();

                    // JLPT 급수 별 
                    $studentJLPT1 = DB::table('students')
                    ->where([
                        ['professor_login_id', '=', $pf],
                        ['JLPT', '=', 1],
                    ])
                    ->count();

                    // 나온값들 카운터해서 변수에 저장
                    // 대학 총 학생 수
                    $collegeStdCount += $studentCount;
                    // 대학 총 남자 수
                    $collegeMstdCount += $mstudentCount;
                    // 대학 총 여자 수
                    $collegeWstdCount += $wstudentCount;
                    // 대학 JLPT 3급 학생 수
                    $collegeStudentJLPT3 += $studentJLPT3;
                    // 대학 JLPT 2급 학생 수
                    $collegeStudentJLPT2 += $studentJLPT2;
                    // 대학 JLPT 1급 학생 수
                    $collegeStudentJLPT1 += $studentJLPT1;
                }
            }

        
            array_push($college_info_arr, array($col => array($collegeStdCount, $collegeMstdCount, $collegeWstdCount, $collegeStudentJLPT3, $collegeStudentJLPT2, $collegeStudentJLPT1)));
        }
        
        // @return : college -> 총인원수, 남자 수, 여자수, JLPT(3, 2, 1) 인원 수
        return $college_info_arr;         

        
    }

}


//  // 학생들 추출
//  $studentList = DB::table('students')
//  ->where('professor_login_id', '=', $professor_id[0])
//  ->pluck('login_id');

//  // 학생 전체 수 추출
//  $studentCount = DB::table('students')
//  ->select(DB::raw("count(login_id) as stdCount"))
//  ->where('professor_login_id', '=', $professor_id[0])
//  ->get();

//  // 여학생 추출
//  $wstudentCount = DB::table('students')
//  ->select(DB::raw("count(login_id) as wStdCount"))
//  ->where([
//      ['professor_login_id', '=', $professor_id[0]],
//      ['gender', '=', 'w'],
//  ])
//  ->get();

//  // 남학생 추출
//  $mstudentCount = DB::table('students')
//  ->select(DB::raw("count(login_id) as mStdCount"))
//  ->where([
//      ['professor_login_id', '=', $professor_id[0]],
//      ['gender', '=', 'm'],
//  ])
//  ->get();

//  // JLPT 급수 별 
//  $studentJLPT3 = DB::table('students')
//  ->select(DB::raw("count(login_id) as JLPT2"))
//  ->where([
//      ['professor_login_id', '=', $professor_id[0]],
//      ['JLPT', '=', 3],
//  ])
//  ->get();

//  // JLPT 급수 별 
//  $studentJLPT2 = DB::table('students')
//  ->select(DB::raw("count(login_id) as JLPT2"))
//  ->where([
//      ['professor_login_id', '=', $professor_id[0]],
//      ['JLPT', '=', 2],
//  ])
//  ->get();

//  // JLPT 급수 별 
//  $studentJLPT1 = DB::table('students')
//  ->select(DB::raw("count(login_id) as JLPT2"))
//  ->where([
//      ['professor_login_id', '=', $professor_id[0]],
//      ['JLPT', '=', 1],
//  ])
//  ->get();
