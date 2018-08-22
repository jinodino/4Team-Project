<?php
//by. hyojin
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    //
    public function createFile($type, $data, $folderName, $fileName){

        $exploded = explode(',', $data);
        $decoded = base64_decode($exploded[1]);

        $mimeType = explode('/',$type);


        $extension = null;
        //확장자 정해주기
        if($mimeType[0] == 'image'){

            if($mimeType[1] == 'jpeg')
                $extension = '.jpg';
            else if ($mimeType[1] == 'png')
                $extension = '.png';
            else if ($mimeType[1] == 'gif')
                $extension = '.gif';
            else if ($mimeType = 'svg+xml')
                $extension = '.svg';

        }else if($mimeType[0] == 'application'){

            //microsoft office
            if($mimeType[1] == 'msword')
                $extension = '.doc';
            else if($mimeType[1] == 'vnd.openxmlformats-officedocument.wordprocessingml.document')
                $extension = '.docx';
            else if($mimeType[1] == 'vnd.ms-powerpoint')
                $extension = '.ppt';
            else if($mimeType[1] == 'vnd.openxmlformats-officedocument.presentationml.presentation')
                $extension = '.pptx';
            else if($mimeType[1] == 'vnd.ms-excel')
                $extension = '.xls';
            else if($mimeType[1] == 'vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                $extension = '.xlsx';

            //pdf
            else if($mimeType[1] == 'pdf')
                $extension = '.pdf';

            //open documents
            else if($mimeType[1] == 'vnd.oasis.opendocument.text')
                $extension = '.odt';
            else if($mimeType[1] == 'vnd.oasis.opendocument.spreadsheet')
                $extension = '.ods';
            else if($mimeType[1] == 'vnd.oasis.opendocument.presentation')
                $extension = '.odp';
        }

        if($extension == null)
            return false;

            //
            $thisDate = date('Ymd');
            $thisTime = date('His');
        //파일 이름
        $fileName = $fileName."_".$thisDate.$thisTime.$extension;
        
        //경로 설정
        //local용
        $path =  public_path().'/'.$folderName.'/'.$fileName;

        //서버용
        //$path = '/home/ubuntu/storage/'.$folderName.'/'.$fileName;

        //파일 폴더에 저장
        try{
            file_put_contents($path, $decoded);
        }catch(\Exception $e){
            return "33";
        }
        
        
        //로컬용 파일 경로 반환
        return '/'.$folderName.'/'.$fileName;

        //서버용 파일 경로 반환
        //return '/storage/'.$folderName.'/'.$fileName;
    }

    public function updateFile(){

    }
    public function deleteFile($file){
        $root = public_path();
        $returnBool = @unlink($root.$file);

        return $returnBool;
    }
}
