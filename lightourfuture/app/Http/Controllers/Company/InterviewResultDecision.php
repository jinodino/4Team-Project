<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class InterviewResultDecision extends Controller
{
    // 면접 합불 여부 판단 -> 수정 
    public function interviewResultDecisionUpdate($interviewId, $studentId, $status) {
        // 해당 interview ID  / 학생 아이디 / 상태(?)
        // 진행중 -> NULL / 합격 -> o / 불합격 -> x
        
        // 면접 내용이 최종면접일 시 -> applies Table -> acceptance_ox => 'o'로 변경
        // interview_schedules Table -> Interview_ID를 가진 Interview_content_choice가 
        // interveiw_infos Table -> content가 "최종면접"이면 applies Table -> acceptance_ox -> o

        $interview_content = DB::table('interview_schedules as is')
        ->select('ii.id')
        ->join('interview_infos as ii', 'ii.id', '=', 'is.Interview_content_choice')
        ->where('is.interview_id', '=', $interviewId)
        ->get();
        
        // 인터뷰 내용이 없을 시
        if(!isset($interview_content[0])) return 0;
        if($status) $status = 'o';
        else $status = 'x';

        $employmentId = DB::table('interview_schedules')
            ->where([
                ['interview_id', '=', $interviewId],
                ['interview_active', '=', 'o'],
            ])
            ->pluck('employment_id');

        // // 인터뷰 내용이 최종면접일 시 applies Table의 acceptance_ox값 'o'로 변경
        // if($interview_content[0]->id == 5 && $status == 'o') {

        //     // 예외처리 채용공고 ID가 없을 시 
        //     if(!isset($employmentId[0])) return 0;

        //     $update = DB::table('applies')
        //     ->where([
        //         ['employment_id', '=', $employmentId[0]],
        //         ['student_login_id', '=', $studentId],
        //     ])
        //     ->update(['acceptance_ox' => $status]);
            
            
        //     if(!$update) return 0;

        // }
        
       
        // interview result -> update
        $update = DB::table('interview_results')
        ->where([
            ['interview_id', '=', $interviewId],
            ['student_login_id', '=', $studentId],
        ])
        ->update(['interview_result' => $status]);

        // 면접 떨어질 시
        // if($status == 'x') {
        //     $update = DB::table('applies')
        //     ->where([
        //         ['employment_id', '=', $employmentId[0]],
        //         ['student_login_id', '=', $studentId],
        //     ])
        //     ->update(['acceptance_ox' => $status]);
        // }

        $update = DB::table('applies')
            ->where([
                ['employment_id', '=', $employmentId[0]],
                ['student_login_id', '=', $studentId],
            ])
            ->update(['acceptance_ox' => $status]);
        

        // update error 
        if(!$update) return 0;

        // good
        return 1;


    }
}
