<?php

use Illuminate\Database\Seeder;

class Skill_LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $prjs = ['無','最上', '上', '中上', '中', '中下', '下', '最下'];
        foreach($prjs as $prj) {
            DB::table('skill_levels')->insert([
                'skill_level' => $prj,
            ]);
        }
    }
}
