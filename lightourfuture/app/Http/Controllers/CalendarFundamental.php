<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PushNotify as PushNotify;

class CalendarFundamental extends Controller
{
    //학생의 스케줄 출력
    public function std_interview_schedule(Request $request){
        $id = $request->get('stdId');

        $std_schedule_list = DB::table('interview_results')
        ->join('interview_schedules', 'interview_results.interview_id', '=', 'interview_schedules.interview_id')
        ->join('interview_infos', 'interview_infos.id', '=', 'interview_schedules.interview_content_choice')
        ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->select('org_companies.org_company_id as company_id',                  //회사 id
                'org_companies.org_name as company_name',                       //회사 이름
                'org_companies.org_name_kana as company_name_kana',             //회사 이름 카나
                'interview_schedules.interview_date as schedule_date',          //스케줄 날짜
                'interview_schedules.interview_start_time as schedule_start_time', //스케줄 시작 시간
                'interview_schedules.interview_end_time as schedule_end_time',  //스케줄 종료 시간
                'interview_results.interview_start_time as result_start_time',  //학생 면접 시작 시간
                'interview_results.interview_end_time as result_end_time',      //학생 면접 종료 시간
                'interview_infos.content as interview_info_content',            //면접 내용
                'employment_infos.employment_id as employment_id',             //채용id
                'interview_schedules.interview_check_ox as interview_check_ox', //교수 허락 여부
                'interview_schedules.interview_count as interview_count',       //면접 차수
                'interview_schedules.interview_id as interview_id',             //면접 ID
                'interview_schedules.interview_place as interview_place')       //면접 장소
        ->where(
            "interview_results.student_login_id", "=", "$id"
        )
        ->get();
        
        
        
        foreach($std_schedule_list as $key => $std_schedule_lists){
            $employ_id = $std_schedule_lists->employment_id;
            $std_schedule_list[$key]->work_content = DB::table('work_matchings')
            ->join('work_infos', 'work_matchings.work_id', '=', 'work_infos.id')
            ->where('work_matchings.employment_id', '=', $employ_id)
            ->select('work_infos.content')
            ->get();
        }
        
        return json_encode($std_schedule_list);
    }
    //학생 면접 시간 수정
    public function addStdInterval(Request $request){
        $id = $request->get('id');
        $appliedStd = $request->get('appliedStd');

        $interview_time = $request->get('interview_time');
        $interval = $request->get('interval');
        $date = $request->get('date');
        $minute = 00;
        $starttime = null;
        $interview_no = array();
        foreach($appliedStd as $appliedStds){
            array_push($interview_no, $appliedStds['no']);
        }

        //푸시알림 및 누적알림 작성
        $pushNotify = new PushNotify();
        $apiKey = $request->get('apiKey');
        
        foreach($appliedStd as $appliedStds){
            $stdId = $appliedStds['login_id'];
            $interview_start_time = $appliedStds['interview_start_time'];
            $interview_id = $appliedStds['interview_id'];
            $companyName = DB::table('employment_infos')
                        ->join('interview_schedules', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->where('interview_schedules.interview_id', '=', $interview_id)
                        ->select('org_companies.org_name')
                        ->get();
            foreach($companyName as $companyNames){
                $company_Name = $companyNames->org_name;
            }
        
            $pushNotify->push_select_send($id, $stdId, $apiKey, "$company_Name 사의 스케줄 시간이 $interview_start_time 으로 변경되었습니다.");
        }
        

        switch($interval){
            case "30분":
            $minute = 30;
            break;
            case "1시간":
            $minute = 60;
            break;
            case "90분":
            $minute = 90;
            break;
            case "2시간":
            $minute = 120;
            break;
        }
        
        foreach($interview_time as $key => $interview_times){
            $time = explode(':', $interview_times);

            $starttime = date("$time[0]:$time[1]:$time[2]");
            $next_date = date("H:i:s", strtotime($starttime . " +$minute minute"));
            
            $first = $date." ".$starttime;
            $second = $date." ".$next_date;
            DB::table('interview_results')
            ->where('no', '=', $interview_no[$key])
            ->update(
                ['interview_start_time'=> "$first"],
                ['interview_end_time' => "$second"]
            );
        }
        
        
        
        return 'success';
    }

    //에이전트와 매칭된 기업 리스트를 출력
    public function interview_company_list(Request $request){
        $user_id = $request->get('agentId');
        $year = date("Y");
        
        $company_list = DB::table('org_matchings')
        ->distinct()
        ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
        ->join('agent_belongs', 'agent_belongs.org_agent_id', '=', 'org_matchings.org_agent_id')
        ->select('org_companies.org_company_id as org_company_id', 'org_companies.org_name as text', 'org_companies.org_name_kana')
        ->where([
            ["agent_belongs.agent_id", "=", "$user_id"],
            ["org_matchings.matching_date", "=","$year"]
        ])
        ->get();

        return $company_list;
        
    }

    //에이전트와 관련된 모든 면접 스케줄 출력
    public function interview_agent_list(Request $request){
        $agentId = $request->get('agentId');

        $schedule_list = DB::table('interview_schedules')
        ->join('interview_infos', 'interview_infos.id', '=', 'interview_schedules.interview_content_choice')
        ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('org_colleges', 'org_colleges.org_college_id', '=', 'org_matchings.org_college_id')
        ->select('interview_schedules.interview_id', 'interview_infos.content', 'interview_schedules.employment_id', 'org_companies.org_name', 'org_companies.org_name_kana',
        'org_colleges.org_name as school', 'org_colleges.org_name_kana',
        'interview_schedules.schedule_title', 'interview_schedules.interview_date as date',
        'interview_schedules.interview_start_time', 'interview_schedules.interview_end_time',
        'interview_schedules.interview_count', 'interview_schedules.interview_place', 'interview_check_ox')
        ->whereRaw("org_matchings.org_agent_id in (select org_agent_id from agent_belongs where agent_id = '$agentId')")
        ->get();

        foreach($schedule_list as $key => $schedule_lists){
            $employment_id = $schedule_lists->employment_id;

            $schedule_list[$key]->branch = DB::table('work_matchings')
            ->join('work_infos', 'work_matchings.work_id', '=', 'work_infos.id')
            ->select('work_infos.content')
            ->where('work_matchings.employment_id', '=', $employment_id)
            ->get();

        }

        return json_encode($schedule_list);
    }

    //에이전트와 선택한 기업과 매칭된 학교 리스트 출력
    public function interview_college_list(Request $request){
        $user_id = $request->get('id');
        $company_id = $request->get('companyid');
        $year = date("Y");

        $college_list = DB::table('org_matchings')
        ->distinct()
        ->join('agent_belongs', 'agent_belongs.org_agent_id', '=', 'org_matchings.org_agent_id')
        ->join('org_colleges', 'org_colleges.org_college_id', '=', 'org_matchings.org_college_id')
        ->select('org_colleges.org_college_id as org_school_id', 'org_colleges.org_name as text', 'org_colleges.org_name_kana')
        ->where([
            ["agent_belongs.agent_id", "=", "$user_id"],
            ["org_matchings.matching_date", "=","$year"],
            ["org_matchings.org_company_id", "=", "$company_id"]
        ])
        ->get();

        return $college_list;
    }

    //채용ID리스트, 채용ID별 업무 분야 출력
    public function interview_employment_id(Request $request){
        $user_id = $request->get('id');
        $company_id = $request->get('company_id');
        $college_id = $request->get('school_id');
        $year = date("Y");

        //채용ID 획득
        $employment_id_acquisition = DB::table('org_matchings')
        ->join('employment_infos', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('agent_belongs', 'agent_belongs.org_agent_id', '=', 'org_matchings.org_agent_id')
        ->where([
            ["agent_belongs.agent_id", "=", $user_id],
            ["org_matchings.matching_date", "=", $year],
            ["org_matchings.org_company_id", "=", $company_id],
            ["org_matchings.org_college_id", "=", $college_id]
        ])
        ->select('employment_infos.employment_id')
        ->get();

        foreach($employment_id_acquisition as $key =>$employment_id_acquisitions){
            $employment_id = $employment_id_acquisitions->employment_id;

            $employment_id_acquisition[$key]->branch = DB::table('work_matchings')
            ->join('work_infos', 'work_matchings.work_id', '=', 'work_infos.id')
            ->select('work_infos.content')
            ->where('work_matchings.employment_id', '=', $employment_id)
            ->get();
        }

        return json_encode($employment_id_acquisition);

    }

    //면접 전형 리스트 출력
    public function interview_content_list(Request $request){
        //type
        $interview_content = DB::table('interview_infos')
        ->select('id', 'content as type')
        ->get();

        return $interview_content;
    }

    //면접 차수 출력
    public function interview_count(Request $request){
        $user_id = $request->get('id');
        $company_id = $request->get('company_id');
        $school_id = $request->get('school_id');
        $employment_id = $request->get('branch');

        $org_matching_id = DB::table('org_matchings')
        ->join('agent_belongs', 'org_matchings.org_agent_id', '=', 'agent_belongs.org_agent_id')
        ->where([
            ['agent_belongs.agent_id', '=', "$user_id"],
            ['org_matchings.org_company_id','=', "$company_id"],
            ['org_matchings.org_college_id', '=', "$school_id"]
        ])
        ->select('org_matchings_id')
        ->get();
        
        $matching_id = null;

        foreach($org_matching_id as $org_matching_ids){
            $matching_id = $org_matching_ids->org_matchings_id;
            break;
        }

        $interview_count = DB::table('employment_infos')
        ->join(DB::raw("(select employment_id, interview_date, max(interview_count) as interview_count from interview_schedules where employment_id = $employment_id group by employment_id) as interview_schedules"),
                'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
        ->where('employment_infos.org_matching_foreign', '=', $matching_id)
        ->select(DB::raw("interview_schedules.interview_count+1 as interview_count"))
        ->get();

        foreach($interview_count as $interview_counts){
            return $interview_counts->interview_count;
        }
        

    }

    //일정 등록
    public function interview_register(Request $request){
        $user_id = $request->get('id');
        $data = $request->get('data');
        $addDate = $request->get('addDate');

        $selectedCompany = $data['selectedCompany'];
        $selectedSchool = $data['selectedSchool'];

        $year = date("Y");
        $employment_id = array();
        

        // 날짜 및 시간 포멧 맞추기
        if($addDate['startMinute'] == 0) {
            $addDate['startMinute'] .=  '0';
        }
        if($addDate['endMinute'] == 0) {
            $addDate['endMinute'] .=  '0';
        }
        if($addDate['month'] < 10) {
            $addDate['month'] = '0' . $addDate['month'];
        }
        // if($addDate['day'] < 10) {
        //     $addDate['day'] = '0' . $addDate['day'];
        // }
        
        $idate = $addDate['year'] . $addDate['month'] . $addDate['day'];
        
        

        $start_time = $addDate['startHour'] .":". $addDate['startMinute'].":00"; 
        $end_time   = $addDate['endHour'] .":". $addDate['endMinute'].":00"; 
        // 날짜 및 시간 포멧 맞추기 끝

        // 날짜 넣기
        $insert = DB::table('interview_schedules')
        ->where([
            ['employment_id', '=', $addDate['branch']]
        ])
        ->insert([
            'employment_id'             => $addDate['branch'],
            'writer_id'                 => $user_id,
            'interview_count'           => $addDate['selectedLevel'],
            'interview_date'            => $idate,
            'interview_start_time'      => $start_time,
            'interview_end_time'        => $end_time,
            'interview_content_choice'  => $addDate['selectedType'],
        ]);
        
        //푸시알림 및 누적알림 작성
        $pushNotify = new PushNotify();
        $apiKey = $request->get('apiKey');
        
        $company_name = DB::table('org_companies')
                            ->where('org_company_id', '=', $selectedCompany)
                            ->select('org_name')
                            ->get();
        foreach($company_name as $company_names){
            $com_name = $company_names->org_name;
        }
        $pro_id = DB::table('org_colleges')
                    ->join('faculties', 'org_colleges.org_college_id', '=', 'faculties.org_college_id')
                    ->join('professors as pro', 'pro.faculty_id', '=', 'faculties.faculty_id')
                    ->where('org_colleges.org_college_id', '=', $selectedSchool)
                    ->select('pro.login_id')
                    ->get();
        foreach($pro_id as $pro_ids){
            $professor_id = $pro_ids->login_id;
            $pushNotify->push_select_send($user_id, $professor_id, $apiKey, "$com_name 사의 일정이 등록되었습니다.");
        }
        
        
        return "success";
    }


    public function interview_schedule_change(Request $request){
        //시간 수정
        $id = $request->get('id');
        $data= $request->get('data');
        
        $interview_id = $data['interview_id'];
        $date = $data['date'];
        $start_time = $data['startHour'].":".$data['startMinute'].":00";
        $end_time = $data['endHour'].":".$data['endMinute'].":00";
        $employment_id = $data['employment_id'];
        $selectedCompany = $data['org_name'];
        DB::table('interview_schedules')
        ->where('interview_id', '=', $interview_id)
        ->update(['interview_date' => $date, 'interview_start_time' => $start_time, 'interview_end_time' => $end_time]);

        //푸시알림 및 누적알림 작성
        $pushNotify = new PushNotify();
        $apiKey = $request->get('apiKey');
        $schoolId = DB::table('employment_infos')
                    ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                    ->where('employment_infos.employment_id', '=', $employment_id)
                    ->select('org_matchings.org_college_id')
                    ->get();
        foreach($schoolId as $schoolIds){
            $selectedSchool = $schoolIds->org_college_id;
            $pro_id = DB::table('org_colleges')
                        ->join('faculties', 'org_colleges.org_college_id', '=', 'faculties.org_college_id')
                        ->join('professors as pro', 'pro.faculty_id', '=', 'faculties.faculty_id')
                        ->where('org_colleges.org_college_id', '=', $selectedSchool)
                        ->select('pro.login_id')
                        ->get();
            foreach($pro_id as $pro_ids){
                $professor_id = $pro_ids->login_id;
                $pushNotify->push_select_send($id, $professor_id, $apiKey, "$selectedCompany 사의 일정이 변경되었습니다.");
            }
        }
        

        return 'success';
    }
    public function interview_schedule_change_year(Request $request){
        $pushNotify = new PushNotify();

        //날짜 수정
        $id = $request->get('id');
        $date= $request->get('date');
        $interview_id = $request->get('interview_id');
        $apiKey = $request->get('apiKey');

        DB::table('interview_schedules')
        ->where('interview_id', '=', $interview_id)
        ->update(['interview_date' => $date]);

        $std_id = DB::table('interview_results')
        ->join("interview_schedules", "interview_results.interview_id", "=", "interview_schedules.interview_id")
        ->join('employment_infos', 'employment_infos.employment_id', '=', 'interview_schedules.employment_id')
        ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
        ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
        ->select(
            "interview_results.student_login_id",
            'org_companies.org_name')
        ->where("interview_schedules.interview_id", "=", $interview_id)
        ->get();

        foreach($std_id as $std_ids){
            $student_id = $std_ids->student_login_id;
            $company_name = $std_ids->org_name;
            $pushNotify->push_select_send($id, $student_id, $apiKey, "$company_name 사의 면접 날짜가 변경되었습니다.");
        }

        return 'success';
    }

    //스케줄 삭제
    public function interview_schedule_delete(Request $request){
        $id = $request->get('id');
        $interview_id = $request->get('interview_id');
        

        //푸시알림 및 누적알림 작성
        $pushNotify = new PushNotify();
        $apiKey = $request->get('apiKey');
        $schoolId = DB::table('employment_infos')
                    ->join('interview_schedules', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
                    ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                    ->where('interview_schedules.interview_id', '=', $interview_id)
                    ->select('org_matchings.org_college_id')
                    ->get();
        $companyName = DB::table('employment_infos')
                    ->join('interview_schedules', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
                    ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                    ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                    ->where('interview_schedules.interview_id', '=', $interview_id)
                    ->select('org_companies.org_name')
                    ->get();
        foreach($companyName as $companyNames){
            $selectedCompany = $companyNames->org_name;
        }
        foreach($schoolId as $schoolIds){
            $selectedSchool = $schoolIds->org_college_id;
            $pro_id = DB::table('org_colleges')
                        ->join('faculties', 'org_colleges.org_college_id', '=', 'faculties.org_college_id')
                        ->join('professors as pro', 'pro.faculty_id', '=', 'faculties.faculty_id')
                        ->where('org_colleges.org_college_id', '=', $selectedSchool)
                        ->select('pro.login_id')
                        ->get();
            foreach($pro_id as $pro_ids){
                $professor_id = $pro_ids->login_id;
                $pushNotify->push_select_send($id, $professor_id, $apiKey, "$selectedCompany 사의 일정이 삭제되었습니다.");
            }
        }
        DB::table('interview_schedules')->where('interview_id', '=', $interview_id)->delete();
        
        return 'delete';
    }

    //교수의 면접 일정 수락/거부
    public function interview_schedule_agree(Request $request){
        $id = $request->get('id');
        $interview_place = $request->get('interview_place');
        $interview_id = $request->get('interview_id');

        
        //푸시알림 및 누적알림 작성
        $collegeName = DB::table("interview_schedules as isc")
                        ->join('employment_infos as ei', 'isc.employment_id', '=', 'ei.employment_id')
                        ->join('org_matchings as om', 'om.org_matchings_id', '=', 'ei.org_matching_foreign')
                        ->join('org_colleges as oc', 'om.org_college_id', '=', 'oc.org_college_id')
                        ->where('isc.interview_id', '=', $interview_id)
                        ->select('oc.org_name', 
                                 'oc.org_name_kana')
                        ->get();
        foreach($collegeName as $collegeNames){
            $college_Name = $collegeNames->org_name;
            $college_Name_kana = $collegeNames->org_name_kana;
        }
        $interview_date = DB::table('interview_schedules')
                            ->select('interview_date')
                            ->where('interview_id', '=', $interview_id)
                            ->get();
        foreach($interview_date as $interview_dates){
            $interviewDate = $interview_dates->interview_date;
        }
        $companyName = DB::table('employment_infos')
                        ->join('interview_schedules', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->where('interview_schedules.interview_id', '=', $interview_id)
                        ->select('org_companies.org_name')
                        ->get();
        foreach($companyName as $companyNames){
            $company_Name = $companyNames->org_name;
        }
        
        $agentId = DB::table("interview_schedules as isc")
                    ->join('employment_infos as ei', 'isc.employment_id', '=', 'ei.employment_id')
                    ->join('org_matchings as om', 'om.org_matchings_id', '=', 'ei.org_matching_foreign')
                    ->join('agent_belongs as ab', 'om.org_agent_id', '=', 'ab.org_agent_id')
                    ->select('ab.agent_id')
                    ->where('isc.interview_id', '=', $interview_id)
                    ->get();
        
        
        
        $pushNotify = new PushNotify();
        $apiKey = $request->get('apiKey');
        foreach($agentId as $agentIds){
            $agent_id = $agentIds->agent_id;
            $pushNotify->push_select_send($id, $agent_id, $apiKey, "$college_Name($college_Name_kana)で $interviewDate $company_Name 社の面接のスケージュールを受諾しました。");
        }

        DB::table('interview_schedules')
        ->where('interview_id', '=', $interview_id)
        ->update(['interview_place' => $interview_place, 'interview_check_ox' => 'o']);
        
        return 'success';
    }

    //교수의 면접 장소 수정
    public function interview_place_change(Request $request){
        $pushNotify = new PushNotify();

        $id = $request->get('id');
        $interview_place = $request->get('interview_place');
        $interview_id = $request->get('interview_id');
        $apiKey = $request->get('apiKey');


        DB::table('interview_schedules')
        ->where('interview_id', '=', $interview_id)
        ->update(['interview_place' => $interview_place]);

        $std_id = DB::table('interview_results')
        ->join("interview_schedules", "interview_results.interview_id", "=", "interview_schedules.interview_id")
        ->join('employment_infos', 'employment_infos.employment_id', '=', 'interview_schedules.employment_id')
        ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
        ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
        ->select(
            "interview_results.student_login_id",
            'org_companies.org_name')
        ->where("interview_schedules.interview_id", "=", $interview_id)
        ->get();

        foreach($std_id as $std_ids){
            $student_id = $std_ids->student_login_id;
            $company_name = $std_ids->org_name;
            $pushNotify->push_select_send($id, $student_id, $apiKey, "$company_name 사의 면접 장소가 변경되었습니다.");
        }

        return 'success';
    }

    public function agent_schedule_list(Request $request){
        $agentId = $request->get('agentId');

        
        $schedule_info = DB::table('interview_schedules')
        ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('org_colleges', 'org_matchings.org_college_id', '=', 'org_colleges.org_college_id')
        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
        ->join('interview_infos', 'interview_infos.id', '=', 'interview_schedules.interview_content_choice')
        ->select("interview_schedules.interview_id",
                "interview_schedules.employment_id",
                "interview_schedules.schedule_title",
                "org_companies.org_company_id as org_id",
                "org_companies.org_name",
                "org_companies.org_name_kana",
                "org_colleges.org_college_id",
                "org_colleges.org_name",
                "org_colleges.org_name_kana",
                "interview_schedules.interview_count",
                "interview_schedules.interview_place",
                "interview_schedules.interview_date as date",
                "interview_schedules.interview_start_time",
                "interview_schedules.interview_end_time",
                "interview_infos.content",
                "interview_schedules.interview_check_ox")
        ->whereRaw("org_matchings.org_agent_id in (select org_agent_id
                                                    from agent_belongs
                                                    where agent_id = '$agentId')
                    and (interview_check_ox = 'o' or interview_check_ox = '' or interview_check_ox is null)")
        ->get();
        
        return $schedule_info;
    
    }

    //스케줄 일정 보기 전 : 날짜, 시간
    public function schedule_Registration_By_Student(request $request){
        $id = $request->get('id');
        $interview_id = $request->get('interview_id');
        $classification = $request->get('classification');
        
        
        switch($classification){
            case "professor":
                $std_name_info = DB::table('interview_schedules')
                    ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                    ->join('employment_infos', 'employment_infos.employment_id', '=', 'interview_schedules.employment_id')
                    ->join('students as std', 'std.login_id', '=', 'interview_results.student_login_id')
                    ->join('professors as pro', 'pro.login_id', '=', 'std.professor_login_id')
                    ->select("interview_results.no",
                            "interview_results.interview_id",
                            "interview_results.interview_start_time",
                            "interview_results.interview_end_time",
                            "std.*")
                    ->whereRaw("interview_schedules.interview_id = '$interview_id'
                                and pro.faculty_id in (select faculty_id 
                                                        from professors 
                                                        where professors.login_id = '$id')")
                    ->orderBy("interview_results.interview_start_time", "asc")
                    ->get();
            break;
            case "agent":
                $std_name_info = DB::table('interview_schedules')
                    ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                    ->join('employment_infos', 'employment_infos.employment_id', '=', 'interview_schedules.employment_id')
                    ->join('students as std', 'std.login_id', '=', 'interview_results.student_login_id')
                    ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
                    ->join('agent_belongs', 'org_matchings.org_agent_id', '=', 'agent_belongs.org_agent_id')
                    ->join('agents', 'agents.login_id', '=', 'agent_belongs.agent_id')
                    ->select("interview_results.no",
                            "interview_results.interview_id",
                            "interview_results.interview_start_time",
                            "interview_results.interview_end_time",
                            "std.*")
                    ->where([
                        ['interview_schedules.interview_id', '=', $interview_id],
                        ['agents.login_id', '=', $id]
                    ])
                    ->orderBy("interview_results.interview_start_time", "asc")
                    ->get();
            break;
            case "student":
                $std_name_info = DB::table('interview_schedules')
                    ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                    ->join('employment_infos', 'employment_infos.employment_id', '=', 'interview_schedules.employment_id')
                    ->join('students as std', 'std.login_id', '=', 'interview_results.student_login_id')
                    ->select("interview_results.no",
                            "interview_results.interview_id",
                            "interview_results.interview_start_time",
                            "interview_results.interview_end_time",
                            "std.*")
                    ->where([
                        ['interview_schedules.interview_id', '=', $interview_id],
                        ['std.login_id', '=', $id]
                    ])
                    ->orderBy("interview_results.interview_start_time", "asc")
                    ->get();
            break;
            case "company":
                $std_name_info = DB::table('interview_schedules')
                    ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                    ->join('employment_infos', 'employment_infos.employment_id', '=', 'interview_schedules.employment_id')
                    ->join('students as std', 'std.login_id', '=', 'interview_results.student_login_id')
                    ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
                    ->join('company_agents', 'company_agents.org_company_id', '=', 'org_matchings.org_company_id')
                    ->select("interview_results.no",
                            "interview_results.interview_id",
                            "interview_results.interview_start_time",
                            "interview_results.interview_end_time",
                            "std.*")
                    ->where([
                        ['interview_schedules.interview_id', '=', $interview_id],
                        ['company_agents.login_id', '=', $id]
                    ])
                    ->orderBy("interview_results.interview_start_time", "asc")
                    ->get();
            break;
        }
        /*
        foreach($std_name_info as $key => $std_name_infos){
            $std_id = $std_name_infos->login_id;
            $interview_schedule_id = $std_name_infos->interview_id;

            $std_name_info["$key"]->interview_time = DB::table('interview_results')
            ->where([
                ['student_login_id', '=', $std_id],
                ['interview_id', '=', $interview_schedule_id]
            ])
            ->select('interview_start_time', 'interview_end_time')
            ->get();
            
        }
        */
        return json_encode($std_name_info);

    }
}
