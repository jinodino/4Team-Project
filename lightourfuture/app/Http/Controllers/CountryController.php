<?php
//by. hyojin
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    //
    public function getCountries(Request $req){
        $table = "country_codes";

        $continent_code_arr = DB::table($table)->pluck('continent');

        $returnArr = array();
        foreach($continent_code_arr as $code)
            $returnArr[$code] = DB::table($table)->where('continent',$code)->select('country_code', 'country_num', 'country_eng', 'country_kana')->get();

        return $returnArr;
    }


    //일본 행정 구역 get
    public function getTodouhuken(Request $req){

        $table1 = 'japan_regions';
        $table2 = 'japan_prefectures';
        
        $arrRegions = DB::table($table1)->select('id','name_kanji','name_kana')->get();
        

        //regions id list 뽑아내기
        $regionId_arr = [];
        foreach($arrRegions as $item)
            $regionId_arr[] = $item->id;
        
        //prefectures list 뽑아내기
        $arrPrefectures = [];
        foreach($regionId_arr as $item)
            $arrPrefectures[$item] = DB::table($table2)->select('id','region_id', 'name_kanji','name_kana')->where('region_id', $item)->get();

        return ['regions'=>$arrRegions, 'prefectures'=> $arrPrefectures];

    }
   


}
