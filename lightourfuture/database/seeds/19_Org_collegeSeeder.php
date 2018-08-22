<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class Org_collegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//yjc01, knu01, snu01
        $org_college = [
            ['org_college_id' => 'yjc01', 'country_code'=> 'KR', 'college_alias' => 'YJC', 'org_name' => '永進専門大学',
            'org_name_kana' => 'ヨンジン', 'address_city' => '大邱市', 'latitude' => '35.896319', 'longitude' => '128.622052',
            'photo_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR4cvZ0orlbpv7KMaq9exErQw92KQdm50YVri6PYHetrvMETdYU',
            'homepage_url'  => 'http://www.yjc.ac.kr/',
            'address' => '大邱市北区伏賢洞', 'credit_total' => 4.5, 'college_category' => 'c'],
            ['org_college_id' => 'knu01', 'country_code'=> 'KR', 'college_alias' => 'KNU', 'org_name' => '慶北大学校',
            'org_name_kana' => 'ケイホクダイガク', 'address_city' => '大邱市', 'latitude' => '35.890252', 'longitude' => '128.611296',
            'homepage_url'  => 'http://www.knu.ac.kr/',
            'photo_url' =>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSfKpTARXt5KWgqrEpxEVEUlwnUcNZJG5AlwqAwXY80s8V0hLmt',
            'address' => '大邱市北区山格洞', 'credit_total' => 4.5, 'college_category' => 'u'],
            ['org_college_id' => 'snu01', 'country_code'=> 'KR', 'college_alias' => 'SNU', 'org_name' => 'ソウル大学',
            'org_name_kana' => 'ソウルダイガク', 'address_city' => 'ソウル市', 'latitude' => '37.460101', 'longitude' => '126.951864',
            'homepage_url'  => 'http://www.snu.ac.kr/',
            'photo_url' => 'http://student.snu.ac.kr/wp-content/uploads/2013/12/snu_portrait.png',
            'address' => 'ソウル市冠岳區冠岳路', 'credit_total' => 4.5, 'college_category' => 'u'],
        ];
        $faker = Faker::create();
        foreach($org_college as $org_colleges){
            DB::table('org_colleges')->insert([
                'org_college_id' => $org_colleges['org_college_id'],
                'country_code' => $org_colleges['country_code'],
                'college_alias' => $org_colleges['college_alias'],
                'org_name' => $org_colleges['org_name'],
                'org_name_kana' => $org_colleges['org_name_kana'],
                'photo_url' => $org_colleges['photo_url'],
                'address_city' => $org_colleges['address_city'],
                'address' => $org_colleges['address'],
                'homepage_url' => $org_colleges['homepage_url'],
                'credit_total' => $org_colleges['credit_total'],
                'college_category' => $org_colleges['college_category']
            ]);
        }
    }
}
