<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PushNotify as PushNotify;
use DB;
class NextInterviewController extends Controller
{
     // 다음 일정으로 넘기기
     public function nextInterview() {
        
        // 현제 인터뷰 아이디 
        $company_manager = request('requester');
        $interview_id = request('noticeId');
        $employment_id = request('employmentId');
        $apiKey = request('apiKey');

        // 푸쉬 알람 
        $pushNotify = new PushNotify();
        // 합불 여부 확인 값
        $comfirm = $this->nextInterviewConfirm($interview_id);

        // 0 -> 합불 여부를 확인해 주세요 
        if(!$comfirm) return 2;

        // 인터뷰에 있는 학생을 다음 일정으로 넘겨주기  //

        // 지원 학생 아이디 리스트
        $applyStdList = DB::table('interview_results')
        ->where([
            ['interview_id', '=', $interview_id],
            ['interview_result', '=', 'o']
        ])
        ->pluck('student_login_id');
        
        // 학생 합불여부 판단 후 -> 다음 일정 유무 판단 -> 다음일정 인터뷰ID 
        $nextInterview_id = $this->nextInterviewStatus($employment_id, $interview_id);
        
        if(!$nextInterview_id) return 3;

        
        // 알림 넘기기
        $passInfo = $this->passStudentList($interview_id);

        // 합격자 명단
        // $passStd = "";
        // foreach($passInfo['passStd'] as $std) {
        //     $passStd .= $std;
        // }

        // 교수님 faculty 구하기
        $faculty = DB::table('professors')
        ->where([
            ['login_id', '=', $passInfo['professorId']],
        ])
        ->pluck('faculty_id');

        // 초대 에이전트 구하기
        $orgAgent = DB::table('professors')
        ->where([
            ['login_id', '=', $passInfo['professorId']],
        ])
        ->pluck('invite_agent_id');

        // 에이전트 명단 
        $agentList =  DB::table('agent_belongs')
        ->where([
            ['org_agent_id', '=', $orgAgent[0]],
        ])
        ->pluck('agent_id');

        // 같은 계열의 교수님 명단
        $professorList = DB::table('professors')
        ->where([
            ['faculty_id', '=', $faculty[0]],
        ])
        ->pluck('login_id');

        $org_name = DB::table('org_companies')
        ->select('org_name', 'org_name_kana')
        ->where([
            ['manager_login_id', '=', $company_manager],
        ])
        ->get();

        $interview_info = DB::table('interview_schedules as interview')
        ->select('interview.interview_count', 'if.content')
        ->join('interview_infos as if', 'interview.interview_content_choice', '=', 'if.id')
        ->where([
            ['interview.interview_id', '=', $interview_id],
        ])
        ->get();
        
       
        foreach($agentList as $agent) {
            $pushNotify->push_select_send($org_name[0]->org_name . ($org_name[0]->org_name_kana), $agent, $apiKey, $org_name[0]->org_name . ($org_name[0]->org_name_kana) . "の" . $interview_info[0]->interview_count . "回の面接" . ($interview_info[0]->content) . "が終わりました");
        }
        foreach($professorList as $professor) {
            $pushNotify->push_select_send($org_name[0]->org_name . ($org_name[0]->org_name_kana), $professor, $apiKey, $org_name[0]->org_name . ($org_name[0]->org_name_kana) . "の" . $interview_info[0]->interview_count . "回の面接" . ($interview_info[0]->content) . "が終わりました");
        }
        
        if($nextInterview_id == 'o') return 4;

        foreach($applyStdList as $std) {
            // 다음일정 날짜 추출
            $interview_date = DB::table('interview_schedules')
            ->where('interview_id', '=', $nextInterview_id)
            ->pluck('interview_date');
            // 다음일정 날짜에 학생들 등록
            $insert = DB::table('interview_results')
            ->insert([
                'interview_id'         => $nextInterview_id,
                'student_login_id'     => $std,
                'interview_start_time' => $interview_date[0],
            ]);

            if(!$insert) return "insert문 에러";
        }

        // 다음 면접일정에 Active 시켜줘야함 -> 현재 하고 있는 건 Active -> x
        $interviewActive     = DB::table('interview_schedules')
        ->where('interview_id', '=', $interview_id)
        ->update([
            'interview_active' => 'x',
        ]);

        if(!$interviewActive) return '지난 면접 활성화 닫기 애러';

        $nextInterviewActive = DB::table('interview_schedules')
        ->where('interview_id', '=', $nextInterview_id)
        ->update([
            'interview_active' => 'o',
        ]);

        if(!$nextInterviewActive) return '다음 면접 활성화 열기 애러';

        
         
        // Employment Notice와 Apply STD LIST SELECT다시 해줘야함
        return 1;
    }

    // 다음일정으로 넘기기
    public function nextInterviewConfirm($interview_id) {
       
       // 현재 지원학생들에 대해 합불 여부를 다 내려줬는지 아닌지 유무확인
       $applyStdResult = DB::table('interview_results')
       ->where('interview_id', '=', $interview_id)
       ->pluck('interview_result');

       foreach ($applyStdResult as $result) {
           // 값이 전부 내려지지 않았을 시 -> 합불 여부 확인해주세요
           if(is_null($result)) return 0;
       }

       // 다음 일정으로 넘김
       return 1;
    }

    // 다음 일정 유무 판단
    public function nextInterviewStatus($employment_id, $interview_id) {
        // 채용건 아이디를 가지고 교수님 컴펌을 받은 인터뷰Id 배열에 넣고
        // 현재 진행중인 인터뷰Id 배열인덱스 값 +1한 인터뷰값 찾음
        
        $interviewList = DB::table('interview_schedules')
        ->where('employment_id', '=', $employment_id)
        ->pluck('interview_id');

        // 마지막 면접일 시 1, 아니면 0
        $finalInterviewConfirm = $this->finalInterviewConfirm($interview_id);
        
        // 마지막 면접 일 시
        if($finalInterviewConfirm == 1) {
          
            // 최종면접이고 면접 본인원의 결과 통보가 다 되었을 경우
            $count = DB::table('interview_results')
            ->where([
                ['interview_id', '=', $interview_id],
                ['interview_result', '=', null],
            ])
            ->count();

            // 위에서 걸러줌
            //if($count == 0) {
            // 채용공고 닫음
            $update = DB::table('employment_infos')
            ->where('employment_id', '=', $employment_id)
            ->update(['employment_owari_ox' => 'o']);
            
            if(!$update) return 0;

            return 'o';
            //}
            // else {
            //     return "학생들 합불 여부를 내려주세요";
            // }

           
        }

        foreach($interviewList as $key => $value) {
            // 현재 인터뷰 값과 같은 인덱스값 찾기
            if($interview_id == $value) {
                $nextkey = $key + 1;
            }
        }

        // 다음 인터뷰 일정 유무 판단
        // 0 -> 다음 인터뷰 일정 없음 --> 인터뷰 일정 잡고 오세요 출력
        if(!isset($interviewList[$nextkey])) return 0;

        // 다음 일정 인터뷰 아이디값 리턴
        return $interviewList[$nextkey];
    }

    // 최종면접인지 확인 
    public function finalInterviewConfirm($interview_id) {
        
        // 면접 종류
        $interviewContentChoice = DB::table("interview_schedules")
        ->where([
            ['interview_id', '=', $interview_id],
        ])
        ->pluck('interview_content_choice');

        // 최종면접일 시 return 1 아니면 0
        if($interviewContentChoice[0] == 5) return 1;
        else return 0;
    }

    // PUSH 알림을 위하여
    // 해당 인터뷰의 지원 학생 중 합격자 들 뽑기
    public function passStudentList($interview_id) {
        // 합격자 리스트 
        $passStdList = DB::table('interview_results')
        ->where([
            ['interview_id', '=', $interview_id],
            ['interview_result', '=', 'o']
        ])
        ->pluck('student_login_id');
        
        // 교수님 아이디
        $professorId  = DB::table('students')
        ->where([
            ['login_id', '=', $passStdList[0]]
        ])
        ->pluck('professor_login_id');



        return array('passStd' => $passStdList, 'professorId' => $professorId[0]);
    }

    // 알림 수신자 교수님 뽑기
    public function professorFind($interview_id) {
        $passStdList = DB::table('interview_results')
        ->where([
            ['interview_id', '=', $interview_id],
            ['interview_result', '=', 'o']
        ])
        ->pluck('student_login_id');
    }
}
