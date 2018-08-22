<?php

use Illuminate\Database\Seeder;

class Work_infoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prjs = ['システムエンジニア', 'プロジェクトマネージャー', 'ネットワークエンジニア', 'データベースエンジニア', 
    'サーバエンジニア', 'Webエンジニア', 'マークアップエンジニア', 'フロントエンジニア', '制御・エンベデッドエンジニア', 'プログラマー',
    '試験エンジニア', 'セールスエンジニア', 'フィールドエンジニア', '支援エンジニア', '社内SE', 'ブリッジSE', 'その他'];
    /* '시스템엔지니어', '프로젝트 매니저', '네트워크 엔지니어', '데이터베이스 엔지니어', 
    '서버 엔지니어', 'Web 엔지니어', '마크업 엔지니어', '프론트 엔지니어', '제어・임베디드 엔지니어', '프로그래머',
    '시험 엔지니어', '세일즈 엔지니어', '필드 엔지니어', '지원 엔지니어', '사내SE', '브리지SE', '기타' */
        foreach($prjs as $prj) {
            DB::table('work_infos')->insert([
                'content' => $prj,
            ]);
        }
    }
}
