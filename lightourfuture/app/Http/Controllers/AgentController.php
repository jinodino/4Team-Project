<?php
//by. hyojin
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\profileImageController;
use App\Http\Controllers\PushNotify as PushNotify;

class AgentController extends Controller
{
    //에이전트 생성
    public function create(Request $req){
        $table = "org_agents";

        do{
            //agent_id id 생성
            $agent_id = 'age'.rand(1, 999999);

            //orgs 테이블에 id 넣기 전에 유니크 값인지 확인
            $row_count = DB::table('orgs')
                        ->where('org_id' , $agent_id)
                        ->get()
                        ->count();

            if($row_count == 0){
                DB::table('orgs')->insert(['org_id' => $agent_id, 'org_classification'=>'agent']);
                break;
            }     

        }while(true);

        $org_agents = $req->org_agents;
        $org_agents['org_agent_id'] = $agent_id;

        //profile Image 저장
        // $profileImage = $req->profileImage;
        // if($profileImage['data'] != null){
        //     $profileImageController = new profileImageController();
        //     $photo_url = $profileImageController->storeAnjung($profileImage, $profileImage['folder']);
        //     $arr['photo_url'] = $photo_url;
        // }
   

        DB::table($table)->insert($org_agents);

        return;
    }

    //소속 에이전트 뽑기
    public function getOrgs(Request $req){
        $table = 'agent_belongs';
        $classification = $req->user['classification'];
        
        //에이전트가 아닐 시 튕겨낸다
        if($classification != 'agent')
            return;

        $login_id = $req->user['login_id'];

        //소속 에이전트의 아이디, 이름
        $returnArr = DB::table('org_agents as oa')
                        ->select('ab.no', 'ab.org_agent_id', 'oa.org_name', 'oa.org_name_kana')
                        ->join('agent_belongs as ab', 'oa.org_agent_id', '=', 'ab.org_agent_id')
                        ->where('ab.agent_id', $login_id)
                        ->orderBy('ab.no')
                        ->get();
       
        return $returnArr;
    }

    //아이디로 에이전트 뽑기
    public function getOrgAgentById(Request $req){
        $org_agent_id = $req->agent_id;
        $table = 'org_agents';

        //소속 에이전트의 아이디, 이름
        $returnArr = DB::table("$table as oa")
                        ->select(
                            'oa.*', 
                            'a.name as manager_name', 
                            'a.name_kana as manager_name_kana',
                            'cc.country_kana'
                        )
                        ->join('agents as a', 'a.login_id', 'oa.manager_login_id')
                        ->leftJoin('country_codes as cc', 'cc.country_code' , 'oa.country_code')
                        ->where('org_agent_id', $org_agent_id)
                        ->first();
        return ['orgAgent'=>$returnArr];
    }

    //에이전트와 연관된 학교 리스트 뽑기
    /*
    public function getOrgRelColInfo(Request $req){

        $org_agent_id = $req->org_agent_id;
        $year = $req->year;

        if($year == null){

            $schoolList = DB::table('agent_college_matchings as ac')
                            ->select(
                                'oc.org_college_id',
                                'oc.country_code',
                                'oc.org_name',
                                'oc.org_name_kana',
                                'oc.college_alias', 
                                'oc.address_city',
                                'cc.country_kana', 
                                'cc.country_eng',
                                DB::raw('Max(ac.matching_date) as matching_date')
                            )
                            ->groupBy('oc.org_college_id')
                            ->join('org_colleges as oc', 'oc.org_college_id', 'ac.org_college_id')
                            ->join('country_codes as cc', 'cc.country_code', 'oc.country_code')
                            ->where('ac.org_agent_id', '=', $org_agent_id)
                            ->get();

            return $schoolList;

        } else {

            $schoolList = DB::table('agent_college_matchings as ac')
                            ->select(
                                'oc.org_college_id',
                                'oc.country_code',
                                'oc.org_name',
                                'oc.org_name_kana',
                                'oc.college_alias', 
                                'oc.address_city'
                            )
                            ->join('org_colleges as oc', 'oc.org_college_id', 'ac.org_college_id')
                            ->where('ac.org_agent_id', '=', $org_agent_id)
                            ->where('ac.matching_date','=', $year)
                            ->get();

            return $schoolList;
        }    
    }
    */

    //에이전트와 매칭한 학교 리스트
    public function getOrgRelColInfo(Request $req){

        $org_agent_id = $req->org_agent_id;
        $thisYear = date('Y');
        $year = $req->year;
        $mode = $req->mode;
        
        //심플 모드일 떄 기업 리스트 뽑기
        if($mode == 'simple'){
            if($year == null){
                $schoolList = DB::table('agent_college_matchings as acm')
                                ->select('oc.*')
                                ->join('org_colleges as oc', 'oc.org_college_id', 'acm.org_college_id')
                                ->where('acm.org_agent_id', $org_agent_id)
                                ->get();

                return $schoolList;

            }else{
                $schoolList = DB::table('agent_college_matchings as acm')
                                ->select('oc.*')
                                ->join('org_colleges as oc', 'oc.org_college_id', 'acm.org_college_id')
                                ->where('acm.org_agent_id', $org_agent_id)
                                ->where('acm.matching_date', $year)
                                ->get();

                return $schoolList;
            }
        }


        //심플 모드가 아닐때
        if($year == null){
            \Log::info('asdfasd');

            $schoolList = DB::table('agent_college_matchings as acm')
                                ->select(
                                    'oc.*',
                                    DB::raw('count(DISTINCT f.faculty_id) as faculty_count'),
                                    // DB::raw("count(DISTINCT CASE WHEN s.employ_year=$thisYear THEN s.login_id END) as student_countttt"),
                                    DB::raw("count(DISTINCT s.login_id) as whoele_student_count"),
                                    DB::raw('Max(acm.matching_date) as max_matching_year')
                                )
                                ->join('org_colleges as oc', 'oc.org_college_id', 'acm.org_college_id')
                                ->leftJoin('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                                ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                                ->leftJoin('students as s', 's.group_id','g.group_id')
                                // ->leftJoin('org_matchings as om', 'om.org_college_id', 'oc.org_college_id')
                                ->groupBy('oc.org_college_id')
                                ->where('acm.org_agent_id', $org_agent_id)
                                ->get();            
        } else {

            $schoolList = DB::table('agent_college_matchings as acm')
                                ->select(
                                    'oc.*',
                                    DB::raw('count(DISTINCT f.faculty_id) as faculty_count'),
                                    DB::raw('count(DISTINCT s.login_id) as whoele_student_count'),
                                    DB::raw('Max(acm.matching_date) as max_matching_year')
                                )
                                ->join('org_colleges as oc', 'oc.org_college_id', 'acm.org_college_id')
                                ->leftJoin('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                                ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                                ->leftJoin('students as s', 's.group_id','g.group_id')
                                // ->leftJoin('org_matchings as om', 'om.org_college_id', 'oc.org_college_id')
                                ->groupBy('oc.org_college_id')
                                ->where('s.employ_year', $year)
                                ->where('acm.org_agent_id', $org_agent_id)
                                ->where('acm.matching_date', $year)
                                ->get();
        }    


        
        foreach($schoolList as $school){
            $org_college_id = $school->org_college_id;
            $max_matching_year = $school->max_matching_year;
            //전체 학생수, 취활 종료 학생수, 내정 낸수, 내정 수락수 ($org_agent_id에 대해 에이전트에 관한 것, 

            //전체 학생수, 내정 완료 학생수 넣어주기
            $row = DB::table('org_colleges as oc')
                        ->select(
                            DB::raw("count(CASE WHEN s.employ_year=$max_matching_year THEN s.login_id END) as student_count"),
                            DB::raw("count(CASE WHEN s.employ_year=$max_matching_year AND s.employment_end_ox = 'o' THEN s.login_id END) as student_end_count")
                        )
                        ->leftJoin('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                        ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                        ->leftJoin('students as s', 's.group_id','g.group_id')
                        ->groupBy('oc.org_college_id')
                        ->where('oc.org_college_id', $org_college_id)
                        ->first();

            //학생수 , 취업 완료 학생수
            $school->student_count = $row->student_count;
            $school->student_end_count = $row->student_end_count;


            //내정수
            $row = DB::table('org_matchings as om')
                        ->select(
                            DB::raw('count(CASE WHEN a.acceptance_ox = "o" THEN 1 END) as acceptance_count'),
                            DB::raw('count(CASE WHEN a.acceptance_ox = "o" AND a.student_acceptance_ox = "o" AND a.professor_acceptance_ox = "o" THEN 1 END) as student_ok_count')
                        )
                        ->join('employment_infos as ei','ei.org_matching_foreign', 'om.org_matchings_id')
                        ->join('applies as a', 'a.employment_id', 'ei.employment_id')
                        ->where('om.org_agent_id', $org_agent_id)
                        ->where('om.org_college_id', $org_college_id)
                        ->where('om.matching_date', $max_matching_year)
                        ->first();

            //총내정수, 내정 수락수
            $school->acceptance_count = $row->acceptance_count;
            $school->student_ok_count = $row->student_ok_count;
        }

        return $schoolList;

    }

    //학교 검색
    public function searchCollege(Request $req){
        
        $org_agent_id = $req->org_agent_id;
        $searchReq = $req->searchReq;

        $agent_college_matchingT = 'agent_college_matchings';
        $org_collegeT = 'org_colleges';
        $facultyT = 'faculties';

        $matching_date = $searchReq['matching_date']; 
        $country_code = $searchReq['country_code'];
        $continent_code = $searchReq['continent_code'];

        if(count($searchReq['college_category']) == 0){
            $searchReq['college_category'] = ['u', 'c'];
        }

        if(count($searchReq['college_category_sub']) == 0){
            $searchReq['college_category_sub'] = [2,3,4,5];
        }

        if(count($searchReq['major_id']) == 0){
            $searchReq['major_id'] = DB::table('major_infos')->pluck('id');
        }


        /*
            if($continent_code == 'ALL' && $country_code == 'ALL'){

                if($matching_date == 'ALL'){

                    $schoolList = DB::table("$org_collegeT as oc")
                                        ->select(
                                            'acm.matching_date',
                                            'oc.*',
                                            'cc.country_kana',
                                            'cc.country_eng'
                                        )
                                        ->join("$agent_college_matchingT as acm", 'acm.org_college_id', 'oc.org_college_id')
                                        ->join("$facultyT as f", 'f.org_college_id', 'oc.org_college_id')
                                        ->join('country_codes as cc', 'cc.country_code', 'oc.country_code')
                                        ->groupBy('oc.org_college_id')
                                        ->where('acm.org_agent_id', $org_agent_id)
                                        ->whereIn('oc.college_category', $searchReq['college_category'])
                                        ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                        ->whereIn('f.major_id', $searchReq['major_id'])
                                        ->get();
                } else{

                    $schoolList = DB::table("$org_collegeT as oc")
                                        ->select(
                                            'acm.matching_date',
                                            'oc.*',
                                            'cc.country_kana',
                                            'cc.country_eng'
                                        )
                                        ->join("$agent_college_matchingT as acm", 'acm.org_college_id', 'oc.org_college_id')
                                        ->join("$facultyT as f", 'f.org_college_id', 'oc.org_college_id')
                                        ->join('country_codes as cc', 'cc.country_code', 'oc.country_code')
                                        ->groupBy('oc.org_college_id')
                                        ->where('acm.org_agent_id', $org_agent_id)
                                        ->where('acm.matching_date', $matching_date)
                                        ->whereIn('oc.college_category', $searchReq['college_category'])
                                        ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                        ->whereIn('f.major_id', $searchReq['major_id'])
                                        ->get();
                }

            } else if($continent_code != 'ALL' && $country_code == 'ALL'){

                if($matching_date == 'ALL'){
                    $schoolList = DB::table("$org_collegeT as oc")
                                        ->select(
                                            'acm.matching_date',
                                            'oc.*',
                                            'cc.country_kana',
                                            'cc.country_eng'
                                        )
                                        ->join("$agent_college_matchingT as acm", 'acm.org_college_id', 'oc.org_college_id')
                                        ->join("$facultyT as f", 'f.org_college_id', 'oc.org_college_id')
                                        ->join('country_codes as cc', 'cc.country_code', 'oc.country_code')
                                        ->groupBy('oc.org_college_id')
                                        ->where('acm.org_agent_id', $org_agent_id)
                                        ->where('cc.continent', $continent_code)
                                        ->whereIn('oc.college_category', $searchReq['college_category'])
                                        ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                        ->whereIn('f.major_id', $searchReq['major_id'])
                                        ->get();
                } else {
                    $schoolList = DB::table("$org_collegeT as oc")
                                        ->select(
                                            'acm.matching_date',
                                            'oc.*',
                                            'cc.country_kana',
                                            'cc.country_eng'
                                        )
                                        ->join("$agent_college_matchingT as acm", 'acm.org_college_id', 'oc.org_college_id')
                                        ->join("$facultyT as f", 'f.org_college_id', 'oc.org_college_id')
                                        ->join('country_codes as cc', 'cc.country_code', 'oc.country_code')
                                        ->groupBy('oc.org_college_id')
                                        ->where('acm.org_agent_id', $org_agent_id)
                                        ->where('cc.continent', $continent_code)
                                        ->where('acm.matching_date', $matching_date)
                                        ->whereIn('oc.college_category', $searchReq['college_category'])
                                        ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                        ->whereIn('f.major_id', $searchReq['major_id'])
                                        ->get();
                }

            }else if($continent_code != 'ALL' && $country_code != 'ALL'){

                if($matching_date == 'ALL'){
                    $schoolList = DB::table("$org_collegeT as oc")
                                        ->select(
                                            'acm.matching_date',
                                            'oc.*',
                                            'cc.country_kana',
                                            'cc.country_eng'
                                        )
                                        ->join("$agent_college_matchingT as acm", 'acm.org_college_id', 'oc.org_college_id')
                                        ->join("$facultyT as f", 'f.org_college_id', 'oc.org_college_id')
                                        ->join('country_codes as cc', 'cc.country_code', 'oc.country_code')
                                        ->groupBy('oc.org_college_id')
                                        ->where('acm.org_agent_id', $org_agent_id)
                                        ->where('oc.country_code', $country_code)
                                        ->whereIn('oc.college_category', $searchReq['college_category'])
                                        ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                        ->whereIn('f.major_id', $searchReq['major_id'])
                                        ->get();
                } else{
                    $schoolList = DB::table("$org_collegeT as oc")
                                        ->select(
                                            'acm.matching_date',
                                            'oc.*',
                                            'cc.country_kana',
                                            'cc.country_eng'
                                        )
                                        ->join("$agent_college_matchingT as acm", 'acm.org_college_id', 'oc.org_college_id')
                                        ->join("$facultyT as f", 'f.org_college_id', 'oc.org_college_id')
                                        ->join('country_codes as cc', 'cc.country_code', 'oc.country_code')
                                        ->groupBy('oc.org_college_id')
                                        ->where('acm.org_agent_id', $org_agent_id)
                                        ->where('oc.country_code', $country_code)
                                        ->where('acm.matching_date', $matching_date)
                                        ->whereIn('oc.college_category', $searchReq['college_category'])
                                        ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                        ->whereIn('f.major_id', $searchReq['major_id'])
                                        ->get();
                }
                    
            }
        */

        // 전체 국가 선택
        if($continent_code == 'ALL' && $country_code == 'ALL'){

            if($matching_date == 'ALL'){
               
                $schoolList = DB::table('agent_college_matchings as acm')
                                    ->select(
                                        'oc.*',
                                        DB::raw('count(DISTINCT f.faculty_id) as faculty_count'),
                                        DB::raw('count(DISTINCT s.login_id) as student_count'),
                                        DB::raw('count(DISTINCT om.org_company_id) as company_count'),
                                        DB::raw('Max(acm.matching_date) as max_matching_year')
                                    )
                                    ->join('org_colleges as oc', 'oc.org_college_id', 'acm.org_college_id')
                                    ->join('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                                    ->join('groups as g', 'g.faculty_id', 'f.faculty_id')
                                    ->leftJoin('students as s', 's.group_id','g.group_id')
                                    ->leftJoin('org_matchings as om', 'om.org_college_id', 'oc.org_college_id')
                                    ->groupBy('oc.org_college_id')
                                    ->where('acm.org_agent_id', $org_agent_id)
                                    ->whereIn('oc.college_category', $searchReq['college_category'])
                                    ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                    ->whereIn('f.major_id', $searchReq['major_id'])
                                    ->get();
        
            } else {
                $schoolList = DB::table('agent_college_matchings as acm')
                                ->select(
                                    'oc.*',
                                    'acm.matching_date',
                                    DB::raw('count(DISTINCT f.faculty_id) as faculty_count'),
                                    DB::raw("count(DISTINCT CASE WHEN s.employ_year=$matching_date THEN s.login_id END) as student_count"),
                                    DB::raw("count(DISTINCT CASE WHEN s.employ_year=$matching_date AND s.employment_end_ox = 'o' THEN s.login_id END) as student_end_count")
                                )
                                ->join('org_colleges as oc', 'oc.org_college_id', 'acm.org_college_id')
                                ->leftJoin('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                                ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                                ->leftJoin('students as s', 's.group_id','g.group_id')
                                ->leftJoin('org_matchings as om', 'om.org_college_id', 'oc.org_college_id')
                                ->groupBy('oc.org_college_id')
                                ->where('acm.org_agent_id', $org_agent_id)
                                ->where('acm.matching_date', $matching_date)
                                ->whereIn('oc.college_category', $searchReq['college_category'])
                                ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                ->whereIn('f.major_id', $searchReq['major_id'])
                                ->get();
            }

        } 
        //전체 대륙 선택
        else if($continent_code != 'ALL' && $country_code == 'ALL'){

            if($matching_date == 'ALL'){
                                
                $schoolList = DB::table('agent_college_matchings as acm')
                                    ->select(
                                        'oc.*',
                                        DB::raw('count(DISTINCT f.faculty_id) as faculty_count'),
                                        DB::raw('count(DISTINCT s.login_id) as student_count'),
                                        DB::raw('count(DISTINCT om.org_company_id) as company_count'),
                                        DB::raw('Max(acm.matching_date) as max_matching_year'),
                                        'cc.country_kana',
                                        'cc.country_eng'
                                    )
                                    ->join('org_colleges as oc', 'oc.org_college_id', 'acm.org_college_id')
                                    ->leftJoin('country_codes as cc', 'cc.country_code', 'oc.country_code')
                                    ->leftjoin('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                                    ->leftjoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                                    ->leftJoin('students as s', 's.group_id','g.group_id')
                                    ->leftJoin('org_matchings as om', 'om.org_college_id', 'oc.org_college_id')
                                    ->groupBy('oc.org_college_id')
                                    ->where('acm.org_agent_id', $org_agent_id)
                                    ->where('cc.continent', $continent_code)
                                    ->whereIn('oc.college_category', $searchReq['college_category'])
                                    ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                    ->whereIn('f.major_id', $searchReq['major_id'])
                                    ->get();

            } else {
                $schoolList = DB::table('agent_college_matchings as acm')
                                    ->select(
                                        'oc.*',
                                        DB::raw('count(DISTINCT f.faculty_id) as faculty_count'),
                                        DB::raw("count(DISTINCT CASE WHEN s.employ_year=$matching_date THEN s.login_id END) as student_count"),
                                        DB::raw("count(DISTINCT CASE WHEN s.employ_year=$matching_date AND s.employment_end_ox = 'o' THEN s.login_id END) as student_end_count")
                                    )
                                    ->join('org_colleges as oc', 'oc.org_college_id', 'acm.org_college_id')
                                    ->leftJoin('country_codes as cc', 'cc.country_code', 'oc.country_code')
                                    ->leftJoin('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                                    ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                                    ->leftJoin('students as s', 's.group_id','g.group_id')
                                    ->leftJoin('org_matchings as om', 'om.org_college_id', 'oc.org_college_id')
                                    ->groupBy('oc.org_college_id')
                                    ->where('s.employ_year', $matching_date)
                                    ->where('acm.org_agent_id', $org_agent_id)
                                    ->where('cc.continent', $continent_code)
                                    ->where('acm.matching_date', $matching_date)
                                    ->whereIn('oc.college_category', $searchReq['college_category'])
                                    ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                    ->whereIn('f.major_id', $searchReq['major_id'])
                                    ->get();
            }

        }
        //하나의 국가 선택
        else if($continent_code != 'ALL' && $country_code != 'ALL'){

            if($matching_date == 'ALL'){

                $schoolList = DB::table('agent_college_matchings as acm')
                                ->select(
                                    'oc.*',
                                    DB::raw('count(DISTINCT f.faculty_id) as faculty_count'),
                                    DB::raw('count(DISTINCT s.login_id) as student_count'),
                                    DB::raw('count(DISTINCT om.org_company_id) as company_count'),
                                    DB::raw('Max(acm.matching_date) as max_matching_year')
                                )
                                ->join('org_colleges as oc', 'oc.org_college_id', 'acm.org_college_id')
                                ->join('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                                ->join('groups as g', 'g.faculty_id', 'f.faculty_id')
                                ->leftJoin('students as s', 's.group_id','g.group_id')
                                ->leftJoin('org_matchings as om', 'om.org_college_id', 'oc.org_college_id')
                                ->groupBy('oc.org_college_id')
                                ->where('acm.org_agent_id', $org_agent_id)
                                ->where('oc.country_code', $country_code)
                                ->whereIn('oc.college_category', $searchReq['college_category'])
                                ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                ->whereIn('f.major_id', $searchReq['major_id'])
                                ->get();

            } else{
                $schoolList = DB::table('agent_college_matchings as acm')
                                    ->select(
                                        'oc.*',
                                        DB::raw('count(DISTINCT f.faculty_id) as faculty_count'),
                                        DB::raw("count(DISTINCT CASE WHEN s.employ_year=$matching_date THEN s.login_id END) as student_count"),
                                        DB::raw("count(DISTINCT CASE WHEN s.employ_year=$matching_date AND s.employment_end_ox = 'o' THEN s.login_id END) as student_end_count")
                                    )
                                    ->join('org_colleges as oc', 'oc.org_college_id', 'acm.org_college_id')
                                    ->leftJoin('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                                    ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                                    ->leftJoin('students as s', 's.group_id','g.group_id')
                                    ->leftJoin('org_matchings as om', 'om.org_college_id', 'oc.org_college_id')
                                    ->groupBy('oc.org_college_id')
                                    ->where('s.employ_year', $matching_date)
                                    ->where('acm.org_agent_id', $org_agent_id)
                                    ->where('oc.country_code', $country_code)
                                    ->where('acm.matching_date', $matching_date)
                                    ->whereIn('oc.college_category', $searchReq['college_category'])
                                    ->whereIn('f.college_category_sub', $searchReq['college_category_sub'])
                                    ->whereIn('f.major_id', $searchReq['major_id'])
                                    ->get();
            }
                
        }

        
        foreach($schoolList as $school){
            $org_college_id = $school->org_college_id;
            $searchYear = null;
            //matching_date가 null이면 max_matching_year 의 학생수 구해서 넣어주기
            if($matching_date == "ALL"){
                $max_matching_year = $school->max_matching_year;
                $searchYear = $max_matching_year;

                //전체 학생수, 내정 완료 학생수 넣어주기
                $row = DB::table('org_colleges as oc')
                            ->select(
                                DB::raw("count(CASE WHEN s.employ_year=$max_matching_year THEN s.login_id END) as student_count"),
                                DB::raw("count(CASE WHEN s.employ_year=$max_matching_year AND s.employment_end_ox = 'o' THEN s.login_id END) as student_end_count")
                            )
                            ->leftJoin('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                            ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                            ->leftJoin('students as s', 's.group_id','g.group_id')
                            ->groupBy('f.faculty_id')
                            ->where('oc.org_college_id', $org_college_id)
                            ->first();

                $school->student_count = $row->student_count;
                $school->student_end_count = $row->student_end_count;
            }else{
                $searchYear = $matching_date;
            }


            //총내정수, 내정 승낙 수 
            $row = DB::table('org_matchings as om')
                        ->select(
                            DB::raw('count(CASE WHEN a.acceptance_ox = "o" THEN 1 END) as acceptance_count'),
                            DB::raw('count(CASE WHEN a.acceptance_ox = "o" AND a.student_acceptance_ox = "o" AND a.professor_acceptance_ox = "o" THEN 1 END) as acceptance_ok_count')
                        )
                        ->join('employment_infos as ei','ei.org_matching_foreign', 'om.org_matchings_id')
                        ->join('applies as a', 'a.employment_id', 'ei.employment_id')
                        ->where('om.org_agent_id', $org_agent_id)
                        ->where('om.org_college_id', $org_college_id)
                        ->where('om.matching_date', $searchYear)
                        ->first();

            //총내정수, 내정 수락수
            $school->acceptance_count = $row->acceptance_count;
            $school->acceptance_ok_count = $row->acceptance_ok_count;
        }

        return $schoolList;
    }

    //학교 리스트 출력 내부 함수
    public function getSchoolList($collegeIdArr, $year){

        if($collegeIdArr == null || count($collegeIdArr) == 0){
            return [];
        }

        $schoolList = [];

        foreach($collegeIdArr as $collegeId){
            if($year == null){
                $school = DB::table('org_colleges as col')
                            ->select(
                                'col.*',
                                DB::raw('count(DISTINCT f.faculty_id) as faculty_count'),
                                DB::raw('count(DISTINCT s.login_id) as student_count'),
                                DB::raw('count(DISTINCT om.org_company_id) as company_count'),
                                'cc.country_kana',
                                'cc.country_eng'
                            )
                            ->groupBy('col.org_college_id')
                            ->leftJoin('country_codes as cc', 'cc.country_code', 'col.country_code')
                            ->leftJoin('faculties as f', 'f.org_college_id', 'col.org_college_id')
                            ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                            ->leftJoin('students as s', 's.group_id','g.group_id')
                            ->leftJoin('org_matchings as om', 'om.org_college_id', 'col.org_college_id')
                            ->where('col.org_college_id', $collegeId)
                            ->get();
            }else {
                $school = DB::table('org_colleges as col')
                                ->select(
                                    'col.*',
                                    DB::raw('count(DISTINCT f.faculty_id) as faculty_count'),
                                    DB::raw('count(s.login_id) as student_count'),
                                    DB::raw('count(DISTINCT om.org_company_id) as company_count'),
                                    DB::raw('count(DISTINCT ei.employment_id) as employment_count'),
                                    'cc.country_kana',
                                    'cc.country_eng'
                                )
                                ->groupBy('col.org_college_id')
                                ->leftJoin('country_codes as cc', 'cc.country_code', 'col.country_code')
                                ->leftJoin('faculties as f', 'f.org_college_id', 'col.org_college_id')
                                ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                                ->leftJoin('students as s', 's.group_id','g.group_id')
                                ->leftJoin('org_matchings as om', 'om.org_college_id', 'col.org_college_id')
                                ->leftJoin('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                ->where('om.matching_date', $year)
                                ->where('s.employ_year', $year)
                                ->where('col.org_college_id', $collegeId)
                                ->get();
            }

            if(count($school) != 0){
                $schoolList[] = $school[0];
            }else{
                $schoolList[] = null;
            }

        }
       
        return $schoolList;
    }

    //올해 영업 체결 학교인지 체크
    public function isThisYearCollege(Request $req){
        $org_agent_id = $req->org_agent_id;
        $org_college_id = $req->org_college_id;
        $thisYear = date('Y');
        $table = 'agent_college_matchings';
        $count = DB::table($table)
                    ->where('matching_date', $thisYear)
                    ->where('org_agent_id', $org_agent_id)
                    ->where('org_college_id', $org_college_id)
                    ->get()
                    ->count();

        $matchingYearList = DB::table($table)
                                ->select('matching_date')
                                ->where('org_college_id', $org_college_id)
                                ->where('org_agent_id', $org_agent_id)
                                ->get();


        if($count == 0){
            return ['isThisYearCollege'=>false, 'matchingYearList' =>$matchingYearList];
        }else{
            return ['isThisYearCollege'=>true, 'matchingYearList' =>$matchingYearList];
        }
    }

    //올해 학교 영업 체결 하기
    public function setThisYearCollege(Request $req){
        $org_agent_id = $req->org_agent_id;
        $org_college_id = $req->org_college_id;
        $thisYear = date('Y');
        $table = 'agent_college_matchings';
        try{
            DB::table($table)->insert(['org_agent_id'=>$org_agent_id, 'org_college_id'=>$org_college_id, 'matching_date'=>$thisYear]);
            return ['returnBool'=>true];
        }catch(\Exception $e){
            return ['returnBool'=>false];
        }
    }

    //올해 영업 체결 기업인지 체크
    public function isThisYearCompany(Request $req){
        $org_agent_id = $req->org_agent_id;
        $org_company_id = $req->org_company_id;
        \Log::info('iscompany'.$org_company_id);

        $thisYear = date('Y');

        $table = 'agent_company_matchings';
        $count = DB::table($table)
                    ->where('matching_date', $thisYear)
                    ->where('org_agent_id', $org_agent_id)
                    ->where('org_company_id', $org_company_id)
                    ->get()
                    ->count();

        $matchingYearList = DB::table($table)
                                ->select('matching_date')
                                ->where('org_company_id', $org_company_id)
                                ->where('org_agent_id', $org_agent_id)
                                ->get();

        if($count == 0){
            return ['isThisYearCompany'=>false, 'matchingYearList'=>$matchingYearList];
        }else{
            return ['isThisYearCompany'=>true, 'matchingYearList'=>$matchingYearList];
        }
    }

    //올해 기업 영업 체결 하기
    public function setThisYearCompany(Request $req){
        $org_agent_id = $req->org_agent_id;
        $org_company_id = $req->org_company_id;
        $thisYear = date('Y');
        $table = 'agent_company_matchings';
        try{
            DB::table($table)->insert(['org_agent_id'=>$org_agent_id, 'org_company_id'=>$org_company_id, 'matching_date'=>$thisYear]);
            return ['returnBool'=>true];
        }catch(\Exception $e){
            return ['returnBool'=>false];
        }
    }

    //학교 리스트 별 학부 리스트 뽑기
    public function getFaculties(Request $req){
        $college_list = $req->college_list;
        $table = "faculties";

        $faculty_list = array();
        foreach($college_list as $college){
            $org_college_id = $college['org_college_id'];
            $faculty_list[$org_college_id] = DB::table($table)->where('org_college_id', $org_college_id)->get();
        }

        return $faculty_list;   
    }

    //에이전트와 연관된 기업 기관 리스트 뽑기
    public function getOrgRelComInfo(Request $req){

        $org_agent_id = $req->org_agent_id;

        $year = $req->year;
        if($year == null){
            $companyList = DB::table('org_companies as oc')
                                ->select(
                                    'oc.*',
                                    'jp.name_kanji as prefecture',
                                    DB::raw('Max(ac.matching_date) as max_matching_year'),
                                    DB::raw('Min(ac.matching_date) as min_matching_year'),
                                    DB::raw('count(CASE WHEN a.acceptance_ox="o" AND a.student_acceptance_ox="o" AND a.professor_acceptance_ox="o" THEN a.apply_id END) as accept_student_count')
                                )
                                ->join('agent_company_matchings as ac', 'oc.org_company_id', '=', 'ac.org_company_id')
                                ->leftJoin('japan_prefectures as jp', 'jp.id','oc.address_to_dou_hu_ken')
                                ->leftJoin('org_matchings as om', 'om.org_company_id', 'oc.org_company_id')
                                ->leftJoin('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')
                                ->groupBy('ac.org_company_id')
                                ->where('ac.org_agent_id', '=', $org_agent_id )
                                ->get();
            return $companyList;

        } else {
            $companyList = DB::table('org_companies as oc')
                                ->select('oc.org_company_id','oc.org_name','oc.org_name_kana', 'ac.matching_date')
                                ->join('agent_company_matchings as ac', 'oc.org_company_id', '=', 'ac.org_company_id')
                                ->where('ac.org_agent_id', '=', $org_agent_id )
                                ->where('ac.matching_date','=', $year)
                                ->get();

            return $companyList;
        }    
    }

    
    //에이전트와 연관된 기업 주소록 리스트 뽑기
    public function getAgentBookCompanyList(Request $req){

        $org_agent_id = $req->org_agent_id;
        $table = 'agent_books';
        $companyBookInfo = DB::table('agent_books as ab')
                                ->select('oc.org_name', 'ab.*')
                                ->join('org_companies as oc', 'oc.org_company_id', 'ab.org_id')
                                ->where('ab.org_agent_id', $org_agent_id)
                                ->where('ab.join_ox', 'x')
                                ->get();

        // $agentBookCompanyList = DB::table("$table as ab")
        //                             ->select(
        //                                 'oc.*'
        //                             )
        //                             ->join('agent_company_matchings as ac', 'ac.org_agent_id', '=', 'ab.org_agent_id')
        //                             ->join('org_companies as oc', 'oc.org_company_id', 'ac.org_company_id')
        //                             ->whereNull('oc.manager_login_id')
        //                             ->where('ab.org_agent_id', '=', $org_agent_id )
        //                             ->get();

        return ['companyBookInfo'=>$companyBookInfo]; 
    }

    //에이전트 주소록 뽑기
    public function getAddressBook(Request $req){
        $org_agent_id = $req->org_agent_id;
        $agent_booksT = 'agent_books';
        $orgT = 'orgs';
        $org_collegesT ='org_colleges';
        $org_companiesT ='org_companies';
        $facultiesT = 'faculties';


        // DB::table()


        // $professor_list =  DB::table("$agent_booksT as ab")
        //                         ->select('ab.no','ab.name', 'ab.email', 'ab.join_ox', 'ab.faculty_id', 'oc.country_code', 'oc.org_name', 'org_name_kana')
        //                         ->join("$facultiesT as f", 'f.faculty_id', '=', 'ab.faculty_id')
        //                         ->join("$org_collegesT as oc", 'oc.org_college_id', '=', 'f.org_college_id')
        //                         ->where('ab.org_agent_id', $org_agent_id)
        //                         ->orderBy('ab.no','desc')
        //                         ->get();

        // $companyAgent_list = DB::table("$agent_booksT as ab")
        //                         ->select('ab.no', 'ab.name', 'ab.email', 'ab.join_ox', 'o.org_id', 'oc.org_name', 'org_name_kana')
        //                         ->join("$orgT as o", 'o.org_id', '=', 'ab.org_id')
        //                         ->join("$org_companiesT as oc", 'oc.org_company_id', '=', 'o.org_id')
        //                         ->where("o.org_classification", 'company')
        //                         ->where('ab.org_agent_id', $org_agent_id)
        //                         ->orderBy('ab.no','desc')
        //                         ->get();

        return ['professor_list'=>$professor_list, 'companyBookInfo'=>$companyAgent_list];
    }


    // 에이전트 주소록 등록
    public function createAddress(Request $req){
        $insertArr = $req->addressItem;
        $insertArr['join_ox'] = 'x';


        $table='agent_books';

        try{
            $returnBool = DB::table($table)->insert($insertArr);
            $professorBookInfo = [];
            $companyBookInfo = [];

            if($req->type == 'p'){
                $professorBookInfo[ $insertArr['faculty_id'] ] = DB::table("agent_books")
                                                                    ->select(
                                                                        'no',
                                                                        'name', 
                                                                        'email', 
                                                                        'join_ox', 
                                                                        'faculty_id' 
                                                                    )
                                                                    ->where('org_agent_id',$insertArr['org_agent_id'])
                                                                    ->where('faculty_id', $insertArr['faculty_id'])
                                                                    ->where('join_ox', 'x')
                                                                    ->get();
            } else if($req->type == 'c'){

                $companyBookInfo = DB::table('agent_books as ab')
                                       ->select('oc.org_name', 'ab.*')
                                       ->join('org_companies as oc', 'oc.org_company_id', 'ab.org_id')
                                       ->where('ab.org_agent_id', $insertArr['org_agent_id'])
                                       ->where('ab.join_ox', 'x')
                                       ->get();
            }
   

            return ['returnBool'=>$returnBool, 'professorBookInfo'=>$professorBookInfo, 'companyBookInfo'=>$companyBookInfo];

        }catch(\Exception $e){
            return ['returnBool'=> false];
        }
    }

    //에이전트 주소록 삭제
    public function deleteAddress(Request $req){
        $classification = $req->classification;
        $org_agent_id = $req->org_agent_id;
        $faculty_id = $req->faculty_id;

        if($classification != 'agent'){
            return ['returnBool'=>false, 'returnCode'=>0];
        }

        $table="agent_books";
        $returnBool = DB::table($table)->where('no', $req->no)->delete();

        if($req->type == 'p'){
            $professorBookInfo[ $req->faculty_id ] = DB::table("agent_books")
                                                        ->select(
                                                            'no',
                                                            'name', 
                                                            'email', 
                                                            'join_ox', 
                                                            'faculty_id' 
                                                        )
                                                        ->where('org_agent_id', $org_agent_id)
                                                        ->where('faculty_id', $faculty_id)
                                                        ->where('join_ox', 'x')
                                                        ->get();

            return ['returnBool'=>$returnBool, 'professorBookInfo'=>$professorBookInfo];

        }else if($req->type == 'c'){
            $companyBookInfo = DB::table('agent_books as ab')
                                    ->select('oc.org_name', 'ab.*')
                                    ->join('org_companies as oc', 'oc.org_company_id', 'ab.org_id')
                                    ->where('ab.org_agent_id', $org_agent_id)
                                    ->where('ab.join_ox', 'x')
                                    ->get();

            return ['returnBool'=>$returnBool, 'companyBookInfo'=>$companyBookInfo, 'org_agent_id'=>$org_agent_id];
        }

    
    }

    //에이전트 - 학교 - 기업 - 기관 매칭 하기  
    public function orgMatching(Request $req){
        $table = 'org_matchings';
        $insertArr['org_agent_id'] = $req->org_agent_id;
        $insertArr['org_college_id'] = $req->org_college_id;
        $insertArr['org_company_id'] = $req->org_company_id;
        $insertArr['matching_date'] = date('Y');
        $insertArr['employment_decision_ox'] = "x";

        try{
            DB::table($table)->insert($insertArr);
            //알림 주기


            //리턴
            return ['returnBool'=>true];
        }
        catch(\Exception $e){
            return ['returnBool'=>false];
        }
    }

    //에이전트 매칭 가져오기
    public function getMatching(Request $req){
        
        $mainTable = 'org_matchings';
        $schoolTable = "org_colleges";
        $companyTable = 'org_companies';

        $employmentTable = "employment_infos";

        $org_agent_id = $req->org_agent_id;
        $year = $req->year;

        if($year == null){

            $matchingList = DB::table("$mainTable as om")
                                ->select( 
                                    'om.org_matchings_id', 
                                    'om.org_agent_id', 
                                    'os.org_college_id', 
                                    'oc.org_company_id', 
                                    'os.college_alias', 
                                    'os.org_name as college_name', 
                                    'os.org_name_kana as college_name_kana', 
                                    'oc.org_name as company_name', 
                                    'oc.org_name_kana as company_name_kana', 
                                    'om.employment_decision_ox', 
                                    'om.matching_date',
                                        DB::raw('count(em.employment_id) as employment_count') 
                                    )
                                ->join("$companyTable as oc", 'om.org_company_id', '=', 'oc.org_company_id')
                                ->join("$schoolTable as os", 'om.org_college_id', '=', 'os.org_college_id')
                                ->leftJoin("$employmentTable as em", 'om.org_matchings_id', '=', 'em.org_matching_foreign')
                                ->where('om.org_agent_id','=', $org_agent_id)
                                ->groupBy('om.org_matchings_id')
                                ->get();
        }else{

            $matchingList = DB::table("$mainTable as om")
                                ->select( 
                                    'om.org_matchings_id', 
                                    'os.college_alias', 
                                    'os.org_name as college_name', 
                                    'os.org_name_kana as college_name_kana', 
                                    'oc.org_name as company_name', 
                                    'oc.org_name_kana as company_name_kana', 
                                    'om.employment_decision_ox', 
                                    'om.matching_date',
                                    DB::raw('count(em.employment_id) as employment_count') 
                                )
                                ->join("$companyTable as oc", 'om.org_company_id', '=', 'oc.org_company_id')
                                ->join("$schoolTable as os", 'om.org_college_id', '=', 'os.org_college_id')
                                ->leftJoin("$employmentTable as em", 'om.org_matchings_id', '=', 'em.org_matching_foreign')
                                ->where('om.org_agent_id','=', $org_agent_id)
                                ->where('om.matching_date', '=', $year)
                                ->groupBy('om.org_matchings_id')
                                ->get(); 
        }


        $employmentList = $this->getEmploymentListByMatchingId($year, $org_agent_id);

        return ['matchingList'=>$matchingList, 'employmentList'=>$employmentList];

    }

    //매칭 아이디별 채용 리스트 뽑기
    public function getEmploymentListByMatchingId($year, $org_agent_id){
        if($year == null){
            $matchingsIdArr = DB::table('org_matchings')
                                ->where('org_agent_id', $org_agent_id)
                                ->pluck('org_matchings_id');
        }else{
            $matchingsIdArr = DB::table('org_matchings')
                                ->where('org_agent_id', $org_agent_id)
                                ->where('matching_date', $year)
                                ->pluck('org_matchings_id');
        }

        $employmentList = [];
        
        \Log::info($matchingsIdArr);
        foreach( $matchingsIdArr as $matchingId ){

            $employmentList[$matchingId] = DB::table('employment_infos  as ei')
                                                ->select(
                                                    'ei.*',
                                                    DB::raw('count(a.apply_id) as student_count')
                                                )
                                                ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')
                                                ->where('ei.org_matching_foreign', $matchingId)
                                                ->groupBy('ei.employment_id')
                                                ->get();
        }

        return $employmentList;
    }

    //학교 아이디별 채용 리스트 뽑기
    public function getEmploymentListByOrgCollegeId(Request $req){
        $org_college_id = $req->org_college_id;
        $org_agent_id = $req->org_agent_id;
        $employmentList = DB::table('org_matchings as om')
                                ->select(
                                    'oc.org_company_id',
                                    'oc.org_name',
                                    'oc.org_name_kana',
                                    'ei.employment_id',
                                    'ei.employment_name',
                                    'ei.apply_open_date',
                                    'ei.apply_deadline_date',
                                    'ei.acceptance_fixed_ox',
                                    'ei.employment_owari_ox',
                                    'ei.whole_interview_count',
                                    DB::raw("Max(CASE WHEN is.interview_active = 'x' THEN is.interview_count END) as interview_count")

                                )
                                ->join('org_companies as oc', 'oc.org_company_id', 'om.org_company_id')
                                ->join('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                ->leftJoin('interview_schedules as is', 'is.employment_id', 'ei.employment_id')
                                ->where('om.org_college_id', $org_college_id)
                                ->where('om.org_agent_id', $org_agent_id)
                                ->groupBy('ei.employment_id')
                                ->get();

        return ['employmentList'=>$employmentList];
    }

    //인터뷰 아이디별 학생 리스트뽑기 
    public function getApplyStudentListByInterviewId(Request $req){
        $interview_id = $req->interview_id;
        $employment_id = $req->employment_id;
        $resultArr = $this->getApplyStudentList($interview_id, $employment_id);
        $studentList = $resultArr['studentList'];
        $passStudentList = $resultArr['passStudentList'];
        $noPassStudentList = $resultArr['noPassStudentList'];
        
        return ['studentList'=>$studentList, 'passStudentList'=>$passStudentList, 'noPassStudentList'=>$noPassStudentList];
    }

    //인터뷰 아이디별 학생 리스트 뽑기 내부 함수
    public function getApplyStudentList($interview_id, $employment_id){

        $studentList = DB::table('interview_results as ir')
                            ->select(
                                's.*',
                                'ir.*',
                                'cc.country_kana',
                                DB::raw('YEAR(CURRENT_TIMESTAMP) - YEAR(s.birth_date) - (RIGHT(CURRENT_TIMESTAMP, 5) < RIGHT(s.birth_date, 5)) as age')
                            )
                            ->join('students as s', 's.login_id', 'ir.student_login_id')
                            ->leftJoin('country_codes as cc', 'cc.country_code', 's.country_code')
                            ->where('ir.interview_id', $interview_id)
                            ->orderBy('ir.interview_result', 'ASC')
                            ->get();
        $passStudentList = [];
        $noPassStudentList = [];
        foreach($studentList as $index=>$student){
            $login_id = $student->login_id;
            $entrySheetList = $this->getFolderEntries($login_id , 'entrySheets');
            $portfolioList = $this->getFolderEntries($login_id , 'portfolios');

            $entrySheet = $entrySheetList[$employment_id];
            $portfolio = [];
            if(isset($portfolioList[$employment_id]))
                $portfolio = $portfolioList[$employment_id];

            $studentList[$index]->entrySheet = $entrySheet;
            $studentList[$index]->portfolio = $portfolio;

            $result = $student->interview_result;
            if($result == 'o'){
                $passStudentList[] = $student;
            }else if( $result == 'x'){
                $noPassStudentList[] = $student;
            }
        }

        return ['studentList'=>$studentList, 'passStudentList'=>$passStudentList, 'noPassStudentList'=>$noPassStudentList];
    }

    //학생들 합격 또는 불합격
    public function setResult(Request $req){
        $interview_id = $req->interview_id;
        $employment_id = $req->employment_id;

        //학생 면접 결과 넣어주기
        $checkStudentIdList = $req->checkStudent_id_list;
        $result = $req->result;
        if($result == 'x'){
            $antiResult = 'o';
        }else{
            $antiResult = 'x';
        }

        foreach($checkStudentIdList as $student_login_id){
            DB::table('interview_results')
                ->where('student_login_id', $student_login_id)
                ->where('interview_id', $interview_id)
                ->update(['interview_result'=>$result]);
        }

        $unCheckStudentList = DB::table('interview_results')
                                ->where('interview_id', $interview_id)
                                ->whereNotIn('student_login_id', $checkStudentIdList)
                                ->pluck('student_login_id');


        foreach($unCheckStudentList as $student_login_id){

            DB::table('interview_results')
                ->where('interview_id', $interview_id)
                ->where('student_login_id', $student_login_id)
                ->whereNull('interview_result')
                ->update(['interview_result'=>$antiResult]);
        }

        $resultArr = $this->getApplyStudentList($interview_id, $employment_id);
        $studentList = $resultArr['studentList'];
        $passStudentList = $resultArr['passStudentList'];
        $noPassStudentList = $resultArr['noPassStudentList'];

        
        // try{

    
        // }catch(\Exception $e){

        //     return ['returnBool'=>false, 'returnCode'=>1];
        // }


        return ['returnBool'=>true, 'studentList'=>$studentList, 'passStudentList'=>$passStudentList, 'noPassStudentList'=>$noPassStudentList];
    }

    //불합격 학생 피드백
    public function setFeedbackNoPassStudent(Request $req){
        $noPassStudentList = $req->noPassStudentList;
        $interview_id = $req->interview_id;
        foreach($noPassStudentList as $student){
            $student_login_id = $student['login_id'];
            $feedback = $student['interview_feedback'];
            DB::table('interview_results')
            ->where('student_login_id', $student_login_id)
            ->where('interview_id', $interview_id)
            ->update(['interview_feedback'=>$feedback]);
        }

        return ['returnBool'=>true];
    }

    //다음 인터뷰로 학생 넘기기, interveiw_active를 다음 차수로 넘기기
    public function setPassNextInterview(Request $req){
        $interview_id = $req->interview_id;
        $passStudentIdList = $req->passStudent_id_list;
        $interview_date = $req->interview_date;

        //현재 차수 active x로 만들기
        DB::table('interview_schedules')
            ->where('interview_id', $interview_id)
            ->update(['interview_active'=>'x']);


        //심사(interview_active)를 다음 면접 차수로 넘기기
        $row = DB::table('interview_schedules')
                    ->select(
                        'interview_count',
                        'employment_id'
                    )
                    ->where('interview_id', $interview_id)
                    ->first();
                    
        $interview_count = $row->interview_count + 1;
        $employment_id = $row->employment_id;

        $result = DB::table('interview_schedules')
                    ->select('interview_id')
                    ->where('employment_id', $employment_id)
                    ->where('interview_count', $interview_count)
                    ->get();

        //다음 차수의 인터뷰가 없을 경우
        if(count($result) == 0)
            return ['returnBool'=>false, 'returnCode'=>1];
       
        $interview_id = $result[0]->interview_id;
        

      //다음 차수의 인터뷰로 학생들 넣어주기
        DB::table('interview_schedules')
            ->where('interview_id', $interview_id)
            // ->where('employment_id', $employment_id)
            // ->where('interview_count', $interview_count)
            ->update(['interview_active'=>'o']);

        foreach($passStudentIdList as $student_login_id){
            DB::table('interview_results')
            ->insert(
                [ 
                    'interview_id'=>$interview_id,
                    'student_login_id'=>$student_login_id, 
                    'interview_start_time'=>$interview_date, 
                    'interview_end_time'=>$interview_date
                ]
            );
        }
        
        //푸시알림 및 누적알림 작성
        $pushNotify = new PushNotify();
        $apiKey = $req->apiKey;
        $id = $req->id;
        
        foreach($passStudentIdList as $passStudentIdLists){

            $company_Name = DB::table('employment_infos')              
                            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
                            ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
                            ->select('org_companies.org_name', 'org_companies.org_name_kana')
                            ->where('employment_infos.employment_id', '=', $employment_id)
                            ->get();
            $temp_interview_count = $interview_count-1;
            foreach($company_Name as $company_Names){
                $org_name = $company_Names->org_name;
                $org_name_kana = $company_Names->org_name;
                //OO사 XX차 면접을 통과하였습니다.=> 학생에게 보내기
                $pushNotify->push_select_send($id, $passStudentIdLists, $apiKey, "$org_name($org_name_kana) 사 $temp_interview_count 차 면접을 통과하였습니다.");
            }
            
            $professorId = DB::table('employment_infos')
                            ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.employment_id')
                            ->join('org_colleges', 'org_colleges.org_college_id', '=', 'org_matchings.org_college_id')
                            ->join('faculties', 'faculties.org_college_id', '=', 'org_colleges.org_college_id')
                            ->join('professors', 'professors.faculty_id', '=', 'faculties.faculty_id')
                            ->select('professors.login_id')
                            ->where('employment_infos.employment_id', '=', $employ_id)
                            ->get();
            $stdName = DB::table('students')
                            ->where('students.login_id', '=', $passStudentIdLists)
                            ->select('students.name')
                            ->get();
            foreach($professorId as $professorIds){
                $professor_id = $professorIds->login_id;
                foreach($stdName as $stdNames){
                    $std_Name = $stdNames->name;
                     foreach($company_Name as $company_Names){
                        $org_name = $company_Names->org_name;
                        $org_name_kana = $company_Names->org_name;
                        //OO학생이 XX사 **차 면접을 통과하였습니다. => 교수에게 보내기
                        $pushNotify->push_select_send($id, $professor_id, $apiKey, "$std_Name 학생이 $org_name($org_name_kana) 사 $temp_interview_count 차 면접을 통과하였습니다.");
                    }
                }
            }
        }
        
        
        
       

        return ['returnBool'=> true];

    }

    //내정 내기
    public function setAcceptance(Request $req){
        $employment_id = $req->employment_id;
        $passStudentIdList = $req->passStudent_id_list;
        $interview_id = $req->interview_id;

        //인터뷰 결과
        DB::table('interview_results')
            ->where('interview_id', $interview_id)
            ->whereIn('student_login_id', $passStudentIdList)
            ->update(['interview_result'=>'o']);

        DB::table('interview_results')
            ->where('interview_id', $interview_id)
            ->whereNotIn('student_login_id', $passStudentIdList)
            ->update(['interview_result'=>'x']);



        //내정 내리기
        // foreach($passStudentIdList as $student_login_id){
        DB::table('applies')
            ->whereIn('student_login_id', $passStudentIdList)
            ->update(['acceptance_ox'=>'o']);
        // }

        \Log::info($interview_id);
        DB::table('interview_schedules')
            ->where('interview_id', $interview_id)
            ->update(['interview_active'=>'x']);

        DB::table('employment_infos')
            ->where('employment_id', $employment_id)
            ->update(['employment_owari_ox'=>'o']);
        
        return ['returnBool'=>true];

    }

    //채용 아이디로 인터뷰 리스트 얻기
    public function getInterviewList(Request $req){
        $employment_id = $req->employment_id;

        $interviewList = DB::table('interview_schedules as is')
                            ->select(
                                'is.*',
                                'ii.id',
                                'ii.content',
                                DB::raw('count(ir.student_login_id) as student_count'),
                                DB::raw('count(CASE WHEN ir.interview_result = "o" THEN ir.student_login_id END) as student_pass_count'),\
                                DB::raw('count(CASE WHEN ir.interview_result = "o" AND ir.interview_feedback != null THEN ir.no END) as feedback_count')
                            )
                            ->join('interview_infos as ii', 'ii.id', 'is.interview_content_choice')
                            ->leftJoin('interview_results as ir', 'ir.interview_id', 'is.interview_id')
                            ->groupBy('is.interview_id')
                            ->where('is.employment_id', $employment_id)
                            ->where('is.interview_check_ox', 'o')
                            ->get();

        return ['interviewList'=>$interviewList];
    }

    //학생 레포지토리 폴더 내용물 읽기
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

    //매칭 아이디로 매칭 가져오기
    public function getMatchingById(Request $req){

        $mainTable = 'org_matchings';
        $schoolTable = "org_colleges";
        $companyTable = 'org_companies';
        $employmentTable = "employment_infos";

        $org_matchings_id = $req->matchingId;

        $matchingRelation = DB::table("$mainTable as om")
                                ->select( 
                                    'om.org_matchings_id', 
                                    'om.org_agent_id', 
                                    'os.org_college_id', 
                                    'oc.org_company_id', 
                                    'os.college_alias', 
                                    'os.org_name as college_name', 
                                    'os.org_name_kana as college_name_kana', 
                                    'oc.org_name as company_name', 
                                    'oc.org_name_kana as company_name_kana'
                                    )
                                ->join("$companyTable as oc", 'om.org_company_id', '=', 'oc.org_company_id')
                                ->join("$schoolTable as os", 'om.org_college_id', '=', 'os.org_college_id')
                                ->where('om.org_matchings_id', $org_matchings_id)
                                ->get(); 

        return $matchingRelation;
    }

    //매칭 지우기
    public function deleteMatching(Request $req){
        $table = "org_matchings";
        $org_matchings_id = $req->org_matchings_id;
        $returnBool = true;
        try{
            $returnBool = DB::table($table)
                            ->where('org_matchings_id', '=', $org_matchings_id)
                            ->delete();
                            
            return ['returnBool'=>$returnBool];
            
        }catch(\Exception $e){
            return['returnBool'=>false];
        }
    }

    //채용 결정
    public function setMatchingDecision(Request $req){
        $table = "org_matchings";
        $org_matchings_id = $req->org_matchings_id;
        $employment_decision_ox = $req->employment_decision_ox;
        //$employment_count = $req->employment_count;

        // if($employment_decision_ox == 'x' && $employment_count > 0){

        //         DB::table("$table as om")
        //             ->select(
        //                 DB::raw('count(a.apply_id) as student_count')
        //             )
        //             ->leftJoin("employment_infos as ei", 'ei.org_matching_foreign', 'om.org_matchings_id')
        //             ->join('applies as a', 'a.employment_id', 'ei.employment_id')
        //             ->groupBy()
        //             ->groupBy('om.org_matchings_id')
        //             ->where('om.org_matchings', $org_matchings_id)
        //             ->first();
            
        // }

        
        try{
            DB::table($table)
                ->where('org_matchings_id', '=', $org_matchings_id)
                ->update(['employment_decision_ox'=> $employment_decision_ox]); 
            return ['returnBool'=>true, 'employment_decision_ox'=> $employment_decision_ox];

        }catch(\Exception $e){

        }
  
    }


    //에이전트 캘린더 출력
    public function agent_calendar(Request $request){
        $agentId = $request->get('agentId');

        
        $schedule_info = DB::table('interview_schedules')
        ->join('employment_infos', 'interview_schedules.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
        ->join('org_companies', 'org_companies.org_company_id', '=', 'org_matchings.org_company_id')
        ->join('org_colleges', 'org_matchings.org_college_id', '=', 'org_colleges.org_college_id')
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
                "interview_schedules.interview_date as date",
                "interview_schedules.interview_start_time",
                "interview_schedules.interview_end_time",
                "interview_infos.content",
                "interview_schedules.interview_check_ox")
        ->whereRaw("org_matchings.org_agent_id in (select org_agent_id
                                                    from agent_belongs
                                                    where agent_id = '$agentId')
                    and (interview_check_ox = 'o' or interview_check_ox = '' or interview_check_ox is null)")
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



    //기업 검색
    public function searchCompany(Request $req){
        $org_agent_id = $req->org_agent_id;
        $searchReq = $req->searchReq;

        $agent_company_matchingT = 'agent_company_matchings';
        $org_companyT = 'org_companies';

        // $business_big_infoT = 'business_big_infos';
        $business_bigT = 'business_bigs';
        // $business_small_infoT = 'business_small_infos';
        $business_smallT = 'business_smalls';


        $matching_date = $searchReq['matching_date']; 
        $region_id = $searchReq['region_id'];
        $prefecture_id = $searchReq['prefecture_id'];
      
        if(count($searchReq['big_infos']) == 0){
            $searchReq['big_infos'] = DB::table('business_big_infos')->pluck('id');
        }

        if(count($searchReq['small_infos']) == 0){
            $searchReq['small_infos'] = DB::table('business_small_infos')->pluck('id');
        }



        // if($matching_date == 'ALL'){
        //     $searchReq['matching_date'] =  DB::table($agent_company_matchingT)
        //                                         ->where('org_agent_id', $org_agent_id)
        //                                         ->pluck('matching_date');
        // }else{
        //     $searchReq['matching_date'] = [$matching_date];
        // }


        if($region_id == 'ALL' && $prefecture_id == 'ALL'){

            if($matching_date == 'ALL'){
                $companyList = DB::table("$org_companyT as oc")
                                    ->select(
                                        'acm.matching_date',
                                        'oc.*',
                                        DB::raw('Max(acm.matching_date) as max_matching_year'),
                                        DB::raw('count(CASE WHEN a.acceptance_ox="o" AND a.student_acceptance_ox="o" AND a.professor_acceptance_ox="o" THEN a.apply_id END) as accept_student_count')
                                    )
                                    ->join("$agent_company_matchingT as acm", 'acm.org_company_id', 'oc.org_company_id')
                                    ->join("$business_bigT as bb", 'bb.org_company_id', 'oc.org_company_id')
                                    ->join("$business_smallT as bs", 'bs.org_company_id', 'oc.org_company_id')
                                    ->leftJoin('org_matchings as om', 'om.org_company_id', 'oc.org_company_id')
                                    ->leftJoin('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                    ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')
                                    ->groupBy('oc.org_company_id')
                                    ->where('acm.org_agent_id', $org_agent_id)
                                    ->whereBetween('worker_count', [ $searchReq['worker_count_infos'][0], $searchReq['worker_count_infos'][1] ])
                                    ->whereIn('bb.business_big_id', $searchReq['big_infos'])
                                    ->whereIn('bs.business_small_id', $searchReq['small_infos'])
                                    ->get();

            }else {
                $companyList = DB::table("$org_companyT as oc")
                                    ->select(
                                        'acm.matching_date',
                                        'oc.*',
                                        DB::raw('Max(acm.matching_date) as max_matching_year'),
                                        DB::raw('count(CASE WHEN a.acceptance_ox="o" AND a.student_acceptance_ox="o" AND a.professor_acceptance_ox="o" THEN a.apply_id END) as accept_student_count')
                                    )
                                    ->join("$agent_company_matchingT as acm", 'acm.org_company_id', 'oc.org_company_id')
                                    ->join("$business_bigT as bb", 'bb.org_company_id', 'oc.org_company_id')
                                    ->join("$business_smallT as bs", 'bs.org_company_id', 'oc.org_company_id')
                                    ->leftJoin('org_matchings as om', 'om.org_company_id', 'oc.org_company_id')
                                    ->leftJoin('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                    ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')        
                                    ->groupBy('oc.org_company_id')
                                    ->where('acm.org_agent_id', $org_agent_id)
                                    ->where('acm.matching_date', $matching_date)
                                    //->whereBetween('worker_count', [ $searchReq['worker_count_infos'][0], $searchReq['worker_count_infos'][1] ])
                                    ->whereIn('bb.business_big_id', $searchReq['big_infos'])
                                    ->whereIn('bs.business_small_id', $searchReq['small_infos'])
                                    ->get();
            }
           

        }else if($region_id != 'ALL' && $prefecture_id == 'ALL'){

            if($matching_date == 'ALL'){

                $companyList = DB::table("$org_companyT as oc")
                                    ->select(
                                        'acm.matching_date',
                                        'oc.*',
                                        'jp.name_kanji',
                                        DB::raw('Max(acm.matching_date) as max_matching_year'),
                                        DB::raw('count(CASE WHEN a.acceptance_ox="o" AND a.student_acceptance_ox="o" AND a.professor_acceptance_ox="o" THEN a.apply_id END) as accept_student_count')
                                    )
                                    ->join("$agent_company_matchingT as acm", 'acm.org_company_id', 'oc.org_company_id')
                                    ->join("$business_bigT as bb", 'bb.org_company_id', 'oc.org_company_id')
                                    ->join("$business_smallT as bs", 'bs.org_company_id', 'oc.org_company_id')
                                    ->join('japan_prefectures as jp', 'jp.id', 'oc.address_to_dou_hu_ken')
                                    ->leftJoin('org_matchings as om', 'om.org_company_id', 'oc.org_company_id')
                                    ->leftJoin('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                    ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')  
                                    ->groupBy('oc.org_company_id')
                                    ->where('acm.org_agent_id', $org_agent_id)
                                    ->where('jp.region_id', $region_id)
                                    ->whereBetween('worker_count', [ $searchReq['worker_count_infos'][0], $searchReq['worker_count_infos'][1] ])
                                    ->whereIn('bb.business_big_id', $searchReq['big_infos'])
                                    ->whereIn('bs.business_small_id', $searchReq['small_infos'])
                                    ->get(); 

            } else {

                $companyList = DB::table("$org_companyT as oc")
                                    ->select(
                                        'acm.matching_date',
                                        'oc.*',
                                        DB::raw('Max(acm.matching_date) as max_matching_year'),
                                        DB::raw('count(CASE WHEN a.acceptance_ox="o" AND a.student_acceptance_ox="o" AND a.professor_acceptance_ox="o" THEN a.apply_id END) as accept_student_count')
                                    )
                                    ->join("$agent_company_matchingT as acm", 'acm.org_company_id', 'oc.org_company_id')
                                    ->join("$business_bigT as bb", 'bb.org_company_id', 'oc.org_company_id')
                                    ->join("$business_smallT as bs", 'bs.org_company_id', 'oc.org_company_id')
                                    ->join('japan_prefectures as jp', 'jp.id', 'oc.address_to_dou_hu_ken')
                                    ->leftJoin('org_matchings as om', 'om.org_company_id', 'oc.org_company_id')
                                    ->leftJoin('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                    ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')  
                                    ->groupBy('oc.org_college_id')
                                    ->where('acm.org_agent_id', $org_agent_id)
                                    ->where('jp.region_id', $region_id)
                                    ->where('acm.matching_date', $matching_date)
                                    ->whereBetween('worker_count', [ $searchReq['worker_count_infos'][0], $searchReq['worker_count_infos'][1] ])
                                    ->whereIn('bb.business_big_id', $searchReq['big_infos'])
                                    ->whereIn('bs.business_small_id', $searchReq['small_infos'])
                                    ->get(); 

            }
         

        }else if($region_id != 'ALL' && $prefecture_id != 'ALL'){
            if($matching_date == 'ALL'){
                $companyList = DB::table("$org_companyT as oc")
                                    ->select(
                                        'acm.matching_date',
                                        'oc.*',
                                        DB::raw('Max(acm.matching_date) as max_matching_year'),
                                        DB::raw('count(CASE WHEN a.acceptance_ox="o" AND a.student_acceptance_ox="o" AND a.professor_acceptance_ox="o" THEN a.apply_id END) as accept_student_count')
                                    )
                                    ->join("$agent_company_matchingT as acm", 'acm.org_company_id', 'oc.org_company_id')
                                    ->join("$business_bigT as bb", 'bb.org_company_id', 'oc.org_company_id')
                                    ->join("$business_smallT as bs", 'bs.org_company_id', 'oc.org_company_id')
                                    ->leftJoin('org_matchings as om', 'om.org_company_id', 'oc.org_company_id')
                                    ->leftJoin('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                    ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')  
                                    ->groupBy('oc.org_company_id')
                                    ->where('acm.org_agent_id', $org_agent_id)
                                    ->where('oc.address_to_dou_hu_ken', $prefecture_id)
                                    ->whereBetween('worker_count', [ $searchReq['worker_count_infos'][0], $searchReq['worker_count_infos'][1] ])
                                    ->whereIn('bb.business_big_id', $searchReq['big_infos'])
                                    ->whereIn('bs.business_small_id', $searchReq['small_infos'])
                                    ->get(); 
            } else {

                $companyList = DB::table("$org_companyT as oc")
                                    ->select(
                                        'acm.matching_date',
                                        'oc.*',
                                        DB::raw('Max(acm.matching_date) as max_matching_year'),
                                        DB::raw('count(CASE WHEN a.acceptance_ox="o" AND a.student_acceptance_ox="o" AND a.professor_acceptance_ox="o" THEN a.apply_id END) as accept_student_count')
                                    )
                                    ->join("$agent_company_matchingT as acm", 'acm.org_company_id', 'oc.org_company_id')
                                    ->join("$business_bigT as bb", 'bb.org_company_id', 'oc.org_company_id')
                                    ->join("$business_smallT as bs", 'bs.org_company_id', 'oc.org_company_id')
                                    ->leftJoin('org_matchings as om', 'om.org_company_id', 'oc.org_company_id')
                                    ->leftJoin('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                    ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')  
                                    ->groupBy('oc.org_company_id')
                                    ->where('acm.org_agent_id', $org_agent_id)
                                    ->where('oc.address_to_dou_hu_ken', $prefecture_id)
                                    ->where('acm.matching_date', $matching_date)
                                    ->whereBetween('worker_count', [ $searchReq['worker_count_infos'][0], $searchReq['worker_count_infos'][1] ])
                                    ->whereIn('bb.business_big_id', $searchReq['big_infos'])
                                    ->whereIn('bs.business_small_id', $searchReq['small_infos'])
                                    ->get(); 

            }

                                            
        }

        return $companyList;

    }

    //학생 추천 코멘트 수정
    public function updateRecommendationComment(Request $req){

        $student_login_id = $req->student_login_id;
        $recommendation_comment = $req->recommendation_comment;
        $table = 'students';

        try{
            $returnBool = DB::table($table)
                            ->where('login_id', '=', $student_login_id)
                            ->update(['recommendation_comment'=> $recommendation_comment]);

            return ['returnBool'=>$returnBool];
        }catch(\Exception $e){

            return ['returnBool'=>false];

        }
    }




    public function getStudentStatus(Request $req){

        $student_login_id = $req->student_login_id;
        $employmentArr = DB::table('employment_infos as ei')
                                ->select(
                                    'ei.employment_id',
                                    'ei.employment_name',
                                    'ei.whole_interview_count',
                                    'ei.acceptance_fixed_ox',
                                    'oc.org_company_id',
                                    'oc.org_name',
                                    'oc.org_name_kana',
                                    'a.apply_id',
                                    DB::raw("Max(CASE WHEN is.interview_active = 'x' THEN is.interview_count END) as next_interview_count")
                                )
                                ->groupBy('ei.employment_id')
                                ->join('org_matchings as om', 'om.org_matchings_id', 'ei.org_matching_foreign')
                                ->join('org_companies as oc', 'oc.org_company_id', 'om.org_company_id')
                                ->join('interview_schedules as is', 'is.employment_id', 'ei.employment_id')
                                ->join('applies as a', 'ei.employment_id', 'a.employment_id')
                                ->where('a.student_login_id', $student_login_id)
                                ->get();
        
        //$apply_count = count($employmentArr);
        //$acceptance_count = 0;

        foreach($employmentArr as $employment){
            $employment_id = $employment->employment_id;

            //탈락차수 뽑기
            $talrakRow = DB::table('interview_results as ir')
                            ->select(
                                'is.interview_count'
                            )
                            ->join('interview_schedules as is', 'is.interview_id', 'ir.interview_id')
                            ->where('ir.student_login_id', $student_login_id)
                            ->where('is.employment_id', $employment_id)
                            ->where('ir.interview_result', 'x')
                            ->get();

            //탈락차수
            $talrack_chasu = null;
            if(count($talrakRow) != 0)
                $talrack_chasu = $talrakRow[0]->interview_count;

            $employment->talrack_chasu = $talrack_chasu;



            //탈락 차수가 없다면
            if($talrack_chasu == null){

                $employment_id = $employment->employment_id;
                $next_interview_count = $employment->next_interview_count;

                //다음 면접 일정 뽑기
                $row = DB::table('interview_schedules')
                            ->select(
                                'interview_date',
                                'interview_start_time',
                                'interview_end_time'
                            )

                            ->where('employment_id', $employment_id)
                            ->where('interview_count', $next_interview_count + 1)
                            ->first();

                $interview_date = null;
                $interview_start_time = null;
                $interview_end_time = null;

                if($row != null){
                    $interview_date = $row->interview_date;
                    $interview_start_time = $row->interview_start_time;
                    $interview_end_time = $row->interview_end_time;
                }
                $employment->interview_date = $interview_date;
                $employment->interview_start_time = $interview_start_time;
                $employment->interview_end_time = $interview_end_time;

      
                //내정 뽑기
                $row = DB::table('applies')
                            ->select(
                                'acceptance_ox',
                                'student_acceptance_ox',
                                'professor_acceptance_ox'
                            )
                            ->where('student_login_id', $student_login_id)
                            ->where('employment_id', $employment_id)
                            ->first();

                $acceptance_ox = null;
                $student_acceptance_ox = null;
                $professor_acceptance_ox = null;
                if($row != null){
                    $acceptance_ox = $row->acceptance_ox;
                    $student_acceptance_ox = $row->student_acceptance_ox;
                    $professor_acceptance_ox = $row->professor_acceptance_ox;
                }

                $employment->acceptance_ox = $acceptance_ox;
                $employment->student_acceptance_ox = $student_acceptance_ox;
                $employment->professor_acceptance_ox = $professor_acceptance_ox;

                // if($acceptance_ox == 'o'){
                //     $acceptance_count++;
                // }

            } 


                                   
        }


        return [
            'employmentArr'=>$employmentArr,
            'student_login_id'=>$student_login_id
            //'apply_count'=>$apply_count,
            //'acceptance_count'=>$acceptance_count
        ];
    }

    //학교 정보 및 해당 학교 에이전트 실적 얻기
    public function getCollegeInfo(Request $req){

        $thisYear = date('Y');
        $org_college_id = $req->org_college_id;
        $org_agent_id = $req->org_agent_id;

        $searchYear = $req->searchYear;
        if($searchYear == null){
            $searchYear = $thisYear;
        }


        //일반 학교 정보 + 학생수, 취활 완료 학생수
        $collegeInfo = $this->getInnerCollegeResultInfo($org_college_id, $org_agent_id, $searchYear);

        //학과 정보 학생수 취활 완료 학생수
        $facultyInfo = $this->getInnerFacultyResultInfo($org_college_id, $org_agent_id, $searchYear);

/*
        $collegeInfo = DB::table("$org_colleges as oc")
                            ->select(
                                'oc.*',
                                'cc.country_eng',
                                'cc.country_kana',
                                DB::raw("count(DISTINCT CASE WHEN s.employ_year=$searchYear THEN s.login_id END) as student_count"),
                                DB::raw("count(DISTINCT CASE WHEN s.employ_year=$searchYear AND s.employment_end_ox = 'o' THEN s.login_id END) as student_end_count"),
                                DB::raw("count(DISTINCT CASE WHEN a.acceptance_ox = 'o' AND om.org_agent_id = '$org_agent_id' AND om.matching_date =$searchYear THEN a.apply_id END) as acceptance_give_count"),
                                DB::raw("count(DISTINCT CASE WHEN a.acceptance_ox = 'o' AND a.student_acceptance_ox = 'o' AND a.professor_acceptance_ox = 'o' AND om.org_agent_id = '$org_agent_id'  AND om.matching_date =$searchYear THEN a.apply_id END) as acceptance_ok_count"),
                                DB::raw("count(DISTINCT CASE WHEN om.org_agent_id='$org_agent_id' AND om.matching_date = $searchYear THEN ei.employment_id END) as employment_count"),
                                DB::raw("count(DISTINCT CASE WHEN om.org_agent_id='$org_agent_id' AND om.matching_date = $searchYear AND ei.employment_owari_ox = 'o' THEN ei.employment_id END) as employment_end_count")   
                            )
                            ->leftJoin('org_matchings as om', 'om.org_college_id', 'oc.org_college_id')
                            ->leftJoin('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                            ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')
                            ->leftJoin('country_codes as cc', 'cc.country_code', 'oc.country_code')
                            ->leftJoin('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                            ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                            ->leftJoin('students as s', 's.group_id','g.group_id')
                            ->groupBy('oc.org_college_id')
                            ->where('oc.org_college_id', $org_college_id)
                            ->first();
*/
        //전체 학생수 : student_count, 취활 종료 학생수:student_end_count, 

        //내정 승낙수 : acceptance_ok_count, 내정수 : acceptance_count, 
        //전체 채용건수 : employment_count, 끝난 채용건 수 : employment_end_count


       
/*        
        $facultyInfo = DB::table("$faculties as f")
                            ->select(
                                'f.*',
                                'm.content as major_tag',
                                DB::raw("count(DISTINCT CASE WHEN s.employ_year = $searchYear THEN s.login_id END) as student_count"),
                                DB::raw("count(DISTINCT CASE WHEN s.employment_end_ox = 'o' AND s.employ_year = $searchYear THEN s.login_id END) as student_end_count"),
                                DB::raw("count(DISTINCT CASE WHEN a.acceptance_ox = 'o' AND om.org_agent_id = '$org_agent_id' AND om.matching_date =$searchYear THEN a.apply_id END) as acceptance_give_count"),
                                DB::raw("count(DISTINCT CASE WHEN a.acceptance_ox = 'o' AND a.student_acceptance_ox = 'o' AND a.professor_acceptance_ox = 'o' AND om.org_agent_id = '$org_agent_id'  AND om.matching_date =$searchYear THEN a.apply_id END) as acceptance_ok_count"),
                                DB::raw("count(DISTINCT CASE WHEN om.org_agent_id='$org_agent_id' AND om.matching_date = $searchYear THEN ei.employment_id END) as employment_count"),
                                DB::raw("count(DISTINCT CASE WHEN om.org_agent_id='$org_agent_id' AND om.matching_date = $searchYear AND ei.employment_owari_ox = 'o' THEN ei.employment_id END) as employment_end_count")
                            )
                            ->groupBy('f.faculty_id')
                            ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                            ->leftJoin('students as s', 's.group_id', 'g.group_id')
                            ->leftJoin('applies as a', 'a.student_login_id', 's.login_id')
                            ->leftJoin('employment_infos as ei', 'ei.employment_id', 'a.employment_id')
                            ->leftJoin('org_matchings as om', 'om.org_matchings_id','ei.org_matching_foreign')
                            ->join('major_infos as m', 'm.id', 'f.major_id')
                            ->where('f.org_college_id', '=', $org_college_id)
                            ->get();
*/

        //채용건 정보 
        $employmentList = DB::table('org_matchings as om')
                                ->select(
                                    'ei.employment_id',
                                    'ei.employment_name',
                                    'ei.acceptance_fixed_ox',
                                    'ei.employment_owari_ox',
                                    'oc.org_company_id',
                                    'oc.org_name',
                                    'oc.org_name_kana',
                                    DB::raw('count(a.apply_id) as apply_count'),
                                    DB::raw("count(CASE WHEN a.acceptance_ox = 'o' THEN 1 END) as acceptance_count"),
                                    DB::raw("count(CASE WHEN a.acceptance_ox = 'o' AND a.student_acceptance_ox = 'o' AND a.professor_acceptance_ox = 'o' THEN a.apply_id END) as acceptance_ok_count")
                                )
                                ->groupBy('ei.employment_id')
                                ->join('org_companies as oc', 'oc.org_company_id', 'om.org_company_id')
                                ->join('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')
                                ->where('om.org_agent_id', $org_agent_id)
                                ->where('om.org_college_id', $org_college_id)
                                ->where('om.matching_date', $searchYear)
                                ->get();



        $professorBookInfo = [];

        if($req->classification == 'agent'){
            $professorBookInfo = $this->getAgentProfessorBook($facultyInfo, $org_agent_id);
        }


        $professorInfo = [];
        $groupInfo = [];
        
        foreach($facultyInfo as $faculty){
            $faculty_id = $faculty->faculty_id;

            //교수 정보
            $professorInfo[$faculty_id] = DB::table("professors")
                                            ->where('faculty_id', $faculty_id)
                                            ->get();

            //그룹 정보
            $groupInfo[$faculty_id] = DB::table("groups")
                                        ->where('faculty_id', $faculty_id)
                                        ->get();
        }
                
        return [
            'collegeInfo'=>$collegeInfo, 
            'facultyInfo'=>$facultyInfo, 
            'professorInfo'=>$professorInfo, 
            'groupInfo'=>$groupInfo, 
            'professorBookInfo'=>$professorBookInfo,
            'employmentList'=>$employmentList, 
            'returnBool'=>true
        ];    
    }

    
    //학교 정보 얻기 내부함수
    public function getInnerCollegeResultInfo($org_college_id, $org_agent_id, $searchYear){
        //일반 학교 정보 + 학생수, 취활 완료 학생수
        $collegeInfo = DB::table("org_colleges as oc")
                            ->select(
                                'oc.*',
                                'cc.country_eng',
                                'cc.country_kana',
                                DB::raw("count(DISTINCT CASE WHEN s.employ_year=$searchYear THEN s.login_id END) as student_count"),
                                DB::raw("count(DISTINCT CASE WHEN s.employ_year=$searchYear AND s.employment_end_ox = 'o' THEN s.login_id END) as student_end_count"),
                                DB::raw("count(DISTINCT CASE WHEN a.acceptance_ox = 'o' AND om.org_agent_id = '$org_agent_id' AND om.matching_date =$searchYear THEN a.apply_id END) as acceptance_count"),
                                DB::raw("count(DISTINCT CASE WHEN a.acceptance_ox = 'o' AND a.student_acceptance_ox = 'o' AND a.professor_acceptance_ox = 'o' AND om.org_agent_id = '$org_agent_id'  AND om.matching_date =$searchYear THEN a.apply_id END) as acceptance_ok_count"),
                                DB::raw("count(DISTINCT CASE WHEN om.org_agent_id='$org_agent_id' AND om.matching_date = $searchYear THEN ei.employment_id END) as employment_count"),
                                DB::raw("count(DISTINCT CASE WHEN om.org_agent_id='$org_agent_id' AND om.matching_date = $searchYear AND ei.employment_owari_ox = 'o' THEN ei.employment_id END) as employment_end_count")   
                            )
                            ->leftJoin('org_matchings as om', 'om.org_college_id', 'oc.org_college_id')
                            ->leftJoin('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                            ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')
                            ->leftJoin('country_codes as cc', 'cc.country_code', 'oc.country_code')
                            ->leftJoin('faculties as f', 'f.org_college_id', 'oc.org_college_id')
                            ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                            ->leftJoin('students as s', 's.group_id','g.group_id')
                            ->groupBy('oc.org_college_id')
                            ->where('oc.org_college_id', $org_college_id)
                            ->first();
        return $collegeInfo;
    }

    //학과 결과 얻기 내부 함수
    public function getInnerFacultyResultInfo($org_college_id, $org_agent_id, $searchYear){

        $facultyList = DB::table("faculties as f")
                            ->select(
                                'f.*',
                                'm.content as major_tag',
                                DB::raw("count(DISTINCT CASE WHEN s.employ_year = $searchYear THEN s.login_id END) as student_count"),
                                DB::raw("count(DISTINCT CASE WHEN s.employment_end_ox = 'o' AND s.employ_year = $searchYear THEN s.login_id END) as student_end_count"),
                                DB::raw("count(DISTINCT CASE WHEN a.acceptance_ox = 'o' AND om.org_agent_id = '$org_agent_id' AND om.matching_date =$searchYear THEN a.apply_id END) as acceptance_count"),
                                DB::raw("count(DISTINCT CASE WHEN a.acceptance_ox = 'o' AND a.student_acceptance_ox = 'o' AND a.professor_acceptance_ox = 'o' AND om.org_agent_id = '$org_agent_id'  AND om.matching_date =$searchYear THEN a.apply_id END) as acceptance_ok_count"),
                                DB::raw("count(DISTINCT CASE WHEN om.org_agent_id='$org_agent_id' AND om.matching_date = $searchYear THEN ei.employment_id END) as employment_count"),
                                DB::raw("count(DISTINCT CASE WHEN om.org_agent_id='$org_agent_id' AND om.matching_date = $searchYear AND ei.employment_owari_ox = 'o' THEN ei.employment_id END) as employment_end_count")
                            )
                            ->groupBy('f.faculty_id')
                            ->leftJoin('groups as g', 'g.faculty_id', 'f.faculty_id')
                            ->leftJoin('students as s', 's.group_id', 'g.group_id')
                            ->leftJoin('applies as a', 'a.student_login_id', 's.login_id')
                            ->leftJoin('employment_infos as ei', 'ei.employment_id', 'a.employment_id')
                            ->leftJoin('org_matchings as om', 'om.org_matchings_id','ei.org_matching_foreign')
                            ->join('major_infos as m', 'm.id', 'f.major_id')
                            ->where('f.org_college_id', '=', $org_college_id)
                            ->get();

        //채용건 리스트
        foreach($facultyList as $faculty){
            $faculty_id = $faculty->faculty_id;
            $employmentList = DB::table('org_matchings as om')
                                    ->select(
                                        'ei.employment_id',
                                        'ei.employment_name',
                                        'ei.acceptance_fixed_ox',
                                        'ei.employment_owari_ox',
                                        'oc.org_company_id',
                                        'oc.org_name',
                                        'oc.org_name_kana',
                                        DB::raw('count(a.apply_id) as apply_count'),
                                        DB::raw("count(CASE WHEN a.acceptance_ox = 'o' AND f.faculty_id = $faculty_id THEN 1 END) as acceptance_count"),
                                        DB::raw("count(CASE WHEN a.acceptance_ox = 'o' AND f.faculty_id = $faculty_id AND a.student_acceptance_ox = 'o' AND a.professor_acceptance_ox = 'o' THEN a.apply_id END) as acceptance_ok_count")
                                    )
                                    ->groupBy('ei.employment_id')
                                    ->join('org_companies as oc', 'oc.org_company_id', 'om.org_company_id')
                                    ->join('employment_infos as ei', 'ei.org_matching_foreign', 'om.org_matchings_id')
                                    ->leftJoin('applies as a', 'a.employment_id', 'ei.employment_id')

                                    ->leftJoin('students as s', 's.login_id', 'a.student_login_id')
                                    ->leftJoin('groups as g', 'g.group_id', 's.group_id')
                                    ->leftJoin('faculties as f', 'f.faculty_id', 'g.faculty_id')
                                    ->where('om.org_agent_id', $org_agent_id)
                                    ->where('om.org_college_id', $org_college_id)
                                    ->where('om.matching_date', $searchYear)
                                    ->get();

            $faculty->employment_list = $employmentList;
        }
     

        return $facultyList;
    }


    //에이전트 교수 주소록 뽑기
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




    // 2018-06-20 (수)
    // 에이전트 개인 프로필 호출 
    public function getProfile() {
        
        // 에이전트 아이디 (로그인 아이디)
        $login_id = request('login_id');

        // 로그인 아이디, 이름, 카타카나, 이메일, 생일
        $info = DB::table('agents')
                    ->select('*')
                    ->where('login_id', '=', $login_id)
                    ->first();
        
        return ['info'=>$info];

    } 

    //에이전트 프로필 사진 수정
    public function uploadImage(){
        $type = request('type');
        $id = request('id');
        $profileImage = request('profileImage');

        $table = null;
        $photo_field = null;
        $id_field = null;


        if($type == 'agent'){
            $table = 'agents';
            $photo_field = 'profile_photo_url';
            $id_field = 'login_id';
        }else if($type == 'orgAgent'){
            $table = 'org_agents';
            $photo_field = 'photo_url';
            $id_field = 'org_agent_id';
        }


        $fileController = new FileController();
        //기존 사진 삭제
        $fileController->deleteFile($profileImage['url']);

        //사진 업로드
        $new_photo_url = $fileController->createFile($profileImage['type'], $profileImage['data'], 'profileImages', $id.'_profileImage');

        DB::table("$table")->where("$id_field", $id)->update(["$photo_field"=>$new_photo_url]);

        return ['returnBool'=>true];
    }

    // 에이전트 개인 프로필 수정 
    public function updateProfile() {
        
        // 에이전트 아이디 (로그인 아이디)
        $agent_id  = request('requester');
        // 바꿀 정보 값 
        $edit_info = request('info');
        
         // 수정
         try{
            DB::table('agents')
            ->where('login_id', '=', $agent_id)
            ->update([
                'name'       => $edit_info['name'],
                'name_kana'  => $edit_info['nameKana'],
                'email'      => $edit_info['email'],
                'birth_date' => $edit_info['birth'],
            ]);

            return ['returnBool'=>true];
        }catch(\Exception $e){
            return ['returnBool'=>false];
        }
        
    }

    //에이전시 프로필 수정
    public function updateOrgProfile(){

        // 에이전트 아이디 (로그인 아이디)
        $org_agent_id  = request('org_agent_id');
        // 바꿀 정보 값 
        $edit_info = request('editInfo');

        // 수정
        try{
            DB::table('org_agents')
            ->where('org_agent_id', $org_agent_id)
            ->update([
                'org_name'       => $edit_info['org_name'],
                'org_name_kana'  => $edit_info['org_name_kana'],
                'address'        => $edit_info['address'],
                'homepage_url'   => $edit_info['homepage_url'],
            ]);

            return ['returnBool'=>true];
        }catch(\Exception $e){
            $returnBool = false;
            return ['returnBool'=>false];
        }
                
    }
}
