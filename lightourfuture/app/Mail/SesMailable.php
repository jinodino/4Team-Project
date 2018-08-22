<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class SesMailable extends Mailable
{
    use Queueable, SerializesModels;

    protected $title;
    protected $text;
    protected $deadline;
    protected $classification;
    protected $inclusion;
    protected $agent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sub,$data,$check)
    {
        
           
        // 매개변수
        // 제목 / 데이터 / 메일종류
        $this->mailSend($sub,$data,$check); 
     
            
            
            
    }
  
    // 메일보내기 
    // check value -> 1 : 교수 기업 회원가입 / 2 : 학생 회원가입
    function mailSend($title, $text, $check) { 
        switch($check) {
            case 1:
                // 교수 및 기업 초대 메일
                $this->view("we")
                -> subject($title)
                -> with ([
                    "url" => $text['url'],
                    "body" => $text['body'],
                ]);
                break;
            case 2:
                // 학생 회원가입 인증코드 발행 메일
                $this->view("Student_Email_Confirm")
                -> subject($title)
                -> with ([
                    "body" => $text['body'],
                ]);
                break;
            case 3:
                // 학생 비밀번호 찾기 메일
                $this->view("we")
                -> subject($title)
                -> with ([
                    "url" => $text['url'],
                    "body" => $text['body'],
                ]);
                break;
        }
    } 



    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
      
    }

   
}
