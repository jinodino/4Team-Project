<?php

use Illuminate\Database\Seeder;
use App\Model\BusinessBigInfo;

class Business_Big_infoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prjs = ['C用の自社サービス', 'B用の自社サービス', '受託開発', '派遣', 'その他'];
        /*'C용 자사 서비스', 'B용 자사 서비스', '수탁개발', '파견', '기타' */
        foreach($prjs as $prj) {
            DB::table('business_big_infos')->insert([
                'content' => $prj,
            ]);
        }
    }
}
