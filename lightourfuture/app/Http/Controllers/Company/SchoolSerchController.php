<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class SchoolSerchController extends Controller
{

    /** 
     *  학교 리스트 출력 함수
     *  @param 
     *  @return Array(Object) CollegeInfo
     */
    public function collegeList() {       
        
   
        $std_count     = 0;  // select college student value
        $std_arr_count = []; // all college student value
        $std_activity_arr_count = []; // 활동중인 학생수 
     
        
        // 담당자 아이디
        $manager_id = request('requester');
        // 기업 아이디 받아옴
        $company_id = DB::table('org_companies')
        ->where('manager_login_id', '=', $manager_id)
        ->pluck('org_company_id');
        
        if(!isset($company_id[0])) return 0;
        
        // 학교 정보 
        $college_info = DB::table('org_colleges as orc')
            ->select('orc.org_college_id', 'cds.country_eng', 'orc.org_name', 'orc.org_name_kana', 'orc.photo_url',
                    'orc.address_city', 'orc.address', 'orc.address', 'orc.homepage_url','orc.college_category',
                    'ora.org_agent_id as org_agent_id', 'ora.org_name as org_agent_name'
                    )
            ->join('country_codes as cds', 'cds.country_code', '=', 'orc.country_code')
            ->join('org_matchings as orm', 'orm.org_college_id', '=', 'orc.org_college_id')
            ->join('org_agents as ora', 'ora.org_agent_id', '=', 'orm.org_agent_id')
            //->join('agents as ag', 'ag.login_id', '=', 'ora.manager_login_id')
            // -> 해당 기업과 매칭 되어있는 학교만 볼때  / 매칭 상관없이 모든 학교 볼때
            // ->where([
            //     ['orm.org_company_id', '=', $company_id[0]],
            //     ['orm.employment_decision_ox', '=', 'o'],
            // ])
            ->get();
            //return $college_info;
                
        
        // 각 학교별 인원수 담을 배열에 값 넣어줌
        foreach($college_info as $college) {
            // 인원수 추출
            $std_count = DB::table('professors as pro')
            ->select(DB::raw("count(student.login_id) as std"))
            ->join('faculties as fac', 'fac.faculty_id', '=', 'pro.faculty_id')
            ->join('org_colleges as college', 'fac.org_college_id', '=', 'college.org_college_id')
            ->join('students as student', 'student.professor_login_id', '=', 'pro.login_id')
            ->where('college.org_college_id', '=',  $college->org_college_id)
            ->get();
            array_push($std_arr_count, $std_count[0]);

            // 해당 대학의 채용활동 중인 학생
            $std_Activity_count = DB::table('professors as pro')
            ->select(DB::raw("count(student.login_id) as std_activity"))
            ->join('faculties as fac', 'fac.faculty_id', '=', 'pro.faculty_id')
            ->join('org_colleges as college', 'fac.org_college_id', '=', 'college.org_college_id')
            ->join('students as student', 'student.professor_login_id', '=', 'pro.login_id')
            ->where([
                ['college.org_college_id', '=',  $college->org_college_id],
                ['student.employ_ox', '=', 'o'],
            ])
            ->get();
            array_push($std_activity_arr_count, $std_Activity_count[0]);
        }
        
        
        // 반환값 
        return array('info' => $college_info, 'total_count' => $std_arr_count, 'activity_count' => $std_activity_arr_count);
    
        
    }

    /**
     * 
     * 익명 학생리스트 ( 성별 나이 전공{join} 일본어 )
     *  @param String CompanyID, ColleteID
     *  @return JSON Student_info, Check(참가, 미참가 확인) 
     */
    public function studentList() {
        
        // 학교 ID
        $college_id = request('selectedSchool');

        // 교수 아이디 얻기 위함 
        $faculty_id = DB::table('faculties')->where('org_college_id', '=', $college_id)->pluck('faculty_id');
        //return $faculty_id;
        $skill_info = []; 
        $professor_arr = []; 
        // 교수님 아이디 알아와야함 -> 학생이랑 매칭
        
        // 여기 잘 생각해봐야함
        foreach($faculty_id as $fci) {
            
            $professor_id = DB::table('professors')->where('faculty_id', '=', $fci)->pluck('login_id');
            
            if(isset($professor_id)) {
                array_push($professor_arr, $professor_id);
            }
         
        }
        
        
        
        //return $professor_arr;
        $std_arr = [];
        foreach($professor_arr as $pra) {
            foreach($pra as $value) {
                $student_name = DB::table('students')
                ->select('login_id', 'employ_ox', 'gender','email','JPT', 'JLPT', 'birth_date', 'recommendation_comment', DB::raw('Floor((TO_DAYS(now())-(TO_DAYS(birth_date)))/365) as age'))
                ->where([
                    ['professor_login_id', '=', $value],
                    // 2018-06-09 수정
                    ['employment_end', '!=', 'o'],
                ])
                ->get();
                
                if(isset($student_name)) {
                    array_push($std_arr, $student_name);
                }
            }
        }

        if(isset($std_arr[0])) return $std_arr[0];
        else return 0;
        
    }

    // 채용결정 -> 지정 학생 상세 2018-06-27
    public function selectDetailStudentInfo() {
        $student_id    = request('stdId');
        $employment_id = request('employId');


        $arr = []; 
        $student_name = DB::table('students')
        ->where('login_id', '=', $student_id)->pluck('name_eng');
        
        // 빈 배열일 시 더미값 -> 빈배열을 만들기 위해
        if(!isset($student_name[0])) return 0;

        $skill_info = DB::table('skills as sks')
        ->select('skl.skill_level', 'skf.skill_name')
        ->join('skill_levels as skl', 'sks.language_level_code', '=', 'skl.no')
        ->join('skill_infos as skf', 'sks.language_code', '=', 'skf.no')
        ->join('students as std', 'std.login_id', '=', 'sks.student_login_id')
        ->where('std.name_eng', '=', $student_name)->get();

        $student_info = DB::table('students')
          ->select('students.*', DB::raw('Floor((TO_DAYS(now())-(TO_DAYS(students.birth_date)))/365) as age'), 'groups.group_num', 'groups.group_name', 'groups.project_video_url')
          ->join('groups', 'students.group_id', '=', 'groups.group_id')
          ->where('name_eng', '=', $student_name)
          ->get();
 
        
        $company_id = request('requester');
        if(!isset($company_id)) return array('skill_info' => $skill_info, 'student_info' => $student_info);

        // 학생 포폴 이력서 레포지토리
        //$company_name = DB::table('org_companies')->where('org_company_id', '=', $company_id)->pluck('org_name');

        $student_dir = "/storage/repository/$student_id/";

        $portfolios = glob($student_dir . "portfolios/{$employment_id}_*");

        if(!isset($portfolios)) return "포트폴리오 파일이 없습니다.";

        $entrySheets = glob($student_dir . "entrySheets/{$employment_id}_*");

        if(!isset($entrySheets)) return "엔트리시트 파일이 없습니다.";
          
        return array('skill_info' => $skill_info, 'student_info' => $student_info, 'entrysheet' => $entrySheets, 'porportfolio' => $portfolios);

    }

    // 익명학생 -> 지정 학생 상세 2018-05-02
    public function detailsStudentInfo() {
        // request student_id
        $student_id = request('stdId');
        
        $arr = []; 
        $student_name = DB::table('students')
        ->where('login_id', '=', $student_id)->pluck('name_eng');
        
        // 빈 배열일 시 더미값 -> 빈배열을 만들기 위해
        if(!isset($student_name[0])) return 0;

        $skill_info = DB::table('skills as sks')
        ->select('skl.skill_level', 'skf.skill_name')
        ->join('skill_levels as skl', 'sks.language_level_code', '=', 'skl.no')
        ->join('skill_infos as skf', 'sks.language_code', '=', 'skf.no')
        ->join('students as std', 'std.login_id', '=', 'sks.student_login_id')
        ->where('std.name_eng', '=', $student_name)->get();

        $student_info = DB::table('students')
          ->select('*', DB::raw('Floor((TO_DAYS(now())-(TO_DAYS(birth_date)))/365) as age'))
          ->where('name_eng', '=', $student_name)->get();

        
        $company_id = request('requester');
        if(!isset($company_id)) return array('skill_info' => $skill_info, 'student_info' => $student_info);

        // 학생 포폴 이력서 레포지토리
        // $company_name = DB::table('org_companies')->where('org_company_id', '=', $company_id)->pluck('org_name');

        // $resum_url = "/storage/repository/$student_id/entrySheets/"; //$company_id.ppt";

        // // repository directory 유무 확인
        // if(!is_dir($resum_url)) return array('skill_info' => $skill_info, 'student_info' => $student_info);

        // // ppt file exists 
        // $resume_file_path =  $repository_type_url + "$company_name[0]";
        // if(!file_exists($resume_file_path)) return;

        // $pf_url = "/storage/$student_id/repository/portfolio/";
        // if(!is_dir($pf_url)) return;

        // // doxc file exists
        // $pf_file_path =  $pf_url + "$company_name[0]";
        // if(!file_exists($pf_file_path)) return;
          
        return array('skill_info' => $skill_info, 'student_info' => $student_info);
    }
    

    /**
     * 지원 학생리스트 출력
     * @param String CompanyID(org_company_id)
     */
    // 지원한 학생 나오고 상세보기 할려면 -> 학생 아이디값 받아서 넘겨야함 
    // 지원학생 이름 / 성별 / 교수 코멘트 / 면접 예정일 / 면접 장소
    public function applyStudentList() {
        // mathcing 아이디가 넘어옴
        $employment_id = request('noticeId'); // employment_ID
        $interview_stage = request('stage'); // interview_count
        
        $interview_id  = DB::table('interview_schedules')->where([
            ['employment_id', '=', $employment_id],
            ['interview_count', '=', $interview_stage],
            ['interview_check_ox', '=', 'o'],
        ])->pluck('interview_id');
        
        if(!isset($interview_id[0])) return 0;
        
        $apply_student_info = DB::table('interview_results as itr')
        ->select('std.login_id', 'std.name_eng', 'std.name_kanji', 'std.name_kana', 'std.gender',
        'std.recommendation_comment', 'std.profile_photo_url', 'itr.interview_start_time',
        'std.japanese_speaking_level',
        'std.personality_grade',
        'std.sincerity_grade',
        'std.major_grade',
        'itr.interview_result', 'itc.employment_id', 'itr.interview_id', 'itc.interview_count' , 'itc.interview_place',
        'orm.org_college_id', 'ocl.org_name as college_name', 'ocl.org_name_kana as college_name_kana', 'prs.name as professor_id' )//, 'ag.login_id as agent_id', 'ag.name as agent_name')
        ->join('applies as app', 'app.student_login_id', '=', 'itr.student_login_id')
        ->join('interview_schedules as itc', 'itr.interview_id', '=', 'itc.interview_id')
        ->join('employment_infos as emi', 'emi.employment_id', '=', 'itc.employment_id')
        ->join('org_matchings as orm', 'orm.org_matchings_id', '=', 'emi.org_matching_foreign')
        ->join('org_colleges as ocl', 'ocl.org_college_id', '=', 'orm.org_college_id')
        ->join('org_agents as ora', 'ora.org_agent_id', '=', 'orm.org_agent_id')
        //->join('agents as ag', 'ag.login_id', '=', 'ora.manager_login_id')
        ->join('students as std', 'std.login_id', '=', 'itr.student_login_id')
        ->join('professors as prs', 'std.professor_login_id', '=', 'prs.login_id')
        ->where([
            ['app.employment_id', '=', $employment_id],
            ['app.apply_permission_ox', '=', 'o'],
            ['itr.interview_id', '=', $interview_id[0]],
            ['itc.interview_check_ox', '=', 'o'],
        ])
        ->get();
        return  $apply_student_info;
        if(!isset($apply_student_info[0])) return 0;
        
        return  $apply_student_info;
    }
    
    // repository안에 파일 path 전달
    public function repository() {
        // request -> student login_id, company login_id
        $student_id = '';
        $company_id = '';

        $company_name = DB::table('org_companies')->where('org_company_id', '=', $company_id)->pluck('org_name');

        $resum_url = "/storage/$student_id/repository/resum/"; //$company_id.ppt";

        // repository directory 유무 확인
        if(!is_dir($resum_url)) return;

        // ppt file exists 
        $resume_file_path =  $repository_type_url + "$company_name[0]";
        if(!file_exists($resume_file_path)) return;

        $pf_url = "/storage/$student_id/repository/portfolio/";
        if(!is_dir($pf_url)) return;

        // doxc file exists
        $pf_file_path =  $pf_url + "$company_name[0]";
        if(!file_exists($pf_file_path)) return;

        return array('resume' => $resume_file_path, 'pf' => $pf_file_path);
    }

    // 채용건 들고오기
    public function matchingschoolList() {
        // 기업 담당자 아이디 -> 기업 아이디

        $date = date('Y-m-d H:i:s');
        
        $manager_id = request('requester');
        $company_id = DB::table('org_companies')->where('manager_login_id', '=', $manager_id)->pluck('org_company_id');
        

        if(!isset($company_id[0])) return 0;
        
        // $matching_data = DB::table('org_matchings')
        // ->where('org_company_id', '=', $company_id[0])
        // ->pluck('org_matchings_id');

        $matching_data = DB::table('employment_infos as emp')
        ->select('orm.org_matchings_id',
        'ora.org_agent_id as org_agent_id', 'ora.org_name as org_agent_name',
        'orc.org_name', 'orc.org_college_id', 'emp.employment_name', 'emp.apply_deadline_date', 'emp.business_type_content',
        'emp.desired_employee_content', 'emp.working_area', 'emp.pay', 'emp.working_sort',
        'emp.business_hour', 'emp.holiday', 'emp.welfare_content',
        'emp.employment_id', 'emp.employment_owari_ox', 'emp.apply_open_date',
        DB::raw("case when emp.apply_deadline_date >= '$date' AND emp.apply_open_date <= '$date' then 'OPEN'
        when emp.apply_deadline_date <= '$date' then 'CLOSE'
        when emp.apply_open_date >= '$date' then 'YET' end as date_result")
        )
        ->join('org_matchings as orm', 'emp.org_matching_foreign', '=', 'orm.org_matchings_id')
        ->join('org_agents as ora', 'ora.org_agent_id', '=', 'orm.org_agent_id')
        ->join('org_colleges as orc', 'orc.org_college_id', '=', 'orm.org_college_id')
        ->join('interview_schedules as its', 'its.employment_id', '=', 'emp.employment_id')
        ->where([
            // 지원 마감일이 지나더라도 지원 마감일을 늘리기 위함
            ['orm.employment_decision_ox', '=', 'o'],
            ['orm.org_company_id', '=', $company_id],
            ['emp.employment_owari_ox', '=', 'x'],
            ['emp.apply_deadline_date', '<=', $date],
            ['its.interview_active', '=', 'o'],
            ['its.interview_check_ox', '=', 'o'],
        ])
        ->orWhere([
            // 지원 내 있는 채용건 
            ['orm.employment_decision_ox', '=', 'o'],
            ['orm.org_company_id', '=', $company_id],
            ['emp.employment_owari_ox', '=', 'x'],
            ['emp.apply_deadline_date', '>=', $date],
            ['emp.apply_open_date', '<=', $date],
            ['its.interview_active', '=', 'o'],
            ['its.interview_check_ox', '=', 'o'],
        ])
        ->get();
        
        
        $employment_id_arr = [];
        $interview_id_arr = [];
        foreach($matching_data as $md) {
            //$employment_id = DB::table('employment_infos')->where('org_matching_foreign', '=', $md->org_matchings_id)->pluck('employment_id');
            $employment_id = DB::table('employment_infos')->where('employment_id', '=', $md->employment_id)->pluck('employment_id');
            
            array_push($employment_id_arr, $employment_id[0]);

            // 인터뷰 아이디 AND 차수
            $interview_id = DB::table('interview_schedules')
            ->where([
                ['employment_id', '=', $employment_id[0]],
                ['interview_active', '=', 'o'],
                ['interview_check_ox', '=', 'o'],
            ])
            ->select('interview_id', 'interview_count', 'iti.content')
            ->join('interview_infos as iti', 'iti.id', '=', 'interview_content_choice')
            ->latest('interview_date')
            ->first();
            
           
            
            // 여기부분 에러 
            //if(!isset($interview_id->interview_id)) return 0;
            if(isset($interview_id)) array_push($interview_id_arr, $interview_id);
            
        }
       
        $count_arr = [];

        // 예외처리
        if(isset($interview_id_arr[0])) {
            foreach($interview_id_arr as $interview) {

            
                $std_count = DB::table('interview_results')
                ->select(DB::raw("count(student_login_id) as std"))
                ->where('interview_id', '=', $interview->interview_id)
                ->get();
                
                array_push($count_arr, $std_count[0]); 
                     
            }
        }
        
        return array('matching_data' => $matching_data, 'count' => $count_arr, 'interview_id' => $interview_id_arr, 'date' => $date);
    }

}
