<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Facades\Crypt;
use DB;
class MailHandler extends Controller
{   

    /**
     * set Initialize 
     *  @param   
     * 
     */ 
    public function __construct(){

    }  

    /**
     * @param String Address
     * @param CryptoString SecretCode
     */
    // post방식 이메일 전송 메서드
    // get방식이면 매개변수 넣어줘야함

    // 에이전트 주소록 메일 발송
    public function mailHandlerMailHandler(Request $request){
        
        
        $arr = [];
        
        $mailAddr = $request->info;
        $type     = $request->type;
        $agent    = $request->org_agent_id;
        $deadline = $this->expireHandler();
        
        foreach($mailAddr as $value) {

            $address = $value['email'];
            if($type == 'p') {
                $classification = 'professor';//$value['classification'];
                $inclusion = $value['faculty_id'];
                
                $data = $this->sendData($deadline, $classification, $inclusion, $agent);
            }
            else if($type == 'c') {
                $classification = 'company';//$value['classification'];
                $inclusion = $value['org_id'];
                $data = $this->sendData($deadline, $classification, $inclusion, $agent);
            }
            
            $sub      = "안녕하십니까 yano입니다";
            
            Mail::send('we', $data, function($m) use ($address, $sub)  {
                // subject() : 제목, to() : 받는 사람, form(m1, m2) : 보내는 사람 (m1 : 이메일 주소, m2 : 사람이름)
                $m->subject($sub)->to($address)->from('no-reply@todolog.app', 'YANO');   
            });
            array_push($arr, $address);
        }
        return $arr;
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
        $sub      = "안녕하십니까 yano입니다";
        Mail::send('we', $data, function($m) use ($address, $sub)  {
            // subject() : 제목, to() : 받는 사람, form(m1, m2) : 보내는 사람 (m1 : 이메일 주소, m2 : 사람이름)
            $m->subject($sub)->to($emailAddr)->from('no-reply@todolog.app', 'YANO');   
        });
           
        
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
        $url     = "http://localhost:8000/#/regist/" . $expireData . "/" . $ID;
        return array('body' => $body, 'url' => $url);
    }

    // 180415 18:34분 손진호
    // 학생 메일 인증 시 메일 발송
    public function studentConfirmMailHandler(Request $request) {
        // 교수 주소록의 학생 개개인의 KEY값을 받기위함
        $info     = json_decode($request->validate_stdInfo);
        $birth    = $info->birth;         // 생일
        $validate = $info->validate_num;  // 교수아이디
        $name     = $info->name;          // 이름
        
        $emailAddr = $request->mailAddr;

        $deadline = $this->expireHandler();
        // 인증 번호 professor_books table의 '학생 인증 번호(난수)'컬럼을 빼서 넣어줌
        $key = DB::table('professor_books')
        ->where([
            ['login_id', '=', $validate],
            ['name', '=', $name],
            ['birth_date', '=', $birth],
        ])
        ->pluck('certification_key');
        
        
        $data     = $this->confirmData($key[0]);

        $sub      = "Light Our Future Email Confirm";
        Mail::send('Student_Email_Confirm', $data, function($m) use ($emailAddr, $sub)  {
            // subject() : 제목, to() : 받는 사람, form(m1, m2) : 보내는 사람 (m1 : 이메일 주소, m2 : 사람이름)
            $m->subject($sub)->to($emailAddr)->from('no-reply@todolog.app', 'YANO');   
        });
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

    public function makeCredential(){


    }

    /**
     * @return JSONObject
     *  교수, 기업 인증 메일 전송 데이터
     */
    public function sendData($expireData, $classification, $inclusion, $agent){
        
        $newEncrypter = new \Illuminate\Encryption\Encrypter(config('app.mkey'), config('app.cipher'));
        $info           = request('info');
        $expireData     = $newEncrypter->encrypt($expireData);
        $inclusion      = $newEncrypter->encrypt($inclusion);
        $agent          = $newEncrypter->encrypt($agent);
        $body    = 'HELLO';
        
        
        //decrypt
        if($classification == 'company') {
            // 초대
            $classification = $newEncrypter->encrypt($classification);
            //13.230.111.246/#/
            $url     = "http://localhost:8000/#/regist/" . $expireData . "/" . $classification . "/" . $inclusion . "/" . $agent;
            return array('body' => $body, 'url' => $url);
        }
        else if($classification == 'professor'){
            $classification = $newEncrypter->encrypt($classification);
            //13.230.111.246/#/
            $url     = "http://localhost:8000/#/regist/" . $expireData . "/" . $classification . "/" . $inclusion . "/" . $agent;
            return array('body' => $body, 'url' => $url);
        }
        
        
    }

    

    /**
     *  비밀번호 찾을 시 메일 전송 데이터
     */
    public function confirmData($confirmCode){
        $body    = 'Confirm Code : ' . $confirmCode;
        return array('body' => $body);
    }
}