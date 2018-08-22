<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class NominatedManagementController extends Controller
{
    // 내정 관리
    public function nominatedInfo() {

        // 기업 매니저 아이디
        $manager_id = request('requester');
        $company_id = DB::table('org_companies')->where('manager_login_id', '=', $manager_id)->pluck('org_company_id');
        
        // 기업 아이디가 없을 시 
        if(!isset($company_id[0])) return 0;

        // 채용공고리스트 (오와리 = o) -> 내정내린 학생 수, 내정 수락 학생 수
        $matching_data = DB::table('org_matchings as orm')
        ->select('orm.org_matchings_id',
        //'ag.login_id as agent_id', 'ag.name as agent_name',
        'ora.org_agent_id as org_agent_id', 'ora.org_name as org_agent_name',
        'orc.org_name', 'orc.org_college_id', 'emp.employment_name', 'emp.apply_deadline_date',
        'emp.business_type_content', 'emp.desired_employee_content', 'emp.working_area',
        'emp.pay', 'emp.pay_content',
        'emp.business_hour', 'emp.holiday', 'emp.welfare_content',
        'emp.employment_id', 'emp.employment_owari_ox', 'emp.apply_open_date',
        'its.interview_id', 'its.interview_date'
        )
        ->join('org_agents as ora', 'ora.org_agent_id', '=', 'orm.org_agent_id')
        //->join('agents as ag', 'ag.login_id', '=', 'ora.manager_login_id')
        ->join('employment_infos as emp', 'emp.org_matching_foreign', '=', 'orm.org_matchings_id')
        ->join('interview_schedules as its', 'its.employment_id', '=', 'emp.employment_id')
        //->join('interview_infos as iti', 'its.interview_content_choice', '=', 'iti.id')
        ->join('org_colleges as orc', 'orc.org_college_id', '=', 'orm.org_college_id')
        ->where([
            ['orm.employment_decision_ox', '=', 'o'],
            ['orm.org_company_id', '=', $company_id],
            ['emp.employment_owari_ox', '=', 'o'],
            ['its.interview_content_choice', '=', 5],
            ['its.interview_check_ox', '=', 'o'],
        ])
        ->get();
        
        
        $employment_id_arr = [];
        foreach($matching_data as $md) {
            
            $employment_id = DB::table('employment_infos')
            ->where([
                ['org_matching_foreign', '=', $md->org_matchings_id],
                ['employment_owari_ox', '=', 'o']
            ])
            ->pluck('employment_id');
            
            array_push($employment_id_arr, $employment_id);
        }
        
        // 내정 확정 학생수
        $open_count_arr = [];
        // 내정 수락 학생수
        $active_count_arr = [];
        
        foreach($employment_id_arr as $em) {
            foreach($em as $value) {
                
                $interview_id = DB::table('interview_schedules')
                ->select('interview_id')
                ->where([
                    ['employment_id', '=', $value],
                    ['interview_check_ox', '=', 'o'],
                ])
                ->oldest('interview_date')
                ->first();
                
                // 채용건은 있는데 면접 날이 없는 경우 예외처리
                if(!isset($interview_id)) $interview = 0;
                else $interview_id = $interview_id->interview_id;
                

                // 채용건에 지원한 학생 들 중 내정을 준 학생들 수
                $open_std_count = DB::table('interview_results as itr')
                ->select(DB::raw("count(student_login_id) as std"))
                ->where([
                    ['interview_id', '=', $interview_id],
                    ['interview_result', '=', 'o'],
                ])
                ->get();
                
                array_push($open_count_arr, $open_std_count[0]); 
                
                // 채용건에 지원한 학생 들 중 내정을 준 학생들 중 내정을 수락한 학생 수
                $active_std_count = DB::table('applies as ap')->select(DB::raw("count(ap.student_login_id) as std"))
                ->join('interview_schedules as is', 'is.employment_id', '=', 'ap.employment_id')
                ->where([
                    ['ap.employment_id', '=', $value],
                    ['is.interview_id', '=', $interview_id],
                    ['ap.acceptance_ox', '=', 'o'],
                    ['ap.professor_acceptance_ox', '=', 'o'],
                    ['is.interview_check_ox', '=', 'o'],
                ])
                ->get();
                
                array_push($active_count_arr, $active_std_count[0]); 
            }
        }
        
        return array('matching_data' => $matching_data, 'nominated' => $open_count_arr, 'acceptNominate' => $active_count_arr);
    }
    
    
    // 이름, 상태값, 성별, 사진, 이메일, 전화번호
    // 내정 준 학생 리스트 및 내정 수락 학생 리스트
    public function nominatedStdList() {
        
        // 기업 아이디
        $manager_id =  request('requester');
        // 채용공고 아이디
        $employment_id = request('notice');
        // 인터뷰 아이디
        $interview_id = request('interview');
        // DB::raw("case when emp.apply_deadline_date >= '$date' AND emp.apply_open_date <= '$date' then 'OPEN'
        // when emp.apply_deadline_date <= '$date' then 'CLOSE'
        // when emp.apply_open_date >= '$date' then 'YET' end as date_result")
        $nominatedList = DB::table('interview_results as itr')
            ->join('interview_schedules as is', 'is.interview_id', '=', 'itr.interview_id')
            ->join('applies as app', 'app.employment_id', '=', 'is.employment_id')
            //->join('resign_infos as res', 'res.id', '=', 'app.resign_id')
            ->join('students as std', 'std.login_id', '=', 'itr.student_login_id')
            ->select('std.login_id', 'std.gender', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.name_kana',
             'std.profile_photo_url', 'std.birth_date', 'std.phone', 'std.profile_photo_url',
             'app.acceptance_ox', 'app.student_acceptance_ox', 'app.professor_acceptance_ox',
             //DB::raw("case when !app.resign_id then app.resign_id, app.resign_comment when app.resign")
             'app.resign_comment'
             //'res.content'
            )
            ->where([
                ['itr.interview_id', '=', $interview_id],
                ['itr.interview_result', '=', 'o'],
                ['app.apply_permission_ox', '=', 'o'],
                ['is.interview_check_ox', '=', 'o'],
            ])
            ->groupby('std.login_id')
            ->get();

        return $nominatedList;
    }
}
    