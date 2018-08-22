<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Professor_calender extends Controller
{
    public function professor_schedule(Request $request){
        $professorId = $request->get('professorId');

        
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
                "org_colleges.org_college_id as org_college_id",
                "org_colleges.org_name as org_college_name",
                "org_colleges.org_name_kana as org_college_name_kana",
                "interview_schedules.interview_count",
                "interview_schedules.interview_place",
                "interview_schedules.interview_date",
                "interview_schedules.interview_start_time",
                "interview_schedules.interview_end_time",
                "interview_infos.content",
                "interview_schedules.interview_check_ox")
        ->whereRaw("faculties.faculty_id in (select faculty_id
                                                from professors
                                                where professors.login_id = '$professorId')
                    and (interview_check_ox = 'o' or interview_check_ox = '' or interview_check_ox is null)")
        ->orderBy("interview_schedules.interview_date", "asc")
        ->orderBy("interview_schedules.interview_start_time", "asc")
        ->get();
        
        foreach($schedule_info as $key => $schedule_infos){
            $employ_id = $schedule_infos->employment_id;

            $work_info = DB::table('work_matchings')
            ->join('work_infos', 'work_matchings.work_id', '=', 'work_infos.id')
            ->join('employment_infos', 'work_matchings.employment_id', '=', 'employment_infos.employment_id')
            ->select("work_infos.content")
            ->whereRaw("employment_infos.employment_id = $employ_id")
            ->get();

            $schedule_info[$key]->work_content = $work_info;
        }

        return json_encode($schedule_info);
    
    }


}
