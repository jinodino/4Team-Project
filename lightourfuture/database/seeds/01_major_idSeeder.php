<?php

use Illuminate\Database\Seeder;

class major_idSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $major = [
            ['content' => 'ウェブデザイン'],            //웹 디자인
            ['content' => 'ゲーム'],                   //게임
            ['content' => 'ネットワーク'],             //네트워크
            ['content' => 'ネットワークセキュリティ'],  //네트워크 보안
            ['content' => 'ウェブデータベース'],        //웹 데이터베이스
            ['content' => 'コンピューター工学'],        //컴퓨터 공학
            ['content' => '電子'],                     //전자
            ['content' => '機械'],                      //기계
            ['content' => '政治'],                      //정치
            ['content' => '外交'],                      //외교
        ];
        
        foreach($major as $majors) {
            
            DB::table('major_infos')->insert([
                'content' => $majors['content'],
            ]);
            
        }
    }
}
