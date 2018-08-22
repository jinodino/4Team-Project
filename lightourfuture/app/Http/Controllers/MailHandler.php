<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SesMailable;
use DB;

class MailHandler extends Controller
{
    public function testMailer(){
        $title = "TESTSTETST";
        $text  = "TESTSET";
        $to = [
            "eric-94@naver.com",
            "ko97devdy@gmail.com"
        ];

        Mail::to($to)->send(new SesMailable($title,$text));
    }

     // 에이전트 주소록 메일 발송
     public function mailHandlerMailHandler(Request $request){
        
        $arr = [];
        
        // 이메일 -> 배열
        $mailAddr = $request->info;
        // 타입   -> 교수 or 기업
        $type     = $request->type;
        // 에이전트 아이디
        $agent    = $request->org_agent_id;
        $deadline = $this->expireHandler();
        
        foreach($mailAddr as $value) {

            // 이메일 주소
            $address = $value['email'];
            // agent_books에 고유 식별 값
            $no      = $value['no'];

            if($type == 'p') {
                $classification = 'professor';

                $inclusion = $value['faculty_id'];
                
                $data = $this->sendData($deadline, $classification, $inclusion, $agent, $no);
            }
            else if($type == 'c') {
                $classification = 'company';//$value['classification'];
                $inclusion = $value['org_id'];
                $data = $this->sendData($deadline, $classification, $inclusion, $agent, $no);
            }
            
            $sub      = "안녕하십니까 yano입니다";
            try {
                Mail::to($address)->send(new SesMailable($sub,$data, "1"));
            } catch (\Swift_RfcComplianceException $e) {
                $returnBool = "메일 양식 확인 부탁드립니다.";
                return ['returnBool'=>false, 'returnMsg' => $returnBool];
            }
            
            
            array_push($arr, $address);
         
            
        }
        return $arr;
    }

     // 학생 메일 인증 시 메일 발송
     public function studentConfirmMailHandler() {
        
        // 교수 주소록의 학생 개개인의 KEY값을 받기위함
        $info     = json_decode(request('stdVerifyInfo'));
        
        $birth    = $info->birth;         // 생일
        $validate = $info->collegeCode;  // 교수아이디
        $name     = $info->name;          // 이름
        
        $emailAddr = $info->emailAddress;

        $deadline = $this->expireHandler();
        // 인증 번호 professor_books table의 '학생 인증 번호(난수)'컬럼을 빼서 넣어줌
        $key = DB::table('professor_books')
        ->where([
            ['login_id', '=', $validate],
            ['name', '=', $name],
            ['birth_date', '=', $birth],
            ['email', '=', $emailAddr],
        ])
        ->pluck('certification_key');

        if(!isset($key)) return 0;
        
        
        $data     = $this->confirmData($key[0]);

        $sub      = "Light Our Future Email Confirm";
        
        try {
            Mail::to($emailAddr)->send(new SesMailable($sub,$data, "2"));
        } catch (\Exception $e) {
            $returnBool = "메일 발송 에러";
            return ['returnBool'=>$returnBool];
        }
        
        
        
        return "Valid Goood";
    }

    /**
     * @return JSONObject
     *  교수, 기업 인증 메일 전송 데이터
     */
    public function sendData($expireData, $classification, $inclusion, $agent, $no){
        
        $newEncrypter = new \Illuminate\Encryption\Encrypter(config('app.mkey'), config('app.cipher'));
        //$info           = request('info');
        // 제한 날짜
        $expireData     = $newEncrypter->encrypt($expireData);
        // 소속
        $inclusion      = $newEncrypter->encrypt($inclusion);
        // 초대 에이전트 명
        $agent          = $newEncrypter->encrypt($agent);
        // agent_books 고유 식별 값
        $no             = $newEncrypter->encrypt($no);
        // 본문
        $body           = 'HELLO';
        
        
        //decrypt
        if($classification == 'company') {
            // 초대
            $classification = $newEncrypter->encrypt($classification);
            //13.230.111.246/#/ 서버용
            $url     = "http://localhost:8000/#/regist/" . $expireData . "/" . $classification . "/" . $inclusion . "/" . $agent . "/" . $no;
            // 우리 서버
            //$url     = "http://13.230.111.246/#/regist/" . $expireData . "/" . $classification . "/" . $inclusion . "/" . $agent . "/" . $no;
            //야노 서버
            //$url     = "http://176.34.42.173/#/regist/" . $expireData . "/" . $classification . "/" . $inclusion . "/" . $agent . "/" . $no;
            return array('body' => $body, 'url' => $url);
        }
        else if($classification == 'professor'){
            $classification = $newEncrypter->encrypt($classification);
            //13.230.111.246/#/
            $url     = "http://localhost:8000/#/regist/" . $expireData . "/" . $classification . "/" . $inclusion . "/" . $agent . "/" . $no;
            // 우리서버
            //$url     = "http://13.230.111.246/#/regist/" . $expireData . "/" . $classification . "/" . $inclusion . "/" . $agent . "/" . $no;
            // 야노서버
            //$url     = "http://176.34.42.173/#/regist/" . $expireData . "/" . $classification . "/" . $inclusion . "/" . $agent . "/" . $no;
            
            return array('body' => $body, 'url' => $url);
        }
        
    }

    /**
     * @return expireData
     */
    public function expireHandler(){
        // now date
        $date = date("Y-m-d H:i:s");
        // deadline date
        $modify_day = date("Y-m-d H:i:s", strtotime($date."+1day"));
        // deadline date -> Encryption data
        //$encode = Crypt::encryptString($modify_day);
        $newEncrypter = new \Illuminate\Encryption\Encrypter(config('app.mkey'), config('app.cipher'));
        $encode = $newEncrypter->encrypt($modify_day);
        
        return $encode;
    }
        

    /**
     *   학생 인증코드 발행 
     */
    public function confirmData($confirmCode){
        $body    = 'Confirm Code : ' . $confirmCode;
        return array('body' => $body);
    }

        /**
     * @param Strign Email, classification, ID
     * 비밀번호 찾을 시 메일발송
     */
    public function findHandlerMailHandler($emailAddr, $user_id){
        $a = new MailHandler();
       
        $address  = $emailAddr;
        $deadline = $a->expireHandler();
        $data     = $a->findPwdData($deadline, $user_id);
        $sub      = "HALO입니다. 비밀번호 분실관련 메일 입니다";
        // Mail::send('we', $data, function($m) use ($address, $sub)  {
        //     // subject() : 제목, to() : 받는 사람, form(m1, m2) : 보내는 사람 (m1 : 이메일 주소, m2 : 사람이름)
        //     $m->subject($sub)->to($emailAddr)->from('no-reply@todolog.app', 'YANO');   
        // });
           
        Mail::to($emailAddr)->send(new SesMailable($sub,$data, "3"));

        return "send";
    }

    /**
     *  비밀번호 찾을 시 메일 전송 데이터
     */
    public function findPwdData($expireData, $id){
        $body    = 'HELLO';
        $newEncrypter = new \Illuminate\Encryption\Encrypter(config('app.mkey'), config('app.cipher'));
        $expireData = $newEncrypter->encrypt($expireData);
        $ID      = $newEncrypter->encrypt($id);
        $url     = "http://localhost:8000/#/regist/" . $expireData . "/" . $id;
        // 우리 서버
        //$url     = "http://13.230.111.246/#/regist/" . $expireData . "/" . $id;
        // 야노 서버
        //$url     = "http://176.34.42.173/#/regist/" . $expireData . "/" . $id;
        
        return array('body' => $body, 'url' => $url);
    }
    
}
