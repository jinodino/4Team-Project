<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\TokenPublisher;
use DB;
use Illuminate\Encryption\Encrypter;
use Config\App;

class AuthController extends Controller
{   

    /**
     * @var 
     * login 
     */
    protected function authenticate(){
        
        $credentials = request(['login_id','password','classification']);

        if ( !$token = $this->verify($credentials) ){
            return response()->json(["error","Unauthorized"], 401);
        }

        return $token;
    }

    // 
    protected function logout(){
        return 1;
        // request token -> 
        $info = request('data');
        $token          = $info['token'];
        $user_id        = $info['id'];
        $classification = $info['classification'];
        
        $tokenCheck = DB::table('logs')
        ->where([
            ['user_id', '=', $user_id],
            ['user_agent', '=', $classification],
        ])
        ->pluck('payload');
        
    
        
        if( !isset($tokenCheck[0]) ) return 0;
        
        $newEncrypter = new \Illuminate\Encryption\Encrypter(config('app.mkey'), config('app.cipher'));
        $tokenCheck = $newEncrypter->decrypt( $tokenCheck[0] );
        $token      = $newEncrypter->decrypt( $token );
        
        if($tokenCheck != $token) return 0;

        DB::table('logs')->where('user_id', '=', $user_id)->delete();

        return 1;
    }

    protected function refresh(){
        
    }

    // 
    protected function me(){
        
        // user_id, classification, Token<id + classification>
        $user_id        = request('id');
        $classification = request('classification');
        $token          = request('accessToken');
        
        // token value 추출
        $payload = DB::table('logs')->where('user_id', '=', $user_id)->pluck('payload');

        $newEncrypter = new \Illuminate\Encryption\Encrypter(config('app.mkey'), config('app.cipher'));
        $token = $newEncrypter->decrypt( $token );
        $payload = $newEncrypter->decrypt( $payload[0] );

        // DB vlaue exists check 
        $db_check = DB::table('logs')->where([
            ['user_id', '=', $user_id],
            ['user_agent', '=', $classification],
        ])->exists();

        // DB value exists check 
        if(!$db_check) return 0;
        // token value check
        else if($payload != $token) return 0;
        else return 1;
        
    }

    // 토큰 생성 <id + classification> 
    protected function store($credentials){
		// 1. 사용자가 로그인
		// 2. 로그인 할 때 세션DB에 값 저장 (id, classification, id + classiffication -> 암호화)
		// 2-1. 토큰값은 로그인 할 때 return 값
        // 3. 각자 classification에 맞는 페이지 띠용! -> create() { 토큰 값을 가지고오든 id, c, token }
        
        $token = $credentials["login_id"] . $credentials["classification"];
        
        $newEncrypter = new \Illuminate\Encryption\Encrypter(config('app.mkey'), config('app.cipher'));
        $token = $newEncrypter->encrypt( $token );

        // $check = $this->isExistUser($credentials);

        // if(!$check) return 0;
        
        
        // Session Activity
        $check = DB::table('logs')->insert([
            'user_id'    => request('login_id'),
            'session_id' => session()->getId(),
            'ip_address' => \Request::ip(),
            'user_agent' => request('classification'),
            'payload'    => $token,
        ]);
         
        if(!$check) return 0;
        
        
        return $token;
	}

    protected function isExistUser($credentials){

        
        // Exists 여부 확인
        $db_exists = DB::table('logs')->where([
            ['user_id', '=', $credentials['login_id']],
        ])->exists();

        if(!$db_exists) return 0;            
        

        return $db_exists."";
    
    }

    protected function verify($credentials){
    
        $newEncrypter = new \Illuminate\Encryption\Encrypter(config('app.mkey'), config('app.cipher'));
        

        $password = DB::table('users')->where([
            ['login_id', '=', $credentials["login_id"]],
            ['classification', '=', $credentials["classification"]],
        ])->pluck('password');

        
        if(!isset($password[0])) return 0;
        
        // 복호화
        $password = $newEncrypter->decrypt($password[0]);

        // 유효성 검사
        if($password != $credentials["password"]) return 0;
        
        return $this->store($credentials);
    }
    
}