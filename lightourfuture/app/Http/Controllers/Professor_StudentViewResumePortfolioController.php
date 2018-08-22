<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Storage;
use Carbon;
use Illuminate\Http\Response;
use ZipArchive;
class Professor_StudentViewResumePortfolioController extends Controller
{
    
    // 기업에 지원한 학생 이력서 또는 포트폴리오 다운로드
    public function applyStudentResumeOrPortfolioDownload() {
        // 교수 아이디 (로그인 아이디)
        $professor_id  = request('professorId');
        // 선택 기업 이름 
        $org_company_id    = request('selectedCompany');
        // 선택 채용건 아이디
        $employment_id = request('selectedRecruitment');
        // 이력서 or 포트폴리오
        $type          = request('type');

        // 채용건 아이디
        $employment_name = DB::table('employment_infos')
        ->where('employment_id', '=', $employment_id)
        ->pluck('employment_name');

        // 교수에 묶여 있는 학생리스트 추출
        $stdList = DB::table('students as std')
        ->join('applies as app', 'app.student_login_id', '=', 'std.login_id')
        ->where([
            ['std.professor_login_id', '=', $professor_id],
            ['app.employment_id', '=', $employment_id[0]->employment_id],
        ])
        ->select('login_id', 'name_eng')
        ->get();
    
        // 서버용
        $public_dir = public_path() . "/storage";
        
        // 로컬용
        //$public_dir = public_path();

        
        try {
            // 알집 넣을 폴더 생성
            if(!is_dir("$public_dir/zip")){
                umask(0);
                mkdir("$public_dir/zip", 0777);
            }else {
                // 예외처리 -> 만약 폴더가 있을 시 폴더안에 파일 삭제 후 폴더 삭제
                // -> 다시 폴더 생성 : 이유 => 프로젝트 용량을 줄이기 위해 한 폴더 내에 한 파일로 모든 알집 관리
                unlink("$public_dir/zip/testzip.zip");
                rmdir("$public_dir/zip");
                umask(0);
                mkdir("$public_dir/zip", 0777);
            }

        } catch (\Exception $e) {
            $returnBool = "폴더 경로 문제입니다";
            return ['returnBool'=>$returnBool];
        }
        
        
        $zipFileName = "testzip.zip";

        $filetopath = $public_dir . "/zip" . "/$zipFileName";

        $zip = new ZipArchive;
        // Resume/Portfolio
        if($type == "Resume") $type = 'entrySheets';
        else $type = 'portfolios';

        // 학생별 폴더 만들어줘야함
        
        //if ($zip->open($public_dir . '/zip/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
        if ($zip->open($filetopath, ZipArchive::CREATE) === TRUE) {

            // 기업명 폴더 생성
            $zip->addEmptyDir("$company_name");
            // 채용건 명 폴더 생성
            $zip->addEmptyDir("$company_name/$employment_name");

            // 학생 레포지토리에서 파일 가져오기
            foreach($stdList as $std) {
                // 학생 아이디
                $stdId = $std->login_id;
                // 학생별 레포지토리
                $student_dir = "/storage/repository/$stdId/$type";
                // 폴더 경로
                $folder_dir  = "$company_name/$employment_name";
                // 해당 학생 폴더에 채용건아이디로 시작한 이력서 및 포폴 들고옴
                $info = glob($student_dir . "/{$employment_id}_{$std->name_eng}_entrySheet_*");
                // 찾은 이력서 및 포폴 해당 .zip파일에 넣어줌
                $zip->addFile("$info[0]", "$folder_dir/$employment_name" . ".pdf");    
            }
            // 종료
            $zip->close();
        }

        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );

        
        

        if(file_exists($filetopath)){
            echo "#";
            return response()->download($filetopath,$zipFileName,$headers);
        }
    }

    // 전체 학생 이력서 또는 포트폴리오 리스트 다운로드
    public function allStudentResumeOrPortfolioDownload() {
        // 교수 아이디 (로그인 아이디)
        $professor_id = request('professorId');
        // 이력서 or 포트폴리오
        $type         = request('type');

        // 학생이 아직 회원가입을 진행하지 않았을 경우 $stdId가 없어서 폴더가 없음
        // 그러기 떄문에 학생 foreach 돌때마다 /storage/repository/$stdId 폴더의 유무를 판단해야함
        // 있을 시 -> $student_dir = "/storage/repository/$stdId/$type" 경로의 파일들 다 끌고오기
        // 없을 시 -> 누락하고 진행

        // 학생 리스트 뽑기
        $stdList = DB::table('students')
        ->where('professor_login_id', '=', $professor_id)
        ->select('login_id', 'name')
        ->get();
        
        // 서버용
        $public_dir = public_path() . "/storage";
        
        // 로컬용
        //$public_dir = public_path();

        // 알집 넣을 폴더 생성
        if(!is_dir("$public_dir/zip")){
            umask(0);
            mkdir("$public_dir/zip", 0777);
        }else {
            // 예외처리 -> 만약 폴더가 있을 시 폴더안에 파일 삭제 후 폴더 삭제
            // -> 다시 폴더 생성 : 이유 => 프로젝트 용량을 줄이기 위해 한 폴더 내에 한 파일로 모든 알집 관리
            unlink("$public_dir/zip/testzip.zip");
            rmdir("$public_dir/zip");
            umask(0);
            mkdir("$public_dir/zip", 0777);
        }
        
        $zipFileName = "testzip.zip";

        $filetopath = $public_dir . "/zip" . "/$zipFileName";

        $zip = new ZipArchive;

        if($type == "Resume") $type = 'entrySheets';
        else $type = 'portfolios';

        // 학생별 폴더 만들어줘야함
        
        //if ($zip->open($public_dir . '/zip/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
        if ($zip->open($filetopath, ZipArchive::CREATE) === TRUE) {
            foreach($stdList as $std) {
                // 학생별 레포지토리
                $student_dir = "/storage/repository/$std->login_id/$type";
                if(is_dir($student_dir)) {
                    // 학생명 폴더 생성
                    $zip->addEmptyDir("$std->name");
                    // 폴더 경로
                    $folder_dir  = "$std->name";
                    // 해당 학생 폴더에 채용건아이디로 시작한 이력서 및 포폴 들고옴
                    $info = glob($student_dir . "/*");
                    // 파일 갯수만 큼 폴더에 넣어줘야함
                    foreach($info as $key => $value) {
                        // 찾은 이력서 및 포폴 해당 .zip파일에 넣어줌
                        $zip->addFile("$value", "$folder_dir/$value");    
                    }
                }
                
            }
            $zip->close();
        }

        return "Valid";
    }


    // 선택 학생 이력서 또는 포트폴리오 리스트
    public function studentPortfolio_list(Request $request) {
        $user_id = $request->get('std_id');
        
        
        //-------------------------------------쓰레기 값-------------------------------------
        return json_encode(
            array('personal_history' => array('첫번째 이력서', '두번째 이력서', '세번째 이력서', '네번째 이력서'), 
                  'portfolio' => array('첫번째 포트폴리오', '두번째 포트폴리오', '세번째 포트폴리오', '네번째 포트폴리오')
        ));
        //-------------------------------------쓰레기 값-------------------------------------

        // 학생별 레포지토리
        $repository_url = '/home/ubuntu/storage/repository';
        $repository_user_url = "/home/ubuntu/storage/repository/$user_id";
        $repository_personal_history_url = "/home/ubuntu/storage/repository/$user_id/personal_history"; //이력서
        $repository_portfolio_url = "/home/ubuntu/storage/repository/$user_id/portfolio";       //포트폴리오
        
        //경로가 없을경우 폴더 생성
        if(!is_dir($repository_url)){
            umask(0);
            mkdir($repository_url, 0777);
        }
        if(!is_dir($repository_user_url)){
            umask(0);
            mkdir($repository_user_url, 0777);
        }
        
        if(!is_dir($repository_personal_history_url)){
            umask(0);
            mkdir($repository_personal_history_url, 0777);
        }
         if(!is_dir($repository_portfolio_url)){
            umask(0);
            mkdir($repository_portfolio_url, 0777);
        }
        
        

        //이력서 리스트를 불러온다.
        $personal_history_list = [];
        //레포지토리 경로를 연다
        $repository_personal_history_url = opendir($repository_personal_history_url);
        //레포지토리 내부 파일의 이름을 읽는다.
        while(($file = readdir($repository_personal_history_url)) !== false)
        {
            if($file == "." || $file == ".."){
                continue;
            }
            
            //key: file_name, value = '파일이름.확장자' 반환
            array_push($personal_history_list, $file);
        }
        closedir($repository_personal_history_url);
        
        
        //포트폴리오 리스트를 불러온다.
        $portfolio_list = [];
        $repository_portfolio_url = opendir($repository_portfolio_url);
        //레포지토리 내부 파일의 이름을 읽는다.
        while(($file = readdir($repository_portfolio_url)) !== false)
        {
            if($file == "." || $file == ".."){
                continue;
            }
            
            //key: file_name, value = '파일이름.확장자' 반환
            array_push($portfolio_list, $file);
        }
        closedir($repository_portfolio_url);
        
        
        
        return json_encode(array('personal_history' => $personal_history_list, 'portfolio' => $portfolio_list));
    }

    //학생 PR동영상 출력
    public function studentPRVideo(Request $request) {
        $user_id = $request->get('std_id');

        $pr_video = DB::table('students')
        ->select('pr_video_url')
        ->where('login_id', '=', $user_id)
        ->get();

        return $pr_video;
    }
}
