<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class CollegeMatchingEmploymentController extends Controller
{
    
    // 기업 매칭 되어있는 국가별 학교 리스트
    public function countryMatchingCollegeList() {
        // 기업 아이디
        $manager_id = request('requester');
        // $company_id = DB::table('company_agents')->select('org_company_id')
        // ->where('login_id', '=', $manager_id)
        // ->get();

        // 기업 기관 아이디
        $company_id = DB::table('org_companies')->where('manager_login_id', '=', $manager_id)->pluck('org_company_id');

        // 매칭되어있는 정보 담을 배열
        $matching_info_arr = [];

        // 해당 매칭 국가 들고오기
        $countryList = DB::table('org_matchings as orm')
        ->select('cc.country_eng')
        ->join('org_colleges as orc', 'orm.org_college_id', '=', 'orc.org_college_id')
        ->join('country_codes as cc', 'orc.country_code', '=', 'cc.country_code')
        ->where('orm.org_company_id', '=', $company_id[0])
        ->groupby('cc.country_eng')
        ->get();

        // 예외처리 
        if(!isset($countryList[0])) return 0;
        
        foreach($countryList as $cl) {
            // 학교 리스트 
            $countryCollegeList = DB::table('org_matchings as orm')
            ->select('orc.*')
            ->join('org_colleges as orc', 'orm.org_college_id', '=', 'orc.org_college_id')
            ->join('country_codes as cc', 'orc.country_code', '=', 'cc.country_code')
            ->where([
                ['orm.org_company_id', '=', $company_id[0]],
                ['cc.country_eng', '=', $cl->country_eng],
            ])
            ->get();
            
            // 국가를 KEY값으로 값넣어줌
            array_push($matching_info_arr, array($cl->country_eng => $countryCollegeList));
        }
        
        // 리턴
        return $matching_info_arr;
    }

    // 선택 학교 채용공고 결과 
    public function collegeMatchingEmploymentList() {
     
        // 학교 클릭 -> college_id 값 
        //          -> 채용 공고 끌고 오기 company_id와 college_id가 같은 채용공고 들고오기
        //          -> 해당 학교에서 채용공고에 지원한 학생 수
        //          -> 내정을 내린 학생 수
        //          -> 내정을 수락한 학생 수

        // 기업 아이디
        $manager_id = request("requester");
        // 기업 기관 아이디
        $company_id = DB::table('org_companies')->where('manager_login_id', '=', $manager_id)->pluck('org_company_id');
        // 학교 아이디 (co01 -> yjc01, snu01, knu01)
        $college_id = request('collegeId');

        $matching_id = DB::table('org_matchings')
        ->where([
            ['org_company_id', '=', $company_id],
            ['org_college_id', '=', $college_id],
        ])
        ->pluck('org_matchings_id');
        
        // 값이 없을 시 예외처리
        if(!isset($matching_id[0])) return 0;



        // 매칭 아이디 배열
        $employment_id_arr = [];
        // 매칭 아이디 얻기
        foreach($matching_id as $id)  {
            if(!isset($id)) return 0;

            $employment_id = DB::table('employment_infos')
            ->where('org_matching_foreign', '=', $id)
            ->pluck('employment_id');

            array_push($employment_id_arr, $employment_id[0]);
        }
        
        
        
        
        // $employment_id_arr = [];

        // foreach($employment_id_arr as $id) {

        //     // 빈 값이 들어있을 시
            

        //     array_push($employment_id_arr, $id);
        // }
        
        //$employment_info_arr    = [];   // 혹시 모를 채용공고 정보
        $first_std_count_arr    = [];   // 처음 지원자 수
        $open_std_count_arr     = [];   // 내정 준 학생 수
        $active_std_count_arr   = [];   // 내정 수락 학생 수


        foreach($employment_id_arr as $id) {

            /* @@혹시나 만드는 채용공고 정보@@ 
            $employment_info = DB::table('employment_infos')
            ->select('*')
            ->where('employment_id', '=', $id)
            ->get();

            array_push($employment_info_arr, $employment_info[0]);
            */

            /* ----------------------채용 공고 지원자 수-------------------------- */
            // 해당 채용건의 첫번째 인터뷰 ID 
            $first_interview_id  = DB::table('interview_schedules')
            ->select('interview_id')
            ->where('employment_id', '=', $id)
            ->first();    
            
            // 해당 채용건의 첫번째 지원자 수
            $first_std_count = DB::table('interview_results')
            ->select(DB::raw("count(student_login_id) as std"))
            ->where([
                ['interview_id', '=', $first_interview_id->interview_id],
            ])
            ->get();
            
            array_push($first_std_count_arr, $first_std_count[0]->std);
            
            /* ----------------------내정 준 학생들 수-------------------------- */

            // 해당 채용건의 마지막 인터뷰 ID 얻기
            $last_interview_id  = DB::table('interview_schedules')
            ->select('interview_id')
            ->where('employment_id', '=', $employment_id[0])
            ->latest('interview_date')
            ->first();

            // 채용건의 마지막 면접에 내정 내린 학생 수
            $open_std_count = DB::table('interview_results')
            ->select(DB::raw("count(student_login_id) as std"))
            ->where([
                ['interview_id', '=', $last_interview_id->interview_id],
                ['interview_result', '=', 'o'],
            ])
            ->get();

            array_push($open_std_count_arr, $open_std_count[0]->std);


            /* ----------------------내정 수락 학생들 수-------------------------- */

            // 내정을 수락한 학생 수
            $active_std_count = DB::table('applies as ap')
            ->select(DB::raw("count(ap.student_login_id) as std"))
            ->join('interview_schedules as is', 'is.employment_id', '=', 'ap.employment_id')
            ->where([
                ['ap.employment_id', '=', $id],
                ['is.interview_id', '=', $last_interview_id->interview_id],
                ['ap.acceptance_ox', '=', 'o'],
                ['ap.professor_acceptance_ox', '=', 'o'],
            ])
            ->get();

            array_push($active_std_count_arr, $active_std_count[0]->std);
        }
    
        
        // 처음 지원 학생 수 / 마지막 면접 값을 가진 내정 내린 학생 수 / 마지막 면접 값을 가진 내정 확정 학생 수
        return array(
            //'employmentInfo' => $employment_info_arr, 혹시모를 채용공고 정보
            'apply_Student'  => $first_std_count_arr,
            'nominated'   => $open_std_count_arr,
            'accept_Nominate' => $active_std_count_arr,
        );
        

    }
}
