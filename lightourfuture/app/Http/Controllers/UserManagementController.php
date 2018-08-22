<?php

namespace App\Http\Controllers;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\MailHandler;
use Illuminate\Encryption\Encrypter;
use Crypt;
use DB;
use Mail;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    static $stdCount = 1;

    public function plus() {
        $a = static::$stdCount++;

        return $a;
    }

    public function index()
    {
        // User Table all Show
        $items = User::all();
    
        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // classification, 소속, agent, 고유 식별 값
        $registerInfo   = $request->registerInfo;
        // 암호화
        $newEncrypter   = new \Illuminate\Encryption\Encrypter(config('app.mkey'), config('app.cipher'));
        $classification = $newEncrypter->decrypt($registerInfo['classification']);
        $inclusion      = $newEncrypter->decrypt($registerInfo['inclusion']);
        $agent          = $newEncrypter->decrypt($registerInfo['agent']);
        // agent_books -> no
        $no             = $newEncrypter->decrypt($registerInfo['no']);
        
        // user table에 넣어줌
        $user = new User([
            'login_id' => $registerInfo['id'],
            'classification' => $classification,
            'password' => $newEncrypter->encrypt($registerInfo['pwd']),
            'join_date' => \Carbon\Carbon::now(),
          ]);
        $check = $user->save();
        // 값이 넣어졌나 안넣어졌나
        if(!$check) return 0;

        switch($classification) {
            case 'company':
                // company_agents table insert
                // login_id, invite_agent_id
                $check = DB::table('org_companies')
                ->where('org_company_id', '=', $inclusion)
                ->update(
                    ['manager_login_id' => $registerInfo['id']]
                );
                
                // 업데이문이 잘 못 되었을 시
                if(!$check) {

                    DB::table('users')->where([
                        ['login_id', '=', $registinfo['id']],
                        //['password', '=', $newEncrypter->encrypt($registinfo['pwd'])],
                        ['classification', '=', 'company'],
                    ])
                    ->delete();
        
                    return 0;
                }

               $check = DB::table('company_agents')
                ->insert([
                    'login_id' => $registerInfo['id'],
                    'invite_agent_id' => $agent,
                    'org_company_id' => $inclusion,
                ]);

                if(!$check) {
                    DB::table('org_companies')
                    ->where('org_company_id', '=', $inclusion)
                    ->update(
                        ['manager_login_id' => NULL]
                    );

                    DB::table('users')->where([
                        ['login_id', '=', $registinfo['id']],
                        //['password', '=', $newEncrypter->encrypt($registinfo['pwd'])],
                        ['classification', '=', 'company'],
                    ])
                    ->delete();
                    return 0;
                } 

                $check = DB::table('agent_books')
                ->where('no', '=', $no)
                ->update(
                    ['join_ox' => 'o']
                );

                if(!$check) {
                    return 0;
                }

                break;
            case 'professor':
                $check = DB::table('professors')
                ->insert([
                    'login_id' => $registerInfo['id'],
                    'invite_agent_id' => $agent,
                    'faculty_id' => $inclusion,
                ]);

                if(!$check) {
                    DB::table('users')->where([
                        ['login_id', '=', $registinfo['id']],
                        //['password', '=', $newEncrypter->encrypt($registinfo['pwd'])],
                        ['classification', '=', 'professor'],
                    ])
                    ->delete();

                    $check = DB::table('agent_books')
                    ->where('no', '=', $no)
                    ->update(
                        ['join_ox' => 'o']
                    );
                    return 0;
                }

                break;
        }

        return 1;

    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // User Edit
        $item = User::where('login_id', $id)->get();
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // User Update
         $item = User::where('login_id', $id)->update(['password' => Crypt::encrypt($request->get('change_password'))]);
         
         if($item) {
             return 'Yes';
         }else {
             return 'No';
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = User::where('login_id', $id)->delete();
        return $item;
        //$item->delete();
    }
    /** 
    * find User ID
    * 
    * @param  JSON > name , birth , mailAddr, userclassification
    * @return String userID 
    */
    protected function findId(Request $request){
        $info = json_decode($request->find_idInfo);
        
        $name           = $info->name;
        $emailAddr      = $info->email;
        $birth          = date($info->birth);
        $classification = $info->classification . 's';
        
        if( $classification === ('companys') ){
            $classification = "company_agents";
        }

        $db_id = DB::table($classification)->where([
            ['name', '=', $name],
            ['email', '=', $emailAddr],
            ['birth_date', '=' , $birth]
        ])->pluck('login_id');
            
        if(!isset($db_id[0])) return 0;
        
        return $db_id[0];
    }

    /**
     *  @param JSON  > id, birth , mailAddr, userclassification
     *  @return SendMail
     */
    protected function findPassword(Request $request){
        
        $info  = json_decode($request->find_pwdInfo);
        
        $user_id        = $info->id;
        $emailAddr      = $info->email;
        $birth      = date($info->birth);
        $classification = $info->classification . 's';

        if( $classification === ('companys') ){
            $classification = "company_agents";
        }
        
        // 유효성 확인 
        $db_check = DB::table($classification)
        ->where([
            ['login_id', '=', $user_id],
            ['email', '=', $emailAddr],
            ['birth_date', '=' , $birth]
        ])->exists();
           
       
       if($db_check) {
           $a = new MailHandler();
           // 임의의 값 이메일 
           if($a->findHandlerMailHandler($emailAddr, $classification)) {
               return  "yes";
           }else {
               return;
           }   
       }else {
           return 0;
       }
        
    }

    /**
     *  학생 회원 등록 시 검증
     *  @param Request 
     */
    public function postMemberCheck() {
        
        $info = json_decode(request('stdVerifyInfo'));
        
        // Std insert info
        $validate       = $info->collegeCode;
        $birth          = date($info->birth);
        $classification = 'student';
        $name           = $info->name;
        // 학생 이메일 추가 해줘야함 2018-06-19
        $emailAddress          = $info->emailAddress;

        // Table : professor_books
        $db_check = DB::table('professor_books')
        ->where([
            ['login_id',   '=', $validate],
            ['birth_date', '=', $birth],
            ['name',       '=', $name],
            ['email',      '=', $emailAddress],
        ])->exists();

        // 값이 없을 시
        if(!$db_check) return 0;


        $mailHandler = new MailHandler();
        return $mailHandler->studentConfirmMailHandler();
        
    }

    /**
     *  학생 회원등록 시 이메일 검증
     *  @param String emailConfirmCode
     */
    public function MailConfirmCodeCheck() {
        
        // professor_books table에서 인증코드 값 비교!
        // 그전에 엑셀 칼럼에 넣어줘야함
        //return request();
        $info     = request('stdInfo');
        $verify   = request('accountInfo');
        
        

        $birth        = $info['birth'];         // 생일
        $validate     = $info['collegeCode'];  // 교수아이디
        $name         = $info['name'];  
        $emailAddress = $info['emailAddress'];

        $key = DB::table('professor_books')
        ->where([
            ['login_id', '=', $validate],
            ['name', '=', $name],
            ['birth_date', '=', $birth],
            ['email', '=', $emailAddress]
        ])
        ->pluck('certification_key');

        $credential = $verify['credential'];
        // 메일 코드 검증
        if($credential != $key[0]) return 0;

        return "Valid";
    }

    /**
     *  회원 ID 중복 검사
     */
    public function postUserIdDuplicates(Request $request) {
        // User input ID
        $input_userid = $request->id;
        // 아이디 중복 검사
        $db_check = DB::table('users')->where('login_id', '=', $input_userid)->exists();

        if(!$db_check) return "yes";

        return;
    }

    /**
     * 학생 회원가입 진행 
     */
    public function postStudentEnter() {
        
        // id, pwd, credential
        $registinfo       = request('accountInfo');
        // collegeCode, name, birth, emailAddress => 교수 아이디, 학생 이름, 학생 생일, 이메일
        $validate_stdInfo = request('stdInfo');
        // emailAddress, credential => 이메일, 이메일 인증 코드(이건 딱히 필요 X)
        $email            = $validate_stdInfo['emailAddress'];
        
        // 인증코드가 올바르지 않습니다.
        if(!$this->MailConfirmCodeCheck()) return 0;

        
        // faculty 아이디
        $faculty_id     = DB::table('professors')->where('login_id', '=', $validate_stdInfo['collegeCode'])->pluck('faculty_id');
        if(!isset($faculty_id[0])) return 0;
        // 기업 아이디
        $org_college_id = DB::table('faculties')->where('faculty_id', '=', $faculty_id[0])->pluck('org_college_id');
        if(!isset($org_college_id[0])) return 0;
        // 국가 코드 아이디
        $country_code   = DB::table('org_colleges')->where('org_college_id', '=', $org_college_id)->pluck('country_code');
        if(!isset($country_code[0])) return 0;
        // 학교 알리아스
        $college_as     = DB::table('org_colleges')->where('org_college_id', '=', $org_college_id)->pluck('college_alias');
        if(!isset($college_as[0])) return 0;

        // 채용활동 년도 date('y');
        $date = DB::table('professor_books')->where([
            ['name',       '=', $validate_stdInfo['name']],
            ['login_id',   '=', $validate_stdInfo['collegeCode']],
            ['birth_date', '=', $validate_stdInfo['birth']],
        ])
        ->pluck('employ_year');

        if(!isset($date[0])) return 0;
        $fulldate = date($date[0]);
        // 채용날자 ex)2018 -> 18
        $date = date('y', strtotime($date[0]));
        
        // facult별 학생 수 -> facultyId를 가진 교수를 뽑고 -> 그 밑에 딸린 학생 수 체크
        $professor_id   = DB::table('professors')->where('faculty_id', '=', $faculty_id[0])->pluck('login_id');
        if(!isset($professor_id[0])) return 0;


        // 학생 수 
        $stdCount = 1;
        foreach($professor_id as $professor) {
            // 교수 값을 가진 학생 수 카운트
            $stdCount += DB::table('students')->where('professor_login_id', '=', $professor)->count();
        }
        
        // 학생 코드 : KR_YJC_18_1_사람수
        $stdNum = $country_code[0] . "_" . $college_as[0] . "_" . $date . "_" . $faculty_id[0] . "_" . $stdCount;

        // 암호화
        $newEncrypter = new \Illuminate\Encryption\Encrypter(config('app.mkey'), config('app.cipher'));

        $db_check = DB::table('users')->insert([
            'login_id'       => $registinfo['id'],
            'password'       => $newEncrypter->encrypt($registinfo['pwd']),
            'classification' => 'student',
        ]);

        // 오류 있을 시
        if(!$db_check) {
            DB::table('users')->where([
                ['login_id', '=', $registinfo['id']],
                ['password', '=', $newEncrypter->encrypt($registinfo['pwd'])],
                ['classification', '=', 'student'],
            ])
            ->delete();

            return 0;
        }

        // 해당 교수의 그룹 아이디 추출
        $group_id = DB::table('groups')->where([
            ['faculty_id', '=', $faculty_id[0]],
            ['group_num', '=', 0]
        ])
        ->pluck('group_id');

        if(!isset($group_id[0])) {
            DB::table('users')->where([
                ['login_id', '=', $registinfo['id']],
                ['password', '=', $newEncrypter->encrypt($registinfo['pwd'])],
                ['classification', '=', 'student'],
            ])
            ->delete();

            return 0;
        } 

        // 폴더 생성
        $repository_user       = "/storage/repository/";
        $repository_std        = $repository_user . $registinfo['id'];
        $repository_entrySheet = $repository_std."/entrySheets";
        $repository_portfolio  = $repository_std."/portfolios";
        //$repository_resum      = $repository_repository."/resum";
        //$repository_portfolio  = $repository_repository."/portfolios ";

        $db_check = DB::table('students')->insert([
            'login_id'           => $registinfo['id'],
            'student_code'       => $stdNum,
            'professor_login_id' => $validate_stdInfo['collegeCode'],
            'name'               => $validate_stdInfo['name'],
            'email'              => $email,
            'employ_year'        => $fulldate,
            'group_id'           => $group_id[0],
            'birth_date'         => $validate_stdInfo['birth'],
            'country_code'       => $country_code[0],
            'employment_end_ox'  => 'x',
        ]);

        if(!$db_check){
            DB::table('users')->where([
                ['login_id', '=', $registinfo['id']],
                //['password', '=', $newEncrypter->encrypt($registinfo['pwd'])],
                ['classification', '=', 'student'],
            ])
            ->delete();
            return 0;
        } 

        try {
            // 경로가 없을경우 폴더 생성
            if(!is_dir($repository_user)){
                umask(0);
                mkdir($repository_user, 0777);
                if(!is_dir($repository_std)) {
                    umask(0);
                    mkdir($repository_std, 0777);
                    if(!is_dir($repository_entrySheet)) {
                        umask(0);
                        mkdir($repository_entrySheet, 0777);
                    }
                    if(!is_dir($repository_portfolio)) {
                        umask(0);
                        mkdir($repository_portfolio, 0777);
                    }
                }
            }
            else {
                 if(!is_dir($repository_std)) {
                     umask(0);
                     mkdir($repository_std, 0777);
                     if(!is_dir($repository_entrySheet)) {
                        umask(0);
                        mkdir($repository_entrySheet, 0777);
                    }
                    if(!is_dir($repository_portfolio)) {
                        umask(0);
                        mkdir($repository_portfolio, 0777);
                    }
                }
            }
        } catch (\Exception $e){
            $returnBool = "폴더 경로 문제입니다";
            return ['returnBool'=>$returnBool];
        }
        


        return 1;   
    }



    /**
     * Professor 가입 메서드 
     * @param Request <classification>
     */
    // public function postProfessorEnter(Request $request) {
    //     // Agent_books Table -> Enter Email Value 비교 
    //     $email_check  
    // }
}
