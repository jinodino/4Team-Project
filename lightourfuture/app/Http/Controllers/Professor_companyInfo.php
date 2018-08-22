<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Professor_companyInfo extends Controller
{
    public function company_list(Request $request){

        $professorId = $request->get('professorId');
        
        $year = date("Y");

        //이 학교에 채용을 하러 오는 기업 정보
        $company_list = DB::table('employment_infos')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_companY_id')
        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
        ->join('japan_prefectures', 'org_companies.address_to_dou_hu_ken', '=', 'japan_prefectures.id')
        ->join('japan_regions', 'japan_regions.id', '=', 'japan_prefectures.id')
        ->whereRaw("faculties.faculty_id in (select faculty_id from professors where professors.login_id = '$professorId')
                  and org_matchings.employment_decision_ox = 'o'
                    and org_matchings.matching_date = '$year'")
        ->select('employment_infos.employment_id',
                    'org_companies.*',
                    'japan_regions.name_kanji as region',
                    'japan_prefectures.name_kanji as to_dou_hu_ken')
        ->get();
        
        

        foreach($company_list as $key => $company_lists){
            $employment_id = $company_lists->employment_id;
            
            
            //채용을 하러 오는 기업의 채용 정보, 지원자수
            $company_default_info = DB::table('applies')
            ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->select('employment_infos.*', DB::raw("count(student_login_id) as apply_count"))
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                        and employment_infos.employment_id = $employment_id")
            ->get();

            foreach($company_default_info as $company_default_infos){
                $company_list[$key]->pay = $company_default_infos->pay;
                $company_list[$key]->apply_count = $company_default_infos->apply_count;
            }

            $works = DB::table('work_infos')
            ->join('work_matchings', 'work_infos.id', '=', 'work_matchings.work_id')
            ->select('work_infos.content')
            ->where('work_matchings.employment_id', '=', "$employment_id")
            ->get();
            
            $company_list[$key]->work = $works;

            //채용을 하러 오는 기업의 면접 날짜
            $company_pass = DB::table('interview_schedules')
            ->join('employment_infos', 'employment_infos.employment_id', '=', 'interview_schedules.employment_id')
            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
            ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
            ->join('professors as pro', 'pro.faculty_id', '=', 'faculties.faculty_id')
            ->select('interview_date')
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                        and interview_schedules.employment_id = $employment_id
                        and interview_schedules.interview_active = 'o'")
            ->get();

            foreach($company_pass as $company_passes){
                $company_list[$key]->interview_date = $company_passes->interview_date;
            }
        }
        /*            
            //채용을 하러 오는 기업의 면접 날짜와 내정 학생 수
            $company_pass = DB::table('interview_schedules')
            ->join('applies', 'applies.employment_id', '=', 'interview_schedules.employment_id')
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->select('interview_date', DB::raw("count(applies.student_login_id) as pass"))
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                        and interview_schedules.employment_id = $employment_id
                        and applies.apply_permission_ox = 'o'
                        and applies.acceptance_ox = 'o'")
            ->get();

            foreach($company_pass as $company_passes){
                $company_list[$key]->interview_date = $company_passes->interview_date;
                $company_list[$key]->pass = $company_passes->pass;
            }


            //채용을 하러 오는 기업에 지원한 학생중 채용 진행중 학생 수
            $company_apply_progress = DB::table('applies')
            ->join(DB::raw("(select employment_id, interview_date, max(interview_count) from interview_schedules where employment_id = '$employment_id' group by employment_id) as interview_schedules"),
            'interview_schedules.employment_id', '=', 'applies.employment_id')
            ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')")
            ->select("employment_infos.employment_id", 
                    DB::raw("count(case when applies.acceptance_ox = '' or applies.acceptance_ox is null then 1 end) as progress"))
            ->get();

            $company_list[$key]->progress = $company_apply_progress[0]->progress;

            //채용을 하러 오는 기업에 지원한 학생중 떨어진 학생 수
            $company_apply_fail = DB::table('applies')
            ->join(DB::raw("(select employment_id, interview_date, max(interview_count) from interview_schedules where employment_id = '$employment_id' group by employment_id) as interview_schedules"),
            'interview_schedules.employment_id', '=', 'applies.employment_id')
            ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')")
            ->select("employment_infos.employment_id", 
                    DB::raw("count(case when applies.acceptance_ox = 'x' then 1 end) as fail"))
            ->get();

            $company_list[$key]->fail = $company_apply_fail[0]->fail;

            //채용을 하러 오는 기업에 합격한 핵싱중 내정 수락자
            $company_apply_fixed = DB::table('applies')
            ->join(DB::raw("(select employment_id, interview_date, max(interview_count) from interview_schedules where employment_id = '$employment_id' group by employment_id) as interview_schedules"),
            'interview_schedules.employment_id', '=', 'applies.employment_id')
            ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')")
            ->select("employment_infos.employment_id", 
                    DB::raw("count(case when applies.acceptance_ox = 'o' and applies.student_acceptance_ox ='o' and applies.professor_acceptance_ox = 'o' then 1 end) as fixed"))
            ->get();

            $company_list[$key]->fixed = $company_apply_fixed[0]->fixed;


            //채용하러 온 기업에 지원한 학생의 이름과 프로필 사진
            $company_apply_std = DB::table("applies")
            ->join("employment_infos", "applies.employment_id", "=", "employment_infos.employment_id")
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng', 'std.profile_photo_url')
            ->whereRaw("employment_infos.employment_id = $employment_id
                            and pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')")
            ->get();

            $company_list[$key]->apply_std = $company_apply_std;


            //채용하러 온 기업에 지원한 학생중 합격한 학생 이름과 프로필 사진
            $company_pass_std = DB::table("applies")
            ->join("employment_infos", "applies.employment_id", "=", "employment_infos.employment_id")
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng', 'std.profile_photo_url', 'std.login_id')
            ->whereRaw("employment_infos.employment_id = $employment_id
                            and pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                            and applies.acceptance_ox = 'o'")
            ->get();

            $company_list[$key]->pass_std = $company_pass_std;

            //채용을 하러 오는 기업에 지원한 학생중 채용 진행중 학생 이름, 프로필 사진, 현재 면접 차수
            $company_progress_std = DB::table('applies')
            ->join(DB::raw("(select employment_id, interview_date, max(interview_count) as interview_count from interview_schedules where employment_id = '$employment_id' group by employment_id) as interview_schedules"),
            'interview_schedules.employment_id', '=', 'applies.employment_id')
            ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng',
                        'std.profile_photo_url', 'interview_schedules.interview_count', 'std.login_id')
            ->whereRaw("pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                        and (applies.acceptance_ox = '' or applies.acceptance_ox is null)")
            ->get();

            $company_list[$key]->progress_std = $company_progress_std;


            //채용을 하러 오는 기업에 지원한 학생중 채용에서 떨어진 학생 이름, 프로필 사진
            $company_fail_std = DB::table("applies")
            ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
            ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng',
                        'std.profile_photo_url', 'std.login_id')
            ->whereRaw("applies.employment_id = $employment_id
                        and pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
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
            ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
            ->select('std.name', 'std.name_kanji', 'std.name_kana', 'std.name_eng', 'std.profile_photo_url', 'std.login_id')
            ->whereRaw("employment_infos.employment_id = $employment_id
                        and pro.faculty_id in (select faculty_id from professors as pro where pro.login_id = '$professorId')
                        and applies.acceptance_ox = 'o'
                        and applies.student_acceptance_ox = 'o'
                        and applies.professor_acceptance_ox = 'o'")
            ->get();

            $company_list[$key]->fixed_std = $company_fix_std;
            
        }
*/
        
        return json_encode($company_list);

    }

    public function company_Info(Request $request){
        $professorId = $request->get('professorId');
        $employment_id = $request->get('employment_id');

        //회사 정보
        $company_info = DB::table('employment_infos')
        ->join('org_matchings', 'employment_infos.org_matching_foreign', '=', 'org_matchings.org_matchings_id')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->select('*')
        ->where('employment_infos.employment_id', '=', $employment_id)
        ->get();

        //이 회사 채용에서 진행중인 학생
        $progress_std = DB::table('applies')
        ->join(DB::raw("(select employment_id, interview_date, max(interview_count), interview_count from interview_schedules where employment_id = '$employment_id' group by employment_id) as interview_schedules"),
        'interview_schedules.employment_id', '=', 'applies.employment_id')
        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
        ->select('std.login_id', 'std.name', 'std.name_eng', 'std.profile_photo_url', 'std.name_kana', 'std.name_kanji', 'interview_schedules.interview_count')
        ->whereRaw("(applies.acceptance_ox = '' or applies.acceptance_ox is null)")
        ->get();

        //합격한 학생
        $pass_std = DB::table('applies')
        ->join(DB::raw("(select employment_id, interview_date, max(interview_count), interview_count from interview_schedules where employment_id = '$employment_id' group by employment_id) as interview_schedules"),
        'interview_schedules.employment_id', '=', 'applies.employment_id')
        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
        ->select('std.login_id', 'std.name', 'std.name_eng', 'std.profile_photo_url', 'std.name_kana', 'std.name_kanji')
        ->whereRaw("applies.acceptance_ox = 'o' ")
        ->get();

        //불합격 학생
        $fail_std = DB::table('applies')
        ->join(DB::raw("(select employment_id, interview_date, max(interview_count), interview_count from interview_schedules where employment_id = '$employment_id' group by employment_id) as interview_schedules"),
        'interview_schedules.employment_id', '=', 'applies.employment_id')
        ->join('students as std', 'std.login_id', '=', 'applies.student_login_id')
        ->select('std.login_id', 'std.name', 'std.name_eng', 'std.profile_photo_url', 'std.name_kana', 'std.name_kanji', 'interview_schedules.interview_count')
        ->whereRaw("applies.acceptance_ox = 'x'")
        ->get();

        

        return ['comInfo' => $company_info, 'std_info' => array($progress_std, $pass_std, $fail_std)];
    }
}