<?php

use Illuminate\Database\Seeder;

class Interview_infoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        $prjs = ['書類審査+面接','職務面接', '追加面接','人間性面接', '最終面接'];
        /* '서류전형＋면접','직무면접', '추가면접','인간성면접', '최종면접' */
        foreach($prjs as $prj) {
            DB::table('interview_infos')->insert([
                'content' => $prj,
            ]);
        }
    }
}
