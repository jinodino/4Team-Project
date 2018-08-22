<?php

use Illuminate\Database\Seeder;

class Interview_resultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $interview_resultSeeder = [
            // 1 -> 0401 ~ 0502 YJC / Krsam
            ['interview_id' => 1, 'student_login_id'=> 'st01', 'interview_end_time' => date('2018-05-03 10:30:00' ), 'interview_start_time' => date('2018-05-03 10:30:00' ),'interview_result' => 'o', 'interview_feedback' => '좋습니다'],
            ['interview_id' => 1, 'student_login_id'=> 'st02', 'interview_end_time' => date('2018-05-03 11:00:00' ), 'interview_start_time' => date('2018-05-03 10:30:00' ),'interview_result' => 'o', 'interview_feedback' => '완벽'],
            ['interview_id' => 1, 'student_login_id'=> 'st03', 'interview_end_time' => date('2018-05-03 11:30:00' ), 'interview_start_time' => date('2018-05-03 11:00:00' ), 'interview_result' => 'x', 'interview_feedback' => '자기PR부족'],
            ['interview_id' => 1, 'student_login_id'=> 'st06', 'interview_end_time' => date('2018-05-03 12:30:00' ), 'interview_start_time' => date('2018-05-03 12:00:00' ), 'interview_result' => 'o', 'interview_feedback' => '자기PR부족'],

            // 2 -> 0402 ~ 0501 YJC / jphalo
            ['interview_id' => 2, 'student_login_id'=> 'st01', 'interview_end_time' =>  date('2018-05-02 10:30:00' ), 'interview_start_time' => date('2018-05-02 10:00:00' ),'interview_result' => 'o', 'interview_feedback' => '좋습니다'],
            ['interview_id' => 2, 'student_login_id'=> 'st02', 'interview_end_time' =>  date('2018-05-02 10:30:00' ), 'interview_start_time' => date('2018-05-02 10:00:00' ),'interview_result' => 'o', 'interview_feedback' => '완벽'],
            ['interview_id' => 2, 'student_login_id'=> 'st03', 'interview_end_time' =>  date('2018-05-02 11:00:00' ), 'interview_start_time' => date('2018-05-02 10:30:00' ), 'interview_result' => 'o', 'interview_feedback' => '자기PR부족'],
            ['interview_id' => 2, 'student_login_id'=> 'st04', 'interview_end_time' =>  date('2018-05-02 12:00:00' ), 'interview_start_time' => date('2018-05-02 11:30:00' ), 'interview_result' => 'o', 'interview_feedback' => '자기분석이 완벽함'],
            ['interview_id' => 2, 'student_login_id'=> 'st05', 'interview_end_time' =>  date('2018-05-02 12:30:00' ), 'interview_start_time' => date('2018-05-02 12:00:00' ), 'interview_result' => 'o', 'interview_feedback' => '자기분석이 완벽함'],
            
            // 3 -> 0401 ~ 0605 SNU / jphalo
            ['interview_id' => 3, 'student_login_id'=> 'st10', 'interview_end_time' => date('2018-06-02 12:30:00' ), 'interview_start_time' => date('2018-06-06 12:00:00' ), 'interview_result' => ''],

            // 4 -> 0401 ~ 0515 KNU / jphalo
            ['interview_id' => 4, 'student_login_id'=> 'st07', 'interview_end_time' => date('2018-05-16 11:30:00'), 'interview_start_time' => date('2018-05-16 10:30:00' ), 'interview_result' => ''],
            ['interview_id' => 4, 'student_login_id'=> 'st08', 'interview_end_time' => date('2018-05-16 12:30:00'), 'interview_start_time' => date('2018-05-16 12:00:00' ), 'interview_result' => ''],
        
            // 5 -> 0401 ~ 0529 KNU / jpsoft
            ['interview_id' => 5, 'student_login_id'=> 'st08', 'interview_end_time' => date('2018-05-30 18:30:00'), 'interview_start_time' => date('2018-05-30 18:00:00' ), 'interview_result' => ''],

            // 6 -> 0401 ~ 0529 KNU / CNjung
            ['interview_id' => 6, 'student_login_id'=> 'st07', 'interview_end_time' => date('2018-05-31 10:30:00'), 'interview_start_time' => date('2018-05-31 10:00:00' ), 'interview_result' => ''],
    
            // 7 -> 0601 ~ 0810 KNU / jphalo -> 아직 지원 불가

            // 8 -> 0401 ~ 0529 SNU / USgood
            ['interview_id' => 8, 'student_login_id'=> 'st09', 'interview_end_time' => date('2018-05-30 17:30:00'), 'interview_start_time' => date('2018-05-30 17:00:00'), 'interview_result' => ''],
            ['interview_id' => 8, 'student_login_id'=> 'st10', 'interview_end_time' => date('2018-05-30 20:30:00'), 'interview_start_time' => date('2018-05-30 20:00:00'), 'interview_result' => ''],                        

            // 9 -> 0503 ~
            ['interview_id' => 9, 'student_login_id'=> 'st01', 'interview_end_time' =>  date('2018-05-03 17:30:00' ), 'interview_start_time' => date('2018-05-03 17:00:00' ),'interview_result' => 'o', 'interview_feedback' => '좋습니다'],
            ['interview_id' => 9, 'student_login_id'=> 'st02', 'interview_end_time' =>  date('2018-05-03 17:30:00' ), 'interview_start_time' => date('2018-05-03 17:00:00' ),'interview_result' => 'o', 'interview_feedback' => '완벽'],
            ['interview_id' => 9, 'student_login_id'=> 'st03', 'interview_end_time' =>  date('2018-05-03 17:30:00' ), 'interview_start_time' => date('2018-05-03 17:00:00' ), 'interview_result' => 'o', 'interview_feedback' => '자기PR부족'],
            ['interview_id' => 9, 'student_login_id'=> 'st04', 'interview_end_time' =>  date('2018-05-03 18:30:00' ), 'interview_start_time' => date('2018-05-03 18:00:00' ), 'interview_result' => 'o', 'interview_feedback' => '자기분석이 완벽함'],
            ['interview_id' => 9, 'student_login_id'=> 'st05', 'interview_end_time' =>  date('2018-05-03 18:30:00' ), 'interview_start_time' => date('2018-05-03 18:00:00' ), 'interview_result' => 'o', 'interview_feedback' => '자기분석이 완벽함'],

            // 10 -> 0504 1030 1230
            ['interview_id' => 10, 'student_login_id'=> 'st01', 'interview_end_time' =>  date('2018-05-03 17:30:00' ), 'interview_start_time' => date('2018-05-03 17:00:00' ),'interview_result' => 'o', 'interview_feedback' => '좋습니다'],
            ['interview_id' => 10, 'student_login_id'=> 'st02', 'interview_end_time' =>  date('2018-05-03 17:30:00' ), 'interview_start_time' => date('2018-05-03 17:00:00' ),'interview_result' => 'o', 'interview_feedback' => '완벽'],
            ['interview_id' => 10, 'student_login_id'=> 'st03', 'interview_end_time' =>  date('2018-05-03 17:30:00' ), 'interview_start_time' => date('2018-05-03 17:00:00' ), 'interview_result' => 'o', 'interview_feedback' => '자기PR부족'],
            ['interview_id' => 10, 'student_login_id'=> 'st04', 'interview_end_time' =>  date('2018-05-03 18:30:00' ), 'interview_start_time' => date('2018-05-03 18:00:00' ), 'interview_result' => 'o', 'interview_feedback' => '자기분석이 완벽함'],
            ['interview_id' => 10, 'student_login_id'=> 'st05', 'interview_end_time' =>  date('2018-05-03 18:30:00' ), 'interview_start_time' => date('2018-05-03 18:00:00' ), 'interview_result' => 'o', 'interview_feedback' => '자기분석이 완벽함'],
            
        ];

        foreach($interview_resultSeeder as $interview_resultSeeders) {
            DB::table('interview_results')->insert([
                'interview_id' => $interview_resultSeeders['interview_id'],
                'student_login_id' => $interview_resultSeeders['student_login_id'],
                'interview_start_time' => $interview_resultSeeders['interview_start_time'],
                'interview_end_time' => $interview_resultSeeders['interview_end_time'],
                'interview_result' => $interview_resultSeeders['interview_result'],
            ]);
        }
    }
}
