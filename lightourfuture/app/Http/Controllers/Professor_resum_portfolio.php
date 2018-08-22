<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Professor_resum_portfolio extends Controller
{
    public function resum_portfolio_list(Request $request){
        $user_id    =   $request->get('student_login_id');
        $type       =   $request->get('selectedType');//이력서 or 포트폴리오
        
        if($type == "이력서"){
            $type = "entrySheets";
        }else if($type == "포트폴리오"){
            $type = "portfolios";
        }
        
        /*
        /storage/students/학생ID/entrySheets
        /storage/students/학생ID/portfolios
        */

        $repository_user = "/storage/students";
        $repository_repository = $repository_url."/$user_id";
        $repository_type    =   $repository_repository."/$type";
        /*
        //경로가 없을경우 폴더 생성
        if(!is_dir($repository_user)){
            umask(0);
            mkdir($repository_user);
        }
        if(!is_dir($repository_repository)){
            umask(0);
            mkdir($repository_repository);
        }
        if(!is_dir($repository_type)){
            umask(0);
            mkdir($repository_type);
        }

        */
        //파일이름을 임시로 저장
        $filename = array();

        //레포지토리 경로를 연다
        $repository_type_url = opendir($repository_type);
        //레포지토리 내부 파일의 이름을 읽는다.
        while(($file = readdir($repository_type_url)) !== false)
        {
            //$fname = ['file_name' => $file];
            if($file == "." || $file == ".."){
                continue;
            }
            
            //key: file_name, value = '파일이름.확장자' 반환
            array_push($filename,$file);
        }
        
        closedir($repository_type_url);

        return json_encode($filename);
        
        
    }
}