<?php
//by. hyojin
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    //기업 대분류 => business_big_infos, 
    //소분류 => business_small_infos,
    //업무 => work_infos
    //복지=> welfare_infos
    //인재상=>desired_employment_infos
    //면접 내용=> interview_infos
 
    //추가
    public function create(Request $req){
        $content = $req->content;
        $table = $req->table;
        DB::table($table)->insert(
            ['content' => $content]
        );
        return DB::table($table)->get();
    }

    //삭제
    public function delete(Request $req){
        \Log::info($req->all());
        $id = $req->id;
        $table = $req->table;
        try{
            $returnBool = DB::table($table)->where('id',$id)->delete();
            $returnData = DB::table($table)->get();
            return ['returnBool'=> $returnBool, 'returnData'=> $returnData];
        }catch(\Exception $e){
            return ['returnBool'=> false];
        }
    }

    //업데이트
    public function update(Request $req){
        \Log::info($req->all());
        $id = $req->id;
        $content = $req->content;
        $table = $req->table;

        DB::table($table)->where('id',$id)->update(['content'=>$content]);
        return DB::table($table)->get();
    }

    
    //리스트업
    public function listUp(Request $req){
        
        $array = $req->tableList;
        $returnArr = array();

        foreach ($array as $key=>$table)
            $returnArr[$table] = DB::table($table)->get();

        return $returnArr;
    }


    //스킬 리스트 업
    public function skillListUp(){

        $skill_field_info = 'skill_field_info';
        $skill_info = 'skill_infos';
        $skill_level = 'skill_levels';

        $skill_level = DB::table($skill_level)->get();

        $skill_field = collect(DB::table($skill_field_info)->select('no','skill_field','skill_field_kana')->get())->keyBy('no');

        $skill_field_list = DB::table($skill_field_info)->get();

        foreach($skill_field_list as $v){
            $filed_id = $v->no;
      
            $skill[$filed_id] = collect(DB::table($skill_info)
                            ->select('no', 'skill_field_no', 'skill_name', 'skill_name_kana')
                            ->where('skill_field_no', '=', $filed_id)
                            ->get())->keyBy('no');
        }

        return ['skill_field_list' => $skill_field, 'skill_list' => $skill, 'skill_level_list'=> $skill_level];
    }

  /*
    //전체 테이블 리스트업
    public function index(){
    
       $business_big = DB::table('business_big_infos')->get();
       $business_small = DB::table('business_small_infos')->get();
       $work = DB::table('work_infos')->get();
       $welfare = DB::table('welfare_infos')->get();
       $desired_employment = DB::table('desired_employment_infos')->get();
       $interview = DB::table('interview_infos')->get();
    
       return [
               'business_big_infos'=> $business_big, 
               'business_small_infos'=> $business_small,
               'work_infos'=> $work,
               'welfare_infos'=> $welfare, 
               'desired_employment_infos'=> $desired_employment, 
               'interview_infos'=> $interview
        ];
    }
    */
}
