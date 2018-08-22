<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class ProfessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $professorSeeder = [
            ['login_id' => 'pr01', 'invite_agent_id'=> 'orgac01', 'faculty_id'=> '1', 'name'=> '정영철',
            'name_kanji'=> '紅吉東', 'name_kana'=> 'ホンギルドン', 'email' => 'gildong@gmail.com',
            'birth_date' => date('850321'), 'major' => 'WEB', 'japaness_skill_ox' => 'o',
            'profile_photo_url' => 'http://cdnweb01.wikitree.co.kr/webdata/editor/201503/23/img_20150323123714_4e427d63.jpg'
            ],
            ['login_id' => 'pr02', 'invite_agent_id'=> 'orgac01', 'faculty_id'=> '1', 'name'=> '김기종',
            'name_kanji'=> '金哲水', 'name_kana'=> 'ギムチョウス', 'email' => 'chulsu@gmail.com',
            'birth_date' => date('860419'), 'major' => 'WEB', 'japaness_skill_ox' => 'x',
            'profile_photo_url' => 'https://comps.canstockphoto.co.kr/%EB%8C%80%ED%95%99-%EA%B5%90%EC%88%98-%EC%95%84%EC%9D%B4%EC%BD%98-eps-%EB%B2%A1%ED%84%B0_csp45263983.jpg'
            ],
            ['login_id' => 'pr03', 'invite_agent_id'=> 'orgac01', 'faculty_id'=> '2', 'name'=> '김경북',
            'name_kanji'=> '김경북', 'name_kana'=> 'ギムソウ', 'email' => '1123@gmail.com',
            'birth_date' => date('860110'), 'major' => 'WEB', 'japaness_skill_ox' => 'x',
            'profile_photo_url' => 'https://en.pimg.jp/009/742/889/1/9742889.jpg'
            ],
            ['login_id' => 'pr04', 'invite_agent_id'=> 'orgac04', 'faculty_id'=> '4', 'name'=> '박서울',
            'name_kanji'=> '김서울', 'name_kana'=> 'ジャジャ', 'email' => 'rjgije@gmail.com',
            'birth_date' => date('861116'), 'major' => 'WEB', 'japaness_skill_ox' => 'x',
            'profile_photo_url' => 'https://st3.depositphotos.com/10863776/15753/v/1600/depositphotos_157532488-stock-illustration-professor-icon-with-mortarboard.jpg'
            ]
        ];

        foreach($professorSeeder as $professorSeeders) {
            DB::table('professors')->insert([
                'login_id' => $professorSeeders['login_id'],
                'invite_agent_id' => $professorSeeders['invite_agent_id'],
                'faculty_id' => $professorSeeders['faculty_id'],
                'name' => $professorSeeders['name'],
                'name_kanji' => $professorSeeders['name_kanji'],
                'name_kana' => $professorSeeders['name_kana'],
                'email' => $professorSeeders['email'],
                'profile_photo_url' => $professorSeeders['profile_photo_url'],
                'birth_date' => $professorSeeders['birth_date'],
                'major' => $professorSeeders['major'],
                'japaness_skill_ox' => $professorSeeders['japaness_skill_ox'],
            ]);
        }
    }
}
