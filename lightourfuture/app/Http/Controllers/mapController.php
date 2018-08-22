<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class mapController extends Controller
{

    // 처음 학교 좌표 등록 함수 -> 학교 등록 시 좌표값 까지 같이 넘겨줘서 디비에 넣어줘야함
    // public function insertLocation() {
    //     $
    // }

    public function map() {
        
        $org_collegeid = request('collegeId');

        $info = DB::table("org_colleges")
        ->where('org_college_id', '=', $org_collegeid)
        ->select('latitude', 'longitude')
        ->get();
        
        $lat = $info[0]->latitude;
        $lng = $info[0]->longitude;
        $test1 = ['lat' => $lat, 'lng' => $lng];
        $test = json_encode($test1);
        return $test;
    }

    
}
