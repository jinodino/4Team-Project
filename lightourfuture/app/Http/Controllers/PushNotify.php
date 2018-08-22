<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PushNotify extends Controller
{

    //토큰 저장
    public function push_token_save(Request $request){
        $id = $request->get('id');
        $token = $request->get('token');

        DB::table('users')
        ->where('login_id', '=', $id)
        ->update(
            ['token'=> "$token"]
        );

    }

    //푸시알림 보내기 예시
    public function push_send(Request $request){
        $id = $request->get('id');
        $apiKey = $request->get('api_key');
        $token = $request->get('token');
        
        $url = 'https://fcm.googleapis.com/fcm/send';

    
        $headers = array(
            "Authorization:key =$apiKey",
            "Content-Type: application/json"
        );

        $send_Data = array(
            "to" => $token,
            "notification" => array(
                "title" => json_encode(array(/*"data" => "Tester", */"title" => "FCM Message", "id" => $id)),
                "body"  => "푸시알림 테스트"
            ),
            
            
        );
        

        $ch = curl_init(); //curl 사용전 초기화 필수
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);                  //0이 default 값이며 POST 통신을 위해 1로 설정
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); //header 지정하기
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여준다. 
                                                            //이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);          //호스트에 대한 인증서 이름 확인
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);      //인증서 확인
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($send_Data));   //POST로 보낼 데이터 지정하기
        //curl_setopt($ch, CURLOPT_POSTFIELDSIZE, 0);         //이 값을 0으로 해야 알아서 &post_data 크기를 측정하는듯
        
        $res = curl_exec($ch);

        //에러 발생시 실행
        if ($res === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);
        

    }

    //푸시알림 보내기 특정 유저 선택시
    public function push_select_send($sendUserId, $getUserId, $apiKey, $notification){
        $id = $sendUserId;  //현재 유저
        $getid = $getUserId;//푸시 알람을 받는 유저
        $apiKey = $apiKey;  //apiKey
        $sendNotification = $notification; //푸시알림 내용
        $token = null; //토큰
        
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        

        //푸시알림을 받는 대상의 토큰 가져오기
        $select_token = DB::table('users')
        ->select('token')
        ->where('login_id', '=', $getid)
        ->get();

        //알림 누적하기
        $insert_notification = DB::table('notifications')
        ->insert(
            ['send_user_id' => "$id", 
            'get_user_id' => "$getid", 
            'notification' => "$sendNotification"]
        );

        foreach($select_token as $select_tokens){
            $token = $select_tokens->token;

            $headers = array(
                "Authorization:key =$apiKey",
                "Content-Type: application/json"
            );

            $send_Data = array(
                "to" => $token,
                "notification" => array(
                    "title" => json_encode(array(/*"data" => "student", */"title" => "Light Our Future", "id" => $getid)),
                    "body"  => $sendNotification,
                    "who"   => "student",
                )
                
            );
            

            $ch = curl_init(); //curl 사용전 초기화 필수
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);                  //0이 default 값이며 POST 통신을 위해 1로 설정
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); //header 지정하기
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여준다. 
                                                                //이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);          //호스트에 대한 인증서 이름 확인
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);      //인증서 확인
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($send_Data));   //POST로 보낼 데이터 지정하기
            //curl_setopt($ch, CURLOPT_POSTFIELDSIZE, 0);         //이 값을 0으로 해야 알아서 &post_data 크기를 측정하는듯
            
            $res = curl_exec($ch);

            //에러 발생시 실행
            if ($res === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }

            curl_close($ch);
        }
    }


    //푸시알림 보내기 특정 유저들(배열) 선택시
    public function push_array_send($sendUserId, $getUserId, $apiKey, $notification){
        $id = $sendUserId;  //현재 유저
        $getid = $getUserId;//푸시 알람을 받는 유저
        $apiKey = $apiKey;  //apiKey
        $sendNotification = $notification; //푸시알림 내용
        $token = null; //토큰
        
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        
        foreach($getid as $getids){
            //푸시알림을 받는 대상의 토큰 가져오기
            $select_token = DB::table('users')
            ->select('token')
            ->where('login_id', '=', $getids)
            ->get();

            //알림 누적하기
            $insert_notification = DB::table('notifications')
            ->insert(
                ['send_user_id' => "$id", 
                'get_user_id' => "$getids", 
                'notification' => "$sendNotification"]
            );

            foreach($select_token as $select_tokens){
                $token = $select_tokens->token;

                $headers = array(
                    "Authorization:key =$apiKey",
                    "Content-Type: application/json"
                );

                $send_Data = array(
                    "to" => $token,
                    "notification" => array(
                        "title" => json_encode(array(/*"data" => "student", */"title" => "Light Our Future", "id" => $getid)),
                        "body"  => $sendNotification,
                        "who"   => "student",
                        
                    )
                    
                );
                

                $ch = curl_init(); //curl 사용전 초기화 필수
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);                  //0이 default 값이며 POST 통신을 위해 1로 설정
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); //header 지정하기
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여준다. 
                                                                    //이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);          //호스트에 대한 인증서 이름 확인
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);      //인증서 확인
                curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($send_Data));   //POST로 보낼 데이터 지정하기
                //curl_setopt($ch, CURLOPT_POSTFIELDSIZE, 0);         //이 값을 0으로 해야 알아서 &post_data 크기를 측정하는듯
                
                $res = curl_exec($ch);

                //에러 발생시 실행
                if ($res === FALSE) {
                    die('Curl failed: ' . curl_error($ch));
                }

                curl_close($ch);
            }
        }
    }
}
