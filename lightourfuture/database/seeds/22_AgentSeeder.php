<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agent = [
            ['login_id' => 'root', 'name' => '矢野',
            'name_kana' => 'ヤノ', 'email'=> 'aaa@naver.com',
            'profile_photo_url' => 'https://st2.depositphotos.com/3369547/11512/v/950/depositphotos_115123878-stock-illustration-man-face-icon-male-person.jpg',
            'birth_date' => date('181121')],
            ['login_id' => 'ag02', 'name' => '二はお',
            'name_kana' => 'ニハオ', 'email'=> 'bbb@naver.com',
            'profile_photo_url' => 'https://png.pngtree.com/element_origin_min_pic/16/07/09/205780ec8d7cd7e.jpg',
            'birth_date' => date('951001')],
            ['login_id' => 'ag03', 'name' => '胃の',
            'name_kana' => 'イノ', 'email'=> 'ccc@naver.com',
            'profile_photo_url' => 'https://png.pngtree.com/element_origin_min_pic/16/07/03/1157788a8237d7d.jpg',
            'birth_date' => date('951001')],
            
        ];
        foreach($agent as $agents){
            $faker = Faker::create();
            DB::table('agents')->insert([
                'login_id' => $agents['login_id'],
                'name' =>$agents['name'],
                'name_kana' =>$agents['name_kana'],
                'email' => $agents['email'],
                'profile_photo_url' =>$agents['profile_photo_url'],
                'birth_date' =>$agents['birth_date']
            ]);
        }
    }
}
