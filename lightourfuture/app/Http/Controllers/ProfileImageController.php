<?php
//by. hyojin
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileImageController extends Controller
{

    public function store(Request $request){
        $exploded = explode(',', $request->data);
        $decoded = base64_decode($exploded[1]);
      
/*
        //확장자 선택
        if(str_contains($exploded[0], 'jpeg'))
            $extension = 'jpg';
        else
            $extension = 'png';
*/
        //파일 이름
        $fileName = $request->name; //.'.'.$extension;
        
        //경로 설정
        $profile = public_path().'/profileImages';
        $path =  $profile.'/'.$fileName;

        //파일 폴더에 저장
        file_put_contents($path,$decoded);

        //파일 DB 넣어주기
        //$fileUpload = FileUpload::create(['image_name'=>$fileName, 'image_src'=>$path]);
        /*
        $fileUpload = new FileUpload([
            'image_name'=>$fileName,
            'image_url'=> $path
        ]);
        $fileUpload->save();
        */
        
        //파일 경로 반환
        return '/profileImages/'.$fileName;
        
    }

    public function remove(Request $req){
        \Log::info($req->all());
        unlink(public_path()."$req->src");
        return $req;
    }


    //이력서 / 포트폴리오 업로드
    //확장자에 따라서 따로 넣어주고 pdf 로 바꿔서 출력싶으나....
    public function storeEs(Request $request){
        \Log::info($request->all());
        $exploded = explode(',', $request->data);
        $decoded = base64_decode($exploded[1]);
        //파일 이름
        $fileName = $request->name;

        //경로 설정
        $repository = public_path().'/entrySheet';
        $path =  $repository.'/'.$fileName;

        //파일 넣어주기
        file_put_contents($path,$decoded);
        
        return $path;
    } 


    public function storeAnjung($profileImage, $fileFolder){ 
        
      $exploded = explode(',', $profileImage['data']);
      $decoded = base64_decode($exploded[1]);

      //파일 이름
      $fileName = $profileImage['name']; //.'.'.$extension;
      //파일 폴더

      //경로 설정
      $path = '/'.$fileFolder.'/'.$fileName;
      $holepath = public_path().$path ;

      //파일 폴더에 저장
      file_put_contents($holepath, $decoded);

        //파일 경로 반환
        return $path;
        
}
    

    
}
