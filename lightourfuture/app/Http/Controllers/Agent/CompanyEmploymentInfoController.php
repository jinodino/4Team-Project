<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class CompanyEmploymentInfoController extends Controller
{
    

    public function index() {

    }

    public function create(Request $req) {
        
        $org_companies = $req->org_companies;

        //company id 생성
        $companyEmploymentInfoController = new CompanyEmploymentInfoController();
        $org_company_id = $companyEmploymentInfoController->randnum($req->country_code);
        $arr = array('org_company_id' => $org_company_id);

        // foreach($org_companies as $value) {
        //     $key = $value['key'];
        //     $arr[$key] = $value['value'];
        // }
        foreach($org_companies as $value) {
           array_push($arr , [$value['key'] => $value['value']]);
             
                
            }
            return($arr);
        
             
              

             
           
            
        
        //\Log::info($)
        // var_dump($arr);
        // // 프라이머리 키 중복 예외 처리
        //  try{
        //     db::table('org_companies')->insert($arr);
        //  }catch(Exception $e){

        //  }
                           /*
        db::table('org_companies')->insert([
            'org_company_id' => $org_company_id,
            'org_name_kanji' => $reqest->get('org_name_kanji'),
            'org_name_kana' => $reqest->get('org_name_kana'),
            'org_name_eng' => $reqest->get('org_name_eng'),
            'country_code' => $reqest->get('country_code'),
            'photo_url' => $reqest->get('photo_url'),
            'address_to' => $reqest->get('address_to'),
            'address_dou' => $reqest->get('address_dou'),
            'address_hu' => $reqest->get('address_hu'),
            'address_ken' => $reqest->get('address_ken'),
            'address' => $reqest->get('address'),
            'homepage_url' => $reqest->get('homepage_url'),
            'establish_date' => $reqest->get('establish_date'),
            'ceo_name_kanji' => $reqest->get('ceo_name_kanji'),
            'ceo_name_kana' => $reqest->get('ceo_name_kana'),
            'worker_count' => $reqest->get('worker_count'), 
            'capital' => $reqest->get('capital'), 
            'business_content' => $reqest->get('business_content'), 
            'company_atmosphere' => $reqest->get('company_atmosphere'), 
            'recommendation_comment' => $reqest->get('recommendation_comment'), 
            'employment_decision_ox' => $reqest->get('employment_decision_ox'), 
            'listed_company_ox'  => $reqest->get('listed_company_ox'), 
         ]);

         $business_big_id = $request->get('business_big_id');

         foreach($business_big_id as $prj) {
            db::table('business_bigs')->insert([
                'business_big_id' => $prj,
                'org_company_id' => $org_company_id,
             ]);
         }

         $business_small_id = $request->get('business_small_id');

         foreach($business_big_id as $prj) {
            db::table('business_smalls')->insert([
                'business_small_id' => $prj,
                'org_company_student_id' => $org_company_id,
             ]);
         }*/
    }

    public function update() {
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

    public function randnum($country_eng) {
        $companyEmploymentInfoController = new CompanyEmploymentInfoController();
        $org_company_id = $companyEmploymentInfoController->country_code($country_eng);
        $num = rand(1, 999999);
        
        return $country_eng . (string)$num;
    }
}
