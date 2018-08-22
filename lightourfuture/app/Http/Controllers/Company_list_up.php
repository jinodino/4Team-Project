<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Company;

class Company_list_up extends Controller
{
    //전체 회사 리스트 업
    public function company_list_up_all(Request $request){

        \Log::info($request->all());
        $company_list   =   Company::all();

        return response()->json($company_list); //json형식으로 반환
    }

    //회사 검색
    public function company_list_up_search(Request $request){
        
        $db_table           =   'org_companies';        //테이블 명
        $search_type        =   $request->get('type'); //검색 타입 설정(전체, 조건)
        $search_contents    =   '%'.$request->get('contents').'%';//검색 내용
        
        
        if($search_type == 'all'){
        //전체 검색
            $search_result = DB::table($db_table)->where('manager_login_id', 'like', $search_contents)
            ->orWhere('org_name_kanji', 'like', $search_contents)
            ->orWhere('org_name_kana', 'like', $search_contents)
            ->orWhere('org_name_eng', 'like', $search_contents)
            ->orWhere('country_code', 'like', $search_contents)
            ->orWhere('address_to', 'like', $search_contents)
            ->orWhere('address_dou', 'like', $search_contents)
            ->orWhere('address_hu', 'like', $search_contents)
            ->orWhere('address_ken', 'like', $search_contents)
            ->orWhere('address', 'like', $search_contents)
            ->orWhere('homepage_url', 'like', $search_contents)
            ->orWhere('establish_date', 'like', $search_contents)
            ->orWhere('ceo_name_kanji', 'like', $search_contents)
            ->orWhere('ceo_name_kana', 'like', $search_contents)
            ->orWhere('worker_count', 'like', $search_contents)
            ->orWhere('capital', 'like', $search_contents)
            ->orWhere('business_content','like', $search_contents)
            ->orWhere('company_atmosphere', 'like', $search_contents)
            ->orWhere('recommendation_comment', 'like', $search_contents)
            ->orWhere('employment_decision_ox', 'like', $search_contents)
            ->orWhere('listed_company_ox', 'like', $search_contents)
            ->get();
        
                
        }else{
        //조건 검색
            $search_result = DB::table($db_table)
                ->where($search_type, 'like', $search_contents)
                ->get();
        }
        
        return $search_result;
        
    }

}
