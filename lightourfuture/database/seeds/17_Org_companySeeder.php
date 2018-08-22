<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
        
class Org_companySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//jphalo jpsoft krsam CNjung USgood
        $companies = [
            ['org_company_id' => 'jphalo', 'manager_login_id'=> 'co01', 'org_name' => '新人補',
            'org_name_kana' => 'クラヨン', 'country_code' => 'JP',
            'address_to_dou_hu_ken' => 3,
            'address' => '東京都新宿区','ceo_name' => '孫振豪','ceo_name_kana' => 'シンチャン','worker_count' => '500',
            'photo_url' => 'http://www2.vollmer-group.com/uploads/media/VOLLMER_Company_01.jpg',
            'capital' => '5000','business_content' => 'サーバー構築や、サーバー保守管理','company_atmosphere' => '暖かいです',
            'recommendation_comment' => 'この会社はいいところです。', 'listed_company_ox' => 'o'],
            ['org_company_id' => 'jpsoft', 'manager_login_id'=> 'co02', 'org_name' => '小後土',
            'org_name_kana' => 'ソプと', 'country_code' => 'JP',
            'address_to_dou_hu_ken' => 2,
            'address' => '東京都新宿区','ceo_name' => '孫雅代','ceo_name_kana' => 'そんまさよ','worker_count' => '2000',
            'photo_url' => 'https://vatnumberuk.com/wp-content/uploads/2018/04/company.png',
            'capital' => '12000','business_content' => 'ポータルサイト運用','company_atmosphere' => '大きいです',
            'recommendation_comment' => 'この会社は大きい。','listed_company_ox' => 'o'],
            ['org_company_id' => 'krsam', 'manager_login_id'=> 'co03', 'org_name' => '三成',
            'org_name_kana' => 'サンション', 'country_code' => 'JP',
            'address_to_dou_hu_ken' => 1,
            'address' => '東京都新宿区','ceo_name' => '二巾禧','ceo_name_kana' => 'leegunhi','worker_count' => '5000',
            'photo_url' => 'https://bizztor.com/in/wp-content/uploads/sites/11/2018/01/how-to-register-a-company-in-India.jpg',
            'capital' => '52000','business_content' => '電子、スマートフォン、保険','company_atmosphere' => 'とても大きいです',
            'recommendation_comment' => 'この会社は非常に大きくて良いところです。', 'listed_company_ox' => 'o'],
            ['org_company_id' => 'CNjung', 'manager_login_id'=> 'co04', 'org_name' => '中鞠',
            'org_name_kana' => 'ｼﾞｭﾝギュク', 'country_code' => 'JP',
            'address_to_dou_hu_ken' => 3,
            'address' => '東京都新宿区','ceo_name' => '太塞土','ceo_name_kana' => 'てせと','worker_count' => '2000',
            'photo_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQvL-2-B1hqp8AdLuVwZblAlqV-e6DloJuLIAcfWtZmjY-jViI',
            'capital' => '12000','business_content' => 'クラウドサービス運用','company_atmosphere' => '大きいです',
            'recommendation_comment' => 'この会社は会社です。', 'listed_company_ox' => 'x'],
            ['org_company_id' => 'USgood', 'manager_login_id'=> 'co05', 'org_name' => 'engs',
            'org_name_kana' => 'エンス', 'country_code' => 'JP',
            'address_to_dou_hu_ken' => 1,
            'address' => '東京都新宿区','ceo_name' => 'goldman','ceo_name_kana' => 'ゴルドーマン','worker_count' => '2000',
            'photo_url' => 'http://www.austarunion.com/Uploads/201704/58e5e9a5a9777.jpg',
            'capital' => '12000','business_content' => 'ゲーム製作','company_atmosphere' => '面白いです。',
            'recommendation_comment' => 'この会社はなんと☆ゲーム☆会社です。', 'listed_company_ox' => 'x'],
        ];
        /* ['org_company_id' => 'jphalo', 'manager_login_id'=> 'co01', 'org_name' => '新人補',
            'org_name_kana' => 'クラヨン', 'country_code' => 'JP',
            'address_to_dou_hu_ken' => 3,
            'address' => '東京都新宿区','ceo_name' => '孫振豪','ceo_name_kana' => 'シンチャン','worker_count' => '500',
            'photo_url' => 'http://www2.vollmer-group.com/uploads/media/VOLLMER_Company_01.jpg',
            'capital' => '5000','business_content' => '서버 구축및 서버 유지 보수 관리','company_atmosphere' => '따뜻합니다',
            'recommendation_comment' => '이 회사는 좋은 곳 입니다.', 'listed_company_ox' => 'o'],
            ['org_company_id' => 'jpsoft', 'manager_login_id'=> 'co02', 'org_name' => '小後土',
            'org_name_kana' => 'ソプと', 'country_code' => 'JP',
            'address_to_dou_hu_ken' => 2,
            'address' => '東京都新宿区','ceo_name' => '孫雅代','ceo_name_kana' => 'そんまさよ','worker_count' => '2000',
            'photo_url' => 'https://vatnumberuk.com/wp-content/uploads/2018/04/company.png',
            'capital' => '12000','business_content' => '포털사이트 운용','company_atmosphere' => '큽니다',
            'recommendation_comment' => '이 회사는 큽니다.','listed_company_ox' => 'o'],
            ['org_company_id' => 'krsam', 'manager_login_id'=> 'co03', 'org_name' => '三成',
            'org_name_kana' => 'サンション', 'country_code' => 'JP',
            'address_to_dou_hu_ken' => 1,
            'address' => '東京都新宿区','ceo_name' => '二巾禧','ceo_name_kana' => 'leegunhi','worker_count' => '5000',
            'photo_url' => 'https://bizztor.com/in/wp-content/uploads/sites/11/2018/01/how-to-register-a-company-in-India.jpg',
            'capital' => '52000','business_content' => '전자, 스마트폰, 보험','company_atmosphere' => '매우매우 큽니다',
            'recommendation_comment' => '이 회사는 매우 크고 좋은 곳 입니다.', 'listed_company_ox' => 'o'],
            ['org_company_id' => 'CNjung', 'manager_login_id'=> 'co04', 'org_name' => '中鞠',
            'org_name_kana' => 'ｼﾞｭﾝギュク', 'country_code' => 'JP',
            'address_to_dou_hu_ken' => 3,
            'address' => '東京都新宿区','ceo_name' => '太塞土','ceo_name_kana' => 'てせと','worker_count' => '2000',
            'photo_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQvL-2-B1hqp8AdLuVwZblAlqV-e6DloJuLIAcfWtZmjY-jViI',
            'capital' => '12000','business_content' => '클라우드 서비스 운용','company_atmosphere' => '큽니다',
            'recommendation_comment' => '이 회사는 회사입니다.', 'listed_company_ox' => 'x'],
            ['org_company_id' => 'USgood', 'manager_login_id'=> 'co05', 'org_name' => 'engs',
            'org_name_kana' => 'エンス', 'country_code' => 'JP',
            'address_to_dou_hu_ken' => 1,
            'address' => '東京都新宿区','ceo_name' => 'goldman','ceo_name_kana' => 'ゴルドーマン','worker_count' => '2000',
            'photo_url' => 'http://www.austarunion.com/Uploads/201704/58e5e9a5a9777.jpg',
            'capital' => '12000','business_content' => '게임 제작','company_atmosphere' => '재미있습니다.',
            'recommendation_comment' => '이 회사는 무려 ☆게임☆회사입니다.', 'listed_company_ox' => 'x'] */
        foreach($companies as $company){
            $faker = Faker::create();
            DB::table('org_companies')->insert([
                'org_company_id' => $company['org_company_id'],
                'manager_login_id' => $company['manager_login_id'],
                'org_name' => $company['org_name'],
                'org_name_kana' => $company['org_name_kana'],
                'country_code' => $company['country_code'],
                'photo_url' => $company['photo_url'],
                'address_to_dou_hu_ken' => $company['address_to_dou_hu_ken'],
                'address' => $company['address'],
                'homepage_url' => $faker->url,
                'establish_date' => $faker->date,
                'ceo_name' => $company['ceo_name'],
                'ceo_name_kana' => $company['ceo_name_kana'],
                'worker_count' => $company['worker_count'],
                'capital' => $company['capital'],
                'business_content' => $company['business_content'],
                'company_atmosphere' => $company['company_atmosphere'],
                'recommendation_comment' => $company['recommendation_comment'],
                
            ]);
        }
    }
}
