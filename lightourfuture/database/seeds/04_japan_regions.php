<?php

use Illuminate\Database\Seeder;

class japan_regions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $region = [
            ['country_code' => 'JP', 'name_kanji' => '北海道地方', 'name_kana'=> ''],
            ['country_code' => 'JP', 'name_kanji' => '東北地方', 'name_kana'=> 'トウホクチホウ'],
            ['country_code' => 'JP', 'name_kanji' => '関東地方', 'name_kana'=> 'カントウチホウ'],
            ['country_code' => 'JP', 'name_kanji' => '中部地方', 'name_kana'=> 'チュウブチホウ'],
            ['country_code' => 'JP', 'name_kanji' => '近畿地方', 'name_kana'=> 'キンキチホウ'],
            ['country_code' => 'JP', 'name_kanji' => '中国地方', 'name_kana'=> 'チュウゴクチホウ'],
            ['country_code' => 'JP', 'name_kanji' => '四国地方', 'name_kana'=> 'シコクチホウ'],
            ['country_code' => 'JP', 'name_kanji' => '九州地方', 'name_kana'=> 'キュウシュウチホウ'],
        ];
        
        foreach($region as $regions) {
            
            DB::table('japan_regions')->insert([
                'country_code' => $regions['country_code'],
                'name_kanji' => $regions['name_kanji'],
                'name_kana' => $regions['name_kana'],
                
            ]);
            
        }
    }
}
