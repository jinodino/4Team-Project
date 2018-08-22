<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
class ReminderController extends Controller
{
    // post방식 이메일 전송 메서드
    // get방식이면 매개변수 넣어줘야함
    public function sendEmailReminder()
    {
        // post로 이메일 주소 받음
        $mail_address = request('message');
        $mail_name    = request('name');
        // 제목 명
        $sub = "이리오너라";
        // 현재 날짜 시간
        $date =     date("Y-m-d H:i:s");
        // 만료 날짜 시간
        $modify_day = date("Y-m-d H:i:s", strtotime($date."+1day"));
        $encode = encrypt($modify_day);
        // view->we.blade.php 파일로 넘겨줄 값 배열
        $data = [
            'title' => '안녕하세요',    
            'body'  => '저는 손진호 입니다.',
            'name' => $mail_name,
            'expire_date' => "http://localhost:8000/#/confirm/" . $encode,
        ];
        // 메일 전송 //
        // 'we'라는 views/we.blade.php 파일에 $data 배열 값을 넘기고 함수 실행
        // use -> 함수 외에서 끌어다 쓸 변수
        Mail::send('we', $data, function($m) use ($mail_address, $sub)  {
            // subject() : 제목, to() : 받는 사람, form(m1, m2) : 보내는 사람 (m1 : 이메일 주소, m2 : 사람이름)
            $m->subject($sub)->to($mail_address)->from('no-reply@todolog.app', '손진호이올시다');   
        });      
        return "send"; 
    }   
}