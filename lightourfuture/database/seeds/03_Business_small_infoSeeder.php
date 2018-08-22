<?php

use Illuminate\Database\Seeder;
use App\Model\BusinessSmallInfo;

class Business_small_infoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prjs = ['メディア', 'ゲーム', 'エンターテインメント', 'EC', '小売り', '美容', '金融', '不動産', '人材', '教育', '旅行', '食べ物', '医療', '通信',
        'コンサルティング', 'マーケティング', 'スティング', 'アウトソーシング', 'SaaS', 'ソフトウェアベンダー', 'ハードウェアベンダ',
        '受託開発', '海外委託開発', '派遣', 'ホスティング', 'IoT', 'AI'];
        /*'미디어', '게임', '엔터테인먼트', 'EC', '소매', '미용', '금융', '부동산', '인재', '교육', '여행', '음식', '의료', '통신',
        '컨설팅', '마케팅', '스팅', '아웃소싱', 'SaaS', '소프트웨어 벤더', '하드웨어 벤더',
        '수탁개발', '해외 위탁 개발', '파견', '호스팅', 'IoT', 'AI' */
        foreach($prjs as $prj) {
            DB::table('business_small_infos')->insert([
                'content' => $prj,
            ]);
        }
    }
}
