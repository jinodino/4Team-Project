<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Professor_home extends Controller
{
    public function callStudent(Request $request){
        $professorId = $request->get('professorId');
        $date = date("Y");
        
        //올해 총 학생 정보
        $std_all_list = DB::table('applies')
            ->distinct()
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->join('employment_infos as ei', 'ei.employment_id', '=', 'applies.employment_id')
            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'org_matching_foreign')
            ->select('std.login_id', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.profile_photo_url')
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                        and org_matchings.matching_date = $date")
            ->get();
            
        //올해 내정 확정 학생
        $std_fix_list = DB::table('applies')
            ->distinct()
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->join('employment_infos as ei', 'ei.employment_id', '=', 'applies.employment_id')
            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'org_matching_foreign')
            ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
            ->select('std.login_id', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.profile_photo_url','org_companies.org_company_id', 'org_companies.org_name', 'org_companies.org_name_kana','org_companies.org_name', 'org_companies.listed_company_ox')
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                        and org_matchings.matching_date = $date
                        and applies.student_acceptance_ox = 'o'
                        and applies.professor_acceptance_ox = 'o'")
            ->get();
        
        //1사 이상 내정 학생
        $std_be_list = DB::table('applies')
            ->distinct()
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->join('employment_infos as ei', 'ei.employment_id', '=', 'applies.employment_id')
            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'org_matching_foreign')
            ->select('std.login_id', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.profile_photo_url')
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                        and org_matchings.matching_date = $date
                        and (std.login_id, 0) not in (SELECT student_login_id, count(case when acceptance_ox = 'o' then 1 end) as no_acceptance
                                                    FROM applies
                                                    group by student_login_id)
                        and (applies.student_acceptance_ox != 'o' or applies.professor_acceptance_ox != 'o')")
            ->get();
        foreach($std_be_list as $key => $std_be_lists){
            $login_id = $std_be_lists->login_id;
            $std_be_list[$key]->be_company_list = DB::table('applies')
                                                    ->distinct()
                                                    ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                                                    ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
                                                    ->join('employment_infos as ei', 'ei.employment_id', '=', 'applies.employment_id')
                                                    ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'org_matching_foreign')
                                                    ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
                                                    ->select('org_companies.org_company_id', 'org_companies.org_name', 'org_companies.org_name_kana','org_companies.org_name', 'org_companies.listed_company_ox')
                                                    ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                                                                and std.login_id = '$login_id'
                                                                and org_matchings.matching_date = $date
                                                                and (std.login_id, 0) not in (SELECT student_login_id, count(case when acceptance_ox = 'o' then 1 end) as no_acceptance
                                                                                            FROM applies
                                                                                            group by student_login_id)
                                                                and (applies.student_acceptance_ox != 'o' or applies.professor_acceptance_ox != 'o')")
                                                    ->get();
        
        }
        //미내정학생
        $std_null_list = DB::table('applies')
            ->distinct()
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->join('employment_infos as ei', 'ei.employment_id', '=', 'applies.employment_id')
            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'org_matching_foreign')
            ->select('std.login_id', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.profile_photo_url')
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                        and org_matchings.matching_date = $date
                        and (std.login_id, 0) in (SELECT student_login_id, count(case when acceptance_ox = 'o' then 1 end) as no_acceptance
                                                    FROM applies
                                                    group by student_login_id)")
            ->get();
        
        
        foreach($std_null_list as $key => $std_null_lists){
            $std_id = $std_null_lists->login_id;


            //학생당 진행중인 회사
            $progress_company = DB::table('applies')
            ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
            ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id", "org_companies.listed_company_ox")
            ->whereRaw("applies.student_login_id = '$std_id'
                            and (applies.acceptance_ox = '' or applies.acceptance_ox is null)
                            and applies.apply_permission_ox = 'o'")
            ->get();

            //학생당 진행중인 채용중 면접 차수
            foreach($progress_company as $key_sub => $progress_companies){
                    $employ_id = $progress_company[$key_sub]->employment_id;

                    $interview_count = DB::table('interview_schedules')
                    ->join('applies', 'interview_schedules.employment_id', '=', 'applies.employment_id')
                    ->select('interview_schedules.interview_count')
                    ->whereRaw("interview_schedules.employment_id = $employ_id
                                    and applies.student_login_id = '$std_id'
                                    and interview_schedules.interview_active = 'o' ")
                    ->get();
                    foreach($interview_count as $interview_counts){
                        $progress_company[$key_sub]->interview_count = null;
                        $progress_company[$key_sub]->interview_count = $interview_counts->interview_count;
                        break;
                    }
            }

            $std_null_list[$key]->std_progress_company = $progress_company;


            //학생당 불합격 회사
            $fail_company = DB::table('applies')
            ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
            ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id", "org_companies.listed_company_ox")
            ->whereRaw("applies.student_login_id = '$std_id'
                            and applies.acceptance_ox = 'x'
                            and applies.apply_permission_ox = 'o'")
            ->get();
            
            //학생당 진행중인 떨어진 학생의 면접 차수
            foreach($fail_company as $key_sub => $fail_companys){
                    $employ_id = $fail_company[$key_sub]->employment_id;
                    

                    $interview_count = DB::table('interview_schedules')
                    ->join('applies', 'interview_schedules.employment_id', '=', 'applies.employment_id')
                    ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                    ->select('interview_schedules.interview_count')
                    ->whereRaw("interview_schedules.employment_id = $employ_id
                                    and applies.student_login_id = '$std_id'
                                    and interview_result = 'x'")
                    ->get();
                    foreach($interview_count as $interview_counts){
                        $fail_company[$key_sub]->interview_count = null;
                        $fail_company[$key_sub]->interview_count = $interview_counts->interview_count;
                        break;
                    }
                    
            }

            $std_null_list[$key]->std_fail_company = $fail_company;

        }
        
        
        
        
        
        return json_encode(array("std_all_count" => sizeOf($std_all_list), "std_fix_list" => $std_fix_list, "std_be_list" => $std_be_list, "std_null_list" => sizeOf($std_null_list), "std_null_list" => $std_null_list));
    }
    
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
                    and (interview_check_ox = 'o' or interview_check_ox = '' or interview_check_ox is null)
                    and interview_date > date_format(now(), '%Y-%m-%d')")
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
    public function mainChart(Request $request){
        $professorId = $request->get('professorId');
        
        $listedCompanyCount = DB::table('applies as ap')
                            ->join('employment_infos as ei', 'ei.employment_id', '=', 'ap.employment_id')
                            ->join('org_matchings as om', 'om.org_matchings_id', '=', 'ei.org_matching_foreign')
                            ->join('org_companies as oc', 'oc.org_company_id', '=', 'om.org_company_id')
                            ->whereRaw("ap.student_login_id in ( select  std.login_id
                    									from students as std
                    									join professors as pro on pro.login_id = std.professor_login_id
                    									join faculties as fac on fac.faculty_id = pro.faculty_id
                    									where fac.faculty_id in (select faculty_id from professors where login_id = '$professorId'))
                    		            and	ap.acceptance_ox = 'o'")
                            ->select(
                                DB::raw("oc.listed_company_ox, 
                                count(case when oc.listed_company_ox = 'o' then 1 when (oc.listed_company_ox is null or oc.listed_company_ox = '' or oc.listed_company_ox = 'x') then 2 end) as listed_company_ox_count"))
                            ->groupBy("oc.listed_company_ox")
                            ->get();
        $temp_null = 0;
        $temp_x = 0;
        $temp_o = 0;
        foreach($listedCompanyCount as $listedCompanyCounts){
            if($listedCompanyCounts->listed_company_ox == null){
                $temp_null = $listedCompanyCounts->listed_company_ox_count;
            }
            if($listedCompanyCounts->listed_company_ox == 'x'){
                $temp_x = $listedCompanyCounts->listed_company_ox_count;
            }
            if($listedCompanyCounts->listed_company_ox == 'o'){
                $temp_o = $listedCompanyCounts->listed_company_ox_count;
            }
        }
        return json_encode(array("listed_company_o" => $temp_o, "listed_company_x" => ($temp_x + $temp_null)));
        
        /*
        select ap.*, oc.*
        from applies as ap
        join employment_infos as ei on ei.employment_id = ap.employment_id
        join org_matchings as om on om.org_matchings_id = ei.org_matching_foreign
        join org_companies as oc on oc.org_company_id = om.org_company_id
        where ap.student_login_id in ( select  std.login_id
        									from students as std
        									join professors as pro on pro.login_id = std.professor_login_id
        									join faculties as fac on fac.faculty_id = pro.faculty_id
        									where fac.faculty_id in (select faculty_id from professors where login_id = 'pr01'))
        		and	ap.acceptance_ox = 'o'
        */
    }
    /*
    public function mainChart(Request $request){
        $professor_id = $request->get('professorId');

        //면접 진행
        $interview_progress = DB::table('students as std')
        ->join('applies', 'std.login_id', '=', 'applies.student_login_id')
        ->join('professors', 'std.professor_login_id', '=', 'professors.login_id')
        ->select(DB::raw("count(distinct std.login_id) as interview_progress"))
        ->whereRaw("professors.faculty_id in (select professors.faculty_id
                                                from professors
                                                where professors.login_id = '$professor_id')
                    and std.login_id not in (select student_login_id
                    from applies
                    where acceptance_ox = 'o')")
        ->get();

        //미진행
        $interview_not = DB::table('students as std')
        ->join('applies', 'std.login_id', '=', 'applies.student_login_id')
        ->join('professors', 'std.professor_login_id', '=', 'professors.login_id')
        ->join('interview_results', 'std.login_id', '=', 'interview_results.student_login_id')
        ->select(DB::raw("count(distinct std.login_id) as interview_not"))
        ->whereRaw("professors.faculty_id in (select professors.faculty_id
                                                from professors
                                                where professors.login_id = '$professor_id')
                    and std.login_id not in (select student_login_id
                                                from applies
                                                where acceptance_ox = 'o')
                    and interview_results.interview_result = 'x'")
        ->get();


        //내정
        $acceptance = DB::table('applies')
        ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
        ->join('professors', 'professors.login_id', '=', 'std.professor_login_id')
        ->select(DB::raw("count(distinct std.login_id) as acceptance"))
        ->whereRaw("professors.faculty_id in (select professors.faculty_id
                                                from professors
                                                where professors.login_id = '$professor_id')
                    and applies.acceptance_ox = 'o'
                    and std.employ_ox = 'o'")
        ->get();

        //취활종료 학생
        $employ_end = DB::table('applies')
        ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
        ->join('professors', 'professors.login_id', '=', 'std.professor_login_id')
        ->select(DB::raw("count(distinct std.login_id) as employ_end"))
        ->whereRaw("professors.faculty_id in (select professors.faculty_id
                                                from professors
                                                where professors.login_id = '$professor_id')
                    and applies.student_acceptance_ox = 'o'
                    and applies.professor_acceptance_ox = 'o'
                    and std.employ_ox = 'x'")
        ->get();

        return [$interview_progress[0]->interview_progress, $interview_not[0]->interview_not, $acceptance[0]->acceptance, $employ_end[0]->employ_end];
    }

    public function interview_schedule(Request $request){
        
        $professor_id = $request->get('professorId');

        $interviewDate = DB::table('interview_schedules')
        ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
        ->join('faculties', 'org_matchings.org_college_id', '=', 'faculties.org_college_id')
        ->select('org_companies.org_name' ,'interview_schedules.interview_date', 'interview_schedules.interview_place')
        ->whereRaw("faculties.faculty_id in (select professors.faculty_id
                                                from professors
                                                where professors.login_id = '$professor_id')")
        ->get();
        
        $companyId = DB::table('interview_schedules')
        ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
        ->join('faculties', 'org_matchings.org_college_id', '=', 'faculties.org_college_id')
        ->select('org_companies.org_company_id')
        ->whereRaw("faculties.faculty_id in (select professors.faculty_id
                                                from professors
                                                where professors.login_id = '$professor_id')")
        ->get();

        return ['date' => $interviewDate, 'employment_id' => $companyId];
    }

    public function interviewStudentData(Request $request){
        $professor_id = $request->get('professorId');
        $employment_id = $request->get('employment_id');

        
    }
    */
}

