<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Professor_StatusManagement extends Controller
{
    public function status_info(Request $request){
        $professorId = $request->get('professorId');
        $date = date("Y");
        $type = $request->get('type');

        $std_list = array();

        //지원 학생
        if($type == "total"){//전체
            $std_list = DB::table('applies')
            ->distinct()
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->join('employment_infos as ei', 'ei.employment_id', '=', 'applies.employment_id')
            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'org_matching_foreign')
            ->select('std.login_id', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.profile_photo_url')
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                        and org_matchings.matching_date = $date")
            ->get();
        }else if($type == "fix"){//내정확정 학생
            $std_list = DB::table('applies')
            ->distinct()
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->join('employment_infos as ei', 'ei.employment_id', '=', 'applies.employment_id')
            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'org_matching_foreign')
            ->select('std.login_id', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.profile_photo_url')
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                        and org_matchings.matching_date = $date
                        and applies.student_acceptance_ox = 'o'
                        and applies.professor_acceptance_ox = 'o'")
            ->get();
        }else if($type == "null"){//내정받은 기업이 없는 학생
            $std_list = DB::table('applies')
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
        }else if($type == "be"){//1사이상 내정받은 학생
            $std_list = DB::table('applies')
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
        }
        
        

        foreach($std_list as $key => $std_lists){
            $std_id = $std_lists->login_id;
            
            //각 학생별 지원한 회사의 숫자
            $apply_count = DB::table('applies')
            ->select(DB::raw("count(student_login_id) as apply_count"))
            ->whereRaw("apply_permission_ox = 'o'
		                and student_login_id = '$std_id'")
            ->get();

            foreach($apply_count as $apply_counts){
                $std_list["$key"]->apply_count = $apply_counts->apply_count;
                break;
            }

            //각 학생별 지원한 회사의 정보
            $std_list["$key"]->apply_company_info = DB::table('applies')
            ->join("employment_infos as emp", 'emp.employment_id', '=', "applies.employment_id")
            ->join("org_matchings as mat", 'mat.org_matchings_id', '=', "emp.org_matching_foreign")
            ->join("org_companies as org_com", 'org_com.org_company_id', '=', "mat.org_company_id")
            ->select('emp.employment_id as employment_id', 'org_com.org_company_id', 'org_com.org_name', 'org_com.org_name_kana')
            ->where('applies.student_login_id', '=', "$std_id")
            ->get();

            
            foreach($std_list["$key"]->apply_company_info as $sub_key => $employ_info){

                $employ_id = $employ_info->employment_id;

                $std_list["$key"]->apply_company_info["$sub_key"]->employment_info = DB::table('work_matchings')
                ->join('work_infos', 'work_matchings.work_id', '=', 'work_infos.id')
                ->where("employment_id", '=', $employ_id)
                ->select('content')
                ->get();

            }

            //각 학생별 불합격 회사의 숫자
            $fail_count = DB::table('applies')
            ->select(DB::raw("count(student_login_id) as fail_count"))
            ->whereRaw("apply_permission_ox = 'o'
                        and acceptance_ox = 'x'
		                and student_login_id = '$std_id'")
            ->get();

            foreach($fail_count as $fail_counts){
                $std_list["$key"]->fail_count = $fail_counts->fail_count;
                break;
            }
            
            //각 학생별 불합격 회사의 정보
            
            $std_list["$key"]->fail_company_info = DB::table('applies')
            ->join("employment_infos as emp", 'emp.employment_id', '=', "applies.employment_id")
            ->join("org_matchings as mat", 'mat.org_matchings_id', '=', "emp.org_matching_foreign")
            ->join("org_companies as org_com", 'org_com.org_company_id', '=', "mat.org_company_id")
            ->select('emp.employment_id as employment_id', 'org_com.org_company_id', 'org_com.org_name', 'org_com.org_name_kana',  'org_com.photo_url')
            ->whereRaw("applies.apply_permission_ox = 'o'
                        and applies.student_login_id = '$std_id'
                        and acceptance_ox = 'x'")
            ->get();
            
            foreach($std_list["$key"]->fail_company_info as $sub_key => $fail_interview_count){

                $employ_id = $fail_interview_count->employment_id;

                $interview_count = DB::table('interview_schedules')
                ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                ->join('applies', 'applies.employment_id', '=', 'interview_schedules.employment_id')
                ->whereRaw("interview_results.student_login_id = '$std_id' 
                            and interview_schedules.employment_id = '$employ_id' 
                            and applies.acceptance_ox = 'x' 
                            and apply_permission_ox = 'o'")
                ->select('interview_count')
                ->get();

                
                foreach($interview_count as $interview_counts){
                    $std_list["$key"]->fail_company_info["$sub_key"]->fail_interview_count = $interview_counts->interview_count;
                    break;
                }

            }

            //각 학생별 진행중 회사의 숫자
            $proceed_count = DB::table('applies')
            ->select(DB::raw("count(student_login_id) as proceed_count"))
            ->whereRaw("apply_permission_ox = 'o'
                        and (acceptance_ox = '' or acceptance_ox is null)
		                and student_login_id = '$std_id'")
            ->get();

            foreach($proceed_count as $proceed_counts){
                $std_list["$key"]->proceed_count = $proceed_counts->proceed_count;
                break;
            }
            
            //각 학생별 진행중 회사의 정보
            $std_list["$key"]->proceed_company_info =DB::table('applies')
            ->join("employment_infos as emp", 'emp.employment_id', '=', "applies.employment_id")
            ->join("org_matchings as mat", 'mat.org_matchings_id', '=', "emp.org_matching_foreign")
            ->join("org_companies as org_com", 'org_com.org_company_id', '=', "mat.org_company_id")
            ->select('emp.employment_id as employment_id', 'org_com.org_company_id', 'org_com.org_name', 'org_com.org_name_kana', 'org_com.photo_url')
            ->whereRaw("applies.apply_permission_ox = 'o'
                        and applies.student_login_id = '$std_id'
                        and (acceptance_ox = '' or acceptance_ox is null)")
            ->get();

            foreach($std_list["$key"]->proceed_company_info as $sub_key => $proceed_interview_count){

                $employ_id = $proceed_interview_count->employment_id;

                $interview_count = DB::table('interview_schedules')
                ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                ->join('applies', 'applies.employment_id', '=', 'interview_schedules.employment_id')
                ->whereRaw("interview_results.student_login_id = '$std_id' 
                            and interview_schedules.employment_id = '$employ_id' 
                            and (applies.acceptance_ox = '' or applies.acceptance_ox is null) 
                            and apply_permission_ox = 'o'")
                ->select('interview_count')
                ->get();

                
                foreach($interview_count as $interview_counts){
                    $std_list["$key"]->proceed_company_info["$sub_key"]->proceed_interview_count = $interview_counts->interview_count;
                    break;
                }

            }

            //각 학생별 합격 회사의 숫자
            $pass_count = DB::table('applies')
            ->select(DB::raw("count(student_login_id) as pass_count"))
            ->whereRaw("applies.apply_permission_ox = 'o'
                        and applies.student_login_id = '$std_id'
                        and acceptance_ox = 'o'")
            ->get();

            foreach($pass_count as $pass_counts){
                $std_list["$key"]->pass_count = $pass_counts->pass_count;
                break;
            }
            
            //각 학생별 합격 회사의 정보 (합격회사, 사퇴회사, 대기회사)
            $std_list["$key"]->pass_company_info["pass_all"] = DB::table('applies')//합격 
            ->join("employment_infos as emp", 'emp.employment_id', '=', "applies.employment_id")
            ->join("org_matchings as mat", 'mat.org_matchings_id', '=', "emp.org_matching_foreign")
            ->join("org_companies as org_com", 'org_com.org_company_id', '=', "mat.org_company_id")
            ->select('emp.employment_id as employment_id', 'org_com.org_company_id', 'org_com.org_name', 'org_com.org_name_kana', 'org_com.photo_url', 'resign_comment', 'resign_id')
            ->whereRaw("applies.apply_permission_ox = 'o'
                        and applies.student_login_id = '$std_id'
                        and acceptance_ox = 'o'
                        and (student_acceptance_ox = 'o' or student_acceptance_ox = 'x')
                        and (professor_acceptance_ox is null or professor_acceptance_ox = '')")
            ->get();
            
            foreach($std_list["$key"]->pass_company_info["pass_all"] as $sub_key => $resign){
                $resign_id = $resign->resign_id;
                $std_list["$key"]->pass_company_info["pass_all"][$sub_key]->content = DB::table('resign_infos')
                ->select('content')
                ->where('id', '=', $resign_id)
                ->get();

                break;
            }
            
            $std_list["$key"]->pass_company_info["pass_resign"] = DB::table('applies')//합격했지만 사퇴한거
            ->join("employment_infos as emp", 'emp.employment_id', '=', "applies.employment_id")
            ->join("org_matchings as mat", 'mat.org_matchings_id', '=', "emp.org_matching_foreign")
            ->join("org_companies as org_com", 'org_com.org_company_id', '=', "mat.org_company_id")
            ->select('emp.employment_id as employment_id', 'org_com.org_company_id', 'org_com.org_name', 'org_com.org_name_kana', 'org_com.photo_url', 'resign_comment', 'resign_id')
            ->whereRaw("applies.apply_permission_ox = 'o'
                        and applies.student_login_id = '$std_id'
                        and acceptance_ox = 'o'
                        and student_acceptance_ox = 'x'
                        and professor_acceptance_ox = 'o'")
            ->get();

            foreach($std_list["$key"]->pass_company_info["pass_resign"] as $sub_key => $resign){
                $resign_id = $resign->resign_id;
                $std_list["$key"]->pass_company_info["pass_resign"][$sub_key]->content = DB::table('resign_infos')
                ->select('content')
                ->where('id', '=', $resign_id)
                ->get();

                break;
            }

            $std_list["$key"]->pass_company_info["pass_standby"] = DB::table('applies')//합격했지만 아무런 대답도 없는것
            ->join("employment_infos as emp", 'emp.employment_id', '=', "applies.employment_id")
            ->join("org_matchings as mat", 'mat.org_matchings_id', '=', "emp.org_matching_foreign")
            ->join("org_companies as org_com", 'org_com.org_company_id', '=', "mat.org_company_id")
            ->select('emp.employment_id as employment_id', 'org_com.org_company_id', 'org_com.org_name', 'org_com.org_name_kana', 'org_com.photo_url')
            ->whereRaw("applies.apply_permission_ox = 'o'
                        and applies.student_login_id = '$std_id'
                        and acceptance_ox = 'o'
                        and (student_acceptance_ox is null or student_acceptance_ox = '')
                        and (professor_acceptance_ox is null or professor_acceptance_ox = '')")
            ->get();

            //-----------------------------------------------------
            //각 학생별 내정 확정 회사의 정보
            $std_list["$key"]->be_nominated_company_info = DB::table('applies')
            ->join("employment_infos as emp", 'emp.employment_id', '=', "applies.employment_id")
            ->join("org_matchings as mat", 'mat.org_matchings_id', '=', "emp.org_matching_foreign")
            ->join("org_companies as org_com", 'org_com.org_company_id', '=', "mat.org_company_id")
            ->select('emp.employment_id as employment_id', 'org_com.org_company_id', 'org_com.org_name', 'org_com.org_name_kana', 'org_com.photo_url')
            ->whereRaw("applies.apply_permission_ox = 'o'
                        and applies.student_login_id = '$std_id'
                        and acceptance_ox = 'o'
                        and student_acceptance_ox = 'o'
                        and professor_acceptance_ox = 'o'")
            ->get();

        }
        
        return $std_list;
        
        
        /*
        //지원 학생
        $apply_std_id = DB::table('applies')
        //->distinct()
        ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
        ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
        ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
        ->whereRaw("pro.faculty_id in (select faculty_id
                                            from professors as pro
                                            where pro.login_id = '$professorId')
                    and org_matchings.matching_date = '$date'")
        ->select('student_login_id', 'std.name as name', 'std.name_eng as name_eng', 'std.name_kanji as name_kanji')
        ->get();


        
        $apply_std_list = array();

        foreach($apply_std_id as $apply_std_ids){
            $std_id = $apply_std_ids->student_login_id;
            $std_name = $apply_std_ids->name;
            $std_name_eng = $apply_std_ids->name_eng;
            $std_name_kanji = $apply_std_ids->name_kanji;

            $apply_info = array();
            $name = array();
            array_push($name, $std_id);
            array_push($name, $std_name);
            array_push($name, $std_name_eng);
            array_push($name, $std_name_kanji);

            array_push($apply_info, array('name' => $name));

            //합격 학생의 회사 목록
            $pass_std_company = DB::table('applies')
            ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->join('resign_infos', 'resign_infos.id', '=', 'applies.resign_id')
            ->whereRaw("acceptance_ox = 'o' and 
                            (applies.apply_id, employment_infos.employment_id) in (select applies.apply_id, applies.employment_id
                                                                                    from applies
                                                                                    join students as std on applies.student_login_id = std.login_id
                                                                                    join professors as pro on  std.professor_login_id = pro.login_id
                                                                                    where pro.faculty_id in (select faculty_id
                                                                                                                from professors
                                                                                                                where professors.login_id = '$professorId'))
                            and org_matchings.matching_date = '$date'
                            and applies.student_login_id = '$std_id'")
            ->select('applies.employment_id', 'org_companies.org_company_id', 'org_companies.org_name', 'org_companies.org_name_kana', 'applies.student_acceptance_ox', 'resign_infos.content as resign_infos', 'applies.resign_comment as resign_comment')
            ->get();

            array_push($apply_info, array('0' => $pass_std_company));

            //대기중학생의 회사 목록
            $stand_std_company = DB::table('applies')
            ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->whereRaw("student_login_id = '$std_id' and apply_permission_ox = 'o' and (acceptance_ox = '' or acceptance_ox is null)")
            ->select('employment_infos.employment_id as employment_id', 'org_companies.org_company_id', 'org_companies.org_name', 'org_companies.org_name_kana')
            ->get();

            foreach($stand_std_company as $key => $stand_std_companies){
                $employ_id = $stand_std_companies->employment_id;

                $interview_count = DB::table('interview_schedules')
                ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                ->join('applies', 'applies.employment_id', '=', 'interview_schedules.employment_id')
                ->whereRaw("applies.student_login_id = '$std_id' and interview_results.student_login_id = '$std_id' 
                and interview_schedules.employment_id = '$employ_id' and (applies.acceptance_ox = '' or applies.acceptance_ox is null) and apply_permission_ox = 'o'")
                ->select('interview_count')
                ->get();

                $stand_std_company->interview_count = $interview_count;
                
            }

            array_push($apply_info, array('1' => $stand_std_company));

            //불합격 학생의 회사 목록
            $fail_std_company = DB::table('interview_results')
            ->join('interview_schedules', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
            ->join('employment_infos', 'employment_infos.employment_id', '=', 'interview_schedules.employment_id')
            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->where([
                ['student_login_id', '=', "$std_id"],
                ['interview_result', '=', 'x']
                ])
            ->select('interview_schedules.employment_id', 'org_companies.org_company_id', 'org_companies.org_name', 'org_companies.org_name_kana')
            ->get();

            foreach($fail_std_company as $sub_key => $fail_std_companies){
                $employ_id = $fail_std_companies->employment_id;

                $fail_std_interview_count = DB::table('interview_schedules')
                ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                ->join('students as std', 'interview_results.student_login_id', '=', 'std.login_id')
                ->select('std.login_id', 'interview_schedules.interview_count', 'interview_results.interview_result')
                ->whereRaw("interview_schedules.employment_id = $employ_id
                            and std.login_id = '$std_id'
                            and interview_result = 'x'")
                ->get();
                foreach($fail_std_interview_count as $fail_std_interview_counts){
                    $fail_std_company[$sub_key]->interview_count = $fail_std_interview_counts->interview_count;
                }
            }

            array_push($apply_info, array('2' => $fail_std_company));

            //내정 수락 학생의 회사 목록
            $fix_std_company = DB::table('applies')
            ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->whereRaw("acceptance_ox = 'o' and student_acceptance_ox = 'o' and professor_acceptance_ox = 'o' and
                            (applies.apply_id, employment_infos.employment_id) in (select applies.apply_id, applies.employment_id
                                                                                    from applies
                                                                                    join students as std on applies.student_login_id = std.login_id
                                                                                    join professors as pro on  std.professor_login_id = pro.login_id
                                                                                    where pro.faculty_id in (select faculty_id
                                                                                                                from professors
                                                                                                                where professors.login_id = '$professorId'))
                            and org_matchings.matching_date = '$date'
                            and applies.student_login_id = '$std_id'")
            ->select('org_companies.org_company_id', 'org_companies.org_name', 'org_companies.org_name_kana')
            ->get();

            array_push($apply_info, $fix_std_company);



            array_push($apply_std_list, $apply_info);
        }

        echo json_encode($apply_std_list);
        */
    }

    public function apply_progress(Request $request){
        $professorId = $request->get('professorId');

        /*내정관리 회사별 지원자 수*/
        $company_apply_result_count = DB::table('org_matchings')
        ->join('faculties', 'org_matchings.org_college_id', '=', 'faculties.org_college_id')
        ->join('employment_infos', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
        ->select('org_companies.org_company_id',
                'org_companies.org_name',
                DB::raw("count(distinct org_companies.org_company_id, org_companies.org_name, applies.student_login_id ) as apply"))
        ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')")
        ->groupBy('org_companies.org_company_id')
        ->get();

        foreach($company_apply_result_count as $key => $company_apply_result_counts){
            $org_company_id = $company_apply_result_counts->org_company_id;

            /*내정 관리 면접 가능자 수*/
            $pass = DB::table('org_matchings')
            ->join('faculties', 'org_matchings.org_college_id', '=', 'faculties.org_college_id')
            ->join('employment_infos', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->select(DB::raw("count(distinct org_companies.org_company_id, org_companies.org_name, applies.student_login_id) as pass"))
            ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')
                        and applies.apply_permission_ox = 'o'
                        and org_companies.org_company_id = '$org_company_id'")
            ->get();

            foreach($pass as $passes){
                $company_apply_result_count[$key]->pass = $passes->pass;
                break;
            }

            /*내정 관리 면접 불가능자 수*/
            $fail = DB::table('org_matchings')
            ->join('faculties', 'org_matchings.org_college_id', '=', 'faculties.org_college_id')
            ->join('employment_infos', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->select(DB::raw("count(distinct org_companies.org_company_id, org_companies.org_name, applies.student_login_id) as fail"))
            ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')
                        and applies.apply_permission_ox = 'x'
                        and org_companies.org_company_id = '$org_company_id'")
            ->get();

            foreach($fail as $fails){
                $company_apply_result_count[$key]->fail = $fails->fail;
                break;
            }
            
            /*내정 관리 면접 수락 대기자 수*/
            $stand = DB::table('org_matchings')
            ->join('faculties', 'org_matchings.org_college_id', '=', 'faculties.org_college_id')
            ->join('employment_infos', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->select(DB::raw("count(distinct org_companies.org_company_id, org_companies.org_name, applies.student_login_id) as stand"))
            ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')
                        and (applies.apply_permission_ox = '' or applies.apply_permission_ox is null)
                        and org_companies.org_company_id = '$org_company_id'")
            ->get();

            foreach($stand as $stands){
                $company_apply_result_count[$key]->stand = $stands->stand;
                break;
            }

            /*내정관리 회사별 내정자 수*/
            $acceptance_o_count = DB::table('org_matchings')
            ->join('faculties', 'org_matchings.org_college_id', '=', 'faculties.org_college_id')
            ->join('employment_infos', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->select(DB::raw("count(distinct org_companies.org_company_id, org_companies.org_name, applies.student_login_id) as acceptance_o_count"))
            ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')
                        and applies.apply_permission_ox = 'o'
                        and applies.acceptance_ox = 'o'
                        and org_companies.org_company_id = '$org_company_id'")
            ->get();

            foreach($acceptance_o_count as $acceptance_o_counts){
                $company_apply_result_count[$key]->acceptance_o_count = $acceptance_o_counts->acceptance_o_count;
                break;
            }

            /*내정관리 회사별 내정 탈락자 수*/
            $acceptance_x_count = DB::table('org_matchings')
            ->join('faculties', 'org_matchings.org_college_id', '=', 'faculties.org_college_id')
            ->join('employment_infos', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->select(DB::raw("count(distinct org_companies.org_company_id, org_companies.org_name, applies.student_login_id) as acceptance_x_count"))
            ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')
                        and applies.apply_permission_ox = 'o'
                        and applies.acceptance_ox = 'x'
                        and org_companies.org_company_id = '$org_company_id'")
            ->get();

            foreach($acceptance_x_count as $acceptance_x_counts){
                $company_apply_result_count[$key]->acceptance_x_count = $acceptance_x_counts->acceptance_x_count;
                break;
            }

            /*내정관리 회사별 내정 진행중 수*/
            $acceptance_stand_count = DB::table('org_matchings')
            ->join('faculties', 'org_matchings.org_college_id', '=', 'faculties.org_college_id')
            ->join('employment_infos', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->select(DB::raw("count(distinct org_companies.org_company_id, org_companies.org_name, applies.student_login_id) as acceptance_stand_count"))
            ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')
                        and applies.apply_permission_ox = 'o'
                        and (applies.acceptance_ox = '' or applies.acceptance_ox is null)
                        and org_companies.org_company_id = '$org_company_id'")
            ->get();

            foreach($acceptance_stand_count as $acceptance_stand_counts){
                $company_apply_result_count[$key]->acceptance_stand_count = $acceptance_stand_counts->acceptance_stand_count;
                break;
            }

            /*내정관리 회사별 내정 확정자 수*/
            $acceptance_fixed_count = DB::table('org_matchings')
            ->join('faculties', 'org_matchings.org_college_id', '=', 'faculties.org_college_id')
            ->join('employment_infos', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->select(DB::raw("count(distinct org_companies.org_company_id, org_companies.org_name, applies.student_login_id) as acceptance_fixed_count"))
            ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')
                        and applies.apply_permission_ox = 'o'
                        and applies.acceptance_ox = 'o'
                        and applies.student_acceptance_ox = 'o'
                        and applies.professor_acceptance_ox = 'o'
                        and org_companies.org_company_id = '$org_company_id'")
            ->get();

            foreach($acceptance_fixed_count as $acceptance_fixed_counts){
                $company_apply_result_count[$key]->acceptance_fixed_count = $acceptance_fixed_counts->acceptance_fixed_count;
                break;
            }
        }

        return json_encode($company_apply_result_count);
        /*
        //채용id, 회사 이름, 지원 숫자
        $company_apply_count = DB::table('applies')
        ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
        ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')")
        ->select('applies.employment_id', 'org_companies.org_name', 'org_companies.org_name_kana', DB::raw("count(applies.student_login_id) as apply"))
        ->groupBy('applies.employment_id')
        ->get();
        //채용id, 합격 학생 수
        $company_pass_count = DB::table('applies')
        ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
        ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')")
        ->select('applies.employment_id', DB::raw("count(case when acceptance_ox = 'o' then 1 end) as pass"))
        ->groupBy('applies.employment_id')
        ->get();
        //채용id, 불합격 학생 수
        $company_fail_count = DB::table('applies')
        ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
        ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')")
        ->select('applies.employment_id', DB::raw("count(case when acceptance_ox = 'x' then 1 end) as fail"))
        ->groupBy('applies.employment_id')
        ->get();
        //채용id, 면접 진행 학생 수
        $company_progress_count = DB::table('applies')
        ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
        ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')")
        ->select('applies.employment_id', DB::raw("count(case when acceptance_ox = '' or acceptance_ox is null then 1 end) as progress"))
        ->groupBy('applies.employment_id')
        ->get();

        foreach($company_apply_count as $key => $company_apply_counts){
            foreach($company_pass_count as $company_pass_counts){
                if($company_apply_count[$key]->employment_id == $company_pass_counts->employment_id){
                    $company_apply_count[$key]->pass = $company_pass_counts->pass;
                    break;
                }
            }

            foreach($company_fail_count as $company_fail_counts){
                if($company_apply_count[$key]->employment_id == $company_fail_counts->employment_id){
                    $company_apply_count[$key]->fail = $company_fail_counts->fail;
                    break;
                }
            }

            foreach($company_progress_count as $company_progress_counts){
                if($company_apply_count[$key]->employment_id == $company_progress_counts->employment_id){
                    $company_apply_count[$key]->progress = $company_progress_counts->progress;
                    break;
                }
            }
        }

        //회사 총 내정 수
        $acceptance_count = DB::table('applies')
        ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
        ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')")
        ->select("applies.employment_id", 'org_companies.org_name', 'org_companies.org_name_kana',  DB::raw("count(case when acceptance_ox = 'o' then 1 end) as acceptance_count"))
        ->groupBy('applies.employment_id')
        ->get();

        //회사 총 내정에 대한 수락 수
        $acceptance_o_count = DB::table('applies')
        ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
        ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')")
        ->select("applies.employment_id", DB::raw("count(case when student_acceptance_ox = 'o' and professor_acceptance_ox = 'o' then 1 end) as acceptance_o_count"))
        ->groupBy('applies.employment_id')
        ->get();

        //회사 총 내정에 대한 수락 거부 수
        $acceptance_x_count = DB::table('applies')
        ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
        ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')")
        ->select("applies.employment_id", DB::raw("count(case when student_acceptance_ox = 'x' and professor_acceptance_ox = 'x' then 1 end) as acceptance_x_count"))
        ->groupBy('applies.employment_id')
        ->get();

        //회사 총 내정에 대한 대기 수
        $acceptance_stand_count = DB::table('applies')
        ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
        ->whereRaw("faculties.faculty_id in (select faculty_id from professors where login_id = '$professorId')")
        ->select("applies.employment_id", DB::raw("count(case when (student_acceptance_ox = '' or student_acceptance_ox is null) and (professor_acceptance_ox = '' or professor_acceptance_ox is null) then 1 end) as acceptance_stand_count"))
        ->groupBy('applies.employment_id')
        ->get();
        
        foreach($acceptance_count as $key => $acceptance_counts){

            foreach($acceptance_o_count as $acceptance_o_counts){
                if($acceptance_count[$key]->employment_id == $acceptance_o_counts->employment_id){
                    $acceptance_count[$key]->acceptance_o_count = $acceptance_o_counts->acceptance_o_count;
                    break;
                }
            }

            foreach($acceptance_x_count as $acceptance_x_counts){
                if($acceptance_count[$key]->employment_id == $acceptance_x_counts->employment_id){
                    $acceptance_count[$key]->acceptance_x_count = $acceptance_x_counts->acceptance_x_count;
                    break;
                }
            }

            foreach($acceptance_stand_count as $acceptance_stand_counts){
                if($acceptance_count[$key]->employment_id == $acceptance_stand_counts->employment_id){
                    $acceptance_count[$key]->acceptance_stand_count = $acceptance_stand_counts->acceptance_stand_count;
                    break;
                }
            }
        
        }

        echo json_encode(['one' => $company_apply_count, 'two' => $acceptance_count]);
*/
    }
    public function interview_result_search(Request $request){
        $professorId = $request->get('professorId');
        $type = $request->get('type');

        $year = date("Y");
        if($type == "total"){//채용하는 회사 전체 리스트
            //이 학교에 채용을 하러 오는 기업 정보
            $company_list = DB::table('employment_infos')
            ->distinct()
            ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_companY_id')
            ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
            ->join('japan_prefectures', 'org_companies.address_to_dou_hu_ken', '=', 'japan_prefectures.id')
            ->join('japan_regions', 'japan_regions.id', '=', 'japan_prefectures.id')
            ->whereRaw("faculties.faculty_id in (select faculty_id from professors where professors.login_id = '$professorId')
                        and org_matchings.employment_decision_ox = 'o'
                        and org_matchings.matching_date = '$year'")
            ->select(   'org_companies.org_company_id',
                        'org_matchings.org_matchings_id',
                        'org_companies.org_name',
                        'org_companies.org_name_kana',
                        'org_companies.worker_count',
                        'org_companies.address',
                        'org_companies.photo_url',
                        'japan_regions.name_kanji as region',
                        'japan_prefectures.name_kanji as to_dou_hu_ken')
            ->get();
            //현재 진행중인 차수랑, 그 차수의 합격 불합격 여부가 있는지
            
        }else if($type == "end"){//면접 종료된 회사
            $company_list = DB::table('employment_infos')
                        ->distinct()
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_companY_id')
                        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
                        ->join('japan_prefectures', 'org_companies.address_to_dou_hu_ken', '=', 'japan_prefectures.id')
                        ->join('japan_regions', 'japan_regions.id', '=', 'japan_prefectures.id')
                        ->whereRaw("faculties.faculty_id in (select faculty_id from professors where professors.login_id = '$professorId')
                                    and org_matchings.employment_decision_ox = 'o'
                                    and org_matchings.matching_date = '$year'
                                    and employment_infos.employment_owari_ox = 'o'")
                        ->select(   'org_companies.org_company_id',
                                    'org_matchings.org_matchings_id',
                                    'org_companies.org_name',
                                    'org_companies.org_name_kana',
                                    'org_companies.worker_count',
                                    'org_companies.address',
                                    'org_companies.photo_url',
                                    'japan_regions.name_kanji as region',
                                    'japan_prefectures.name_kanji as to_dou_hu_ken')
                        ->get();
        }else if($type == "progress"){//채용 진행중인 회사
            $company_list = DB::table('employment_infos')
            ->distinct()
            ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_companY_id')
            ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
            ->join('japan_prefectures', 'org_companies.address_to_dou_hu_ken', '=', 'japan_prefectures.id')
            ->join('japan_regions', 'japan_regions.id', '=', 'japan_prefectures.id')
            ->whereRaw("faculties.faculty_id in (select faculty_id from professors where professors.login_id = '$professorId')
                        and org_matchings.employment_decision_ox = 'o'
                        and org_matchings.matching_date = '$year'
                        and employment_infos.employment_id in (select distinct employment_id
                                                                    from interview_schedules
                                                                    where interview_active = 'o')")
            ->select(   'org_companies.org_company_id',
                        'org_matchings.org_matchings_id',
                        'org_companies.org_name',
                        'org_companies.org_name_kana',
                        'org_companies.worker_count',
                        'org_companies.address',
                        'org_companies.photo_url',
                        'japan_regions.name_kanji as region',
                        'japan_prefectures.name_kanji as to_dou_hu_ken')
            ->get();
        }
        
        foreach($company_list as $key => $company_lists){
            $matching_id = $company_lists->org_matchings_id;
            
            //한 회사가 가지고 있는 채용정보 ID
            $company_list[$key]->employment_id = DB::table('employment_infos')
            ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_companY_id')
            ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
            ->whereRaw("faculties.faculty_id in (select faculty_id from professors where professors.login_id = '$professorId')
                    and org_matchings.employment_decision_ox = 'o'
                    and org_matchings.matching_date = '$year'
                    and employment_infos.org_matching_foreign = '$matching_id'")
            ->select('employment_infos.employment_id')
            ->get();
           
            
            
            //채용을 하러 오는 기업의 내정 학생 수
            $company_pass = DB::table('employment_infos')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->select(DB::raw("count(distinct applies.student_login_id) as pass"))
            ->whereRaw("employment_infos.org_matching_foreign = '$matching_id'
                        and applies.apply_permission_ox = 'o'
                        and applies.acceptance_ox = 'o'")
            ->get();
            
            foreach($company_pass as $company_passes){
                $company_list[$key]->pass = $company_passes->pass;
            }
            
            //채용을 하러 오는 기업에 지원한 학생중 채용 진행중 학생 수
            $company_apply_progress = DB::table('employment_infos')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->join('interview_schedules', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
            ->whereRaw("employment_infos.org_matching_foreign = $matching_id
                        and applies.apply_permission_ox = 'o' 
                        and applies.acceptance_ox is null or applies.acceptance_ox = ''
                        and interview_schedules.interview_active = 'o'")
            ->select(DB::raw("count(distinct applies.student_login_id) as progress"))
            ->get();

            foreach($company_apply_progress as $company_apply_progresses){
                $company_list[$key]->progress = $company_apply_progresses->progress;
            }

            //채용을 하러 오는 기업에 지원한 학생중 떨어진 학생 수
            $company_apply_fail = DB::table('employment_infos')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->whereRaw("employment_infos.org_matching_foreign = $matching_id
                        and applies.acceptance_ox = 'x'")
            ->select(DB::raw("count(distinct applies.student_login_id) as fail"))
            ->get();

            foreach($company_apply_fail as $company_apply_fails){
                $company_list[$key]->fail = $company_apply_fails->fail;
            }
            

            //채용을 하러 오는 기업에 합격한 핵싱중 내정 수락자 수
            $company_apply_fixed = DB::table('employment_infos')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->whereRaw("employment_infos.org_matching_foreign = $matching_id
                        and applies.apply_permission_ox = 'o' 
                        and applies.acceptance_ox = 'o'
                        and applies.student_acceptance_ox = 'o'
                        and applies.professor_acceptance_ox = 'o'")
            ->select(DB::raw("count(distinct applies.student_login_id) as fixed"))
            ->get();

            foreach($company_apply_fixed as $company_apply_fixeds){
                $company_list[$key]->fixed = $company_apply_fixeds->fixed;
            }
            
            
            $company_employ = $company_lists->employment_id;
            
            //각 채용정보별 지원한 학생에 관련된 정보 출력
            foreach($company_list[$key]->employment_id as $subKey => $employment_ids){
                $emploies_id = $employment_ids->employment_id;
                
                //채용목록
                $company_work_info = DB::table('work_matchings')
                ->join('employment_infos', 'employment_infos.employment_id', '=', 'work_matchings.employment_id')
                ->join('work_infos', 'work_matchings.work_id', '=', 'work_infos.id')
                ->where('employment_infos.employment_id', '=', $emploies_id)
                ->select('work_infos.content')
                ->get();
                
                $company_list[$key]->employment_id[$subKey]->work_info = $company_work_info;
                
                //채용하러 온 기업에 지원한 학생의 이름과 프로필 사진
                $company_apply_std = DB::table("applies")
                ->join("employment_infos", "applies.employment_id", "=", "employment_infos.employment_id")
                ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng', 'std.profile_photo_url')
                ->whereRaw("employment_infos.employment_id = $emploies_id")
                ->get();
                
                $company_list[$key]->employment_id[$subKey]->apply_std = $company_apply_std;
                
                //채용하러 온 기업에 지원한 학생중 합격한 학생 이름과 프로필 사진
                $company_pass_std = DB::table("applies")
                ->join("employment_infos", "applies.employment_id", "=", "employment_infos.employment_id")
                ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng', 'std.profile_photo_url', 'std.login_id')
                ->whereRaw("employment_infos.employment_id = $emploies_id
                            and applies.apply_permission_ox = 'o'
                            and applies.acceptance_ox = 'o'")
                ->get();

                $company_list[$key]->employment_id[$subKey]->pass_std = $company_pass_std;

                //채용을 하러 오는 기업에 지원한 학생중 채용 진행중 학생 이름, 프로필 사진, 현재 면접 차수
                $company_progress_std = DB::table('applies')
                ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                ->join('interview_schedules', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
                ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng',
                            'std.profile_photo_url', 'interview_schedules.interview_count', 'std.login_id')
                ->whereRaw("employment_infos.employment_id = '$emploies_id'
                            and interview_schedules.interview_active = 'o'
                            and applies.apply_permission_ox = 'o'
                            and (applies.acceptance_ox = '' or applies.acceptance_ox is null)")
                ->get();

                $company_list[$key]->employment_id[$subKey]->progress_std = $company_progress_std;


                //채용을 하러 오는 기업에 지원한 학생중 채용에서 떨어진 학생 이름, 프로필 사진
                $company_fail_std = DB::table("applies")
                ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
                ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng',
                            'std.profile_photo_url', 'std.login_id')
                ->whereRaw("applies.employment_id = $emploies_id
                            and applies.acceptance_ox = 'x'")
                ->get();
                

                //채용에서 떨어진 학생의 떨어진 면접 차수
                foreach($company_fail_std as $index => $company_fail_stds){
                    $student_id = $company_fail_stds->login_id;

                    $fail_std = DB::table('interview_schedules')
                    ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                    ->join('students as std', 'interview_results.student_login_id', '=', 'std.login_id')
                    ->select('std.login_id', 'interview_schedules.interview_count')
                    ->whereRaw("interview_schedules.employment_id = $emploies_id
                                and std.login_id = '$student_id'
                                and interview_result = 'x'")
                    ->get();
                        
                    
                    foreach($fail_std as $fail_stds){
                        $company_fail_stds->interview_count = $fail_stds->interview_count;
                    }
                }

                $company_list[$key]->employment_id[$subKey]->fail_std = $company_fail_std;
                
                
                //채용을 하러 오는 기업에 합격한 학생중 내정 수락한 학생 이름, 프로필 사진
                $company_fix_std = DB::table("applies")
                ->join("employment_infos", "applies.employment_id", "=", "employment_infos.employment_id")
                ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng', 'std.profile_photo_url', 'std.login_id')
                ->whereRaw("employment_infos.employment_id = $emploies_id
                            and applies.apply_permission_ox = 'o'
                            and applies.acceptance_ox = 'o'
                            and applies.student_acceptance_ox = 'o'
                            and applies.professor_acceptance_ox = 'o'")
                ->get();

                $company_list[$key]->employment_id[$subKey]->fixed_std = $company_fix_std;
                
            }
            
        }
        
        //foreach($company_list as $key => $company_lists){
            //$company_employ = $company_lists->employment_id;
            
            /*
            foreach($company_employ as $company_emploies){
                $company_emploies->employment_id;
                
            }
            */
            
            /*
            //채용을 하러 오는 기업의 내정 학생 수
            $company_pass = DB::table('employment_infos')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->select(DB::raw("count(applies.student_login_id) as pass"))
            ->whereRaw("employment_infos.employment_id = $employment_id
                        and applies.apply_permission_ox = 'o'
                        and applies.acceptance_ox = 'o'")
            ->get();
            
            foreach($company_pass as $company_passes){
                $company_list[$key]->pass = $company_passes->pass;
            }
            */
            /*
            //채용을 하러 오는 기업에 지원한 학생중 채용 진행중 학생 수
            $company_apply_progress = DB::table('employment_infos')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->join('interview_schedules', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
            ->whereRaw("employment_infos.employment_id = $employment_id
                        and applies.apply_permission_ox = 'o' 
                        and applies.acceptance_ox is null or applies.acceptance_ox = ''
                        and interview_schedules.interview_active = 'o'")
            ->select(DB::raw("count(applies.student_login_id) as progress"))
            ->get();

            foreach($company_apply_progress as $company_apply_progresses){
                $company_list[$key]->progress = $company_apply_progresses->progress;
            }

            //채용을 하러 오는 기업에 지원한 학생중 떨어진 학생 수
            $company_apply_fail = DB::table('employment_infos')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->whereRaw("employment_infos.employment_id = $employment_id
                        and applies.acceptance_ox = 'x'")
            ->select(DB::raw("count(applies.student_login_id) as fail"))
            ->get();

            foreach($company_apply_fail as $company_apply_fails){
                $company_list[$key]->fail = $company_apply_fails->fail;
            }
            

            //채용을 하러 오는 기업에 합격한 핵싱중 내정 수락자 수
            $company_apply_fixed = DB::table('employment_infos')
            ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->whereRaw("employment_infos.employment_id = $employment_id
                        and applies.apply_permission_ox = 'o' 
                        and applies.acceptance_ox = 'o'
                        and applies.student_acceptance_ox = 'o'
                        and applies.professor_acceptance_ox = 'o'")
            ->select(DB::raw("count(applies.student_login_id) as fixed"))
            ->get();

            foreach($company_apply_fixed as $company_apply_fixeds){
                $company_list[$key]->fixed = $company_apply_fixeds->fixed;
            }
            


            //채용하러 온 기업에 지원한 학생의 이름과 프로필 사진
            $company_apply_std = DB::table("applies")
            ->join("employment_infos", "applies.employment_id", "=", "employment_infos.employment_id")
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng', 'std.profile_photo_url')
            ->whereRaw("employment_infos.employment_id = $employment_id")
            ->get();

            $company_list[$key]->apply_std = $company_apply_std;


            //채용하러 온 기업에 지원한 학생중 합격한 학생 이름과 프로필 사진
            $company_pass_std = DB::table("applies")
            ->join("employment_infos", "applies.employment_id", "=", "employment_infos.employment_id")
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng', 'std.profile_photo_url', 'std.login_id')
            ->whereRaw("employment_infos.employment_id = $employment_id
                        and applies.apply_permission_ox = 'o'
                        and applies.acceptance_ox = 'o'")
            ->get();

            $company_list[$key]->pass_std = $company_pass_std;

            //채용을 하러 오는 기업에 지원한 학생중 채용 진행중 학생 이름, 프로필 사진, 현재 면접 차수
            $company_progress_std = DB::table('applies')
            ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('interview_schedules', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
            ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng',
                        'std.profile_photo_url', 'interview_schedules.interview_count', 'std.login_id')
            ->whereRaw("employment_infos.employment_id = '$employment_id'
                        and interview_schedules.interview_active = 'o'
                        and applies.apply_permission_ox = 'o'
                        and (applies.acceptance_ox = '' or applies.acceptance_ox is null)")
            ->get();

            $company_list[$key]->progress_std = $company_progress_std;


            //채용을 하러 오는 기업에 지원한 학생중 채용에서 떨어진 학생 이름, 프로필 사진
            $company_fail_std = DB::table("applies")
            ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng',
                        'std.profile_photo_url', 'std.login_id')
            ->whereRaw("applies.employment_id = $employment_id
                        and applies.acceptance_ox = 'x'")
            ->get();
            

            //채용에서 떨어진 학생의 떨어진 면접 차수
            foreach($company_fail_std as $sub_key => $company_fail_stds){
                $student_id = $company_fail_stds->login_id;

                $fail_std = DB::table('interview_schedules')
                ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                ->join('students as std', 'interview_results.student_login_id', '=', 'std.login_id')
                ->select('std.login_id', 'interview_schedules.interview_count')
                ->whereRaw("interview_schedules.employment_id = $employment_id
                            and std.login_id = '$student_id'
                            and interview_result = 'x'")
                ->get();

                foreach($fail_std as $fail_stds){
                    $company_fail_std[$sub_key]->interview_count = $fail_stds->interview_count;
                    break;
                }
            }

            $company_list[$key]->fail_std = $company_fail_std;
            
            
            //채용을 하러 오는 기업에 합격한 학생중 내정 수락한 학생 이름, 프로필 사진
            $company_fix_std = DB::table("applies")
            ->join("employment_infos", "applies.employment_id", "=", "employment_infos.employment_id")
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng', 'std.profile_photo_url', 'std.login_id')
            ->whereRaw("employment_infos.employment_id = $employment_id
                        and applies.apply_permission_ox = 'o'
                        and applies.acceptance_ox = 'o'
                        and applies.student_acceptance_ox = 'o'
                        and applies.professor_acceptance_ox = 'o'")
            ->get();

            $company_list[$key]->fixed_std = $company_fix_std;
            */


        //}

        return json_encode($company_list);
        
    }
}