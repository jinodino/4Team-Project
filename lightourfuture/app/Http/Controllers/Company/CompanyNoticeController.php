<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Controllers\Company\InterviewResultDecision;
class CompanyNoticeController extends Controller
{
    // 기업 -> 학교 채용의사 변경 알림  / frontend -> Company/profile -> Matching Information 
    public function companyMatchingSwitchNotice() {
        
        // 발신자 -> 기업 / 수신자 -> 매칭 담당 에이전트
        $to         = request('agentId');       // 기관 에이전트 ID
        $from       = request('requester');     // 기업 ID
        $status     = request('status');        // 매칭 상태
        $college_id = request('schoolId');      // 학교 아이디
        
        // 매칭 상태 
        if($status) $status_data = "X";
        else $status_data = "O";
        
        // 기업 이름 
        $company_name = DB::table('org_companies')->where('manager_login_id', '=', $from)->select('org_name', 'org_name_kana')->get();
        
        // 학교 카타카나 이름
        $college_name = DB::table('org_colleges')->where('org_college_id', '=', $college_id)->select('org_name', 'org_name_kana')->get();

        // 전송 메일
        $sendData = "{$company_name[0]->org_name} ({$company_name[0]->org_name_kana})会社が {$college_name[0]->org_name}({$college_name[0]->org_name_kana})大学の採用意思を{$status_data}で変更要請をお願いします。";
        
        // 기관 에이전트에 있는 모든 사람에게 전송
        $agentList = DB::table('agent_belongs')->where('org_agent_id', '=', $to)->pluck('agent_id');

        
        foreach($agentList as $al) {
            
            $notification = DB::table('notifications')->insert([
                'send_user_id' => $from,
                'get_user_id'  => $al,
                'notification' => $sendData,
            ]);
            
            // 전송 실패 시 
            if(!$notification) return 0;
        }
        
        // 전송 성공
        return 1;
    }

    // 지원 마감에 대한 함수 -> 지금 안씀
    public function employmentSwitchNotice() {
        // 발신자 -> 기업 / 수신자 -> 매칭 담당 에이전트
        return request();
        $to               = request('agentId');      // 에이전트 ID
        $from             = request('requester');    // 기업 ID
        $status           = request('status');       // 지원 마감 상태
        $noticeId         = request('noticeId');     // 채용공고 ID -> employmentID
        $newApplyDaedline = request('newDeadline');  // 바꿀 deadLine

        // 학교 기업 이름 값 뽑기
        $co_id = DB::table('org_matchings')->where('org_matchings_id', '=', $noticeId)->select('org_college_id', 'org_company_id')->get();
        
        // 기업 이름 
        $company_name = DB::table('org_companies')->where('org_company_id', '=', $co_id[0]->org_company_id)->select('org_name', 'org_name_kana')->get();
        
        // 학교 카타카나 이름
        $college_name = DB::table('org_colleges')->where('org_college_id', '=', $co_id[0]->org_college_id)->select('org_name', 'org_name_kana')->get();
        
        // 채용공고 이름
        $employment_name = DB::table('employment_infos')->where('employment_id', '=', $noticeId)->pluck('employment_name');
        
        // 지원 마감 상태
        if($status) $status_data = "OPEN";
        else $status_data = "CLOSE";
        
        // applyDeadline 있을 시 -> 채용공고 ClOSE -> OPEN <새로운 applyDeadline>
        if($newApplyDaedline) $sendData = "{$company_name[0]->org_name} ({$company_name[0]->org_name_kana})会社と {$college_name[0]->org_name}({$college_name[0]->org_name_kana})大学と協力する採用件「{$employment_name[0]}」の状況を{$status_data}と締め切りを{$newApplyDaedline}まで変更要請をお願いします。";
        // applyDeadline 없을 시 -> 채용공고 OPEN -> ClOSE 
        else $sendData = "{$company_name[0]->org_name} ({$company_name[0]->org_name_kana})会社と {$college_name[0]->org_name}({$college_name[0]->org_name_kana})大学と協力する採用件「{$employment_name[0]}」の状況をCLOSEで変更要請をお願いします。";
    
        $notification = DB::table('notifications')->insert([
            'send_user_id' => $from,
            'get_user_id'  => $to,
            'notification' => $sendData,
        ]);

        if(!$notification) return 0;

        return 1;
    }

    // 기업 -> 학생 합불 여부 확인 통보 알람
    public function studentInterviewResultDecisionNotice() {
        // 발산지 -> 기업 / 수신자 -> 교수 AND 에이전트

        // 지원 학생 리스트 출력 시 
        // 리스트에서 뽑을 수 있는 것 -> 에이전트 ID, employment ID, matchings ID, college ID
        // 공고리스트 눌렀을 시 학생에서 뽑을 수 있는 것
        // -> 학생 ID(교수 ID, 학생 이름), 학교 ID(학교이름), 에이전트 ID, employment ID, interview ID, Interview result, Interview count
        // 수신자 -> 교수 O , 에이전트 O / 발신자 -> 기업 O
        // 학교 아이디 -> 학교 이름, 학생 이름 <OO기업의 소프트웨어 모집공고에 영진대학교의 OOO학생의 면접 결과는 합/불 입니다>
        // 기업 아이디 -> 기업 이름 
        // employment ID -> 모집 공고  제목
        // Interview count -> 회차
        // Interview result -> 합 / 불 
        
        $to1                = request('agentId');       // 에이전트 ID
        $from               = request('companyId');     // 기업 ID -> 기업 이름
        $college_id         = request('schoolId');      // 학교 ID -> 학교 이름
        $student_id         = request('stdId');         // 학생 ID -> 학생 이름 -> 배열
        $noticeId           = request('noticeId');      // 채용 ID -> 채용 이름   
        $interview_id       = request('interviewId');   // 인터뷰 ID
        $interview_count    = request('stage');         // 인터뷰 회차
        $interview_result   = request('result');        // 면접 결과    

        // // 교수 아이디 -> 수신자
        // $to2 = DB::table('students')->where('login_id', '=', $student_id)->pluck('professor_login_id');

        // // 기관 에이전트에 있는 모든 사람에게 전송
        // $agentList = DB::table('agent_belongs')->where('org_agent_id', '=', $to1)->pluck('agent_id');

        
        // // 수신자 담기
        // $to_arr = [];
        // foreach($agentList as $al) {
        //     array_push($to_arr, $al);    
        // }
        // array_push($to_arr, $to2[0]);

        // // 기업 이름 
        // $company_name = DB::table('org_companies')->where('manager_login_id', '=', $from)->select('org_name', 'org_name_kana')->get();
        
        // // 학교 카타카나 이름
        // $college_name = DB::table('org_colleges')->where('org_college_id', '=', $college_id)->select('org_name', 'org_name_kana')->get();

        // // 채용공고 이름
        // $employment_name = DB::table('employment_infos')->where('employment_id', '=', $noticeId)->pluck('employment_name');

        // // 매칭 상태 
        // if($interview_result) $status_data = "PASS";
        // else $status_data = "FAIL";

        

        $InterviewResultDecision = new InterviewResultDecision();

        // 면접결과 선고 && 면접결과 교수 및 에이전트 전송
        foreach($student_id as $std) {
        
            // param -> 인터뷰 ID, 학생 ID, 상태
            $decision = $InterviewResultDecision->InterviewResultDecisionUpdate($interview_id, $std, $interview_result);

            if(!$decision) return 0;

            // // 학생 이름 
            // $student_name = DB::table('students')->where('login_id', '=', $std)->select('name', 'name_eng')->get();

            // $sendData = "{$company_name[0]->org_name}({$company_name[0]->org_name_kana})会社の「{$employment_name[0]}」採用件の{$interview_count}回の面接に{$college_name[0]->org_name}({$college_name[0]->org_name_kana})
            // 大学の{$student_name[0]->name}({$student_name[0]->name_eng})学生の
            // 面接結果は{$status_data}です。";
            // // <OO기업의 소프트웨어 모집공고 O차 면접에 영진대학교의 OOO학생의 면접 결과는 합/불 입니다>

            // // 알림 테이블 삽입
            // //$check = 0; // 메일 발송 체크 값
            // foreach($to_arr as $arr) {
            //     $notification = DB::table('notifications')->insert([
            //         'send_user_id' => $from,
            //         'get_user_id'  => $arr,
            //         'notification' => $sendData,
            //     ]);

            //     if(!$notification) return 0;
            // }


            //if($check != 2) return 0;
        }
        

        return 1;
    }

    // 채용건 마감 함수 recruitStatus.vue -> 
    public function closeEmploymentNotice() {
        // 채용건 아이디, 기업 아이디, 에이전트 아이디
        $to               = request('agent');      // org_agent_id -> 기관에 담긴 모든 인원에게 알림 설정
        $from             = request('requester');  // 기업 ID
        $noticeId         = request('notice');     // 채용공고 ID -> employmentID
        return request();
        // org_matching_foreign 
        $matching_foreign = DB::table('employment_infos')->where('employment_id', '=', $noticeId)->pluck('org_matching_foreign');

        // 학교 기업 이름 값 뽑기
        $co_id = DB::table('org_matchings')->where('org_matchings_id', '=', $matching_foreign[0])->select('org_college_id', 'org_company_id')->get();
        
        // 기업 이름 
        $company_name = DB::table('org_companies')->where('org_company_id', '=', $co_id[0]->org_company_id)->select('org_name', 'org_name_kana')->get();
        
        // 학교 카타카나 이름
        $college_name = DB::table('org_colleges')->where('org_college_id', '=', $co_id[0]->org_college_id)->select('org_name', 'org_name_kana')->get();
        
        // 채용공고 이름
        $employment_name = DB::table('employment_infos')->where('employment_id', '=', $noticeId)->pluck('employment_name');

        // 알람 메시지
        $sendData = "{$company_name[0]->org_name} ({$company_name[0]->org_name_kana})会社と {$college_name[0]->org_name}({$college_name[0]->org_name_kana})大学と協力する採用件「{$employment_name[0]}」の状況をCLOSEで変更要請をお願いします。";

        
        // 에이전트 소속 인원 추출
        $agentList = DB::table('agent_belongs')->where('org_agent_id', '=', $to)->pluck('agent_id');
        
        foreach($agentList as $al) {
            
            $notification = DB::table('notifications')->insert([
                'send_user_id' => $from,
                'get_user_id'  => $al,
                'notification' => $sendData,
            ]);
            
            // 전송 실패 시 
            if(!$notification) return 0;
        }

        return 1;

    }
}
