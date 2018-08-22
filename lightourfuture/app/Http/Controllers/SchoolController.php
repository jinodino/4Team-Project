<?php
//by. hyojin
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\FileController;

class SchoolController extends Controller
{
    //에이전트가 아니면 튕기는 기능 추가해야함
    
    public function create(Request $req){
        $table = 'org_colleges';
        $facultiesTable = "faculties";
        $org_colleges = $req->org_colleges;
        $matchingTable = 'agent_college_matchings';

        if(count($req->faculties) == 0){
            return ['returnBool'=>false, 'returnCode' => 0];
        }

        do{
            //college id 생성
            $org_college_id = 'col'.rand(1, 999999);

            //orgs 테이블에 id 넣기 전에 유니크 값인지 확인
            $row_count = DB::table('orgs')
                        ->where('org_id' , $org_college_id)
                        ->get()
                        ->count();

            if($row_count == 0){
                $org_colleges['org_college_id'] = $org_college_id;
                DB::table('orgs')->insert(['org_id' => $org_college_id, 'org_classification'=>'college']);
                break;
            }
                
        }while(true);
    
       
        //college id 프라이머리 예외처리 방법 2가지
        //1. where 절로 같은 아이디 있으면 랜덤 다시 돌리기
           

        //프로필 이미지 저장
        $fileController = new FileController();
        $profileImage = $req->profileImage;

        if($profileImage['data'] != null)
            $org_colleges['photo_url'] = $fileController->createFile($profileImage['type'], $profileImage['data'], 'profileImages', $org_college_id.'_progileImage');

        DB::table($table)->insert($org_colleges);

        //학과 저장
        $returnBool = $this->createFaculty($req->faculties, $org_college_id, null);
        if(!$returnBool){

            DB::table($table)->where('org_college_id', $org_college_id)->delete();
            DB::table('orgs')->where('org_id', $org_college_id)->delete(); 

            if(isset($org_colleges['photo_url'])){
                $fileController->deleteFile($org_colleges['photo_url']);
            }

            return ['returnBool'=>false, 'returnCode'=>1];
        }
        //agent-company 관계 테이블에 insert
        $host_org_agent_id = $req->host_org_agent_id;
        $year = date('Y');
        DB::table($matchingTable)->insert(['org_agent_id'=>$host_org_agent_id, 'org_college_id'=>$org_college_id, 'matching_date'=> $year]);

        return ['org_college_id'=> $org_college_id, 'org_agent_id'=>$host_org_agent_id, 'returnBool'=>true];
    }
    

    //학교 정보 수정
    public function update(Request $req){
        $table = 'org_colleges';
        $facultiesTable = "faculties";
        $groupsTable = "groups";
        $org_college_id = $req->org_college_id;
        $org_colleges = $req->org_colleges;
        unset($org_colleges['org_college_id']);


        //프로필 이미지 저장
        $fileController = new FileController();
        $profileImage = $req->profileImage;

        if($profileImage['data'] != null){
            if($profileImage['photo_url'] != null){
                $fileController->deleteFile($profileImage['photo_url']);
            }
            $org_colleges['photo_url'] = $fileController->createFile($profileImage['type'], $profileImage['data'], 'profileImages', $org_college_id.'_profileImage');
        }

        DB::table($table)->where('org_college_id', $org_college_id)->update($org_colleges);

        //학과 저장
        $faculties = $req->faculties;
        $facultiesArr = array();
        $facultyIdArr = [];
        foreach( $faculties as $faculty){
            if(!isset($faculty['faculty_id']))
                $facultiesArr[] = $faculty;
            else{
                $facultyIdArr[] = $faculty['faculty_id'];
                DB::table('faculties')
                    ->where('faculty_id', $faculty['faculty_id'])
                    ->update([ 
                        'college_category_sub'=>$faculty['college_category_sub'],
                        'department_name'=>$faculty['department_name'],
                        'major_name'=>$faculty['major_name'],
                        'class_name'=>$faculty['class_name'],
                        'major_id'=>$faculty['major_id']
                    ]);
            }
        }
        if(count($facultiesArr) != 0){
            $returnBool = $this->createFaculty($facultiesArr, $org_college_id, $facultyIdArr);
            return ['returnBool'=>$returnBool, 'returnCode'=>1];
        }



        return ['org_college_id'=> $org_college_id, 'returnBool'=>true];

    }


    //학교 정보 가져오기
    public function getCollegeInfo(Request $req){

        $thisYear = date('Y');
        $org_college_id = $req->org_college_id;


        $org_colleges = 'org_colleges';
        $faculties = 'faculties';
        $groups = 'groups';
        $professors = 'professors';  

        //일반 학교 정보 + 학생수, 취활 완료 학생수
        $collegeInfo = DB::table("$org_colleges")
                            ->select(
                                '*'
                            )
                            ->where('org_college_id', $org_college_id)
                            ->first();

        //전체 학생수 : student_count, 취활 종료 학생수:student_end_count, 

        //내정 승낙수 : acceptance_ok_count, 내정수 : acceptance_count, 
        //전체 채용건수 : employment_count, 끝난 채용건 수 : employment_end_count


        //학과 정보 학생수 취활 완료 학생수
        $facultyInfo = DB::table("$faculties")
                            ->select(
                                '*'
                            )
                            ->where('org_college_id', '=', $org_college_id)
                            ->get();

        $professorInfo = [];
        $groupInfo = [];
        foreach($facultyInfo as $faculty){
            $faculty_id = $faculty->faculty_id;

            //교수 정보
            $professorInfo[$faculty_id] = DB::table($professors)
                                            ->where('faculty_id', $faculty_id)
                                            ->get();

            //그룹 정보
            $groupInfo[$faculty_id] = DB::table($groups)
                                        ->where('faculty_id', $faculty_id)
                                        ->get();
        }
                
        return ['collegeInfo'=>$collegeInfo, 'facultyInfo'=>$facultyInfo, 'returnBool'=>true];    
    }
    

    //학과 실적 정보 뽑기
    /*
    public function getFacultyResultInfo(Request $req){
        $searchYear = $req->searchYear;
        $org_agent_id = $req->org_agent_id;
        $org_college_id = $req->org_college_id;
        $faculty_id = $req->faculty_id; 

        //해당 학과 학생 아이디 리스트 뽑기
        $studentIdArr = DB::table('faculties as f')
                            ->join('groups as g', 'g.faculty_id', 'f.faculty_id')
                            ->join('students as s', 's.group_id','g.group_id')
                            ->where('f.faculty_id', $faculty_id)
                            ->where('s.employ_year', $searchYear)
                            ->pluck('s.login_id');

        //채용건 리스트
        $employmentInfoList = DB::table('org_matchings as om')
                                    ->select(
                                        'ei.employment_id',
                                        'ei.employment_name',
                                        'ei.acceptance_fixed_ox',
                                        'ei.employment_owari_ox',
                                        'oc.org_company_id',
                                        'oc.org_name',
                                        'oc.org_name_kana',
                                        DB::raw('count(a.apply_id) as apply_count'),
                                        DB::raw('count(CASE WHEN a.acceptance_ox = "o" THEN 1 END) as acceptance_give_count'),
                                        DB::raw('count(CASE WHEN a.acceptance_ox = "o" AND a.student_acceptance_ox = "x" AND a.professor_acceptance_ox = "o" THEN a.apply_id END) as acceptance_consent_count')
                                    )
                                    ->groupBy('ei.employment_id')
                                    ->join('org_companies as oc', 'oc.org_company_id', 'om.org_company_id')
                                    ->join('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                    ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')
                                    ->where('om.org_agent_id', $org_agent_id)
                                    ->where('om.org_college_id', $org_college_id)
                                    ->where('om.matching_date', $searchYear)
                                    ->get();


        $statisticsInfo = DB::table('employment_infos as ei')
                            ->select(
                                DB::raw('count(DISTINCT ei.employment_id) as employment_count'),
                                DB::raw('count(CASE WHEN ei.employment_owari_ox = "o" THEN ei.employment_id END) as employment_owari_count')
                            )
                            ->groupBy('om.org_college_id')
                            ->join('org_matchings as om', 'ei.org_matching_foreign', 'om.org_matchings_id')
                            ->where('om.org_agent_id', $org_agent_id)
                            ->where('om.org_college_id', $org_college_id)
                            ->where('om.matching_date', $searchYear)
                            ->get();

        $employment_count = 0;
        $employment_owari_count = 0;

        if(count($statisticsInfo) != 0){
            $statisticsInfo = $statisticsInfo[0];
            $employment_count = $statisticsInfo->employment_count;
            $employment_owari_count = $statisticsInfo->employment_owari_count;
        }

        $no_acceptance_employment_count = 0;
        foreach($employmentInfoList as $item){
            $employment_id = $item->employment_id;
            $row = DB::table('employment_infos as ei')
                        ->select(
                            DB::raw('count(CASE WHEN a.acceptance_ox = "o" THEN a.apply_id END) - count(CASE WHEN a.acceptance_ox = "o" AND a.student_acceptance_ox = "o" AND a.professor_acceptance_ox = "o" THEN a.apply_id END) as jjongCount')
                        
                        )
                        ->groupBy('ei.employment_id')
                        ->join('applies as a', 'a.employment_id', 'ei.employment_id')
                        ->where('ei.employment_id', $employment_id)
                        ->get();

            if(count($row) != 0){
                $row = $row[0];
                if($row->jjongCount == 0){
                    $no_acceptance_employment_count++;
                }
            }

        }
                            






        return [
            'employmentInfoList' => $employmentInfoList, 
            'employment_count'=>$employment_count,
            'no_acceptance_employment_count'=>$no_acceptance_employment_count,
            'employment_owari_count'=>$employment_owari_count,
        ];
    }
    */


    //에이전트 교수 주소록 뽑기
    /*
    public function getAgentProfessorBook($facultyInfo, $org_agent_id){
        $professorBookInfo = [];

        foreach($facultyInfo as $faculty){
            $faculty_id = $faculty->faculty_id;
            $professorBookInfo[$faculty_id] =  DB::table("agent_books")
                                                    ->select(
                                                        'no',
                                                        'name', 
                                                        'email', 
                                                        'join_ox', 
                                                        'faculty_id' 
                                                        // 'oc.country_code', 
                                                        // 'oc.org_name', 
                                                        // 'org_name_kana'
                                                    )
                                                    // ->join("$facultiesT as f", 'f.faculty_id', '=', 'ab.faculty_id')
                                                    // ->join("$org_collegesT as oc", 'oc.org_college_id', '=', 'f.org_college_id')
                                                    ->where('org_agent_id', $org_agent_id)
                                                    ->where('faculty_id', $faculty_id)
                                                    ->where('join_ox', 'x')
                                                    //->orderBy('ab.no','desc')
                                                    ->get();
        }

        return $professorBookInfo;

    }
    */

    //학교ID에 따른 학과 리스트 얻기
    public function getfacultyList(Request $req){

        $org_college_id = $req->org_college_id;
        $facultyList = DB::table('faculties as f')
                            ->select(
                                'f.*',
                                'm.content as major_tag'
                            )
                            ->join('major_infos as m', 'm.id', 'f.major_id')
                            ->where('f.org_college_id', $org_college_id)
                            ->get();
        $collegeInfo = DB::table('org_colleges')
                            ->select(
                                'org_name',
                                'org_name_kana',
                                'credit_total'
                            )
                            ->where('org_college_id', $org_college_id)
                            ->first();
        if(count($facultyList) == 0){
            return ['returnBool'=>false];
        }else{
            return ['facultyList'=>$facultyList, 'collegeInfo'=>$collegeInfo];
        }
    }

    //학과ID에 따른 학생 리스트 얻기
    public function getStudentList(Request $req){
        $faculty_id = $req->faculty_id;
        $org_agent_id = $req->org_agent_id;
        $year = $req->year;


        //학생 ID LIST 뽑기
        if($year == null){
            $studentIdList = DB::table('students as s')
                                ->join('groups as g', 'g.group_id', 's.group_id')
                                ->where('g.faculty_id', $faculty_id)
                                ->pluck('s.login_id');
        }else {
            $studentIdList = DB::table('students as s')
                                ->join('groups as g', 'g.group_id', 's.group_id')
                                ->where('s.employ_year', $year)
                                ->where('g.faculty_id', $faculty_id)
                                ->pluck('s.login_id');
        }



        foreach($studentIdList as $student_login_id){
            
            $item = null;

            //학생별 지원수 뽑기
            $queryResult = DB::table('students as s')
                                ->select(
                                    's.*',
                                    'g.*',
                                    DB::raw('YEAR(CURRENT_TIMESTAMP) - YEAR(s.birth_date) - (RIGHT(CURRENT_TIMESTAMP, 5) < RIGHT(s.birth_date, 5)) as age'),
                                    DB::raw('count(CASE WHEN a.apply_permission_ox = "o" THEN a.apply_id END) as apply_count')
                                )
                                ->join('groups as g', 'g.group_id', 's.group_id')
                                ->leftJoin('applies as a', 'a.student_login_id', 's.login_id')
                                ->where('s.login_id', $student_login_id)
                                ->groupBy('s.login_id')
                                ->first();


            if($queryResult != null){
                $item = $queryResult;
            }
            
            $studentList[$student_login_id] = $item;



            //학생별 내정수 뽑기
            $queryResult = DB::table('students as s')
                                ->select(
                                    DB::raw('count(CASE WHEN a.acceptance_ox = "o" THEN a.apply_id END ) as acceptance_count')
                                )
                                ->leftJoin('applies as a', 'a.student_login_id', 's.login_id')
                                ->where('s.login_id', $student_login_id)
                                ->groupBy('s.login_id')
                                ->first();


            $acceptance_count = 0;
            if($queryResult != null){
                $acceptance_count = $queryResult->acceptance_count;
            }
            
            $studentList[$student_login_id]->acceptance_count = $acceptance_count;


            //학생별 내정 회사 뽑기
            $queryResult = DB::table('students as s')
                                ->select(
                                    'oc.org_name',
                                    'oc.org_name_kana'
                                )
                                ->leftJoin('applies as a', 'a.student_login_id', 's.login_id')
                                ->join('employment_infos as e','e.employment_id', 'a.employment_id')
                                ->join('org_matchings as om', 'om.org_matchings_id', 'e.org_matching_foreign')
                                ->join('org_companies as oc', 'oc.org_company_id', 'om.org_company_id')
                                ->where('s.login_id', $student_login_id)
                                ->where('a.acceptance_ox', 'o')
                                ->where('a.student_acceptance_ox', 'o')
                                ->where('a.professor_acceptance_ox', 'o')
                                ->groupBy('s.login_id')
                                ->first();

            $final_company_name = null;
            $final_company_name_kana = null;
            if($queryResult != null){
                $final_company_name =  $queryResult->org_name;
                $final_company_name_kana =  $queryResult->org_name_kana;
            }            
            $studentList[$student_login_id]->final_company_name = $final_company_name;
            $studentList[$student_login_id]->final_company_name_kana = $final_company_name_kana;

            
            //학생별 탈락수
            $talrack_count = DB::table('interview_results')
                                ->where('student_login_id', $student_login_id)
                                ->where('interview_result','x')
                                ->groupBy('interview_id')
                                ->get()->count();

            $studentList[$student_login_id]->talrack_count = $talrack_count;

        }


        if(count($studentIdList) == 0){
            return ['returnBool'=>false];
        }else{
            return ['returnBool'=>true, 'studentList'=>$studentList,'org_agent_id'=>$org_agent_id];
        }
    }


    //학과 삭제
    public function deleteFaculty(Request $req){

        $faculty_id = $req->faculty_id;

        //해당 학과에 교수가 있는지 확인
        $professorT = 'professors';
        $proCount = DB::table($professorT)->where('faculty_id',$faculty_id)->get()->count();
        if($proCount > 0)
            return ['returnBool'=> false, 'returnCode' => 1];
        
        //해당 학과에 그룹이 있는지 확인
        $groupT = 'groups';
        $proCount = DB::table($groupT)->where('faculty_id',$faculty_id)->get()->count();
        if($proCount > 1)
            return ['returnBool'=> false, 'returnCode' => 2];


        //해당 학과의 그룹에 학생이 있는지 확인
        $stdT = 'students';
        $groupId = DB::table($groupT)->select('group_id')->where('faculty_id', $faculty_id)->get()[0]->group_id;
        $proCount = DB::table($stdT)->where('group_id', $groupId)->get()->count();
        if($proCount != 0)
            return ['returnBool'=>false, 'returnCode'=> 3];

        $facultyT = 'faculties';
        try{
            DB::table($groupT)->where('group_id', $groupId)->delete(); 
        }catch(\Exception $e){
            return ['returnBool'=> false];
        }

        try{
            DB::table($facultyT)->where('faculty_id', $faculty_id)->delete();
        }catch(\Exception $e){
            return ['returnBool'=> false];
        }

        return ['returnBool'=>true];

    }

    // //다중 매칭 테이블 등록
    // public function multiMatching($table, $itemArr, $main_field, $main_id){
    //     //이미 항목을 가지고 있는 main_id라면....
    //     $rowCount = DB::table($table)->where($main_field, $main_id)->get()->count();
    //     if($rowCount != 0)
    //          DB::Delete("DELETE FROM $table WHERE $main_field = '$main_id'");
        
    //     foreach($itemArr as $item){
    //         $item['org_college_id'] = $main_id;
    //         DB::table($table)->insert($item);
    //     }   
    // }    

    public function createFaculty($itemArr, $main_id, $notInArr){
        \Log::info('학과 생성');
        $facultyT = "faculties";
        $groupT = "groups";

        // DB::table("$groupT as g")->join("$facultyT as f", 'f.faculty_id', 'g.faculty_id')->where('f.org_college_id', $main_id)->delete();
        // DB::table($facultyT)->where('org_college_id', $main_id)->delete();

        $facultyIdList = array();
        foreach($itemArr as $item){
            $item['org_college_id'] = $main_id;

            try{
                $facultyIdList[] = DB::table($facultyT)->insertGetId($item, 'faculty_id');
            }catch(\Exception $e){
                \Log::info('학과 생성 에러');
                // $org_collegeT = 'org_colleges';
                // $orgT = 'orgs';
                if($notInArr != null){
                    DB::table($facultyT)->where('org_college_id', $main_id)->whereNotIn('faculty_id', $notInArr)->delete();
                }else{
                    DB::table($facultyT)->where('org_college_id', $main_id)->delete();
                }
                // DB::table($org_collegeT)->where('org_college_id', $main_id)->delete();
                // DB::table($orgT)->where('org_id', $main_id)->delete(); 

                return false;
            }
        } 

        //디폴트 그룹 만들어 주기
        $thisYear = date('Y');
        foreach($facultyIdList as $key=>$faculty_id){
            \Log::info($faculty_id);
            $insert_arr = ['faculty_id'=>$faculty_id, 'group_num'=>0, 'showcase_year'=>$thisYear];
            DB::table($groupT)->insert($insert_arr);
        }

        return true;
    }
}