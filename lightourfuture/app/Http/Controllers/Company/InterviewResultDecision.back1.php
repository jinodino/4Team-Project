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
        ->select('ii.content')
        ->join('interview_infos as ii', 'ii.id', '=', 'is.Interview_content_choice')
        ->where('is.interview_id', '=', $interviewId)
        ->get();
        
        // 인터뷰 내용이 없을 시
        if(!isset($interview_content[0])) return 0;
        

        $employmentId = DB::table('interview_schedules')
            ->where([
                ['interview_id', '=', $interviewId],
                ['interview_active', '=', 'o'],
            ])
            ->pluck('employment_id');

        // 인터뷰 내용이 최종면접일 시 applies Table의 acceptance_ox값 'o'로 변경
        if($interview_content[0]->content == '최종면접') {

            // $employmentId = DB::table('interview_schedules')
            // ->where([
            //     ['interview_id', '=', $interviewId],
            //     ['interview_active', '=', 'o'],
            // ])
            // ->pluck('employment_id');

            // 예외처리 채용공고 ID가 없을 시 
            if(!isset($employmentId[0])) return 0;

            $update = DB::table('applies')
            ->where([
                ['employment_id', '=', $employmentId[0]],
                ['student_login_id', '=', $studentId],
            ])
            ->update(['acceptance_ox' => 'o']);
            
            
            if(!$update) return 0;

        }
        
        if($status) $status = 'o';
        else $status = 'x';
        // interview result -> update
        $update = DB::table('interview_results')
        ->where([
            ['interview_id', '=', $interviewId],
            ['student_login_id', '=', $studentId],
        ])
        ->update(['interview_result' => $status]);

        // 면접 떨어질 시
        if($status == 'x') {
            $update = DB::table('applies')
            ->where([
                ['employment_id', '=', $employmentId[0]],
                ['student_login_id', '=', $studentId],
            ])
            ->update(['acceptance_ox' => 'x']);
        }

        // 채용공고 닫기 위함
        if($interview_content[0]->content == '최종면접') {
            // 면접 본인원의 결과 통보가 다 되었을 경우
            $count = DB::table('interview_results')
            ->where([
                ['interview_id', '=', $interviewId],
                ['interview_result', '=', null],
            ])
            ->count();

            if($count == 0) {
                // 채용공고 닫음
                $update = DB::table('employment_infos')
                ->where('employment_id', '=', $employmentId[0])
                ->update(['employment_owari_ox' => 'o']);
                
                if(!$update) return 0;
            }

        }
        

        // update error 
        if(!$update) return 0;

        // good
        return 1;


    }
}
