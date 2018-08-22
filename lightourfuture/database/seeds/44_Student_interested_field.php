<?php

use Illuminate\Database\Seeder;

class Student_interested_field extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//st01, 02,03,04,05
        $student_intersted_field = [
            ['business_small_id' => 1, 'student_login_id'=> 'st01'],
            ['business_small_id' => 2, 'student_login_id'=> 'st04'],
            ['business_small_id' => 3, 'student_login_id'=> 'st03'],
            ['business_small_id' => 6, 'student_login_id'=> 'st05'],
            ['business_small_id' => 8, 'student_login_id'=> 'st01'],
            ['business_small_id' => 10, 'student_login_id'=> 'st02'],
            ['business_small_id' => 15, 'student_login_id'=> 'st04'],
            ['business_small_id' => 6, 'student_login_id'=> 'st02'],
            ['business_small_id' => 9, 'student_login_id'=> 'st05'],
            ['business_small_id' => 20, 'student_login_id'=> 'st01'],
            ['business_small_id' => 2, 'student_login_id'=> 'st03'],
            ['business_small_id' => 11, 'student_login_id'=> 'st03'],
            ['business_small_id' => 10, 'student_login_id'=> 'st01'],
            ['business_small_id' => 2, 'student_login_id'=> 'st06'],
            ['business_small_id' => 11, 'student_login_id'=> 'st06'],
            ['business_small_id' => 10, 'student_login_id'=> 'st06'],
            ['business_small_id' => 2, 'student_login_id'=> 'st07'],
            ['business_small_id' => 11, 'student_login_id'=> 'st07'],
            ['business_small_id' => 10, 'student_login_id'=> 'st07'],
            ['business_small_id' => 2, 'student_login_id'=> 'st08'],
            ['business_small_id' => 11, 'student_login_id'=> 'st08'],
            ['business_small_id' => 10, 'student_login_id'=> 'st08'],
            ['business_small_id' => 2, 'student_login_id'=> 'st09'],
            ['business_small_id' => 11, 'student_login_id'=> 'st09'],
            ['business_small_id' => 10, 'student_login_id'=> 'st09'],
            ['business_small_id' => 2, 'student_login_id'=> 'st10'],
            ['business_small_id' => 11, 'student_login_id'=> 'st10'],
            ['business_small_id' => 10, 'student_login_id'=> 'st10'],
        ];

        foreach($student_intersted_field as $student_intersted_fields) {
            DB::table('student_interested_field')->insert([
                'business_small_id' => $student_intersted_fields['business_small_id'],
                'student_login_id' => $student_intersted_fields['student_login_id'],
            ]);
        }
    }
}
