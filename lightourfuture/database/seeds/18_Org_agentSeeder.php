<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class Org_agentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agent = [
            ['org_agent_id' => 'orgac04', 'manager_login_id'=> 'ag02', 'org_name' => 'hello', 
            'org_name_kana' => 'ヘロ', 'country_code' => 'JP',
            'photo_url' => 'https://n6-img-fp.akamaized.net/free-photo/nature-colorful-landscape-dusk-cloud_1203-5705.jpg?size=338&ext=jpg',
            'address_to_dou_hu_ken' => 3, 'address' => '東京都新宿区'],
            ['org_agent_id' => 'orgac02', 'manager_login_id'=> 'root', 'org_name' => 'ハロー', 
            'org_name_kana' => 'ハロー', 'country_code' => 'JP', 
            'photo_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSSItdrZVn5Y1-L-h7CHkH_xlVYeEi9CvV0li6rB54TLwQggyzSrw',
            'address_to_dou_hu_ken' => 1, 'address' => '東京都新宿区'],
            ['org_agent_id' => 'orgac03', 'manager_login_id'=> 'ag02', 'org_name' => 'グローバルタッチ', 
            'org_name_kana' => 'グローバルタッチ', 'country_code' => 'JP', 
            'photo_url' => 'https://www.pets4homes.co.uk/images/articles/771/large/cat-lifespan-the-life-expectancy-of-cats-568e40723c336.jpg',
            'address_to_dou_hu_ken' => 2, 'address' => '東京都新宿区'],
            ['org_agent_id' => 'orgac01', 'manager_login_id'=> 'ag03', 'org_name' => 'ハロー + グローバルタッチ', 
            'org_name_kana' => 'ハロー + グローバルタッチ', 'country_code' => 'JP', 
            'photo_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTJQYqUVaRT0-nxBJqRWu39IhDVbChgL2nkUox-zwVjsOJdSLnfbA',
            'address_to_dou_hu_ken' => 1, 'address' => '東京都新宿区'],

        ];
        foreach($agent as $agents){
            $faker = Faker::create();
            DB::table('org_agents')->insert([
                'org_agent_id' => $agents['org_agent_id'],
                'manager_login_id' => $agents['manager_login_id'],
                'org_name' => $agents['org_name'],
                'org_name_kana' => $agents['org_name_kana'],
                'country_code' => $agents['country_code'],
                'photo_url' => $agents['photo_url'],
                'address_to_dou_hu_ken' => $agents['address_to_dou_hu_ken'],
                'address' => $agents['address'],
                'homepage_url' => $faker->url,
            ]);
        }
    }
}
