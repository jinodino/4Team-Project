<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class CompanyInfoController extends Controller
{

    /**
     *  기업 정보, 채용정보 받아오기
     * @param String company_id
     * @return Array[Object]
     */

     //Column not found: 1054 Unknown column 'com.employment_decision_ox' in 'field list' (SQL: select `com`.`manager_login_id`, `com`.`org_name_kanji`, `com`.`org_name_kana`, `com`.`org_name_eng`, `com`.`country_code`, `com`.`photo_url`, `com`.`address`, `com`.`homepage_url`, `com`.`establish_date`, `com`.`ceo_name_kanji`, `com`.`ceo_name_kana`, `com`.`worker_count`, `com`.`capital`, `com`.`business_content`, `com`.`company_atmosphere`, `com`.`recommendation_comment`, `com`.`employment_decision_ox`, `com`.`listed_company_ox`, `emp`.`apply_deadline_date`, `emp`.`business_type_content`, `emp`.`desired_employee_content`, `emp`.`working_area`, `emp`.`pay`, `emp`.`business_hour`, `emp`.`holiday`, `emp`.`welfare_content`, `emp`.`acceptance_fixed_ox`, `emp`.`employment_year_date`, `emp`.`file_url` from `org_companies` as `com` inner join `employment_infos` as `emp` on `emp`.`org_company_id` = `com`.`org_company_id` where `com`.`org_company_id` = jphalo)",
     public function detailsCompanyInfo() {
        $date = date('Y-m-d');
        
        $company_id = request('requester');
        // 기업 정보
        $company_id = DB::table('org_companies')->where('manager_login_id', '=', $company_id)->pluck('org_company_id');

        if(!isset($company_id[0])) return 0;

        $detail_company_info = DB::table('org_companies as com')
        ->select('com.manager_login_id', 'cag.email', 'cag.name_kana', 'cag.name', 'com.org_name_kana', 'com.org_name', 
                 'com.country_code', 'com.photo_url', 'com.address_to_dou_hu_ken' , 'com.address', 'com.homepage_url', 'com.establish_date',
                 'com.ceo_name', 'com.ceo_name_kana', 'com.worker_count', 'com.capital', 'com.business_content',
                 'com.company_atmosphere', 'com.recommendation_comment', 'com.listed_company_ox', 'coc.country_eng') 
        ->join('country_codes as coc', 'coc.country_code', '=', 'com.country_code')
        ->join('company_agents as cag', 'com.manager_login_id', '=', 'cag.login_id')
        ->where('com.org_company_id', '=', $company_id[0])
        ->get();
        
        // 기업 이미지
        $company_img = DB::table('org_imgs')->where('org_id', '=', $company_id[0])->pluck('photo_url');

        $matching_data = DB::table('org_matchings as orm')
        ->select(
            'orm.org_matchings_id', 'orc.org_name as college_name', 'orc.org_college_id',
            'orm.employment_decision_ox', 'orm.org_agent_id', 'orm.matching_date',
            'ora.org_name as org_agent_name', 'ora.org_name_kana as org_agent_name_kana'
         )
        ->join('org_agents as ora', 'ora.org_agent_id', '=', 'orm.org_agent_id')
        ->join('org_colleges as orc', 'orc.org_college_id', '=', 'orm.org_college_id')
        ->where([
            //['orm.employment_decision_ox', '=', 'o'],
            ['orm.org_company_id', '=', $company_id[0]]
        ])
        ->get();

        // 전체 채용 공고 리스트
        $matching_id = DB::table('org_matchings')->where('org_company_id', '=', $company_id[0])->pluck('org_matchings_id');
        $matching_id_arr =[];
        foreach($matching_id as $mti) {
            array_push($matching_id_arr, $mti);
        }
        // 제목 학교 에이전트 데드라인
        $employment_data_arr = [];
        // 0502
        foreach($matching_id_arr as $mia) {
            $employment_data = DB::table('employment_infos as emi')
            ->select('emi.employment_id', 'emi.employment_name', 'emi.apply_deadline_date', 'emi.business_type_content', 'emi.desired_employee_content', 'emi.working_area',
            'orm.matching_date', 'orm.employment_decision_ox', 'emi.employment_owari_ox', 'emi.data_entry_time', 'emi.apply_open_date',
            'orc.org_college_id', 'orc.org_name as college_name', 'orc.org_name_kana as college_name_kana',
            'ora.org_name as org_agent_name', 'ora.org_name_kana as agent_name_kana', 'ora.org_agent_id as org_agent_id',
            DB::raw("case when emi.apply_deadline_date >= '$date' AND emi.apply_open_date <= '$date' then 'OPEN'
            when emi.apply_deadline_date <= '$date' then 'CLOSE'
            when emi.apply_open_date >= '$date' then 'YET' end as date_result")
            )
            ->join('org_matchings as orm', 'orm.org_matchings_id', 'emi.org_matching_foreign')
            ->join('org_colleges as orc', 'orc.org_college_id', '=', 'orm.org_college_id')
            ->join('org_agents as ora', 'ora.org_agent_id', '=', 'orm.org_agent_id')
            ->where([
                // 지원 시간 < x < 마감시간
                ['emi.org_matching_foreign', '=', $mia],
                ['emi.employment_owari_ox', '=', 'x'],
                ['emi.apply_deadline_date', '>=', $date],
                ['emi.apply_open_date', '<=', $date],
            ])
            ->orWhere([
                ['emi.org_matching_foreign', '=', $mia],
                ['emi.employment_owari_ox', '=', 'x'],
                ['emi.apply_deadline_date', '<=', $date],
            ])
            ->orWhere([
                ['emi.org_matching_foreign', '=', $mia],
                ['emi.employment_owari_ox', '=', 'x'],
                ['emi.apply_open_date', '>=', $date],
            ])
            ->orderby('emi.data_entry_time', 'desc')
            ->get();
            if(isset($employment_data[0])) {
                array_push($employment_data_arr, $employment_data[0]);
            }
        }
        
        // 채용공고 상세 정보
        $employment_detail_data_arr = [];

        foreach($matching_id_arr as $mia) {
            $employment_data = DB::table('employment_infos as emi')
            ->select('emi.employment_name', 'emi.apply_deadline_date', 'emi.business_type_content',  'emi.desired_employee_content', 'working_area',
            'emi.pay', 'emi.pay_content', 'emi.bonus_pay', 'emi.transport_pay', 'emi.overtime_pay', 'emi.house_pay', 'emi.business_hour', 'emi.holiday',
            'emi.welfare_content', 'emi.expected_acceptance_date', 'emi.whole_interview_count', 'emi.acceptance_fixed_ox',
            'emi.employment_owari_ox', 'emi.file_url', 'emi.apply_open_date',
            'orm.matching_date', 'orm.employment_decision_ox',
            'orc.org_name as college_name', 'orc.org_name_kana as college_name_kana',
            'ora.org_name as org_agent_name', 'ora.org_name_kana as agent_name_kana',
            'ora.org_agent_id as org_agent_id'
            )
            ->join('org_matchings as orm', 'orm.org_matchings_id', 'emi.org_matching_foreign')
            ->join('org_colleges as orc', 'orc.org_college_id', '=', 'orm.org_college_id')
            ->join('org_agents as ora', 'ora.org_agent_id', '=', 'orm.org_agent_id')
            ->where([
                ['emi.org_matching_foreign', '=', $mia],
                ['emi.employment_owari_ox', '=', 'x'],
            ])
            ->get();

            if(isset($employment_data[0])) {
                array_push($employment_detail_data_arr, $employment_data[0]);
            }
            
        }
        
        /* 기업의  */
        return  array('comInfo' => $detail_company_info, 'matching_school_info' => $matching_data,
                      'employment_list_info' => $employment_data_arr, 'employment_detail_info' => $employment_detail_data_arr,
                      'company_publish_images' => $company_img, 'date' => $date);
    }

    public function employmentDetailInfo() {
        // 채용 아이디 받아야함
        $employment_id = request('selectedNotice');
        
        // 채용 정보
        $employment_data = DB::table('employment_infos as emi')
            ->select('emi.employment_name', 'emi.apply_deadline_date', 'emi.business_type_content',  'emi.desired_employee_content', 'working_area',
            'emi.pay', 'emi.pay_content', 'emi.bonus_pay', 'emi.transport_pay', 'emi.overtime_pay', 'emi.house_pay', 'emi.business_hour', 'emi.holiday', 'emi.welfare_content', 'emi.expected_acceptance_date', 'emi.whole_interview_count', 'emi.acceptance_fixed_ox',
            'emi.employment_owari_ox', 'emi.file_url',
            'orm.matching_date', 'orm.employment_decision_ox',
            'orc.org_name as college_name', 'orc.org_name_kana as college_name_kana',
            'ora.org_name as agent_name', 'ora.org_name_kana as agent_name_kana'
            )
            ->join('org_matchings as orm', 'orm.org_matchings_id', 'emi.org_matching_foreign')
            ->join('org_colleges as orc', 'orc.org_college_id', '=', 'orm.org_college_id')
            ->join('org_agents as ora', 'ora.org_agent_id', '=', 'orm.org_agent_id')
            ->where([
                ['emi.employment_id', '=', $employment_id],
                //['emi.employment_owari_ox', '=', 'x']
                ])
            ->get();
        // 빈배열일 시 반환
        if(!isset($employment_data[0])) return 0;
        // 복지 정보
        $welfare_info = DB::table('welfares as wel')
        ->select('wif.content')
        ->join('welfare_infos as wif', 'wif.id', '=' ,'wel.welfare_id')
        ->where('wel.employment_id', '=', $employment_id)
        ->get();

        // 일 정보
        $work_info = DB::table('work_matchings as wm')
        ->select('wi.content')
        ->join('work_infos as wi', 'wi.id', '=' ,'wm.work_id')
        ->where('wm.employment_id', '=', $employment_id)
        ->get();

        
        return array('employment_detail_info' => $employment_data, 'welfare_info' => $welfare_info, 'work_info' => $work_info);
    }

    public function profileInfoChange() {
        $requester = request('requester'); // 기업 담당자 ID
        $nameEn    = request('nameEn');    // 기업 담당자 NAME
        $nameKn    = request('nameKana');  // 기업 담당자 NAME_KANA
        $email     = request('emailAddr'); // 기업 담당자 email

        $check     = 0;
        $companyMagagerInfo = DB::table('company_agents')->select('name', 'name_kana', 'email')->where('login_id', '=', $requester)->get();

        foreach($companyMagagerInfo as $com) {
            if($com->name == $nameEn) $check++;
            if($com->name_kana == $nameKn) $check++;
            if($com->email == $email) $check++;
        }

        // 기존 정보와 같을 시 UPDATE가 안되므로 예외처리
        if($check == 3) return 2;
        
        // 정보 UPDATE
        $update = DB::table('company_agents')
        ->where('login_id', '=', $requester)
        ->update([
            'name'      => $nameEn,
            'name_kana' => $nameKn,
            'email'     => $email,
        ]);
        
        if(!$update) return 0;
        
        return 1;
         
    }

    public function sessiontest(Request $res) {
        session()->put('user_id', 'son');
        var_dump($res->session()->get('user_id')) ;
    }

    public function sessionuser(Request $res) {
        return $res->session()->get('user_id');
    }
    public function sessionde(Request $res) {
        return $res->session()->pull('user_id');
    }
}