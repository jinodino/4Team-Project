<?php

use Illuminate\Database\Seeder;

class japan_prefectures extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prefecture = [
            ['region_id' => '2','name_kanji' => '青森県', 'name_kana'=> 'アオモリケン'],
            ['region_id' => '2','name_kanji' => '秋田県', 'name_kana'=> 'アキタケン'],
            ['region_id' => '2','name_kanji' => '山形県', 'name_kana'=> 'ヤマガタケン'],
            ['region_id' => '2','name_kanji' => '福島県', 'name_kana'=> 'フクシマケン'],

        ];
        
        foreach($prefecture as $prefectures) {
            
            DB::table('japan_prefectures')->insert([
                'region_id' => $prefectures['region_id'],
                'name_kanji' => $prefectures['name_kanji'],
                'name_kana' => $prefectures['name_kana'],
                
            ]);
            
        }
    }
}
