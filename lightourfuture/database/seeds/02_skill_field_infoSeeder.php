<?php

use Illuminate\Database\Seeder;

class Skill_field_info_code extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $skill = [
                ['skill_field' => 'OS'],
                ['skill_field' => 'computer language'],
                ['skill_field' => 'DB'],
                ['skill_field' => 'Server'],
                ['skill_field' => 'other'],
            ];
            foreach($skill as $skills) {
                DB::table('skill_field_info')->insert([
                    'skill_field' => $skills['skill_field'],
                ]);
                
            }
    
    
    }
}
