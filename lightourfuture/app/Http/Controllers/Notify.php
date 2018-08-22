<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class Notify extends Controller
{
    //누적알림
    public function notification_list(Request $request){
        $user_id = $request->get('id'); //현재 유저
        $now = date('Y-m-d H:i:s');
        
        $notify_list = DB::table('notifications')
        ->select('no', 
                 'notification',
                 DB::raw("date(notification_time) as date"))
        ->whereRaw("get_user_id ='$user_id' && (notification_readtime is null or notification_readtime = '')")
        ->orderBy('no', 'desc')
        ->get();

        /*
        DB::table('notifications')
        ->where('get_user_id', $user_id)
        ->update(['notification_readtime' => $now]);
        */

        return $notify_list;
    }
    
    //선택한 누적알림 지우기
    public function notification_select_delete(Request $request){
        $no = $request->get('index_no');
        $now = date('Y-m-d H:i:s');
        
        DB::table('notifications')
        ->where('no', $no)
        ->update(['notification_readtime' => $now]);
        
        return 'delete success';
    }
    
    //해당 유저의 누적알림 모두 지우기
    public function notification_all_delete(Request $request){
        $user_id = $request->get('id'); //현재 유저
        $now = date('Y-m-d H:i:s');
        
        DB::table('notifications')
        ->where('get_user_id', $user_id)
        ->update(['notification_readtime' => $now]);
        

        return 'delete success';
    }
}
