<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class CollegeInfoController extends Controller
{
    // 학교 리스트 뽑기 
    public function collegeList() {
        // 현재 년도
        $nowYear = date('Y');

        $college_info = DB::table('org_colleges as orc')
        ->select(
            'orc.org_college_id',       // 학교 아이디
            'cds.country_eng',          // 국가
            'orc.org_name',             // 학교 명
            'orc.org_name_kana',        // 학교 명 카타카나
            //'orc.photo_url',            // 포토 URL   -> 요구사항에 없음
            //'orc.address_city',         // 주소(도시) -> 요구사항에 없음
            //'orc.address',              // 상세 주소  -> 요구사항에 없음
            //'orc.latitude',             // 지도 -> 좌표값
            //'orc.longitude',            // 지도 -> 좌표값
            //'orc.homepage_url',         // 홈피 URL
            //'orc.college_category',     // 일반대 OR 전문대
            'ora.org_agent_id as org_agent_id',  // 에이전트 아이디
            'ora.org_name as org_agent_name'     // 에이전트 명
        )
        ->join('country_codes as cds', 'cds.country_code', '=', 'orc.country_code')
        ->join('agent_college_matchings as acm', 'orc.org_college_id', '=', 'acm.org_college_id')
        ->join('org_agents as ora', 'ora.org_agent_id', '=', 'acm.org_agent_id')
        // 조건 -> 학교와 에이전트가 채용의사를 밝혔을 경우(에이전트가 데려온 학교인 경우) AND 채용의사를 밝힌 년도가 현재년도 일 시
        ->where([
            ['acm.matching_date', '=', $nowYear],
        ])
        ->get();

        // 학교가 없을 시
        if(!isset($college_info[0])) return 0;

        return $college_info;
    }
     /**
     * 학교 정보 뽑기
     * @return Array [college_name => array('college_info' => college_info, 'faculty' => faculty_info)]
     */
    public function selectCollegeInfo() {
        // 선택 대학교 아이디
        $manager_id = request('collegeId');
        // 현재 년도
        $nowYear = date('Y');
        
        // 학교 정보 
        // 왼쪽 부분    => 학교 명, 국가, 담당 에이전트 명, 
        // 오른 쪽 부분 => 학교 명, 담당 에이전트 명, 지도 좌표값(latitude, longitude), 
        $college_info = DB::table('org_colleges as orc')
            ->select(
                'orc.org_college_id',       // 학교 아이디
                'cds.country_eng',          // 국가
                'orc.org_name',             // 학교 명
                'orc.org_name_kana',        // 학교 명 카타카나
                'orc.photo_url',            // 포토 URL   -> 요구사항에 없음
                'orc.address_city',         // 주소(도시) -> 요구사항에 없음
                'orc.address',              // 상세 주소  -> 요구사항에 없음
                'orc.latitude',             // 지도 -> 좌표값
                'orc.longitude',            // 지도 -> 좌표값
                'orc.homepage_url',         // 홈피 URL
                'orc.college_category',     // 일반대 OR 전문대
                'ora.org_agent_id as org_agent_id',  // 에이전트 아이디
                'ora.org_name as org_agent_name'     // 에이전트 명
            )
            ->join('country_codes as cds', 'cds.country_code', '=', 'orc.country_code')
            ->join('agent_college_matchings as acm', 'orc.org_college_id', '=', 'acm.org_college_id')
            ->join('org_agents as ora', 'ora.org_agent_id', '=', 'acm.org_agent_id')
            // 조건 -> 학교와 에이전트가 채용의사를 밝혔을 경우(에이전트가 데려온 학교인 경우) AND 채용의사를 밝힌 년도가 현재년도 일 시
            ->where([
                ['acm.matching_date', '=', $nowYear],
                ['orc.org_college_id', '=', $manager_id],
            ])
            ->get();

            // 학교가 없을 시
            if(!isset($college_info[0])) return 0;

            // Array () => Key : collegeName Value -> collegeInfo, facultyList
            $info = [];


            // 학교안에 있는 계열 구하기
            foreach($college_info as $collegeList) {
                
                $facultyList = DB::table('faculties')
                ->select(
                    'faculty_id',       // 계열 아이디
                    'department_name'  // 계열 이름
                )
                ->where([
                    ['org_college_id', '=', $collegeList->org_college_id],
                ])
                ->get();

                $faculty_Std_Count = [];

                foreach($facultyList as $fac) {
                    $group = DB::table('groups')
                    ->where('faculty_id', '=', $fac->faculty_id)
                    ->pluck('group_id');

                    // 학생수
                    $facultyStudentCount = 0;

                    foreach($group as $gp) {
                        $count = DB::table('students')
                        ->where([
                            ['group_id', '=', $gp],
                            ['employ_year', '=', $nowYear],
                        ])
                        ->count();

                        $facultyStudentCount += $count;
                    }
                    array_push($faculty_Std_Count, $facultyStudentCount);
                }
                
            
                
                array_push($info, array($collegeList->org_college_id => array('info' => $collegeList, 'faculty' => $facultyList, 'stdList' => $faculty_Std_Count)));
                
            }
        // return => array('학교이름' => array('학교정보' => value, '계열정보' => value))
        return $info;
    }

    /**
     * 계열 @@클릭 시@@ 계열 내의 정보
     *  (해당년도 총 학생, 취업상태 중인 학생 => 남녀 비율, JLPT 취득 현황 AND 반정보)
     * 
     */
    public function facultyDetailInfo() {
        // 계열 -> 그룹 -> 학생
        // 해당 faculty 아이디를 가진 group들을 찾아서 학생수 카운트
        $faculty_id = request('faculty');
        
        $nowYear = date('Y');
        // 해당 계열의 그룹 아이디를 받음 => 물론 배열임
        $groupList = DB::table('groups')
        ->where([
            ['faculty_id', '=', $faculty_id],
            ['showcase_year', '=', $nowYear],
        ])
        ->select(
            'group_id',         // 그룹 아이디
            'group_num',        // 그룹 넘버
            'group_name',       // 그룹 이름
            'project_title',    // 프로젝트 명
            'project_content',  // 프로젝트 내용
            'project_video_url' // 프로젝트 발표 영상 url
        )
        ->get();
        // 계열 정보들
        $collegeStdCount = 0;       // 학생 총 수
        $collegeEmployCount = 0;    // 학생 취업활동 중인 수
        $collegeMstdCount = 0;      // 남자
        $collegeWstdCount = 0;      // 여자
        $collegeStudentJLPT3 = 0;   // 3급
        $collegeStudentJLPT2 = 0;   // 2급
        $collegeStudentJLPT1 = 0;   // 1급

        $stdList = [];

        $std = DB::table('students as std')
            ->join('groups as gp', 'std.group_id', '=', 'gp.group_id')
            ->join('faculties as fac', 'gp.faculty_id', '=', 'fac.faculty_id')
            ->where([
                ['fac.faculty_id', '=', $faculty_id],
                ['employ_year', '=', $nowYear],
            ])
            ->select(
                'std.login_id',
                'std.name_eng',
                'std.name_kana',
                'std.profile_photo_url',
                'std.group_id',
                'std.JLPT',
                'std.japanese_speaking_level',
                'std.personality_grade',
                'std.sincerity_grade',
                'std.major_grade',
                'std.employment_end_ox',
                DB::raw("TRUNCATE((TO_DAYS(now())-(TO_DAYS(std.birth_date)))/365, 0) as age"),
                'gp.group_id',
                'gp.group_num',
                'gp.group_name'
            )
            ->get();
        
        // 학생들을 뽑아줘야함 list = group_id
        foreach($groupList as $list) {
            // $std = DB::table('students as std')
            // ->join('groups as gp', 'std.group_id', '=', 'gp.group_id')
            // ->where('std.group_id', '=', $list->group_id)
            // ->select(
            //     'std.login_id',
            //     'std.name_eng',
            //     'std.name_kana',
            //     'std.profile_photo_url',
            //     'std.group_id',
            //     'std.JLPT',
            //     'std.japanese_speaking_level',
            //     'std.personality_grade',
            //     'std.sincerity_grade',
            //     'std.major_grade',
            //     DB::raw("TRUNCATE((TO_DAYS(now())-(TO_DAYS(std.birth_date)))/365, 0) as age"),
            //     'gp.group_id',
            //     'gp.group_num',
            //     'gp.group_name'
            // )
            // ->get();

            // array_push($stdList, $std);
            // 총 학생 수
            $studentCount = DB::table('students')
            ->where([
                ['group_id', '=', $list->group_id],
                ['employ_year', '=', $nowYear],
            ])
            ->count();

            // 취업활동 중인 학생 수
            $employCount = DB::table('students')
            ->where([
                ['group_id', '=', $list->group_id],
                ['employment_end_ox', '=', 'o'],
                ['employ_year', '=', $nowYear],
            ])
            ->count();

            // 여학생 추출
            $wstudentCount = DB::table('students')
            ->where([
                ['group_id', '=', $list->group_id],
                ['gender', '=', 'w'],
                ['employ_year', '=', $nowYear],
            ])
            ->count();

            // 남학생 추출
            $mstudentCount = DB::table('students')
            ->where([
                ['group_id', '=', $list->group_id],
                ['gender', '=', 'm'],
                ['employ_year', '=', $nowYear],
            ])
            ->count();

            // JLPT 급수 별 
            $studentJLPT3 = DB::table('students')
            ->where([
                ['group_id', '=', $list->group_id],
                ['JLPT', '=', 3],
                ['employ_year', '=', $nowYear],
            ])
            ->count();

            // JLPT 급수 별 
            $studentJLPT2 = DB::table('students')
            ->where([
                ['group_id', '=', $list->group_id],
                ['JLPT', '=', 2],
                ['employ_year', '=', $nowYear],
            ])
            ->count();

            // JLPT 급수 별 
            $studentJLPT1 = DB::table('students')
            ->where([
                ['group_id', '=', $list->group_id],
                ['JLPT', '=', 1],
                ['employ_year', '=', $nowYear],
            ])
            ->count();

            // 나온값들 카운터해서 변수에 저장
            // 계열 총 학생 수
            $collegeStdCount += $studentCount;
            // 계열 취업활동 중인 학생 수
            $collegeEmployCount += $employCount;
            // 계열 총 남자 수
            $collegeMstdCount += $mstudentCount;
            // 계열 총 여자 수
            $collegeWstdCount += $wstudentCount;
            // 계열 JLPT 3급 학생 수
            $collegeStudentJLPT3 += $studentJLPT3;
            // 계열 JLPT 2급 학생 수
            $collegeStudentJLPT2 += $studentJLPT2;
            // 계열 JLPT 1급 학생 수
            $collegeStudentJLPT1 += $studentJLPT1;
            
        }


        // 계열 정보 AND 그룹 리스트
        return array(
            'facultyInfo' => array($collegeStdCount, $collegeEmployCount, $collegeMstdCount, $collegeWstdCount, $collegeStudentJLPT3, $collegeStudentJLPT2, $collegeStudentJLPT1), 
            'groupList'   => $groupList,
            'stdList'     => $std,
        );
    }

    public function studentList() {
        
        // faculty ID
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
}
