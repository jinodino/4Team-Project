<?php
//by. hyojin
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PushNotify as PushNotify;

class StudentController extends Controller
{


    //지원, 면접, 내정 현황
    public function getStatus(Request $req){
        $login_id = $req->login_id;

        $applyT="applies";
        $org_matchingT = "org_matchings";
        $org_companyT = "org_companies";
        $employment_infoT = "employment_infos";
        $interviewResultT = "interview_results";
        $interviewScheduleT = "interview_schedules";
        $interviewInfoT = 'interview_infos';
        $companyT = "org_companies";
        $matchingT = "org_matchings";


        $applyStatus = [];
        $interviewStatus = [];
        $norminateStatus = [];
        $finalCompanyInfo = null;
        $interview_employment_id = [];
        $applied_employment_id = DB::table($applyT)
                                    ->where('student_login_id', $login_id)
                                    ->pluck('employment_id');

        if(count($applied_employment_id) != 0){

            $applyStatus =  DB::table("$applyT as a")
                                ->select(
                                    'oc.org_company_id', 
                                    'oc.org_name', 
                                    'oc.org_name_kana',
                                    'ei.employment_id',
                                    'ei.employment_name',
                                    'ei.whole_interview_count',
                                    'ei.apply_deadline_date',
                                    'ei.acceptance_fixed_ox',
                                    'a.apply_permission_ox',
                                    'a.apply_id'
                                )
                                ->join("$employment_infoT as ei", 'ei.employment_id', '=', 'a.employment_id')
                                ->join("$org_matchingT as om", 'om.org_matchings_id', '=', 'ei.org_matching_foreign')
                                ->join("$org_companyT as oc", 'oc.org_company_id', '=', 'om.org_company_id')
                                ->where('a.student_login_id', '=', $login_id)
                                ->get();

            $interview_employment_id = DB::table("$interviewResultT as ir")
                                            ->join("$interviewScheduleT as is", 'is.interview_id', 'ir.interview_id')
                                            ->groupBy('is.employment_id')
                                            ->where('ir.student_login_id', $login_id)
                                            ->pluck('is.employment_id');          

            $interviewStatus =  DB::table("$interviewScheduleT as is")
                                    ->select(
                                        'is.employment_id',
                                        'is.interview_id',
                                        'is.schedule_title',
                                        'is.interview_content',
                                        'is.interview_active',
                                        'is.interview_count',
                                        'is.interview_place',
                                        'is.interview_date',
                                        'is.interview_start_time as interview_shedule_start_time',
                                        'is.interview_end_time as interview_shedule_end_time',

                                        'ir.no as interview_result_id',
                                        'ir.interview_start_time as interview_result_start_time',
                                        'ir.interview_end_time as interview_result_end_time',
                                        'ir.interview_result',
                                        'ir.interview_feedback',

                                        'ii.id',
                                        'ii.content'
                                    )
                                    ->join("$interviewResultT as ir", 'ir.interview_id', '=', 'is.interview_id')
                                    ->join("$interviewInfoT as ii", 'ii.id', '=', 'is.interview_content_choice')
                                    ->where('is.interview_check_ox', 'o')
                                    ->where('ir.student_login_id', $login_id)
                                    ->orderBy('is.interview_date')
                                    ->get(); 
         

            $norminateStatus = DB::table("$applyT as a")
                                ->select(
                                    'm.org_matchings_id',
                                    'c.org_company_id',

                                    'c.org_name',
                                    'c.org_name_kana',

                                    'ei.employment_id',
                                    'ei.employment_name',

                                    'a.apply_id',
                                    'a.student_acceptance_ox',
                                    'a.professor_acceptance_ox'
                                    
                                )
                                ->join("$employment_infoT as ei", 'ei.employment_id', '=', 'a.employment_id')
                                ->join("$matchingT as m", 'm.org_matchings_id', '=', 'ei.org_matching_foreign')
                                ->join("$companyT as c", 'c.org_company_id', '=', 'm.org_company_id')
                                ->where('a.student_login_id', $login_id)
                                //->where('a.apply_permission_ox', 'o')
                                ->where('a.acceptance_ox', 'o')
                                ->get();

            $finalCompanyInfo = $this->getFinalCompany($login_id);
         
        }


        return ['applyStatus'=>$applyStatus, 
        'interviewStatus'=>$interviewStatus, 
        'norminateStatus'=>$norminateStatus, 
        'appliedEmploymentList'=>$applied_employment_id, 
        'finalCompanyInfo'=>$finalCompanyInfo, 
        'interviewEmploymentList'=>$interview_employment_id];
    }

    //학생 내정, 승낙 회사
    public function getFinalCompany($login_id){
        if($login_id == null)
            return null;
        
        $applyT="applies";
        $employment_infoT = 'employment_infos';
        $matchingT = 'org_matchings';
        $companyT = 'org_companies';

        $norminateStatus = DB::table("$applyT as a")
                                ->select(
                                    'm.org_matchings_id',
                                    'c.org_company_id',

                                    'c.org_name',
                                    'c.org_name_kana',

                                    'ei.employment_id',
                                    'ei.employment_name',

                                    'a.apply_id',
                                    'a.student_acceptance_ox',
                                    'a.professor_acceptance_ox'
                                )
                                ->join("$employment_infoT as ei", 'ei.employment_id', '=', 'a.employment_id')
                                ->join("$matchingT as m", 'm.org_matchings_id', '=', 'ei.org_matching_foreign')
                                ->join("$companyT as c", 'c.org_company_id', '=', 'm.org_company_id')
                                ->where('a.student_login_id', $login_id)
                                ->where('a.acceptance_ox', 'o')
                                ->where('a.apply_permission_ox', 'o')
                                ->where('a.student_acceptance_ox', 'o')
                                ->where('a.professor_acceptance_ox', 'o')
                                ->get();
        if(count($norminateStatus) == 0)
            return null;
        else 
            return $norminateStatus[0];
    }

    //내정 수락
    public function setAcceptance(Request $req){
        $login_id = $req->login_id;
        $apply_id = $req->apply_id;
        $table="applies";
        
        DB::table($table)->where('student_login_id', $login_id)->update(['student_acceptance_ox'=>'x']);
        DB::table($table)->where('student_login_id', $login_id)->where('apply_id', $apply_id)->update(['student_acceptance_ox' => 'o']);

        //푸시알림 및 누적 알림
        $apiKey = $req->api_key;
        $pushNotify = new PushNotify();
        $professorId = DB::table('students')->where('login_id', $login_id)->select('name', 'professor_login_id')->get();
        foreach($professorId as $professorIds){
            $pushNotify->push_select_send($login_id, $professorIds->professor_login_id, $apiKey, "$professorIds->name 학생이 내정을 수락하였습니다.");
        }
        return ['returnBool'=>true];

    }
    
    //내정 사퇴
    public function setResignation(Request $req){
        $login_id = $req->login_id;
        $resign_apply_list = $req->resign_apply_list;
        $table="applies";

        foreach($resign_apply_list as $value){
            $apply_id = $value['apply_id'];
            $resign_id = $value['resign_id'];

            DB::table($table)
                ->where('student_login_id', $login_id)
                ->where('apply_id', $apply_id)
                ->update(['student_acceptance_ox'=>'x', 'resign_id'=>$resign_id]);
        }

        //푸시알림 및 누적 알림
        $apiKey = $req->api_key;
        $pushNotify = new PushNotify();
        $professorId = DB::table('students')->where('login_id', $login_id)->select('name', 'professor_login_id')->get();
        foreach($professorId as $professorIds){
            $pushNotify->push_select_send($login_id, $professorIds->professor_login_id, $apiKey, "$professorIds->name 학생이 내정을 사퇴하였습니다.");
        }

        return ['returnBool'=>true];
    }

    //결정 취소
    public function removeDecision(Request $req){
        $login_id = $req->login_id;
        $apply_id = $req->apply_id;
        $student_acceptance_ox = $req->student_acceptance_ox;
        $table="applies";
        
        DB::table($table)->where('student_login_id', $login_id)->where('apply_id', $apply_id)->update(['student_acceptance_ox' => NULL]);
        
        //푸시알림 및 누적 알림
        $apiKey = $req->api_key;
        $pushNotify = new PushNotify();
        $professorId = DB::table('students')->where('login_id', $login_id)->select('name', 'professor_login_id')->get();
        foreach($professorId as $professorIds){
            if($student_acceptance_ox == 'o'){
                $pushNotify->push_select_send($login_id, $professorIds->professor_login_id, $apiKey, "$professorIds->name 학생이 내정 승낙을 취소하였습니다.");
            }else{
                $pushNotify->push_select_send($login_id, $professorIds->professor_login_id, $apiKey, "$professorIds->name 학생이 내정 사퇴를 취소하였습니다.");
            }
        }

        return ['returnBool'=>true];
    }

    //프로필 정보 뽑기
    public function getProfileInfo(Request $req){
        
        $login_id = $req->login_id;

        $students = 'students';
        $country_codes = 'country_codes';

        //기본 정보
        $student_profile = DB::table("$students as std")
                                    ->select(
                                        'std.student_no',
                                        'std.student_code',
                                        'std.name',
                                        'std.name_eng',
                                        'std.name_kanji',
                                        'std.name_kana',
                                        'std.admission_date',
                                        'std.graduate_date',
                                        'std.email',
                                        'std.employ_ox',
                                        'std.employ_year', //학생이 건들 수 없음
                                        'std.profile_photo_url', 
                                        'std.birth_date', 
                                        'std.group_part_content',
                                        'std.gender',
                                        'std.credit',
                                        'std.github_url',
                                        'std.address',
                                        'std.phone',
                                        'std.sub_phone',
                                        'std.army_ox',
                                         DB::raw('YEAR(CURRENT_TIMESTAMP) - YEAR(std.birth_date) - (RIGHT(CURRENT_TIMESTAMP, 5) < RIGHT(std.birth_date, 5)) as age'),
                                        'coc.continent',
                                        'coc.country_code',
                                        'coc.country_eng',
                                        'coc.country_kana')
                                    ->leftjoin("$country_codes as coc", 'coc.country_code', '=', 'std.country_code')
                                    ->where('login_id', '=', $login_id)->get();

        $student_profile = $student_profile[0];
        
        if($student_profile->graduate_date != null)
            $student_profile->graduate_date = date('Y-m', strtotime($student_profile->graduate_date));
        
        if($student_profile->admission_date != null)
            $student_profile->admission_date = date('Y-m', strtotime($student_profile->admission_date));
        
        $finalCompanyInfo = $this->getFinalCompany($login_id);
                            
        return ['student_profile'=>$student_profile, 'finalCompanyInfo'=> $finalCompanyInfo];
    }

    //프로필 수정 
    public function updateProfileInfo(Request $req){
        $login_id = $req->login_id;
        $profile_edit_info = $req->profile_edit_info;
        $students = 'students';

        try{
            $returnBool = DB::table($students)->where('login_id', '=', $login_id)->update($profile_edit_info);
            return ['returnBool'=>$returnBool];
        }catch(\Exception $e){
            $returnBool = false;
            return ['returnBool'=>$returnBool];
        }
    }

    //학생 소속 정보 뽑기
    public function getAffiliationInfo(Request $req){

        $login_id = $req->login_id;
        $students = 'students';
        $org_colleges = 'org_colleges';
        $faculties = 'faculties';
        $groups = 'groups';
        $professors = 'professors';

        $affiliation_info = DB::table("$students as s") 
                                ->select(
                                    'p.name as professor_name',
                                    'p.email as professor_email',
                                    'p.major as professor_major',
                                    
                                    'g.group_id', 
                                    'g.group_num',
                                    'g.group_name',
                                    'g.project_title',
                                    'g.project_content', 
                                    'g.showcase_year', 

                                    'f.department_name',
                                    'f.faculty_id',
                                    'f.major_name',
                                    'f.class_name',
                                    'f.college_category_sub',

                                    'o.country_code',
                                    'o.college_alias',
                                    'o.org_college_id',
                                    'o.org_name',
                                    'o.org_name_kana',
                                    'o.college_category',
                                    'o.credit_total'

                                    )
                                ->join("$professors as p", 's.professor_login_id', '=', 'p.login_id')
                                ->join("$groups as g", 's.group_id', '=', 'g.group_id') 
                                ->join("$faculties as f", 'g.faculty_id' , '=', 'f.faculty_id')  
                                ->join("$org_colleges as o", 'f.org_college_id' , '=', 'o.org_college_id')
                                ->where('s.login_id', '=', $login_id)
                                ->get();  

        if(count($affiliation_info)==0)
            return;

           
            
        $faculty_id = $affiliation_info[0]->faculty_id;

        $group_list = DB::table($groups)
                        ->where('faculty_id', $faculty_id)
                        ->where('showcase_year', date('Y'))
                        ->get();
            
        return ['affiliation_info'=>$affiliation_info[0], 'group_list'=>$group_list];
    }

    //getVideoSrc
    public function getVideoSrc(Request $req){
        $login_id = $req->login_id;
        $table = 'students';

        $videoSrc = DB::table($table)->select('pr_video_url')->where('login_id', $login_id)->get()[0]->pr_video_url;
        return ['videoSrc'=>$videoSrc];
    }

    public function updateVideoSrc(Request $req){
        $login_id = $req->login_id;
        $editedVideoSrc = $req->editedVideoSrc;
        $table = 'students';
        try{
            DB::table($table)->where('login_id', $login_id)->update(['pr_video_url'=> $editedVideoSrc]);
            return ['returnBool'=> true];
        }catch(\Exception $e){
            return ['returnBool'=> false]; 
        }
    }
 
    //공인 어학 정보 뽑기
    public function getLangInfo(Request $req){
        $login_id = $req->login_id;

        $students = 'students';
        $student_lang = DB::table($students)
                                ->select(
                                        'JLPT', 'JLPT_acquisition_date', 
                                        'JPT', 'JPT_acquisition_date', 
                                        'TOEIC', 'TOEIC_acquisition_date', 
                                        'TOEFL', 'TOEFL_acquisition_date',
                                        'mock_TOEIC', 'mock_TOEIC_acquisition_date'
                                        )
                                ->where('login_id', '=', $login_id)
                                ->get();

        $student_lang = $student_lang[0];  

        if($student_lang->JLPT_acquisition_date != null)
            $student_lang->JLPT_acquisition_date = date('Y-m', strtotime($student_lang->JLPT_acquisition_date));

        if($student_lang->JPT_acquisition_date != null)
            $student_lang->JPT_acquisition_date = date('Y-m', strtotime($student_lang->JPT_acquisition_date));
        
        if($student_lang->TOEIC_acquisition_date != null)
            $student_lang->TOEIC_acquisition_date = date('Y-m', strtotime($student_lang->TOEIC_acquisition_date));
        
        if($student_lang->TOEFL_acquisition_date != null)
            $student_lang->TOEFL_acquisition_date = date('Y-m', strtotime($student_lang->TOEFL_acquisition_date));
        
        if($student_lang->mock_TOEIC_acquisition_date != null)
            $student_lang->mock_TOEIC_acquisition_date = date('Y-m', strtotime($student_lang->mock_TOEIC_acquisition_date));
        
        return ['student_lang_info'=>$student_lang];
    }

    //공인 어학 수정
    public function updateLangInfo(Request $req){
        $login_id = $req->login_id;
        $langInfo = $req->langInfo;

        $students = "students";
        try{
            $returnBool = DB::table($students)->where('login_id', '=', $login_id)->update($langInfo);
            return['returnBool'=>$returnBool];
        }catch(\Exception $e){
            return['returnBool'=>$returnBool];
        }
    }       

    //기타 외국어 뽑기
    public function getLangMatchInfo(Request $req){
        $login_id = $req->login_id;

        $languages = 'languages';
        $langInfo = 'language_infos';
        $langLevel ='language_levels';

        $language_match = DB::table("$languages as l")
                            ->select('l.language_code', 'l.language_level_code', 'li.language', 'li.language_kana', 'll.language_level')
                            ->join("$langInfo as li", 'li.no', '=','l.language_code')
                            ->join("$langLevel as ll", 'll.no', '=', 'l.language_level_code')
                            ->where('student_login_id', '=', $login_id)
                            ->get();

        return $language_match;
    }

    //기타 외국어 수정
    public function updateLangMatchInfo(Request $req){

        $table = 'languages';
        $login_id = $req->login_id;
   

        // //기타 외국어 정보 초기화
        $rowCount = DB::table($table)->where( 'student_login_id', $login_id)->get()->count();
        if($rowCount != 0)
             DB::table($table)->where('student_login_id', $login_id)->delete();

        //insert_arr 생성
        $lang_match = $req->lang_match;
        $returnBool = false;
        if(count($lang_match) != 0){
            $insert_arr = [];
            foreach($lang_match as $value){
                $language_code = $value['language_code'];
                $language_level_code = $value['language_level_code'];
                $insert_arr[] = ['student_login_id'=>$login_id, 'language_code'=>$language_code,'language_level_code'=>$language_level_code]; 
            }
            
            //기타 외국어 정보 삽입
            $returnBool = DB::table($table)->insert($insert_arr);  
        }
      

        return ['returnBool'=>$returnBool];
    }

   //학생 스킬 정보 뽑기
    public function getSkillMatchInfo(Request $req){    
        $login_id = $req->login_id;

        $skill_matchT = 'skills';
        $skill_levelT = 'skill_levels';
       

        $skill_match_print = collect(DB::table("$skill_matchT as s")
                                        ->select(
                                            "s.language_code", 
                                            's.language_level_code',
                                            'sl.skill_level'
                                        )->join("$skill_levelT as sl", 'sl.no', '=', 's.language_level_code')
                                        ->where('s.student_login_id', '=', $login_id)
                                        ->get()     
                                )->keyBy('language_code');

        $skill_infoT = 'skill_infos';
        $skillList = DB::table($skill_infoT)->select('no')->get();
        foreach($skillList as $skill){
            $skill_code = $skill->no;

            $row = DB::table($skill_matchT)
                        ->select(
                            'language_level_code'
                        )
                        ->where('student_login_id', '=', $login_id)
                        ->where('language_code', '=', $skill_code)
                        ->get(); 
            if(count($row) == 0)
                $skill_match[$skill_code] = [ 'language_code'=>$skill_code, 'language_level_code'=>1];
            else
                $skill_match[$skill_code] = [ 'language_code'=>$skill_code, 'language_level_code'=>$row[0]->language_level_code ];

            //\Log::info($row);

        
        }

        // $skill_match = collect(DB::table($skill_matchT)
        //                         ->select(
        //                             "language_code", 
        //                             'language_level_code'
        //                         )->where('student_login_id', '=', $login_id)
        //                         ->get()
        //                 )->keyBy('language_code');     

        \Log::info($skill_match_print);
        return ['skill_match_print'=>$skill_match_print, 'skill_match'=>$skill_match];
    }

    //학생 스킬 정보 수정
    public function updateSkillMatchInfo(Request $req){
        $table = "skills";
        $login_id = $req->login_id;
        $skill_match = $req->skill_match;

        // 스킬 정보 초기화
        $rowCount = DB::table($table)->where( 'student_login_id', $login_id)->get()->count();
        if($rowCount != 0)
             DB::table($table)->where('student_login_id', $login_id)->delete();

        //스킬 정보 삽입
        $returnBool = DB::table($table)->insert($skill_match);  

        return ['returnBool'=>$returnBool];

    }

    //학생 보유 자격, 흥미 있는 분야, 일본 취업 동기, 자기 pr 문장 뽑기
    public function getAppealInfo(Request $req){
        $login_id = $req->login_id;

        $mainT = 'students';
        $interested_match = 'student_interested_field';

        $student_contents = DB::table($mainT)
                                ->select(
                                    'qualification_content', 
                                    'motivation_content', 
                                    'interested_field_content', 
                                    'pr_content'
                                )->where('login_id', $login_id)
                                ->get();

        $student_interested_match = DB::table($interested_match)
                                        ->select(
                                            'business_small_id'
                                        )->where('student_login_id',$login_id)
                                        ->get();

        return ['student_contents' => $student_contents[0], 'student_interested_match' => $student_interested_match];

    }   

    //학생 보유 자격, 흥미 있는 분야, 일본 취업 동기, 자기 pr 문장 수정
    public function updateAppealInfo(Request $req){
        $login_id = $req->login_id;
        $key = $req->key;
        $student_content = $req->student_content;

        $mainT = 'students';

        if($key == 'interested_field_content'){
            //초기화
            $student_interested_table = 'student_interested_field';
            DB::table($student_interested_table)->where('student_login_id',$login_id)->delete();
            $student_interested_match = $req->student_interested_match;

            if(count($student_interested_match) != 0){
                //넣어주기
                $insert_arr = [];
                foreach($student_interested_match as $value)
                    $insert_arr[] = ['student_login_id'=>$login_id, 'business_small_id'=>$value];
                
                DB::table($student_interested_table)->insert($insert_arr);
            }
        }

        $returnBool = DB::table($mainT)->where('login_id', $login_id)->update([$key=>$student_content]);
        return['returnBool'=>true];
    }

    //프로필 이미지 업로드
    public function updateProfileImage(Request $req){
        $table = 'students';

        $login_id = $req->login_id;

        //프로필 이미지 파일 저장
        $fileController = new FileController();
        $profileImage = $req->profileImage; 

        

        if($profileImage['data'] != null){
            $photo_url = $req->photo_url;
            \Log::info($photo_url);
            $fileController->deleteFile($photo_url);

            $input_arr['profile_photo_url'] = $fileController->createFile($profileImage['type'], $profileImage['data'], 'profileImages', $login_id.'_profileImage');
            DB::table($table)->where('login_id', $login_id)->update($input_arr);
            return ['returnBool' => true];
        }else{
            return ['returnBool' => false];
        }

    }

    //지원 가능 기업 + 채용건 리스트업
    public function getEmploymentList(Request $req){
        // $login_id = $req->login_id;
        // $org_college_id = $this->getOrgCollegeId($login_id);

        $org_college_id = $req->orgId;
        $companyList =  $this->getCompanyList($org_college_id);
                            
        return ['companyList' => $companyList];
    }

    //학교와 매칭된 기업 리스트 뽑기
    public function getCompanyList($org_college_id){

        $org_matching = "org_matchings";
        $org_company = "org_companies";
        $employment_info ="employment_infos";
        $thisYear = date('Y');

        $companyList =  DB::table("$org_matching as om")
                            ->select(
                                'om.org_matchings_id',
                                'oc.org_company_id', 
                                'om.employment_decision_ox',
                                'oc.org_name', 
                                'org_name_kana',
                                'oc.photo_url', 
                                'ei.employment_id', 
                                'ei.employment_name',
                                'ei.working_area',
                                'ei.acceptance_fixed_ox',
                                'ei.apply_open_date',
                                'ei.apply_deadline_date',
                                DB::raw('count(a.employment_id) as student_count')
                            )
                            ->join("$org_company as oc", 'oc.org_company_id', '=', 'om.org_company_id')
                            ->leftjoin("$employment_info as ei", 'ei.org_matching_foreign', '=', 'om.org_matchings_id')
                            ->leftjoin("applies as a", 'a.employment_id', 'ei.employment_id')
                            ->groupBy('ei.employment_id')
                            ->where('om.org_college_id', '=', $org_college_id)
                            ->where('om.matching_date', '=', $thisYear)
                            ->get();

        return $companyList;
    }

    public function searchCompanyEmploymentList(Request $req){

        $org_matching = "org_matchings";
        $org_company = "org_companies";
        $employment_info ="employment_infos";
        $thisYear = date('Y');

        $org_college_id = $req->orgId;
        \Log::info($org_college_id);
        $searchReq = $req->searchReq;

        //기업 정보

        //사업 대분류
        if(count($searchReq['big_infos']) == 0 ){
            $searchReq['big_infos'] = DB::table('business_big_infos')->pluck('id');
        }

        
        //사업 소분류
        if(count($searchReq['small_infos']) == 0){
            $searchReq['small_infos'] = DB::table('business_small_infos')->pluck('id');
        }
    
        //채용 정보
        //원하는 인재상 
        if(count($searchReq['desired_employments_infos']) == 0){
            $searchReq['desired_employments_infos'] = DB::table('desired_employment_infos')->pluck('id');
        }

        //복리 후생
        if(count($searchReq['welfare_content_infos']) == 0){
            $searchReq['welfare_content_infos'] = DB::table('welfare_infos')->pluck('id');
        }

        //업무 내용
        if(count($searchReq['work_matchings_infos']) == 0){
            $searchReq['work_matchings_infos'] = DB::table('work_infos')->pluck('id');
        }
        
        $companyList =  DB::table("$org_matching as om")
                            ->select(
                                'oc.org_company_id', 
                                'om.employment_decision_ox',
                                'oc.org_name', 
                                'org_name_kana',
                                'oc.photo_url', 
                                'ei.employment_id', 
                                'ei.employment_name',
                                'ei.working_area',
                                'ei.acceptance_fixed_ox',
                                'ei.apply_open_date',
                                'ei.apply_deadline_date',
                                DB::raw('count(distinct a.apply_id) as student_count')
                            )
                            ->join("$org_company as oc", 'oc.org_company_id', '=', 'om.org_company_id')
                            //사업 대분류
                            ->leftJoin('business_bigs as bb', 'bb.org_company_id', 'oc.org_company_id')
                            ->join('business_big_infos as bbi', 'bbi.id', 'bb.business_big_id')
                            //사업 소분류
                            ->leftJoin('business_smalls as bs', 'bs.org_company_id', 'oc.org_company_id')
                            ->join('business_small_infos as bsi', 'bsi.id', 'bs.business_small_id')
                            
                            ->leftJoin("$employment_info as ei", 'ei.org_matching_foreign', '=', 'om.org_matchings_id')
                            //인재상
                            ->leftJoin('desired_employments as de', 'de.employment_id', 'ei.employment_id')
                            ->join('desired_employment_infos as dei', 'dei.id', 'de.desired_employment_id')
                            
                            //복지
                            ->leftJoin('welfares as w', 'w.employment_id', 'ei.employment_id')
                            ->join('welfare_infos as wi', 'wi.id', 'w.welfare_id')

                            //업무 내용
                            ->leftJoin('work_matchings as wm', 'wm.employment_id', 'ei.employment_id')
                            ->join('work_infos as woi', 'woi.id', 'wm.work_id')

                            ->leftjoin("applies as a", 'a.employment_id', 'ei.employment_id')

                            //->groupBy('oc.org_company_id')
                            ->groupBy('a.employment_id')

                            
                            //기업
                            // ->where(function($query) use($searchReq, $org_college_id, $thisYear){
                                //사원수
                                ->whereBetween('oc.worker_count', $searchReq['worker_count_infos'])
                                //사업 대분류
                                ->whereIn('bb.business_big_id', $searchReq['big_infos'])
                                //사업 소분류
                                ->whereIn('bs.business_small_id', $searchReq['small_infos'])


                                ->where('om.org_college_id', $org_college_id)
                                ->where('om.matching_date', $thisYear)
                            // })

                            //채용건
                            // ->where(function($query) use($searchReq, $org_college_id, $thisYear){
                                //페이
                                ->whereBetween('ei.pay', $searchReq['pay_infos'])
                                //원하는 인재상
                                ->whereIn('de.desired_employment_id', $searchReq['desired_employments_infos'])
                                //복지
                                ->whereIn('w.welfare_id', $searchReq['welfare_content_infos'])
                                //업무 종류
                                ->whereIn('wm.work_id', $searchReq['work_matchings_infos'])
                                // $query->where('om.org_college_id', $org_college_id);
                                // $query->where('om.matching_date', $thisYear);
                            // })
                            ->get();

        
        return $companyList;
    }

    //지원하기 체크
    public function createApplyCheck(Request $req){
        
        $login_id = $req->login_id;
        $employment_id = $req->employment_id;
        $org_matchings_id = $req->org_matchings_id;
     

        //0. 학생이 해당 지원년도의 자격이 있는지 체크
        $studentT = 'students';
        $resultArr0 = DB::table($studentT)->select('employ_year')->where('login_id', $login_id)->get();
        if($resultArr0[0]->employ_year != date('Y'))
            return ['returnBool'=>false, 'returnCode'=>0, 'employ_year'=>$resultArr0[0]->employ_year];


        //1. 이미 지원한 채용건일 때 튕기기
        $apply = "applies";
        $resultArr1 = DB::table($apply)->where('student_login_id', '=', $login_id)->where('employment_id', $employment_id)->get();
        if(count($resultArr1) == 1){
            return ['returnBool'=>false, 'returnCode'=>1];
        }



        //2. 지원기간이 아닐때 튕기기
        $realTime = date('Y-m-d H:i:s');
        $table = 'employment_infos';
        $resultArr2 = DB::table($table)
                        ->whereDate( 'apply_open_date', '<=', $realTime)
                        ->whereDate('apply_deadline_date', '>=', $realTime)
                        ->where('employment_id', '=', $employment_id)
                        ->get();

        if(count($resultArr2) == 0)
            return ['returnBool'=>false, 'returnCode'=>2];
        

        

        //3. 등록된 이력서가 없을 때 튕기기
        $entrySheetArr = $this->getFolderEntries($login_id, 'entrySheets');
        if(!isset($entrySheetArr[$employment_id]))
            return ['returnBool' => false, 'returnCode'=>3];
        else
            $entrySheet = $entrySheetArr[$employment_id];


        $portfolioArr = $this->getFolderEntries($login_id, 'portfolios');
        if(isset($portfolioArr[$employment_id]))
            $portfolio = $portfolioArr[$employment_id];
        else
            $portfolio = null;

        //4.해당 채용건의 부모의 매칭이 채용 미결정일때
        $employment_decision_ox= DB::table('org_matchings')
                                    ->select('employment_decision_ox')
                                    ->where('org_matchings_id', $org_matchings_id)
                                    ->get();
        if(count($employment_decision_ox) == 0){
            return ['returnBool'=> false];
        }else{
            $employment_decision_ox = $employment_decision_ox[0]->employment_decision_ox;
            if($employment_decision_ox != 'o'){
                return ['returnBool'=> false, 'returnCode'=>4];
            }
        }

        //4. 등록된 파일 보여주고 지원할지 말지 받기
        return ['returnBool'=>true, 'entrySheet'=>$entrySheet, 'portfolio'=>$portfolio];

    }

    //지원하기
    public function createApply(Request $req){
        $table = "applies";
        $employment_id = $req->employment_id;
        $login_id = $req->login_id;
        $apiKey = $req->apiKey;
       // try{
            $returnBool = DB::table($table)->insert(['employment_id'=>$employment_id, 'student_login_id'=>$login_id, 'apply_permission_ox'=>null]);

            if($returnBool == true){
                //푸시알림 및 누적 알림
            $pushNotify = new PushNotify();
            $professorId = DB::table('students')
                            ->where('login_id', $login_id)
                            ->select('name', 'professor_login_id')
                            ->get();
            $company_name = DB::table('employment_infos')
                            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
                            ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
                            ->select('org_companies.org_name')
                            ->where('employment_infos.employment_id', '=', $employment_id)
                            ->get();
            foreach($professorId as $professorIds){
                foreach($company_name as $company_names){
                    $pushNotify->push_select_send($login_id, $professorIds->professor_login_id, $apiKey, "$professorIds->name 학생이 $company_names->org_name 사에 지원하였습니다..");
                    break;
                }
            }
        }

            return ['returnBool'=> $returnBool];
        // }catch(\Exception $e){
        //     $returnBool = false;
        //     return ['returnBool'=> $returnBool];
        // }
    }

    //지원 취소
    public function deleteApply(Request $req){
        
        $login_id = $req->login_id;
        $apply_id = $req->apply_id;
        $table = "applies";

        try{
            DB::table($table)->where('apply_id', $apply_id)->delete();
            \Log::info($apply_id);
            return ['returnBool'=>true];
        }catch(\Exception $e){
            return ['returnBool'=>false];
        }
    }

    //폴더 추가
    public function createFolder(Request $req){

        $login_id = $req->login_id;
        $table = 'students';
        $resultArr = DB::table($table)->select('name_eng')->where('login_id', $login_id)->get();


        //존재 하지 않는 학생이면 리턴
        if(count($resultArr) == 0) 
            return ['returnBool'=>false, 'returnCode'=>0];
        
        //영어이름을 등록하지 않았으면 리턴
        $name_eng = $resultArr[0]->name_eng;
        if($name_eng == null)
            return ['returnBool'=>false, 'returnCode'=>1];

        $createdFolder = $req->createdFolder;

        $employment_id = $createdFolder['employment_id'];
        // $company_name = DB::table('employment_infos as ei')
        //                     ->select('oc.org_name_kana')
        //                     ->join('org_matchings as om', 'om.org_matchings_id', 'ei.org_matching_foreign')
        //                     ->join('org_companies as oc', 'oc.org_company_id', 'om.org_company_id')
        //                     ->where('ei.employment_id', $employment_id)
        //                     ->get()[0]->org_name_kana;

        //이미 존재하는 폴더이면 리턴
        $isFolderExist = $this->isFolderAreadyExist($login_id, $employment_id, 'entrySheets');
        if($isFolderExist)
            return ['returnBool'=>false, 'returnCode'=>2];
        

        $entrySheet = $createdFolder['entrySheet'];
        $portfolio = $createdFolder['portfolio'];

        $fileController = new FileController();
        $fileController->createFile($entrySheet['type'], $entrySheet['data'], "/repository/$login_id/entrySheets", $employment_id."_".$name_eng."_entrySheet");
        if($portfolio['data'] != null)
            $fileController->createFile($portfolio['type'], $portfolio['data'], "/repository/$login_id/portfolios",$employment_id.'_'.$name_eng.'_portfolio');

        return ['returnBool'=>true];
    }

    //폴더 삭제
    public function deleteFolder(Request $req){
        $entrySheet_url = $req->entrySheet_url;
        $portfolio_url = $req->portfolio_url;
        

        
        //폴더 삭제
        $fileController = new FileController();

        $b1 = $fileController->deleteFile($entrySheet_url);
        if($portfolio_url != null)
            $b2 = $fileController->deleteFile($portfolio_url);
        else 
            $b2 = true;

        if($b1 && $b2){
            return ['returnBool'=>true];
        }else{
            return ['returnBool'=>false];
        }
    }

    //폴더 수정
    public function updateFolder(Request $req){
        $login_id = $req->login_id;
        $table = 'students';
        $resultArr = DB::table($table)->select('name_eng')->where('login_id', $login_id)->get();
        if(count($resultArr) == 0) 
            return ['returnBool'=>false, 'returnCode'=>1];
        
        $name_eng = $resultArr[0]->name_eng;

        $updatedFolder = $req->updatedFolder;

        $employment_id = $updatedFolder['employment_id'];
        $entrySheet = $updatedFolder['entrySheet'];
        $portfolio = $updatedFolder['portfolio'];

        $fileController = new FileController();

        $returnBool1 = false;
        if($entrySheet['data'] != null){
            $fileController->deleteFile($entrySheet['url']);
            $fileController->createFile($entrySheet['type'], $entrySheet['data'], "/repository/$login_id/entrySheets", $employment_id."_".$name_eng."_entrySheet");
            $returnBool1 = true;
        }

        $returnBool2 = false;

        if($portfolio['data'] !=  null){
            $fileController->deleteFile($portfolio['url']);
            $fileController->createFile($portfolio['type'], $portfolio['data'], "/repository/$login_id/portfolios", $employment_id."_".$name_eng."_portfolio");
            $returnBool2 = true;
        }else{
            if($portfolio['name'] ==  null){
                $fileController->deleteFile($portfolio['url']);
                $returnBool2 = true;
            }
        }

        return ['returnBool'=>($returnBool1 || $returnBool2)];
    }


    //폴더 목록 뽑기
    public function getRepositoryInfo(Request $req){
        $login_id = $req->login_id;


        $repository = $this->readRepository($login_id);
        $repository_info = $repository['repository_info'];
        $employment_id_list = $repository['employment_id_list'];

        $org_college_id = $this->getOrgCollegeId($login_id);
        $repository_employment_list = $this->getRepositoryEmploymentList($org_college_id, $employment_id_list);
        $repository_list = $this->getEmploymentInfo($employment_id_list);
        //\Log::info($repository_employment_list);

        return ['repository_list'=>$repository_list,'repository_info'=>$repository_info, 'repository_employment_list'=>$repository_employment_list];
    }

    //학생 폴더 entry확인 및 출력
    public function readRepository($login_id){

       $entrySheet_list = $this->getFolderEntries($login_id, 'entrySheets');
       $portfolio_list = $this->getFolderEntries($login_id, 'portfolios');

       //이력서 파일 폴더를 기준으로 이력서를 등록한 employment_id 뽑기
       $employment_id_list = array_keys($entrySheet_list);

       $repository_info = array();
       foreach($employment_id_list as $employment_id){

            //학생이 지원한 employment_id인지 확인
            $count = DB::table("applies")
                        ->where('employment_id', $employment_id)
                        ->where('student_login_id', $login_id)
                        ->get()
                        ->count();

            if($count == 0)
                $apply_ox = "x";
            else
                $apply_ox = "o";

            $entrySheet_url = $entrySheet_list[$employment_id]['path'];
            $entrySheet_name = $entrySheet_list[$employment_id]['name'];

            $portfolio_url = null;
            $portfolio_name = null;

            if(isset($portfolio_list[$employment_id])){
                $portfolio_url = $portfolio_list[$employment_id]['path'];
                $portfolio_name = $portfolio_list[$employment_id]['name'];
            }

            $repository_info[$employment_id] = [
                'employment_id'=>$employment_id, 
                'entrySheet_url'=>$entrySheet_url, 
                'entrySheet_name'=>$entrySheet_name, 
                'portfolio_url'=>$portfolio_url,
                'portfolio_name'=>$portfolio_name,
                'apply_ox'=>$apply_ox
            ];
       }

       return ['employment_id_list'=>$employment_id_list, 'repository_info'=>$repository_info];

    }

    //폴더 내용물 읽기
    public function getFolderEntries($login_id, $folderName){

        //로컬용
        $root_path = public_path();

        //서버용
        //$root_path = '/home/ubuntu/storage';

        $whole_path = "$root_path/repository/$login_id/$folderName";

        //로컬용
        $relative_path = "/repository/$login_id/$folderName";

        //서버용
        //$relative_path = "/storage/repository/$login_id/$folderName";

        $file_list = array();

        //폴더 오픈
        $openFolder = dir($whole_path);

        //폴더 읽기
        while( $entry = ($openFolder->read()) ){
            if(strpos($entry, '.pdf'))
                $file_list[] = $entry;
        }
        
        //폴더 닫기
        $openFolder->close();

        // \Log::info($file_list);
        // '.' , '..' 지우기
        // unset($file_list[0]);
        // unset($file_list[1]);

        //\Log::info($file_list);

        
    
        $folderInfo = array();

        foreach($file_list as $file){
            $employment_id = (int)explode('_',$file)[0];

            $folderInfo[$employment_id] = ['path'=>"$relative_path/$file", 'name'=>$file]; 
        }

        
        return $folderInfo;
    }

    //이미 있는 폴더인지 확인
    public function isFolderAreadyExist($login_id,$employment_id, $folderName){
        
        //로컬용
        $root_path = public_path();

        //서버용
        //$root_path = '/home/ubuntu/storage';

        $whole_path = "$root_path/repository/$login_id/$folderName";

        //로컬용
        $relative_path = "/repository/$login_id/$folderName";

        //서버용
        //$relative_path = "/storage/repository/$login_id/$folderName";

        $file_list = array();

        //폴더 오픈
        $openFolder = dir($whole_path);

        //폴더 읽기
        while( $entry = ($openFolder->read()) ){
            $file_list[] = $entry;

            $folder_id = (int)explode('_',$entry)[0];

            if($folder_id == $employment_id){
                return true;
            }

        }

        return false;
    }


    //소속 학교 아이디 뽑기
    public function getOrgId(Request $req){
        $login_id = $req->login_id;
        $org_college_id = $this->getOrgCollegeId($login_id);

        return ['org_college_id'=>$org_college_id];
    }

    //학생의 소속 org_college_id뽑기
    public function getOrgCollegeId($login_id){
        $student = 'students';
        $group = 'groups';
        $faculty = 'faculties';
        $org_college = 'org_colleges';
        $org_college_id = DB::table("$student as s")
                            ->select('o.org_college_id')
                            ->join("$group as g", 'g.group_id' , '=', 's.group_id')
                            ->join("$faculty as f", 'f.faculty_id', '=', 'g.faculty_id')
                            ->join("$org_college as o", 'o.org_college_id', '=', 'f.org_college_id')
                            ->where('s.login_id','=', $login_id)
                            ->get();

        $org_college_id = $org_college_id[0]->org_college_id;

        return $org_college_id;
    }

    //학생이 이력서를 올릴수 있는 채용건 리스트 뽑기
    public function getRepositoryEmploymentList($org_college_id, $employment_id_list){

        $org_matching = "org_matchings";
        $org_company = "org_companies";
        $employment_info ="employment_infos";
        $thisYear = date('Y');
        $today = date('Y-m-d H:i:s');

        $repositoryEmploymentList =  DB::table("$org_matching as om")
                                        ->select(
                                            'om.org_matchings_id',
                                            'oc.org_company_id', 
                                            'oc.org_name', 
                                            'oc.org_name_kana',
                                            'ei.employment_id', 
                                            'ei.employment_name',
                                            'ei.apply_open_date',
                                            'ei.apply_deadline_date'
                                        )
                                        ->join("$org_company as oc", 'oc.org_company_id', '=', 'om.org_company_id')
                                        ->join("$employment_info as ei", 'ei.org_matching_foreign', '=', 'om.org_matchings_id')
                                        ->where('om.org_college_id', '=', $org_college_id)
                                        ->where('om.matching_date', '=', $thisYear)
                                        ->whereDate('ei.apply_deadline_date', '>', $today)
                                        ->whereNotIn('ei.employment_id', $employment_id_list)
                                        ->get();

        return $repositoryEmploymentList;
    }

    //employment_id로 employment 정보 가져오기
    public function getEmploymentInfo($employment_id_list){
        $org_matchingT = 'org_matchings';
        $org_companyT = 'org_companies';
        $employment_infoT = 'employment_infos';

        $repository_list = DB::table("$employment_infoT as ei")
                                ->select(
                                    'ei.employment_id',
                                    'om.org_matchings_id',
                                    'oc.org_company_id',
                                    'ei.employment_name',
                                    'oc.org_name',
                                    'oc.org_name_kana',
                                    'ei.apply_deadline_date'
                                )
                                ->join("$org_matchingT as om", 'om.org_matchings_id', '=', 'ei.org_matching_foreign')
                                ->join("$org_companyT as oc", 'oc.org_company_id', '=', 'om.org_company_id')
                                ->whereIn('ei.employment_id', $employment_id_list)
                                ->get();

        return $repository_list;
    }






}
