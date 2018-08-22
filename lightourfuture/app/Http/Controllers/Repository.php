<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Repository extends Controller
{

    public function repository(Request $request){
        //이력서 or 포트폴리오 레포지토리 파일이름 찾기
        $user_id = $request->get('std_user_id');    //유저 id
        $type = $request->get('type');          //이력서인지 포트폴리오인지 구분
        $url = $request->url();
        
        switch ($type){
            case 'personal_history':
                $type = 'personal_history';//이력서
            break;
            case 'portfolio':
                $type = 'portfolio';//포트폴리오
            break;
        }

        $repository_url = '/home/ubuntu/storage/repository';
        $repository_user_url = "/home/ubuntu/storage/repository/$user_id";
        $repository_type_url = "/home/ubuntu/storage/repository/$user_id/$type";
        
        
        //경로가 없을경우 폴더 생성
        if(!is_dir($repository_url)){
            mkdir($repository_url, 0777);
        }
        if(!is_dir($repository_user_url)){
            mkdir($repository_user_url, 0777);
        }
        if(!is_dir($repository_type_url)){
            mkdir($repository_type_url, 0777);
        }
        
        //레포지토리 경로를 연다
        $repository_type_url = opendir($repository_type_url);
        //레포지토리 내부 파일의 이름을 읽는다.
        while(($file = readdir($repository_type_url)) !== false)
        {
            $fname = ['file_name' => $file];
            if($file == "." || $file == ".."){
                continue;
            }
            
            //key: file_name, value = '파일이름.확장자' 반환
            echo $fname;
        }

        closedir($repository_type_url);

    }

    
}
