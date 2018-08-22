<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon;
use ZipArchive;
use Storage;
class StudentEntrySheetDownloadController extends Controller
{
    public function studentEntrySheetDownload() {
        
        // 채용건 ID && 스테이지 ID
        $employment_id = request('noticeId'); // employment_ID
        $interview_stage = request('stage'); // interview_count
        
        $interview_id  = DB::table('interview_schedules')->where([
            ['employment_id', '=', $employment_id],
            ['interview_count', '=', $interview_stage],
            ['interview_check_ox', '=', 'o'],
        ])->pluck('interview_id');
        
        if(!isset($interview_id[0])) return 0;

        $applyStd = DB::table('interview_results as itr')
        ->where([
            ['itr.interview_id', '=', $interview_id[0]],
        ])
        ->join('students as std', 'itr.student_login_id', '=', 'std.login_id')
        ->select('std.login_id', 'std.name_eng', 'name_kana')
        ->get();

            
        $public_dir = public_path() . "/storage";

        if(!is_dir("$public_dir/zip")){
            umask(0);
            mkdir("$public_dir/zip", 0777);
        }else {
            unlink("$public_dir/zip/testzip.zip");
            // rmdir("$public_dir/zip");
            // umask(0);
            // mkdir("$public_dir/zip", 0777);
        }
        
        $zipFileName = "testzip.zip";

        $filetopath = $public_dir . "/zip/" . $zipFileName;
        //return $zipFileName; 
        $zip = new ZipArchive;

        // 학생별 폴더 만들어줘야함
        
        if ($zip->open($public_dir . '/zip/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
            foreach($applyStd as $std) {
                // 학생 아이디
                $stdId = $std->login_id;
                // 학생별 레포지토리
                $student_dir = "/storage/repository/$stdId/";
                // 학생 폴더 명
                $folder_name = "$std->name_eng" . "($std->name_kana)";
                // .zip파일에 학생 폴더 생성
                $zip->addEmptyDir($folder_name);
                // 해당 학생 폴더에 채용건아이디로 시작한 이력서 및 포폴 들고옴
                $entrySheet = glob($student_dir . "entrySheets/{$employment_id}_{$std->name_eng}_entrySheet*"); 
                $portfolio  = glob($student_dir . "portfolios/{$employment_id}_{$std->name_eng}_portfolio*"); 
                // 찾은 이력서 및 포폴 해당 .zip파일에 넣어줌
                $zip->addFile("$entrySheet[0]", "$folder_name/entrySheet.pdf");
                // 포트폴리오는 선택형 -> 있으면 넣고 아니면 뺌
                if(isset($portfolio[0])) {
                    $zip->addFile("$portfolio[0]", "$folder_name/portfolio.pdf");        
                }        
                // 종료
                $zip->close();
            }
        }

        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );

        
        

        if(file_exists($filetopath)){
            echo "#";
            return response()->download($filetopath,$zipFileName,$headers);
        }
        
    }
}
