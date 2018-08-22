<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class User_profiles extends Controller
{
    public function user_profile(Request $request){
        
        $user_id        = $request->get('login_id');
        $classification = $request->get('classification');
        
        //유저 ID, 유저 계층에 따라 유저 기본 프로필 데이터 반환
        switch($classification){
            case 'student':
                $profile_result = DB::table('students')->where('login_id', '=', $user_id)->get();

                break;
            case 'professor':
                $profile_result = DB::table('professors')->where('login_id', '=', $user_id)->get();

                break;
            case 'agent':
                $profile_result = DB::table('agents')->where('login_id', '=', $user_id)->get();

                break;
            case 'company_agent':
                $profile_result = DB::table('company_agents')->where('login_id', '=', $user_id)->get();

                break;
            case 'org_company':
                $profile_result = DB::table('org_companies')->where('org_company_id', '=', $user_id)->get();

                break;
            case 'org_agent':
                $profile_result = DB::table('org_agents')->where('org_agent_id', '=', $user_id)->get();

                break;
            case 'org_college':
                $profile_result = DB::table('org_colleges')->where('org_college_id', '=', $user_id)->get();

                break;
        }
        
        
        return  $profile_result;
    }

    public function destory() {
        $profile_result = DB::table('company_agents')->where('login_id', '=', $user_id)->delete();
    }
}
