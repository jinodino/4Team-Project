<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\profileImageController;

class professor_profile extends Controller
{
    public function profile(Request $request){

        $user_id = $request->get('professorId');
        //교수의 프로필 데이터를 불러와서 뷰로 전송
        $profile = DB::table('professors as pro')
        ->join('faculties', 'pro.faculty_id', '=', 'faculties.faculty_id')
        ->join('org_colleges as college', 'faculties.org_college_id', '=', 'college.org_college_id')
        ->select('pro.login_id as userId',
                'college.org_name as school',
                'faculties.org_college_id as schoolId',
                'pro.name as korea', 'pro.name_kanji as japan',
                'pro.name_kana as katakana',
                'pro.email as email',
                'pro.birth_date as birth',
                'pro.major as major',
                'pro.japaness_skill_ox as japaneseLanguageAbility',
                'pro.profile_photo_url as imageUrl')
        ->where('pro.login_id', '=', $user_id)
        ->get();
/*
                        ->join('faculties', 'faculties.org_college_id', '=', 'org_matchings.org_college_id')
                        ->join('professors', 'professors.faculty_id', '=', 'faculties.faculty_id')
*/

        
        return $profile;
    }

    public function profile_save(Request $request){
        //프로필이미지 컨트롤로 객체 선언
        //$profileImageController = new profileImageController();

        $user_info = json_decode($request->get('profileInfo'));
        $user_id = $user_info->userId;

        $profileImage = $request->get('profileImage');
        $fileName = $profileImage['name'];
        //$photo_url = $profileImageController->storeAnjung($profileImage, $profileImage['folder']); //요부분에서 자꾸 에러남


        
        //--------------프로필 이미지 저장----------------------------------
        
        @$repository_url = '/home/ubuntu/storage/profileImage';
        @$repository_user_url = $repository_url.'/user';
        @$repository_id_url = $repository_user_url."/$user_id";

        //경로가 없을경우 폴더 생성
        if(@!is_dir($repository_url)){
            @umask(0);
            @mkdir($repository_url);
        }
        if(@!is_dir($repository_user_url)){
            @umask(0);
            @mkdir($repository_user_url);
        }
        if(@!is_dir($repository_id_url)){
            @umask(0);
            @mkdir($repository_id_url);
        }

        @$exploded = explode(',', $profileImage['data']);
        @$decode = base64_decode($exploded[1]);
        @$fileName = $profileImage['name'];

        @$path = $repository_id_url."/$fileName";
        @file_put_contents($path, $decode);
        @chmod($path, 0777);


        //
        $export_img_url = "/storage/profileImage/user/$user_id/$fileName";
        //-------------------여기까지가 저장 부분-------------------------------------


        DB::table('professors')->where('login_id', '=', $user_id)
        ->update(
            ['name' => $user_info->korea, 'name_kanji' => $user_info->japan, 'major' => $user_info->major,
            'name_kana' => $user_info->katakana, 'email' => $user_info->email, 'birth_date' => date("$user_info->birth"),
            'japaness_skill_ox' => $user_info->japaneseLanguageAbility, 'profile_photo_url' => $export_img_url]
        );
        //public_path().$photo_url

        $user_id = $request->get('professorId');
        //교수의 프로필 데이터를 불러와서 뷰로 전송
        $profile = DB::table('professors as pro')
        ->join('faculties', 'pro.faculty_id', '=', 'faculties.faculty_id')
        ->join('org_colleges as college', 'faculties.org_college_id', '=', 'college.org_college_id')
        ->select('pro.login_id as userId', 'college.org_name as school', 
        'faculties.org_college_id as schoolId', 'pro.name as korea', 'pro.name_kanji as japan',
        'pro.name_kana as katakana', 'pro.email as email', 'pro.birth_date as birth', 'pro.major as major',
        'pro.japaness_skill_ox as japaneseLanguageAbility', 'pro.profile_photo_url as imageUrl')
        ->where('pro.login_id', '=', $user_id)
        ->get();

        return $profile;
        //------------------------------------------------
    }
}