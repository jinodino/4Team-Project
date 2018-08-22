<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PushNotify as PushNotify;

class ApplyManagement extends Controller
{
        //교수_지원 관리
        public function newChart(Request $request){
                $professor_id = $request->get('professorId');

                $chart_result = DB::table('org_matchings')
                ->join('employment_infos', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign' )
                ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                ->join('applies', 'applies.employment_id', '=', 'employment_infos.employment_id')
                ->whereRaw(
                        "applies.student_login_id in (select login_id
                                                        from students
                                                        where professor_login_id = '$professor_id')")
                ->select(
                        
                        'org_companies.org_name as company',
                        DB::raw('count(applies.employment_id) as value'))
                ->groupBy('company')
                ->get();
                
                $companyname = array();
                $value = array();
                foreach($chart_result as $chart_results){
                        array_push($companyname, $chart_results->company);
                        array_push($value, $chart_results->value);
                
                }
                echo json_encode(['company' => $companyname, 'value' => $value]);
                
        }

        public function interview_info_list(Request $request){
                
                $professorId    = $request->get('professorId'); //교수ID
                $pageName       = $request->get('PageName');    //현재 페이지
                $select         = $request->get('select');      //선택한 기업 || 선택 진행여부
//this.info       = res.data.info; //기업면접 날짜, 시간, 장소
//this.stdinfo    = res.data.std; //기업에 지원한 학생의 정보(이름, 아이디, 면접일(날짜, 시간, 장소), 일본어능력JPT, JLPT, 내정수, 면접 승인여부, 사진)
//{name:"rrr", interviewDate:"2018.05.30", time:"18:00", place:"300호", jlpt:"2급", jpt:"900", statusCount:"1",interview:"x",avatar:"/images/professor/jang.jpg"}
                
                if($pageName == 'applyManagement'){
                        //인터뷰 일정
                        $interview_schedule = DB::table('interview_schedules')
                        ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->whereRaw("org_matchings.org_company_id in (select org_company_id 
                                                                        from org_companies 
                                                                        where org_name = '$select')
                                        and
                                        org_matchings.org_college_id in (select faculties.org_college_id 
                                                                        from professors 
                                                                        join faculties on professors.faculty_id = faculties.faculty_id 
                                                                        where professors.login_id = '$professorId')")
                        ->select('interview_schedules.interview_date as date', 
                                'interview_schedules.interview_start_time as time', 
                                'interview_schedules.interview_place as place'
                                )
                        ->get();
                        
                        $interview_std_list = DB::table('interview_results')
                        ->join('interview_schedules', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'interview_schedules.employment_id')
                        ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
                        ->join('students', 'students.login_id', '=', 'interview_results.student_login_id')
                        ->join('applies', 'applies.student_login_id', '=', 'students.login_id')
                        ->whereRaw("org_matchings.org_company_id in (select org_company_id 
                                                                        from org_companies 
                                                                        where org_name = '$select')
                                        and
                                        org_matchings.org_college_id in (select faculties.org_college_id 
                                                                        from professors 
                                                                        join faculties on professors.faculty_id = faculties.faculty_id 
                                                                        where professors.login_id = '$professorId')")
                        ->select('students.name as name', 
                                'students.name_eng as name_eng', 
                                'students.name_kanji as name_kanji', 
                                'students.name_kana as name_kana', 
                                'interview_schedules.interview_date as interviewDate',
                                'interview_results.interview_start_time as time', 
                                'interview_schedules.interview_place as place', 
                                'employment_infos.employment_id',
                                'students.JLPT as jlpt', 
                                'students.JPT as jpt', 
                                DB::raw("count(case when applies.acceptance_ox = 'o' then 1 end) as statusCount"),
                                'applies.apply_permission_ox as interview', 
                                'students.profile_photo_url as avatar')
                        ->get();

                        echo json_encode(['info' => $interview_schedule, 'std' => $interview_std_list]);
                }else{


                        echo "내정 현황";

                }
        }
        public function apply_permission_o(Request $request){
                $professorId    = $request->get('professorId');
                $studentId      = $request->get('studentId');
                $employment_id  = $request->get('employment_id');

                DB::table('applies')
                        ->where([
                                ['employment_id', '=', $employment_id],
                                ['student_login_id', '=', $studentId],
                        ])
                        ->update(['apply_permission_ox' => 'o']);

                return '지원 승낙';
        }
        public function apply_permission_x(Request $request){
                $professorId    = $request->get('professorId');
                $studentId      = $request->get('studentId');
                $employment_id  = $request->get('employment_id');

                DB::table('applies')
                        ->where([
                                ['employment_id', '=', $employment_id],
                                ['student_login_id', '=', $studentId],
                        ])
                        ->update(['apply_permission_ox' => 'x']);

                return '지원 거부';
        }

        //-----------------현재 사용하고 있지 않습니다.-----------------------------
        public function newChart_acceptance(Request $request){
                $professor_id = $request->get('professor_id');

                //학생수
                //면접 미진행 학생수
                $std_interview_progress_x_count = DB::table('professors as pro')
                ->join('students as std', 'std.professor_login_id', '=', 'pro.login_id')
                ->whereRaw("pro.faculty_id in (select faculty_id from professors where login_id = '$professor_id')
                                and
                                                (std.login_id not in (select student_login_id from interview_results)
                                or
                                                (std.login_id, 0) in (SELECT student_login_id, 
                                                                        count(case when interview_result = 'o' OR interview_result = '' then 1 end) as result
                                                                        FROM interview_results
                                                                        group by student_login_id))")
                ->select(DB::raw("count(std.login_id) as std_num"))
                ->get();
                
                //면접 진행 학생수
                $std_interview_progress_o_count = DB::table('interview_results')
                ->select(DB::raw("count(interview_results.student_login_id) as std_num"))
                ->whereRaw("interview_results.interview_id in (SELECT interview_schedules.interview_id
                                                                FROM interview_schedules
                                                                join employment_infos on interview_schedules.employment_id = employment_infos.employment_id
                                                                join org_matchings on employment_infos.org_matching_foreign = org_matchings.org_matchings_id
                                                                join faculties on faculties.org_college_id = org_matchings.org_college_id
                                                                join professors as pro on faculties.faculty_id = pro.faculty_id
                                                                where pro.faculty_id in (select faculty_id
                                                                                        from professors
                                                                                        where login_id = '$professor_id')
                                                                group by interview_schedules.employment_id
                                                                order by interview_count asc)
                                and interview_results.interview_result = 'o' or interview_results.interview_result = ''")
                ->get();

                //내정1사이상 학생수
                $std_acceptance_o_count = DB::table("applies")
                ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
                ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
                ->whereRaw("acceptance_ox = 'o' and pro.faculty_id in (select faculty_id
                                                                        from professors
                                                                        where login_id = '$professor_id')")
                ->select(DB::raw("count(distinct applies.student_login_id) as std_num"))
                ->get();
                //구직활동 종료 학생수
                $std_employ_ox_count = DB::table("students as std")
                ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
                ->whereRaw("pro.faculty_id in (select faculty_id
                                                from professors
                                                where login_id = '$professor_id')
                                and
                                std.employ_ox = 'x'")
                ->select(DB::raw("count(std.login_id) as std_num"))
                ->get();

                $value = array($std_interview_progress_x_count[0]->std_num, 
                                $std_interview_progress_o_count[0]->std_num,
                                $std_acceptance_o_count[0]->std_num, 
                                $std_employ_ox_count[0]->std_num);

                /*
                면접 미진행     : 학생이름
                면접 진행       : 학생이름, 학생별 면접 진행중인 회사명
                내정1사이상     : 학생이름, 학생별 면접 진행중인 회사명, 내정받은 회사
                구직활종 종료   : 학생이름, 내정확정회사명
                */
                
                //면접 미진행
                $std_interview_progress_x = DB::table('professors as pro')
                ->distinct()
                ->join('students as std', 'std.professor_login_id', '=', 'pro.login_id')
                ->whereRaw("pro.faculty_id in (select faculty_id from professors where login_id = '$professor_id')
                                                and
                                                (std.login_id not in (select student_login_id from interview_results)
                                                or
                                                (std.login_id, 0) in (SELECT student_login_id, 
                                                                        count(case when interview_result = 'o' OR interview_result = '' then 1 end) as result
                                                                        FROM interview_results
                                                                        group by student_login_id))")
                ->select("std.profile_photo_url as avatar", 
                        "std.name as name", 
                        "std.name_eng", 
                        "std.name_kanji", 
                        "std.name_kana")
                ->get();
                //면접 진행
                $std_interview_progress_o = DB::table('interview_results')
                ->join('interview_schedules', 'interview_results.interview_id', '=', 'interview_schedules.interview_id')
                ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
                ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
                ->join('students as std', 'interview_results.student_login_id', '=', 'std.login_id')
                ->select("std.profile_photo_url as avatar",
                        "std.name as name","std.name_eng",
                        "std.name_kanji", "std.name_kana",
                        "org_companies.org_name",
                        "org_companies.org_name_kana")
                ->whereRaw("interview_results.interview_id in (SELECT interview_schedules.interview_id
                                                                FROM interview_schedules
                                                                join employment_infos on interview_schedules.employment_id = employment_infos.employment_id
                                                                join org_matchings on employment_infos.org_matching_foreign = org_matchings.org_matchings_id
                                                                join faculties on faculties.org_college_id = org_matchings.org_college_id
                                                                join professors as pro on faculties.faculty_id = pro.faculty_id
                                                                where pro.faculty_id in (select faculty_id
                                                                                        from professors
                                                                                        where login_id = '$professor_id')
                                                                group by interview_schedules.employment_id
                                                                order by interview_count asc)
                                and 
                                interview_results.interview_result = 'o'
                                or 
                                interview_results.interview_result = ''")
                ->get();
                //내정1사 이상
                $std_accept_o_interview_list = DB::table('org_companies')
                ->join('org_matchings', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
                ->join('employment_infos as emp_info', 'emp_info.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                ->join('interview_schedules', 'interview_schedules.employment_id', '=', 'emp_info.employment_id')
                ->join('interview_results', 'interview_schedules.interview_id', '=', 'interview_results.interview_id')
                ->join('students as std', 'std.login_id', '=', 'interview_results.student_login_id')
                ->select('std.name as name',
                        'std.profile_photo_url as avatar',
                        'std.name_eng',
                        'std.name_kanji',
                        'std.name_kana',
                        'org_companies.org_name',
                        'org_companies.org_name_kana')
                ->whereRaw("interview_schedules.interview_id in (select interview_id
                                                                from interview_schedules
                                                                where interview_schedules.employment_id in (select employment_id
                                                                                                                from applies
                                                                where applies.student_login_id in (select distinct student_login_id
                                                                                                        from applies
                                                                                                        join students as std on std.login_id = applies.student_login_id
                                                                                                        join professors as pro on std.professor_login_id = pro.login_id
                                                                                                        where acceptance_ox = 'o' and pro.faculty_id in (select faculty_id
                                                                                                                                                        from professors
                                                                                                                                                        where login_id = '$professor_id'))
                                                                and (acceptance_ox = '' or acceptance_ox is null)
                                                                and apply_permission_ox != 'x')
                                and interview_schedules.interview_id in (select interview_id
                                                                        from interview_schedules
                                                                        where (employment_id, interview_count) in (select employment_id, max(interview_count)
                                                                                                                        from interview_schedules
                                                                                                                        group by employment_id)
                                                                        group by employment_id))")
                ->get();

                $std_accept_o = DB::table('applies')
                ->join("employment_infos as emp_info", "applies.employment_id", "=", "emp_info.employment_id")
                ->join("org_matchings", "emp_info.org_matching_foreign", "=", "org_matchings.org_matchings_id")
                ->join("org_companies", "org_matchings.org_company_id", "=", "org_companies.org_company_id")
                ->join("students as std", "std.login_id", "=", "applies.student_login_id")
                ->whereRaw("(apply_id, student_login_id) in (select apply_id, student_login_id
                                                                from applies
                                                                join students as std on std.login_id = applies.student_login_id
                                                                join professors as pro on std.professor_login_id = pro.login_id
                                                                where acceptance_ox = 'o' and pro.faculty_id in (select faculty_id
                                                                                                                from professors
                                                                                                                where login_id = '$professor_id'))")
                ->get();

                $std_acceptance_o = array('acceptance_o_list' => $std_accept_o, 'acceptance_o_interview_list' => $std_accept_o_interview_list);
                //구직활동 종료
                $std_employ_ox = DB::table('students as std')
                ->join('applies', 'std.login_id', '=', 'applies.student_login_id')
                ->join('employment_infos','applies.employment_id', '=', 'employment_infos.employment_id')
                ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
                ->whereRaw("std.login_id in (SELECT std.login_id
                                                FROM students as std
                                                join professors as pro on std.professor_login_id = pro.login_id
                                                where pro.faculty_id in (select faculty_id
                                                                        from professors
                                                                        where login_id = '$professor_id')
                                                and
                                                std.employ_ox = 'x')
                                and applies.apply_permission_ox != 'x'")
                ->select("std.profile_photo_url as avatar",
                        'std.name as name',
                        'std.name_eng',
                        'std.name_kanji',
                        'std.name_kana',
                        'org_companies.org_name',
                        'org_companies.org_name_kana')
                ->get();
                $std_employ_ox_fixed_company = DB::table('students as std')
                ->join('applies', 'std.login_id', '=', 'applies.student_login_id')
                ->join('employment_infos','applies.employment_id', '=', 'employment_infos.employment_id')
                ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
                ->whereRaw("std.login_id in (SELECT std.login_id
                                                FROM students as std
                                                join professors as pro on std.professor_login_id = pro.login_id
                                                where pro.faculty_id in (select faculty_id
                                                                        from professors
                                                                        where login_id = '$professor_id')
                                                and
                                                std.employ_ox = 'x')
                                and applies.apply_permission_ox != 'x'
		                and applies.student_acceptance_ox = 'o'
                                and applies.professor_acceptance_ox = 'o'")
                ->select("std.profile_photo_url as avatar",
                        'std.name as name',
                        'std.name_eng',
                        'std.name_kanji',
                        'std.name_kana',
                        'org_companies.org_name',
                        'org_companies.org_name_kana')
                ->get();
                $std_employ = array('std_employ_ox_list' => $std_employ_ox, 'std_employ_ox_fixed_company' => $std_employ_ox_fixed_company);
                
                $stdInterviewInfo = array($std_interview_progress_x, $std_interview_progress_o, $std_acceptance_o, $std_employ);

                return json_encode(['values' => $value, 'stdInterviewInfo' => $stdInterviewInfo]);
        }
        
        //---------------------------------------------------------------------------



        public function Professor_totalCompanyList(Request $request){
                //교수 면접 진행하는 회사별 학생 관리

                $professorId = $request->get('professorId');
                $year = date("Y");

                /*채용을 하러 오는 회사 목록*/
                $employ_list = DB::table('employment_infos')
                ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_companY_id')
                ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
                ->select('employment_infos.employment_id',
                        'employment_infos.apply_open_date',
                        'employment_infos.apply_deadline_date', 
                        'org_companies.org_company_id',
                        'org_matchings.org_matchings_id',
                        'org_companies.org_name',
                        'org_companies.org_name_kana')
                ->whereRaw("faculties.faculty_id in (select faculty_id 
                                                        from professors 
                                                        where professors.login_id = '$professorId')
                                and org_matchings.employment_decision_ox = 'o'
                                and org_matchings.matching_date = '$year'")
                ->get();

                foreach($employ_list as $key => $employ_lists){
                        $employ_id = $employ_lists->employment_id;

                        /*모집 분야*/
                        $work_info = DB::table('work_infos')
                        ->join('work_matchings', 'work_infos.id', '=', 'work_matchings.work_id')
                        ->select('work_infos.content')
                        ->where('work_matchings.employment_id', '=', "$employ_id")
                        ->get();

                        $employ_list[$key]->work_info = $work_info;

                        /*이 채용의 면접 일*/
                        $interview_date = DB::table('interview_schedules')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'interview_schedules.employment_id')
                        ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
                        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
                        ->select('interview_date')
                        ->whereRaw("interview_schedules.employment_id = $employ_id
                                        and faculties.faculty_id in (select faculty_id 
                                                                        from professors as pro 
                                                                        where pro.login_id = '$professorId')")
                        ->get();

                        foreach($interview_date as $interview_dates){
                                $employ_list[$key]->interview_date = $interview_dates->interview_date;
                        }
                        /*이 채용에 지원한 학생 수*/
                        $apply_std_count = DB::table('applies')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->join('professors as pro', 'pro.login_id', '=', 'std.professor_login_id')
                        ->select(DB::raw("count(student_login_id) as apply_count"))
                        ->whereRaw("applies.employment_id = $employ_id 
                                        and pro.faculty_id in (select faculty_id 
                                                                from professors as pro 
                                                                where pro.login_id = '$professorId')")
                        ->get();

                        foreach($apply_std_count as $apply_std_counts){
                                $employ_list[$key]->apply = $apply_std_counts->apply_count;
                        }
                        /*이 채용에 지원할 수 있는 학생 수*/
                        $pass_std_count = DB::table('applies')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->join('professors as pro', 'pro.login_id', '=', 'std.professor_login_id')
                        ->select(DB::raw("count(student_login_id) as pass"))
                        ->whereRaw("applies.employment_id = $employ_id 
                                        and pro.faculty_id in (select faculty_id 
                                                                from professors as pro 
                                                                where pro.login_id = '$professorId')
                                        and applies.apply_permission_ox = 'o'")
                        ->get();

                        foreach($pass_std_count as $pass_std_counts){
                                $employ_list[$key]->pass = $pass_std_counts->pass;
                        }

                        /*이 채용에 지원할 수 없는 학생 수*/
                        $apply_accept_x = DB::table('applies')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->join('professors as pro', 'pro.login_id', '=', 'std.professor_login_id')
                        ->select(DB::raw("count(student_login_id) as fail"))
                        ->whereRaw("applies.employment_id = $employ_id 
                                        and pro.faculty_id in (select faculty_id 
                                                                from professors as pro
                                                                where pro.login_id = '$professorId')
                                        and applies.apply_permission_ox = 'x'")
                        ->get();
                        foreach($apply_accept_x as $apply_accept_xs){
                                $employ_list[$key]->fail = $apply_accept_xs->fail;
                        }

                        /*이 채용에 지원했지만 아직 허가가 나오지 않은 학생 수*/
                        $apply_accept_progress = DB::table('applies')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->join('professors as pro', 'pro.login_id', '=', 'std.professor_login_id')
                        ->select(DB::raw("count(student_login_id) as progress"))
                        ->whereRaw("applies.employment_id = $employ_id 
                                        and pro.faculty_id in (select faculty_id
                                                                from professors as pro
                                                                where pro.login_id = '$professorId')
                                        and (applies.apply_permission_ox = '' or applies.apply_permission_ox is null)")
                        ->get();
                        foreach($apply_accept_progress as $apply_accept_progresses){
                                $employ_list[$key]->progress = $apply_accept_progresses->progress;
                        }


                }
                
                return array('employ_list' => $employ_list, 'server_time' => date('Y-m-d H:i:s'));

                
        }

        public function professor_ApplyInfo(Request $request){
                $professorId    =       $request->get('professorId');
                $company_id     =       $request->get('company_id');
                $employment_id  =       $request->get('employment_id');

                $apply_count    =       array();
                //지원학생 숫자
                $std_count = DB::table('applies')
                ->where('employment_id', '=', $employment_id)
                ->select(DB::raw("count(student_login_id) as std_count"))
                ->get();
                
                foreach($std_count as $std_counts){
                        if(is_null($std_counts->std_count) or $std_counts->std_count == 0){
                                return 0;
                        }
                        array_push($apply_count, ['std_count' => $std_counts->std_count]);
                        break;
                }
                //수락학생 숫자
                $apply_permission_o_count = DB::table('applies')
                ->where('employment_id', '=', $employment_id)
                ->select(DB::raw("count(case when apply_permission_ox = 'o' then 1 end) as permission_o"))
                ->get();
                foreach($apply_permission_o_count as $apply_permission_o_counts){
                        array_push($apply_count, ['std_count' => $apply_permission_o_counts->permission_o]);
                        break;
                }
                //거부학생 숫자
                $apply_permission_x_count = DB::table('applies')
                ->where('employment_id', '=', $employment_id)
                ->select(DB::raw("count(case when apply_permission_ox = 'x' then 1 end) as permission_x"))
                ->get();
                foreach($apply_permission_x_count as $apply_permission_x_counts){
                        array_push($apply_count, ['std_count' => $apply_permission_x_counts->permission_x]);
                        break;
                }
                //수락거절대기 학생 숫자
                $apply_permission_stand_count = DB::table('applies')
                ->where('employment_id', '=', $employment_id)
                ->select(DB::raw("count(case when apply_permission_ox = '' or apply_permission_ox is null then 1 end) as permission_stand"))
                ->get();
                foreach($apply_permission_stand_count as $apply_permission_stand_counts){
                        array_push($apply_count, ['std_count' => $apply_permission_stand_counts->permission_stand]);
                        break;
                }
                //-------------------------------------------------------------
                //지원 학생 숫자 종합
                //$apply_count = array('std_count' => $std_count[0]->std_count, 'apply_permission_o_count' => $apply_permission_o_count[0]->permission_o,
                //                'apply_permission_x_count' => $apply_permission_x_count[0]->permission_x, 'apply_permission_stand_count' => $apply_permission_stand_count[0]->permission_stand);
                //-------------------------------------------------------------


                //지원학생 이름 및 사진
                $std_apply_info = DB::table('applies')
                ->where('employment_id', '=', $employment_id)
                ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
                ->select('std.login_id', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.name_kana', 'std.profile_photo_url')
                ->get();


                foreach($std_apply_info as $key => $std_apply_infos){
                        $std_id = $std_apply_infos->login_id;

                        $accept_info = array();

                        //학생당 진행중인 회사
                        $progress_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
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
                                        $progress_company[$key_sub]->interview_count = $interview_counts->interview_count;
                                        break;
                                }
                        }

                        $std_apply_info[$key]->std_progress_company = $progress_company;

                        //학생당 합격한 회사
                        $pass_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select("applies.student_login_id", "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
                        ->whereRaw("applies.student_login_id = '$std_id' and (applies.acceptance_ox = 'o') and applies.apply_permission_ox = 'o' ")
                        ->get();

                        $std_apply_info[$key]->std_pass_company = $pass_company;


                        //학생당 불합격 회사
                        $fail_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
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
                                ->whereRaw("interview_schedules.employment_id = '$employ_id'
                                                and applies.student_login_id = '$std_id'
                                                and interview_result = 'x'")
                                ->get();
                                foreach($interview_count as $interview_counts){
                                        $fail_company[$key_sub]->interview_count = $interview_counts->interview_count;
                                        break;
                                }
                        }

                        $std_apply_info[$key]->std_fail_company = $fail_company;

                }

                //지원학생 이름 및 사진
                $apply_permission = DB::table('applies')
                ->where('employment_id', '=', $employment_id)
                ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
                ->select('std.login_id', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.name_kana', 'std.profile_photo_url')
                ->get();

                foreach($apply_permission as $key => $apply_permissions){
                        $std_id = $apply_permissions->login_id;

                        //학생당 진행중인 회사
                        $progress_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
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
                                        $progress_company[$key_sub]->interview_count = $interview_counts->interview_count;
                                        break;
                                }
                        }

                        $apply_permission[$key]->std_progress_company = $progress_company;

                        //학생당 합격한 회사
                        $pass_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select("applies.student_login_id", "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
                        ->whereRaw("applies.student_login_id = '$std_id' and (applies.acceptance_ox = 'o') and applies.apply_permission_ox = 'o' ")
                        ->get();

                        $apply_permission[$key]->std_pass_company = $pass_company;


                        //학생당 불합격 회사
                        $fail_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
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
                                ->whereRaw("interview_schedules.employment_id = '$employ_id'
                                                and applies.student_login_id = '$std_id'
                                                and interview_result = 'x'")
                                ->get();
                                foreach($interview_count as $interview_counts){
                                        $fail_company[$key_sub]->interview_count = $interview_counts->interview_count;
                                        break;
                                }
                        }

                        $apply_permission[$key]->std_fail_company = $fail_company;

                }

                //수락학생 이름 및 사진
                $apply_permission_o = DB::table('applies')
                ->where([
                        ['employment_id', '=', $employment_id],
                        ['apply_permission_ox', '=', 'o']
                ])
                ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
                ->select('std.login_id', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.name_kana', 'std.profile_photo_url')
                ->get();

                foreach($apply_permission_o as $key => $apply_permission_os){
                        $std_id = $apply_permission_os->login_id;

                        //학생당 진행중인 회사
                        $progress_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
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
                                        $progress_company[$key_sub]->interview_count = $interview_counts->interview_count;
                                        break;
                                }
                        }

                        $apply_permission_o[$key]->std_progress_company = $progress_company;

                        //학생당 합격한 회사
                        $pass_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select("applies.student_login_id", "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
                        ->whereRaw("applies.student_login_id = '$std_id' and (applies.acceptance_ox = 'o') and applies.apply_permission_ox = 'o' ")
                        ->get();

                        $apply_permission_o[$key]->std_pass_company = $pass_company;


                        //학생당 불합격 회사
                        $fail_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
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
                                ->whereRaw("interview_schedules.employment_id = '$employ_id'
                                                and applies.student_login_id = '$std_id'
                                                and interview_result = 'x'")
                                ->get();
                                foreach($interview_count as $interview_counts){
                                        $fail_company[$key_sub]->interview_count = $interview_counts->interview_count;
                                        break;
                                }
                        }

                        $apply_permission_o[$key]->std_fail_company = $fail_company;

                }

                //거부학생 이름 및 사진
                $apply_permission_x = DB::table('applies')
                ->where([
                        ['employment_id', '=', $employment_id],
                        ['apply_permission_ox', '=', 'x']
                ])
                ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
                ->select('std.login_id', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.name_kana', 'std.profile_photo_url', 'applies.employment_id')
                ->get();
                
                foreach($apply_permission_x as $key => $apply_permission_xs){
                        $std_id = $apply_permission_xs->login_id;

                        //학생당 진행중인 회사
                        $progress_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
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
                                        $progress_company[$key_sub]->interview_count = $interview_counts->interview_count;
                                        break;
                                }
                        }

                        $apply_permission_x[$key]->std_progress_company = $progress_company;

                        //학생당 합격한 회사
                        $pass_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select("applies.student_login_id", "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
                        ->whereRaw("applies.student_login_id = '$std_id' and (applies.acceptance_ox = 'o') and applies.apply_permission_ox = 'o' ")
                        ->get();

                        $apply_permission_x[$key]->std_pass_company = $pass_company;


                        //학생당 불합격 회사
                        $fail_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
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
                                ->whereRaw("interview_schedules.employment_id = '$employ_id'
                                                and applies.student_login_id = '$std_id'
                                                and interview_result = 'x'")
                                ->get();
                                foreach($interview_count as $interview_counts){
                                        $fail_company[$key_sub]->interview_count = $interview_counts->interview_count;
                                        break;
                                }
                        }

                        $apply_permission_x[$key]->std_fail_company = $fail_company;

                }

                //대기학생 이름 및 사진
                $apply_permission_stand = DB::table('applies')
                ->whereRaw("
                        employment_id = $employment_id and (apply_permission_ox = '' or apply_permission_ox is null)
                ")
                ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
                ->select('std.login_id', 'std.name', 'std.name_eng', 'std.name_kanji', 'std.name_kana', 'std.profile_photo_url', 'applies.employment_id')
                ->get();

                foreach($apply_permission_stand as $key => $apply_permission_stands){
                        $std_id = $apply_permission_stands->login_id;

                        //학생당 진행중인 회사
                        $progress_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
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
                                        $progress_company[$key_sub]->interview_count = $interview_counts->interview_count;
                                        break;
                                }
                        }

                        $apply_permission_stand[$key]->std_progress_company = $progress_company;

                        //학생당 합격한 회사
                        $pass_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select("applies.student_login_id", "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
                        ->whereRaw("applies.student_login_id = '$std_id' and (applies.acceptance_ox = 'o') and applies.apply_permission_ox = 'o' ")
                        ->get();

                        $apply_permission_stand[$key]->std_pass_company = $pass_company;


                        //학생당 불합격 회사
                        $fail_company = DB::table('applies')
                        ->join('employment_infos', 'employment_infos.employment_id', '=', 'applies.employment_id')
                        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
                        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
                        ->select('employment_infos.employment_id', "org_companies.org_name", "org_companies.org_name_kana", "org_companies.org_company_id")
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
                                ->whereRaw("interview_schedules.employment_id = '$employ_id'
                                                and applies.student_login_id = '$std_id'
                                                and interview_result = 'x'")
                                ->get();
                                foreach($interview_count as $interview_counts){
                                        $fail_company[$key_sub]->interview_count = $interview_counts->interview_count;
                                        break;
                                }
                        }

                        $apply_permission_stand[$key]->std_fail_company = $fail_company;

                }

                $apply_permission_info = array('permission' => $apply_permission, 'permission_o' => $apply_permission_o, 'permission_x' => $apply_permission_x, 'permission_stand' => $apply_permission_stand);
                
                //지원자 수(종합)
                //지원자 아이디, 이름, 사진
                echo json_encode(['apply_std_count' => $apply_count, 'apply_std_info' => $std_apply_info, 'apply_permission_info' => $apply_permission_info]);
                
        }


        public function accept_change(Request $request){
                $pushNotify = new PushNotify();

                $professorId = $request->get('professorId');

                $permission_o = $request->get('permission_o');
                $permission_x = $request->get('permission_x');
                $permission_stand = $request->get('permission_stand');
                $employment_id = $request->get('employment_id');
                
                $apiKey = $request->get('apiKey');
                
                
                foreach($permission_o as $permission_os){
                        
                        DB::table('applies')
                        ->where([
                                ['employment_id', '=', $employment_id],
                                ['student_login_id', '=', $permission_os['login_id']]
                        ])
                        ->update(['apply_permission_ox' => 'o']);
                        
                        $pushNotify->push_select_send($professorId, $permission_os['login_id'], $apiKey, "면접 수락되었습니다.");
                }
        

                foreach($permission_x as $permission_xs){
                        DB::table('applies')
                        ->where([
                                ['employment_id', '=', $employment_id],
                                ['student_login_id', '=', $permission_xs['login_id']]
                        ])
                        ->update(['apply_permission_ox' => 'x']);

                        $pushNotify->push_select_send($professorId, $permission_xs['login_id'], $apiKey, "면접 거절되었습니다.");
                }

                foreach($permission_stand as $permission_stands){
                        DB::table('applies')
                        ->where([
                                ['employment_id', '=', $employment_id],
                                ['student_login_id', '=', $permission_stands['login_id']]
                        ])
                        ->update(['apply_permission_ox' => null]);

                        //$pushNotify->push_select_send($professorId, $permission_stands['login_id'], $apiKey, "면접 승낙 답변을 기다립니다.");
                }

                return 'success';
                
        }
        

        public  function real_accept() {
                $pushNotify = new PushNotify();
                // 채용 아이디
                $employment_id = request('employment_id');
                //교수 아이디
                $professor_id  = request('professorId');
                // 학생 리스트(수락)
                $permission_o  = request('permission_o');
                // 학생 리스트(거절)
                $permission_x  = request('permission_x');
                //api키
                $apiKey = request('apiKey');
                // 인터뷰 아이디
                $interview_id = DB::table('interview_schedules')
                ->select('interview_id', 'interview_date', 'interview_start_time')
                ->where([
                    ['employment_id', '=', $employment_id],
                    ['interview_check_ox', '=', 'o'],
                    ['interview_active', '=', 'o'],
                ])
                ->latest('interview_date')
                ->first();
                
                $interview_start_time = $interview_id->interview_date . ' ' . $interview_id->interview_start_time;

                foreach($permission_o as $permission_os) {
                        $std = $permission_os["login_id"];
                        try{
                                DB::table('interview_results')
                                ->insert([
                                        'interview_id'         => $interview_id->interview_id,
                                        'student_login_id'     => $std,
                                        'interview_start_time' => $interview_start_time,
                                        'interview_result'     => null,
                                ]);
                        }catch(\Illuminate\Database\QueryException $e){
                                
                        }
                        $pushNotify->push_select_send($professor_id, $std, $apiKey, "구직 지원이 수락되었습니다.");
                }
                
                foreach($permission_x as $permission_xs){
                        $std = $permission_xs["login_id"];
                        $pushNotify->push_select_send($professor_id, $std, $apiKey, "구직 지원이 거부되었습니다.");
                }
        }
}
