<?php

use Illuminate\Database\Seeder;

class Desired_employment_infoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prjs = ['率直さ', '熱意', '誠実', '挑戦精神', 'ハングリー精神', '礼儀',
    '誠実さ', '持続力', '謙遜', '愛性', '吸収力', '成長欲求', '会社貢献への欲求', '好奇心',
    '自主性', '開城', '柔軟性', '技術的', '技術力', 'リーダーシップ', '創造性', '目端', '意思疎通能力', 'グローバル人材', 'ファッションセンス'];
    /* '솔직함', '열의', '성실', '도전정신', '헝그리 정신', '예의',
    '꾸준함', '지속력', '겸손', '귀염성', '흡수력', '성장욕구', '회사공헌욕구', '호기심',
    '자주성', '개성', '유연성', '기술적', '기술력', '리더쉽', '창조성', '눈치', '의사소통능력', '글로벌 인재', '패션감각' */
        foreach($prjs as $prj) {
            DB::table('desired_employment_infos')->insert([
                'content' => $prj,
            ]);
        }
    }
}
