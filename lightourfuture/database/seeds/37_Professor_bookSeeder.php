<?php

use Illuminate\Database\Seeder;

class Professor_bookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Professor_bookSeeder = [
            // pr01 -> student table 들어갈 값 st01 ~ st06
            ['login_id' => 'pr01', 'student_code' => 'YJC000001', 'name'=> '손진호', 'faculty_id' => 1, 'birth_date'=> date('950420'), 'employ_year' => '2018'],
            ['login_id' => 'pr01', 'student_code' => 'YJC000002', 'name'=> '장준수', 'faculty_id' => 1, 'birth_date'=> date('940611'), 'employ_year' => '2018'],
            ['login_id' => 'pr01', 'student_code' => 'YJC000003', 'name'=> '이효진', 'faculty_id' => 1, 'birth_date'=> date('950816'), 'employ_year' => '2018'],
            ['login_id' => 'pr01', 'student_code' => 'YJC000004', 'name'=> '류호형', 'faculty_id' => 1, 'birth_date'=> date('951109'), 'employ_year' => '2018'],
            ['login_id' => 'pr01', 'student_code' => 'YJC000005', 'name'=> '조수진', 'faculty_id' => 1, 'birth_date'=> date('970311'), 'employ_year' => '2018'],
            ['login_id' => 'pr01', 'student_code' => 'YJC000006', 'name'=> '장다연', 'faculty_id' => 1, 'birth_date'=> date('970809'), 'employ_year' => '2018'],
            // pr01 -> student table 안들어감 
            ['login_id' => 'pr01', 'student_code' => 'YJC000007', 'name'=> '성기혁', 'faculty_id' => 1, 'birth_date'=> date('940509'), 'employ_year' => '2018'],
            ['login_id' => 'pr01', 'student_code' => 'YJC000008', 'name'=> '하재형', 'faculty_id' => 1, 'birth_date'=> date('941109'), 'employ_year' => '2018'],
            ['login_id' => 'pr01', 'student_code' => 'YJC000019', 'name'=> '박효동', 'faculty_id' => 1, 'birth_date'=> date('950503'), 'employ_year' => '2018'],

            // pr03 -> student table 들어갈 값 st07 ~ st08
            ['login_id' => 'pr03', 'student_code' => 'KNU000001', 'name'=> '홍길동', 'faculty_id' => 2, 'birth_date'=> date('950420'), 'employ_year' => '2018'],
            ['login_id' => 'pr03', 'student_code' => 'KNU000002', 'name'=> '박동지', 'faculty_id' => 2, 'birth_date'=> date('940611'), 'employ_year' => '2018'],
            ['login_id' => 'pr03', 'student_code' => 'KNU000003', 'name'=> '박첨지', 'faculty_id' => 2, 'birth_date'=> date('950816'), 'employ_year' => '2018'],

            // pr03 -> student table 들어갈 값 st09 ~ st10
            ['login_id' => 'pr04', 'student_code' => 'SNU000001', 'name'=> '박주장', 'faculty_id' => 2, 'birth_date'=> date('950420'), 'employ_year' => '2018'],
            ['login_id' => 'pr04', 'student_code' => 'SNU000002', 'name'=> '김동생', 'faculty_id' => 2, 'birth_date'=> date('940611'), 'employ_year' => '2018'],
            ['login_id' => 'pr04', 'student_code' => 'SNU000003', 'name'=> '박누나', 'faculty_id' => 2, 'birth_date'=> date('950816'), 'employ_year' => '2018'],
        ];
        
        foreach($Professor_bookSeeder as $Professor_bookSeeders) {
            DB::table('professor_books')->insert([
                'login_id' => $Professor_bookSeeders['login_id'],
                'name' => $Professor_bookSeeders['name'],
                'birth_date' => $Professor_bookSeeders['birth_date'],
                'faculty_id' => $Professor_bookSeeders['faculty_id'],
                //'group_id' => $Professor_bookSeeders['group_id'],
                'employ_year' => $Professor_bookSeeders['employ_year'],
            ]);
        }
    }
}
