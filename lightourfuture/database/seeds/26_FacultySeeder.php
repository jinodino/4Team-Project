<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//yjc01, knu01, snu01
        $faculty = [
            ['org_college_id'=> 'yjc01', 'department_name' => 'コンピュータ情報系列', 'major_id' => 5, 'major_name' => 'WDJ',
            'college_category_sub' => 3, 'class_name' => '日本クラス'],
            ['org_college_id'=> 'knu01', 'department_name' => 'コンピュータ工学', 'major_id' => 4, 'major_name' => 'computer',
            'college_category_sub' => 4, 'class_name' => null],
            ['org_college_id'=> 'yjc01', 'department_name' => '電子機械', 'major_id' => 7, 'major_name' => 'ele',
            'college_category_sub' => 2, 'class_name' => '日本クラス'],
            ['org_college_id'=> 'snu01', 'department_name' => '政治外交', 'major_id' => 9, 'major_name' => 'jungchi',
            'college_category_sub' => 4, 'class_name' => null],

        ];
        
        foreach($faculty as $faculties) {
            
            DB::table('faculties')->insert([
                'org_college_id' => $faculties['org_college_id'],
                'department_name' => $faculties['department_name'],
                'major_id' => $faculties['major_id'],
                'major_name' => $faculties['major_name'],
                'college_category_sub' => $faculties['college_category_sub'],
                'class_name' => $faculties['class_name']
            ]);
            
        }
    }
}
