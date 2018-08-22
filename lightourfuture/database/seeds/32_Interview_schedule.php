<?php

use Illuminate\Database\Seeder;

class Interview_schedule extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $interview_schedule = [//1, 3, 2, 4, 3, 5, 5
            //1
            ['employment_id' => 1, 'interview_count' => 1, 'interview_place' => '永進専門大学情報館', 'interview_date' => date('20180503'), 'interview_start_time' =>  date('10:30') ,
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 1, 'interview_active' => 'o', 'interview_content' => '', 'interview_check_ox' => 'o'],
            //2
            ['employment_id' => 2, 'interview_count' => 1, 'interview_place' => '永進専門大学情報館', 'interview_date' => date('20180502'), 'interview_start_time' =>  date('10:30') ,
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 1, 'interview_active' => 'x','interview_content' => '', 'interview_check_ox' => 'o'],
            //3
            ['employment_id' => 3, 'interview_count' => 1, 'interview_place' => 'ソウル大学図書館', 'interview_date' => date('20180606'), 'interview_start_time' =>  date('10:30'),
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //4
            ['employment_id' => 4, 'interview_count' => 1, 'interview_place' => '慶北大学体育館', 'interview_date' => date('20180516'), 'interview_start_time' =>  date('10:30'),
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //5
            ['employment_id' => 5, 'interview_count' => 1, 'interview_place' => '慶北大学体育館', 'interview_date' => date('20180530'), 'interview_start_time' =>  date( '17:30') ,
            'interview_end_time' => date('18:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //6
            ['employment_id' => 6, 'interview_count' => 1, 'interview_place' => '慶北大学体育館', 'interview_date' => date('20180531'), 'interview_start_time' =>  date( '17:30') ,
            'interview_end_time' => date('18:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //7
            ['employment_id' => 7, 'interview_count' => 1, 'interview_place' => '慶北大学体育館', 'interview_date' => date('20180811'), 'interview_start_time' =>  date( '17:30') ,
            'interview_end_time' => date('18:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //8
            ['employment_id' => 8, 'interview_count' => 1, 'interview_place' => '永進専門大学体育館', 'interview_date' => date('20180530'), 'interview_start_time' =>  date( '17:30') ,
            'interview_end_time' => date('18:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //9
            ['employment_id' => 2, 'interview_count' => 2, 'interview_place' => '永進専門大学情報館', 'interview_date' => date('20180503'), 'interview_start_time' =>  date( '17:30') ,
            'interview_end_time' => date('18:30'), 'interview_content_choice' => 4, 'interview_active' => 'x','interview_content' => '', 'interview_check_ox' => 'o'],
            //10
            ['employment_id' => 2, 'interview_count' => 3, 'interview_place' => '永進専門大学情報館', 'interview_date' => date('20180504'), 'interview_start_time' =>  date( '10:30') ,
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 5, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            
            //11
            ['employment_id' => 2, 'interview_count' => 4, 'interview_place' => '永進専門大学情報館', 'interview_date' => date('20180504'), 'interview_start_time' =>  date('10:30') ,
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 1, 'interview_active' => null,'interview_content' => '', 'interview_check_ox' => 'x'],
            
        ];
        /* //1
            ['employment_id' => 1, 'interview_count' => 1, 'interview_place' => '영진 정보관', 'interview_date' => date('20180503'), 'interview_start_time' =>  date('10:30') ,
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 1, 'interview_active' => 'o', 'interview_content' => '', 'interview_check_ox' => 'o'],
            //2
            ['employment_id' => 2, 'interview_count' => 1, 'interview_place' => '영진 정보관', 'interview_date' => date('20180502'), 'interview_start_time' =>  date('10:30') ,
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 1, 'interview_active' => 'x','interview_content' => '', 'interview_check_ox' => 'o'],
            //3
            ['employment_id' => 3, 'interview_count' => 1, 'interview_place' => '서울대 도서관', 'interview_date' => date('20180606'), 'interview_start_time' =>  date('10:30'),
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //4
            ['employment_id' => 4, 'interview_count' => 1, 'interview_place' => '경북대 체육관', 'interview_date' => date('20180516'), 'interview_start_time' =>  date('10:30'),
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //5
            ['employment_id' => 5, 'interview_count' => 1, 'interview_place' => '경북대 체육관', 'interview_date' => date('20180530'), 'interview_start_time' =>  date( '17:30') ,
            'interview_end_time' => date('18:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //6
            ['employment_id' => 6, 'interview_count' => 1, 'interview_place' => '경북대 체육관', 'interview_date' => date('20180531'), 'interview_start_time' =>  date( '17:30') ,
            'interview_end_time' => date('18:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //7
            ['employment_id' => 7, 'interview_count' => 1, 'interview_place' => '경북대 체육관', 'interview_date' => date('20180811'), 'interview_start_time' =>  date( '17:30') ,
            'interview_end_time' => date('18:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //8
            ['employment_id' => 8, 'interview_count' => 1, 'interview_place' => '영진 체육관', 'interview_date' => date('20180530'), 'interview_start_time' =>  date( '17:30') ,
            'interview_end_time' => date('18:30'), 'interview_content_choice' => 1, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            //9
            ['employment_id' => 2, 'interview_count' => 2, 'interview_place' => '영진 정보관', 'interview_date' => date('20180503'), 'interview_start_time' =>  date( '17:30') ,
            'interview_end_time' => date('18:30'), 'interview_content_choice' => 4, 'interview_active' => 'x','interview_content' => '', 'interview_check_ox' => 'o'],
            //10
            ['employment_id' => 2, 'interview_count' => 3, 'interview_place' => '영진 정보관', 'interview_date' => date('20180504'), 'interview_start_time' =>  date( '10:30') ,
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 5, 'interview_active' => 'o','interview_content' => '', 'interview_check_ox' => 'o'],
            
            //11
            ['employment_id' => 2, 'interview_count' => 4, 'interview_place' => '영진 정보관', 'interview_date' => date('20180504'), 'interview_start_time' =>  date('10:30') ,
            'interview_end_time' => date('12:30'), 'interview_content_choice' => 1, 'interview_active' => null,'interview_content' => '', 'interview_check_ox' => 'x'],
             */
        foreach($interview_schedule as $interview_schedules) {
            
            DB::table('interview_schedules')->insert([
                'employment_id' => $interview_schedules['employment_id'],
                'interview_count' => $interview_schedules['interview_count'],
                'interview_content_choice' => $interview_schedules['interview_content_choice'],
                'interview_place' => $interview_schedules['interview_place'],
                'interview_date' => $interview_schedules['interview_date'],
                'interview_start_time' => $interview_schedules['interview_start_time'],
                'interview_end_time' => $interview_schedules['interview_end_time'],
                'interview_active' => $interview_schedules['interview_active'],
                'interview_content' => $interview_schedules['interview_content'],
                'interview_check_ox' => $interview_schedules['interview_check_ox'],
            ]);
            
        }
        
    }
}
