<?php

use Illuminate\Database\Seeder;

class Welfare_infoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prjs = ['休暇', 'ボーナス', '支援金', '社員教育', '創業支援'];
        /* '휴가', '성과금', '지원금', '사원교육', '창업지원' */
        foreach($prjs as $prj) {
            DB::table('welfare_infos')->insert([
                'content' => $prj,
            ]);
        }
    }
}
