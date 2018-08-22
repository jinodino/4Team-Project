<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class MakeInterviewScheduleController extends Controller
{
    // 다음 면접일정 등록
    public function nextInterviewSchedule($interview_id) {

        // employment_id  받아와야함
        $employment_id = DB::table('interview_schedules')
        ->where('interview_id', '=', $interview_id)
        ->pluck('employment_id');

        $start_time = DB::table('interview_schedules')
        ->where('interview_id', '=', $interview_id)
        ->select('interview_date', 'interview_start_time')
        ->get();
        
        $start_time = $start_time[0]->interview_date . ' ' . $start_time[0]->interview_start_time;
        
        if(!isset($employment_id[0])) return;

        $interview_ids = DB::table('interview_schedules')
        ->where('employment_id', '=', $employment_id[0]) 
        ->pluck('interview_id');
          
        $index = sizeof($interview_ids) - 2;
        //$interview_id[$index];
        // 그 채용 공고에서 가장 마지막 Interview_id값을 받아온다
        // $interview_id = DB::table('interview_schedules')
        // ->select('interview_id')
        // ->where([
        //     ['employment_id', '=', $value],
        //     ['interview_check_ox', '=', 'o'],
        // ])
        // ->oldest('interview_date')
        // ->first();
        

        // 마지막 인터뷰 interview_results table -> interview_result 'o'인 학생리스트 추출
        $passStdList = DB::table('interview_results')
        ->where([
            ['interview_id', '=', $interview_ids[$index]],
            ['interview_result', '=', 'o'],
        ])
        ->pluck('student_login_id');

        
        // 끝값도 해야함
        foreach($passStdList as $pass) {
            // 다음 인터뷰 일정의 interview_id값을 가지고 interview_results테이블에 insert해준다.
            $insert = DB::table('interview_results')
            ->insert(['interview_id' => $interview_id, 'student_login_id' => $pass, 'interview_start_time' => $start_time]);

            if(!$insert) return 'no';
        }
        
        return 'yes';
    }
}
