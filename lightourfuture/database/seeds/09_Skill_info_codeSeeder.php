<?php

use Illuminate\Database\Seeder;

class Skill_info_codeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skill = [
            ['skill_field_no' => 1, 'skill_name'=> 'Linux'],
            ['skill_field_no' => 1, 'skill_name'=> 'Windows'],
            ['skill_field_no' => 1, 'skill_name'=> 'Android'],
            ['skill_field_no' => 2, 'skill_name'=> 'C'],
            ['skill_field_no' => 2, 'skill_name'=> 'C++'],
            ['skill_field_no' => 2, 'skill_name'=> 'HTML'],
            ['skill_field_no' => 2, 'skill_name'=> 'Java'],
            ['skill_field_no' => 2, 'skill_name'=> 'JavaScript'],
            ['skill_field_no' => 2, 'skill_name'=> 'php'],
            ['skill_field_no' => 2, 'skill_name'=> 'css'],
            ['skill_field_no' => 2, 'skill_name'=> 'python'],
            ['skill_field_no' => 2, 'skill_name'=> 'C#'],
            ['skill_field_no' => 3, 'skill_name'=> 'Oracle'],
            ['skill_field_no' => 3, 'skill_name'=> 'Mysql'],
            ['skill_field_no' => 4, 'skill_name'=> 'Apachi'],
            ['skill_field_no' => 4, 'skill_name'=> 'Tomcat'],
            ['skill_field_no' => 4, 'skill_name'=> 'nodeJs'],
            ['skill_field_no' => 5, 'skill_name'=> 'Laravel'],
            ['skill_field_no' => 5, 'skill_name'=> 'JSP'],
            ['skill_field_no' => 5, 'skill_name'=> 'Vue.Js'],
        ];
        foreach($skill as $skills) {
            DB::table('skill_infos')->insert([
                'skill_field_no' => $skills['skill_field_no'],
                'skill_name' => $skills['skill_name'],
            ]);
            
        }


    }
}
