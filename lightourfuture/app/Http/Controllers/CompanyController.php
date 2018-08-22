<?php
//by. hyojin
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PushNotify as PushNotify;

class CompanyController extends Controller
{

    //올년도
    //
    //

    public function index() {

    }

    //회사 등록
    public function create(Request $req) {

        // //에이전트가 아니면 튕기는 기능
        $host_org_agent_id = $req->host_org_agent_id;
        if($host_org_agent_id == null){
            return ['returnBool'=>false];
        }


        $table = "org_companies";
        $matchingTable = "agent_company_matchings";
        $org_companies = $req->org_companies;

        do{
            //company id 생성
            $org_company_id = 'com'.rand(1, 999999);
            //orgs 테이블에 id 넣기 전에 유니크 값인지 확인
            $row_count = DB::table('orgs')
                        ->where('org_id' , $org_company_id)
                        ->get()
                        ->count();

            if($row_count == 0){
                $org_companies['org_company_id'] = $org_company_id;
                DB::table('orgs')->insert(['org_id' => "$org_company_id", 'org_classification'=>'company']);
                break;
            }
                
        }while(true);


        //프로필 이미지 저장
        $fileController = new FileController();
        $profileImage = $req->profileImage;

        if($profileImage['data'] != null)
            $org_companies['photo_url'] = $fileController->createFile($profileImage['type'], $profileImage['data'], 'profileImages', $org_company_id.'_profileImage');

        DB::table($table)->insert($org_companies);

        //이미지들 폴더에 저장
        $images = $req->images;

        $imgPath_arr = array();
        foreach($images as $key=>$image){
            if($image['data'] != null)
                $imgPath_arr[] =  $fileController->createFile($image['type'], $image['data'], 'images', $org_company_id."_image_$key");
        }  

        //이미지들 다중 매칭 테이블에 저장
        $this->multiMatching('org_imgs', $imgPath_arr, 'photo_url','org_id', $org_company_id);

        
    


      
        //체크 항목 테이블에 insert
        foreach($req->checkBox as $key=> $value){
            if($key == 'business_bigs')
                $sub_field = 'business_big_id';
            else if($key == 'business_smalls')
                $sub_field = 'business_small_id';
                
            $this->multiMatching( $key, $value, $sub_field,'org_company_id', $org_company_id);
        }


        //agent-company 관계 테이블에 insert
        $thisYear = date('Y');
        DB::table($matchingTable)->insert(['org_agent_id'=>$host_org_agent_id, 'org_company_id'=>$org_company_id, 'matching_date'=> $thisYear]);

        
        return  ['org_company_id'=>$org_company_id];
    }

    //회사 정보 수정
    public function update(Request $req) {

        $org_companies = $req->org_companies;
        $org_company_id = $org_companies['org_company_id'];
        unset($org_companies['org_company_id']);

        //프로필 이미지 넣어주기
        $fileController = new FileController();
        $profileImage = $req->profileImage; 
        if($profileImage['data'] != null){
            if($profileImage['photo_url'] != null){
                $fileController->deleteFile($profileImage['photo_url']);
            }
            $org_companies['photo_url'] = $fileController->createFile($profileImage['type'], $profileImage['data'], 'profileImages', $org_company_id.'_profileImage');
        }

        $table = "org_companies"; 
        

        DB::table($table)->where('org_company_id', $org_company_id)->update($org_companies);


        //기존에 삭제된 이미지 폴더에서도 삭제
        $deletedImages = $req->deletedImages;

        foreach($deletedImages as $deletedImage){
            $fileController->deleteFile($deletedImage);
        }

        //이미지들 폴더에 저장
        $images = $req->images;
        $imgPath_arr = array();
        $checkArr = [0,1,2];
        foreach($images as $image){
            if($image['photo_url'] != null){
                $imgPath_arr[] = $image['photo_url'];
                $checkKey = array_keys( $checkArr, (int)explode('_', $image['photo_url'])[2]);
                
                \Log::info($checkKey);
                if(count($checkKey) == 0)
                    continue;
                else
                    $checkKey = $checkKey[0];
                    
                array_splice($checkArr, $checkKey,1);
            }
        } 

        $imageIndex = 0;
        foreach($images as $image){
            if(isset($image['data']) && $image['data'] != null){
                // if(isset($checkArr[$imageIndex]))
                //     $index = $checkArr[$imageIndex];
                // else
                //     $index = $imageIndex;
                $imgPath_arr[] =  $fileController->createFile($image['type'], $image['data'], 'images', $org_company_id."_image_".$checkArr[$imageIndex]);
                $imageIndex++;
            }
        }

        //이미지 경로들 다중 매칭 테이블에 저장
        $this->multiMatching('org_imgs', $imgPath_arr, 'photo_url','org_id', $org_company_id);

        //체크 항목 테이블에 insert
        foreach($req->checkBox as $key=> $value){
            if($key == 'business_bigs')
                $sub_field = 'business_big_id';
            else if($key == 'business_smalls')
                $sub_field = 'business_small_id';
                
            $this->multiMatching( $key, $value, $sub_field, 'org_company_id', $org_company_id);
        }
        
        return ['org_company_id'=>$org_company_id, 'returnBool'=>true];
    }


    //회사 정보 가져오기
    public function getCompanyInfo(Request $req){
        $table = 'org_companies';
        $org_company_id = $req->org_company_id;
        \Log::info($org_company_id);

        $org_companies = DB::table($table)->where('org_company_id', '=', $org_company_id)->get()[0];

         $address_todou_hu_ken = $org_companies->address_to_dou_hu_ken;
         $region_id = DB::table('japan_prefectures')->select('region_id')->where('id', $address_todou_hu_ken)->get();
         if(count($region_id) != 0)
            $region_id = $region_id[0]->region_id;
        else
            $region_id = null;

        //사업 내용 대분류
        $business_bigs = 'business_bigs';
        $business_big_infos = 'business_big_infos';

        //사업 내용 소분류
        $business_smalls = 'business_smalls';
        $business_small_infos = 'business_small_infos';

        //기관 이미지
        $org_imgs = 'org_imgs';

        $business_bigs =  DB::table("$business_bigs as bb")
                            ->select('bi.id', 'bi.content')
                            ->join("$business_big_infos as bi", 'bb.business_big_id', '=', 'bi.id')
                            ->where('bb.org_company_id', '=', $org_company_id)
                            ->get();
        
        $business_smalls = DB::table("$business_smalls as bs")
                            ->select('bi.id', 'bi.content')
                            ->join("$business_small_infos as bi", 'bs.business_small_id', '=', 'bi.id')
                            ->where('bs.org_company_id', '=', $org_company_id)
                            ->get();
        $org_imgs = DB::table($org_imgs)
                        ->select('photo_url')
                        ->where('org_id', '=', $org_company_id)
                        ->get();
        

        return ['org_companies'=>$org_companies, 'business_bigs'=>$business_bigs, 'business_smalls'=>$business_smalls, 'org_imgs'=>$org_imgs, 'region_id'=>$region_id];
  
    }                   

    //채용정보 가져오기
    public function getRecruit(Request $req){

        $table = 'employment_infos';

        $work_matchings = 'work_matchings';
        $work_infos = 'work_infos';

        $desired_employments = 'desired_employments';
        $desired_employment_infos = 'desired_employment_infos';

        $welfares = 'welfares';
        $welfare_infos = 'welfare_infos';

        //채용 아이디가 한개 일 때
        $employment_id = $req->employment_id;

        $employment_infos = DB::table($table)
                            ->where('employment_id', '=', $employment_id)
                            ->get();

        $interview_scheduleT = 'interview_schedules';
        $interview_schedule = DB::table($interview_scheduleT)
                                    ->where('employment_id', $employment_id)
                                    ->get();

        
        /*
        $region_id = null;
        if(property_exists($employment_infos, 'address_to_dou_hu_ken')){
            $address_todou_hu_ken = $employment_infos->address_to_dou_hu_ken;
            $region_id = DB::table('japan_prefectures')->select('region_id')->where('id', $address_todou_hu_ken)->get();
            if(count($region_id) != 0)
                $region_id = $region_id[0]->region_id;
        }
        */

        

        //업무내용 분류
        $work_matchings = DB::table("$work_matchings as wm")
                            ->select('wi.id', 'wi.content')
                            ->join("$work_infos as wi", 'wm.work_id', '=', 'wi.id')
                            ->where('wm.employment_id', '=', $employment_id)
                            ->get();

        //원하는 인재상
        $desired_employments = DB::table("$desired_employments as de")
                                ->select('dei.id', 'dei.content')
                                ->join("$desired_employment_infos as dei", 'de.desired_employment_id', '=', 'dei.id')
                                ->where('de.employment_id', '=', $employment_id)
                                ->get();
        //대우, 복지
        $welfares = DB::table("$welfares as w")
                        ->select('wi.id', 'wi.content')
                        ->join("$welfare_infos as wi", 'w.welfare_id', '=', 'wi.id')
                        ->where('w.employment_id', '=', $employment_id)
                        ->get();


        return ['employment_infos'=>$employment_infos[0], 'work_matchings'=>$work_matchings, 'desired_employments'=>$desired_employments, 'welfares'=>$welfares, 'interview_schedule'=> $interview_schedule];
    }

    //채용 정보 등록
    public function createRecruit(Request $req){

        //에이전트 아니면 튕겨주기

        $table = "employment_infos";
        $employment_infos = $req->DB;


        $login_id = $req->login_id;
        $insertArr = array();
        foreach($employment_infos as $key=>$value)
            $insertArr[$key] = $value;
        
        $insertArr['employment_owari_ox'] = 'x';
        
        
        //매칭 아이디 얻기
        $org_matching_foreign = $req->DB['org_matching_foreign'];
    
        //채용 정보 등록
        $employment_id = DB::table($table)->insertGetId($insertArr,'employment_id');

        //인터뷰 등록
        $interview_scheduleT = 'interview_schedules';
        $interview_schedule = $req->interview_schedule;
        $interview_schedule[0]['interview_active'] = 'o';

        $pushNotify = new PushNotify();
        $apiKey = $req->apiKey;
        
        foreach($interview_schedule as $value){
            $value['employment_id'] = $employment_id;
            DB::table($interview_scheduleT)->insert($value);
        }
        
        $stdId = DB::table('employment_infos as ei')
                    ->distinct()
                    ->join('org_matchings as om', 'om.org_matchings_id', '=', 'ei.org_matching_foreign')
                    ->join('org_colleges as oc', 'oc.org_college_id', '=', 'om.org_college_id')
                    ->join('org_companies as ocp', 'ocp.org_company_id', '=', 'om.org_company_id')
                    ->join('faculties as fc', 'fc.org_college_id', '=', 'oc.org_college_id')
                    ->join('professors as pro', 'pro.faculty_id', '=', 'fc.faculty_id')
                    ->join('students as std', 'std.professor_login_id', '=', 'pro.login_id')
                    ->select('std.login_id', 'std.name', 'ocp.org_name', 'ocp.org_name_kana')
                    ->where('om.org_matchings_id', '=', $org_matching_foreign)
                    ->get();
        foreach($stdId as $stdIds){
            $std_Id = $stdIds->login_id;
            $company_name = $stdIds->org_name;
            $company_name_kana = $stdIds->org_name_kana;
            $pushNotify->push_select_send($login_id, $std_Id, $apiKey, "$company_name($company_name_kana)사의 채용정보가 등록되었습니다.");
        }
        
        $proId = DB::table('employment_infos as ei')
                    ->distinct()
                    ->join('org_matchings as om', 'om.org_matchings_id', '=', 'ei.org_matching_foreign')
                    ->join('org_colleges as oc', 'oc.org_college_id', '=', 'om.org_college_id')
                    ->join('org_companies as ocp', 'ocp.org_company_id', '=', 'om.org_company_id')
                    ->join('faculties as fc', 'fc.org_college_id', '=', 'oc.org_college_id')
                    ->join('professors as pro', 'pro.faculty_id', '=', 'fc.faculty_id')
                    ->select('pro.login_id', 'pro.name', 'ocp.org_name', 'ocp.org_name_kana')
                    ->where('om.org_matchings_id', '=', $org_matching_foreign)
                    ->get();
                    
        foreach($proId as $proIds){
            $pro_Id = $proIds->login_id;
            $company_name = $proIds->org_name;
            $company_name_kana = $proIds->org_name_kana;
            $pushNotify->push_select_send($login_id, $pro_Id, $apiKey, "$company_name($company_name_kana)사의 채용정보가 등록되었습니다.");
        }

        // 2018-05-14 작성자 ID값 interview_schedules Table에 넣어주기 // 
        // 인터뷰 날짜
        // $interview_date = date('Y-m-d', strtotime("+1 days", strtotime($employment_infos['apply_deadline_date'])));
        // $interview_start_time = "00:00:00";

        // //디폴트 서류 심사 인터뷰 스케쥴 잡기
        // $interview_scheduleT = 'interview_schedules';
        // DB::table($interview_scheduleT)->insertGetId([
        //     'employment_id'    => $employment_id,
        //     'writer_id'         => $login_id,
        //     'interview_count'  => 1,
        //     'interview_date'   => $employment_infos['apply_deadline_date'],
        //     'schedule_title'   => '서류심사',
        //     'interview_date'   => $interview_date,
        //     'interview_active' => 'o',
        //     'interview_start_time'     => $interview_start_time,
        //     'interview_content_choice' => 1,
        //     'interview_check_ox'       => 'o',
        // ]);

   
        //업무 내용 분류
        $work_matchings = $req->checkBoxValue['work_matchings'];
        $this->multimatching($work_matchings['table'], $work_matchings['value'], 'work_id', 'employment_id', $employment_id);

        //원하는 인재상
        $desired_employments = $req->checkBoxValue['desired_employments'];
        $this->multimatching($desired_employments['table'], $desired_employments['value'], 'desired_employment_id', 'employment_id', $employment_id);

        //대우, 복리후생 분류
        $welfares = $req->checkBoxValue['welfares'];
        $this->multimatching($welfares['table'], $welfares['value'], 'welfare_id', 'employment_id', $employment_id);

        return $employment_id;
    }

    //채용 정보 수정
    public function updateRecruit(Request $req){
        $table = 'employment_infos';
   
        $updateArr = $req->DB;
        $employment_id = $updateArr['employment_id'];
        unset($updateArr['employment_id']);
    

        DB::table($table)->where('employment_id', $employment_id)->update($updateArr);


        //인터뷰 스케줄 수정
        $interview_scheduleT = 'interview_schedules';
        DB::table($interview_scheduleT)->where('employment_id', $employment_id)->delete();
        
        $interview_schedule = $req->interview_schedule;
        foreach($interview_schedule as $value){
            $value['employment_id'] = $employment_id;
            DB::table($interview_scheduleT)->insert($value);
        }

        //업무 내용 분류
        $work_matchings = $req->checkBoxValue['work_matchings'];
        $this->multimatching($work_matchings['table'], $work_matchings['value'], 'work_id', 'employment_id', $employment_id);

        //원하는 인재상
        $desired_employments = $req->checkBoxValue['desired_employments'];
        $this->multimatching($desired_employments['table'], $desired_employments['value'], 'desired_employment_id', 'employment_id', $employment_id);

        //대우, 복리후생 분류
        $welfares = $req->checkBoxValue['welfares'];
        $this->multimatching($welfares['table'], $welfares['value'], 'welfare_id', 'employment_id', $employment_id);

        return $employment_id;

    }

    //채용 정보 삭제
    public function deleteRecruit(Request $req){

        $employment_id = $req->employment_id;
        $login_id = $req->login_id;
        $orgAgent = $req->orgAgent;


        $table = 'employment_infos';

        try{
            DB::table($table)->where('employment_id', $employment_id)->delete();
            return ['returnBool'=>true];
        }catch(\Exception $e){
            $interview_scheduleT = 'interview_schedules';
            $interview_schedule_arr = DB::table($interview_scheduleT)->select('interview_id')->where('employment_id', $employment_id)->get();
            
            //등록된 인터뷰 스케줄이 디폴트 외에 있으면 리턴
            if(count($interview_schedule_arr) > 1)
                return ['returnBool'=>false, 'returnCode'=>1];

            $interview_id = $interview_schedule_arr[0]->interview_id;
            $interview_resultT = 'interview_results';
            $apply_std_count = DB::table($interview_resultT)->where('interview_id', $interview_id)->get()->count();

            //지원한 학생이 한 명이라도 있으면 리턴
            if($apply_std_count > 0){
                return['retuenBool'=>false, 'returnCode'=>2];
            }
            else{
                DB::table($interview_scheduleT)->where('employment_id', $employment_id)->delete();
                DB::table('work_matchings')->where('employment_id', $employment_id)->delete();
                DB::table('welfares')->where('employment_id', $employment_id)->delete();
                DB::table('desired_employments')->where('employment_id', $employment_id)->delete();
                DB::table($table)->where('employment_id', $employment_id)->delete();

                return['returnBool'=>true];
            }   
        }
    }

    //매칭 아이디를 이용해 채용정보 리스트 가져오기
    public function getEmploymentList(Request $req){

        $org_matchings_id = $req->org_matchings_id;
        $table = 'employment_infos';
        $employment_list = DB::table($table)
                            ->select('employment_id', 'employment_name', 'apply_open_date', 'apply_deadline_date', 'acceptance_fixed_ox', 'whole_interview_count')
                            ->where('org_matching_foreign', $org_matchings_id)
                            //->whereYear('apply_open_date', date('Y'))
                            ->get();
        return ['employment_list'=>$employment_list];
    }

  


    

    /**
     * @param String country_eng
     * @return String country_code
     */
    public function country_code($country_eng) {
        $country_code = db::table('country_codes')
        ->where('country_eng', '=', $country_eng)
        ->pluck('country_code');

        return $country_code;
    }

    /*
    public function randnum($country_eng) {
        $companyEmploymentInfoController = new CompanyEmploymentInfoController();
        $org_company_id = $companyEmploymentInfoController->country_code($country_eng);
        $num = rand(1, 999999);
        
        return $country_eng . (string)$num;
    }
    */

    //다중 매칭 테이블 등록
    public function multiMatching($table, $subid_arr, $sub_field ,$main_field, $main_id){
        //이미 항목을 가지고 있는 main_id라면....
        $rowCount = DB::table($table)->where( $main_field, $main_id)->get()->count();
        if($rowCount != 0)
             DB::Delete("DELETE FROM $table WHERE $main_field = '$main_id'");
        
        foreach($subid_arr as $subid){
            //$insert_arr = [$sub_field=>$subid,  ];
            if(is_string($subid))
               DB::Insert("INSERT INTO $table($sub_field, $main_field, data_entry_time) VALUES ('$subid', '$main_id', CURRENT_TIMESTAMP())");
            else
               DB::Insert("INSERT INTO $table($sub_field, $main_field, data_entry_time) VALUES ($subid, '$main_id', CURRENT_TIMESTAMP())");
        }
         
    }

}