<?php

use Illuminate\Database\Seeder;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [

            // 학생
            // yjc

            ['login_id' => 'st01', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'student'],
            ['login_id' => 'st02', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'student'],
            ['login_id' => 'st03', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'student'],
            ['login_id' => 'st04', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'student'],
            ['login_id' => 'st05', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'student'],
            ['login_id' => 'st06', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'student'],
            // knu
            ['login_id' => 'st07', 'password'=> 'eyJpdiI6ImhMM hKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'student'],
            ['login_id' => 'st08', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'student'],
            // snu
            ['login_id' => 'st09', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'student'],
            ['login_id' => 'st10', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'student'],
            // 교수
            ['login_id' => 'pr01', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'professor'],
            ['login_id' => 'pr02', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'professor'],
            ['login_id' => 'pr03', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'professor'],
            ['login_id' => 'pr04', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'professor'],
            // 에이전트
            ['login_id' => 'root', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'agent'],
            ['login_id' => 'ag02', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'agent'],
            ['login_id' => 'ag03', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'agent'],
            // 기업
            ['login_id' => 'co01', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'company'],
            ['login_id' => 'co02', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'company'],
            ['login_id' => 'co03', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'company'],
            ['login_id' => 'co04', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'company'],
            ['login_id' => 'co05', 'password'=> 'eyJpdiI6ImhMMXhKMzRGcEl6OExINEE2alFYa2c9PSIsInZhbHVlIjoiVU5PVHNUbk04S2MwTHh2YktIa05NQT09IiwibWFjIjoiOTVkZWQ2MzczNTFhNzllNDc0YmYwZDNiMWVjNTNkOGY2YjhmMmFkMTYyODVmYWNkYWM1ODRjNzg2ZDA1ZDY2NiJ9', 'token' => '' , 'classification' => 'company'],
        ];
        
        foreach($users as $user) {
            
            DB::table('users')->insert([
                'login_id' => $user['login_id'],
                'password' => $user['password'],
                'token' => $user['token'],
                'classification' => $user['classification'],
            ]);
            
        }
    }
}
