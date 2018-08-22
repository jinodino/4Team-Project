<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class CompanyListController extends Controller
{
    // 매칭 기업 리스트 
    public function MatchingCompanyList() {
        
        // 학생 아이디 -> 교수 아이디 -> 학과 아이디 -> 대학 아이디 -> 매칭 아이디
        $student_id = /*request('stdId')*/'st01';
        // 교수 아이디
        $professor_id = DB::table('students')->where('login_id', '=', $student_id)->pluck('professor_login_id');
        // 학과 아이디 
        $faculty_id = DB::table('professors')->where('login_id', '=', $professor_id)->pluck('faculty_id');
        // 대학 아이디
        $college_id = DB::table('faculties')->where('faculty_id', '=', $faculty_id)->pluck('org_college_id');
        // 기업 아이디
        $company_id = DB::table('org_matchings')->where('org_college_id', '=', $college_id)->pluck('org_company_id');

        
        $companyList_arr = [];
        foreach($company_id as $com) {
            $company_info = DB::table('org_companies as orc')
            ->select('orc.org_company_id', 'orc.org_name as company_name', 'orc.org_name_kana  as company_name_kana',
            'ora.org_agent_id', 'ora.org_name', 'ora.org_name_kana',
            'orm.org_matchings_id', 'orm.employment_decision_ox'
            )
            ->join('org_matchings as orm', 'orm.org_company_id', '=', 'orc.org_company_id')
            ->join('org_agents as ora', 'ora.org_agent_id', '=', 'orm.org_agent_id')
            ->where([
                ['orm.org_company_id', '=', $com],
                ['orm.org_college_id', '=', $college_id],
            ])
            ->get();
            array_push($companyList_arr, $company_info[0]);
        }
        
        return $companyList_arr;
    }
    // 기업 정보 AND 채용건
    public function companyInfo() {

        // 기업 아이디 
        $company_id  = 'jphalo';

        // 기업 정보
        $company_info = DB::table('org_companies as com')
        ->select('com.manager_login_id', 'cag.email', 'cag.name_kana', 'cag.name', 'com.org_name_kana', 'com.org_name', 
                 'com.country_code', 'com.photo_url', 'com.address_to_dou_hu_ken' , 'com.address', 'com.homepage_url', 'com.establish_date',
                 'com.ceo_name', 'com.ceo_name_kana', 'com.worker_count', 'com.capital', 'com.business_content',
                 'com.company_atmosphere', 'com.recommendation_comment', 'com.listed_company_ox', 'coc.country_eng') 
        ->join('country_codes as coc', 'coc.country_code', '=', 'com.country_code')
        ->join('company_agents as cag', 'com.manager_login_id', '=', 'cag.login_id')
        ->where('com.org_company_id', '=', $company_id)
        ->get();
        $company_img = DB::table('org_imgs')->where('org_id', '=', $company_id)->pluck('photo_url');
        // 채용건 
        $matching_id = DB::table('org_matchings')->where('org_company_id', '=', $company_id)->pluck('org_matchings_id');
        $matching_id_arr =[];
        foreach($matching_id as $mti) {
            array_push($matching_id_arr, $mti);
        }
        // 제목 학교 에이전트 데드라인
        $employment_data_arr = [];

        foreach($matching_id_arr as $mia) {
            $employment_data = DB::table('employment_infos as emi')
            ->select('emi.employment_id', 'emi.employment_name', 'emi.apply_deadline_date', 'emi.business_type_content', 'emi.desired_employee_content', 'working_area',
            'orm.matching_date', 'orm.employment_decision_ox',
            'orc.org_college_id', 'orc.org_name as college_name', 'orc.org_name_kana as college_name_kana',
            'ora.org_name as agent_name', 'ora.org_name_kana as agent_name_kana'
            )
            ->join('org_matchings as orm', 'orm.org_matchings_id', 'emi.org_matching_foreign')
            ->join('org_colleges as orc', 'orc.org_college_id', '=', 'orm.org_college_id')
            ->join('org_agents as ora', 'ora.org_agent_id', '=', 'orm.org_agent_id')
            ->where('emi.org_matching_foreign', '=', $mia)
            ->get();
            array_push($employment_data_arr, $employment_data[0]);
        }

        return array('company_info' => $company_info, 'company_publish_imgs' => $company_img,  'employment_info' => $employment_data_arr);
        
    }

    // 채용 공고 상세
    public function detailEmploymentInfo() {
        // 매칭 아이디 가지고 와야함
        $matching_id = '1';
        $employment_data = DB::table('employment_infos as emi')
            ->select('emi.employment_name', 'emi.apply_deadline_date', 'emi.business_type_content',  'emi.desired_employee_content', 'working_area',
            'emi.pay', 'emi.business_hour', 'emi.holiday', 'emi.welfare_content', 'emi.expected_acceptance_date', 'emi.whole_interview_count', 'emi.acceptance_fixed_ox',
            'emi.employment_owari_ox', 'emi.file_url',
            'orm.matching_date', 'orm.employment_decision_ox',
            'orc.org_name as college_name', 'orc.org_name_kana as college_name_kana',
            /*2018 04 28 14:29 지노*/'ora.org_name as agent_name', 'ora.org_name_kana as agent_name_kana'
            )
            ->join('org_matchings as orm', 'orm.org_matchings_id', 'emi.org_matching_foreign')
            ->join('org_colleges as orc', 'orc.org_college_id', '=', 'orm.org_college_id')
            ->join('org_agents as ora', 'ora.org_agent_id', '=', 'orm.org_agent_id')
            ->where('emi.org_matching_foreign', '=', $matching_id)
            ->get();

        return $employment_data;
    }

}
