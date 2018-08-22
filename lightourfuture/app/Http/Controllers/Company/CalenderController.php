<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CalenderController extends Controller
{
    public function schedule(Request $request){
        // 기업 아이디 있어야함
        $companyId = 'co01';//$request->get('companyId');

        $company_id = DB::table('org_companies')
        ->where('manager_login_id', '=', $companyId)
        ->pluck('org_company_id');
        
        $schedule_info = DB::table('interview_schedules as its')
        ->join('interview_infos as iti', 'iti.id', '=','its.interview_content_choice')
        ->join('employment_infos as emi', 'its.employment_id', '=', 'emi.employment_id')
        ->join('org_matchings as orm', 'orm.org_matchings_id', '=', 'emi.org_matching_foreign')
        ->join('org_colleges as orc', 'orm.org_college_id', '=', 'orc.org_college_id')
        ->select("its.interview_id",
                "its.employment_id",
                "its.schedule_title",
                "orc.org_college_id as org_college_id",
                "orc.org_name as org_college_name",
                "orc.org_name_kana as org_college_name_kana",
                "its.interview_count",
                "its.interview_place",
                "its.interview_date as date",
                "its.interview_start_time",
                "its.interview_end_time",
                "iti.content",
                "its.interview_check_ox"
        )
        ->where('orm.org_company_id', '=', $company_id[0])
        ->get();

        // 채용 정보 추가
        foreach($schedule_info as $key => $value){
            $employ_id = $value->employment_id;

            $work_info = DB::table('work_matchings')
            ->join('work_infos', 'work_matchings.work_id', '=', 'work_infos.id')
            ->join('employment_infos', 'work_matchings.employment_id', '=', 'employment_infos.employment_id')
            ->select("work_infos.content")
            ->whereRaw("employment_infos.employment_id = $employ_id")
            ->get();

            $schedule_info[$key]->work_content = $work_info;

            // 지원 학생 추가                                                                                                                        
            $applystdList = DB::table('interview_results as its')
            ->join('students as std', 'its.student_login_id', '=', 'std.login_id')
            ->select(
                'its.interview_id',
                'its.student_login_id',
                'its.interview_start_time',
                'its.interview_end_time',
                'std.name',
                'std.name_eng',
                'std.name_kana'
            )
            ->where('interview_id', '=', $value->interview_id)
            ->get();

            $schedule_info[$key]->applyStudent = $applystdList;
        }

        
        return $schedule_info;
        //return json_encode($schedule_info);
        //return array('schedules' => $schedule_info, 'applyStudent' => )
    }

    public function scheduleStudentList() {
        // 인터뷰 ID
        $interview_id = request('interviewId');
        $interview_id = 4;

        // 해당 면접에 대한 학생 면접 일정
        $interviewStudent = DB::table('interview_results as itr')
        ->join('students as std', 'itr.student_login_id', '=', 'std.login_id')
        ->select(
            'std.name', 'std.name_eng', 'std.name_kana',
            'itr.interview_start_time', 'itr.interview_end_time'
        )
        ->where('itr.interview_id', '=', $interview_id)
        ->get();

        return $interviewStudent;
    }
}


// $schedule_info = DB::table('interview_schedules')
//         ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
//         ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
//         //->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
//         ->join('org_colleges', 'org_matchings.org_college_id', '=', 'org_colleges.org_college_id')
//         //->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
//         ->join('interview_infos', 'interview_infos.id', '=', 'interview_schedules.interview_content_choice')
//         ->select("interview_schedules.interview_id",
//                 "interview_schedules.employment_id",
//                 "interview_schedules.schedule_title",
//                 //"org_companies.org_company_id as org_id",
//                 //"org_companies.org_name",
//                 //"org_companies.org_name_kana",
//                 "org_colleges.org_college_id as org_college_id",
//                 "org_colleges.org_name as org_college_name",
//                 "org_colleges.org_name_kana as org_college_name_kana",
//                 "interview_schedules.interview_count",
//                 "interview_schedules.interview_place",
//                 "interview_schedules.interview_date as date",
//                 "interview_schedules.interview_start_time",
//                 "interview_schedules.interview_end_time",
//                 "interview_infos.content",
//                 "interview_schedules.interview_check_ox")
//         // ->whereRaw("faculties.faculty_id in (select faculty_id
//         //                                         from professors
//         //                                         where professors.login_id = '$professorId')
//         //             and (interview_check_ox = 'o' or interview_check_ox = '' or interview_check_ox is null)")
//            ->where('org_matchings.org_company_id', '=', $companyId)
//         ->get();