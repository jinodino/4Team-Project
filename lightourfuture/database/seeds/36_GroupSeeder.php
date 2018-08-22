<?php

use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $GroupSeeder = [
            ['faculty_id'=> '1', 'group_num' => 1, 'group_name' => 'ハイキングファイト', 
            'project_content' => 'computerJapan1 TEAM', 'showcase_year' => date('Y'),
            'project_title' => 'ハイキング',
            ],
            ['faculty_id'=> '1', 'group_num' => 2, 'group_name' => 'フェノミコ', 
            'project_content' => 'computerJapan2 TEAM', 'showcase_year' => date('Y'),
            'project_title' => 'ペントミノ',
            ],
            ['faculty_id'=> '1', 'group_num' => 3, 'group_name' => 'マイグミ', 
            'project_content' => 'computerJapan5 TEAM', 'showcase_year' => date('Y'),
            'project_title' => 'オルゴール',
            ],
            ['faculty_id'=> '1', 'group_num' => 4, 'group_name' => 'やってみよう', 
            'project_content' => 'computerJapan8 TEAM', 'showcase_year' => date('Y'),
            'project_title' => 'LOF',
            ],
            ['faculty_id'=> '1', 'group_num' => 5, 'group_name' => 'さしすせそ', 
            'project_content' => 'computerJapan1 TEAM', 'showcase_year' => date('Y'),
            'project_title' => 'さしすせそ',
            ],
            ['faculty_id'=> '1', 'group_num' => 6, 'group_name' => '販機自販機', 
            'project_content' => 'computerJapan2 TEAM', 'showcase_year' => date('Y'),
            'project_title' => '自販機管理',
            ],
            ['faculty_id'=> '1', 'group_num' => 7, 'group_name' => '十ッ分十分', 
            'project_content' => 'computerJapan5 TEAM', 'showcase_year' => date('Y'),
            'project_title' => '日本語10分で十分だ',
            ],
            ['faculty_id'=> '1', 'group_num' => 8, 'group_name' => '学生管理という', 
            'project_content' => 'computerJapan8 TEAM', 'showcase_year' => date('Y'),
            'project_title' => '学生管理私の手の中に',
            ],
            ['faculty_id'=> '2', 'group_num' => 1, 'group_name' => '電気ぴりっと', 
            'project_content' => 'eleTEAM', 'showcase_year' => date('Y'),
            'project_title' => 'ぴりっと',
            ],
            ['faculty_id'=> '4', 'group_num' => 1, 'group_name' => '開始は政治に', 
            'project_content' => 'eleTEAM', 'showcase_year' => date('Y'),
            'project_title' => '政治が世界を作る',
            ],
            ['faculty_id'=> '1', 'group_num' => 0,'group_name' => '', 
            'project_content' => 'eleTEAM', 'showcase_year' => date('Y'),
            'project_title' => '',
            ],
            
        ];
/*
            ['faculty_id'=> '1', 'group_num' => 1, 'group_name' => '하이킹화이팅', 
            'project_content' => 'computerJapan1 TEAM', 'showcase_year' => date('Y'),
            'project_title' => '하이킹',
            ],
            ['faculty_id'=> '1', 'group_num' => 2, 'group_name' => '페노미코', 
            'project_content' => 'computerJapan2 TEAM', 'showcase_year' => date('Y'),
            'project_title' => '펜토미노',
            ],
            ['faculty_id'=> '1', 'group_num' => 3, 'group_name' => '마이구미', 
            'project_content' => 'computerJapan5 TEAM', 'showcase_year' => date('Y'),
            'project_title' => '오르골',
            ],
            ['faculty_id'=> '1', 'group_num' => 4, 'group_name' => '얏떼미요', 
            'project_content' => 'computerJapan8 TEAM', 'showcase_year' => date('Y'),
            'project_title' => 'LOF',
            ],
            ['faculty_id'=> '1', 'group_num' => 5, 'group_name' => '사시수세소', 
            'project_content' => 'computerJapan1 TEAM', 'showcase_year' => date('Y'),
            'project_title' => '사시수세소',
            ],
            ['faculty_id'=> '1', 'group_num' => 6, 'group_name' => '판기자판기', 
            'project_content' => 'computerJapan2 TEAM', 'showcase_year' => date('Y'),
            'project_title' => '자판기관리',
            ],
            ['faculty_id'=> '1', 'group_num' => 7, 'group_name' => '쥿분쥬우분', 
            'project_content' => 'computerJapan5 TEAM', 'showcase_year' => date('Y'),
            'project_title' => '일본어 10분이면 충분하다',
            ],
            ['faculty_id'=> '1', 'group_num' => 8, 'group_name' => '학생관리란', 
            'project_content' => 'computerJapan8 TEAM', 'showcase_year' => date('Y'),
            'project_title' => '학생관리 내손안에',
            ],
            ['faculty_id'=> '2', 'group_num' => 1, 'group_name' => '전기찌릿', 
            'project_content' => 'eleTEAM', 'showcase_year' => date('Y'),
            'project_title' => '찌릿찌릿',
            ],
            ['faculty_id'=> '4', 'group_num' => 1, 'group_name' => '시작은 정치로', 
            'project_content' => 'eleTEAM', 'showcase_year' => date('Y'),
            'project_title' => '정치가 세상을 만든다',
            ],
            ['faculty_id'=> '1', 'group_num' => 0,'group_name' => '', 
            'project_content' => 'eleTEAM', 'showcase_year' => date('Y'),
            'project_title' => '',
            ],
            */
        foreach($GroupSeeder as $GroupSeeders) {
            DB::table('groups')->insert([
                'faculty_id' => $GroupSeeders['faculty_id'],
                'group_num' => $GroupSeeders['group_num'],
                'group_name' => $GroupSeeders['group_name'],
                'project_content' => $GroupSeeders['project_content'],
                'project_title' => '',
                'showcase_year' => $GroupSeeders['showcase_year'],
            ]);
        }
    }
}
