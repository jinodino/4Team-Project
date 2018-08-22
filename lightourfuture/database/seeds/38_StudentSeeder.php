<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
             /*
            $table->integer('mock_TOEIC')->nullable();          //모의토익
            $table->date('mock_TOEIC_acquisition_date')->nullable();//취득년월
             */
        $StudentSeeder = [
            // YJC 6명 ----------------------
            ['login_id' => 'st01', 'student_no' => '1401163', 'student_code'=> 'YJC00001', 'professor_login_id'=> 'pr01', 'name' => '손진호',
            'name_eng' => 'SonJinho', 'name_kanji' => '遜眞浩', 'name_kana' => 'ソンジンホ', 'email' => 'sjh@gmail.com', 'profile_photo_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRPgWqdzflBT9V76cAmKuBURbN-e4CixESgnJnL4n2J-c_zTToe8w',
            'birth_date' => date('950420'), 'employ_year' => '2018','graduate_date' => date('20190228'),'skill_contents'=>'cよくやった、ジャバ・できない', 'employ_year', 
            'group_id' => '4', 'group_part_content' => 'BACKEND', 'gender' => 'm', 'credit' => 4.3, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => '私の夢を実現するために', 'interested_field_content' => 'IOT',
            'pr_content' => '私は上手です。',
            'recommendation_comment' => '優れています', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st02', 'student_no' => '131154', 'student_code'=> 'YJC00002', 'professor_login_id'=> 'pr01', 'name' => '장준수',
            'name_eng' => 'Jangjunsu', 'name_kanji' => '長俊需', 'name_kana' => 'ジャンヨンス', 'email' => 'hgd@gmail.com', 'profile_photo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Won_Bin_from_acrofan.jpg/250px-Won_Bin_from_acrofan.jpg',
            'birth_date' => date('940611'), 'employ_year' => '2018','graduate_date' => date('20190228'),'skill_contents'=>'cよくやった、ジャバ・できない',
            'group_id' => '1', 'group_part_content' => 'BACKEND', 'gender' => 'm', 'credit' => 4.3, 'JPT' => 400, 'JLPT' => 1, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => '日本風が良くて', 'interested_field_content' => 'DB',
            'pr_content' => '私は上手です。',
            'recommendation_comment' => 'いいです。', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st03', 'student_no' => '160515', 'student_code'=> 'YJC00003', 'professor_login_id'=> 'pr01', 'name' => '이효진',
            'name_eng' => 'KimChulsu', 'name_kanji' => '利孝進', 'name_kana' => 'チョウス', 'email' => 'kcs@gmail.com', 'profile_photo_url' => 'http://theshow.pro/wp-content/uploads/2014/04/430_%EC%97%AC%EC%84%B1-MC_%EC%95%84%EB%82%98%EC%9A%B4%EC%84%9C_%EC%9D%B4%ED%9A%A8%EC%A7%84-1.jpg',
            'birth_date' => date('950816'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'cよくやった、ジャバ・できない', 'employ_ox' => 'x',
            'group_id' => '2', 'group_part_content' => 'FRONTEND', 'gender' => 'm', 'credit' => 2.1, 'JPT' => 700, 'JLPT' => 1, 'mock_TOEIC' => 200,
            'TOEIC' => 100, 'TOEFL' => 210, 'activities' => '', 'qualification_content' => '', 'motivation_content' => '日本が好きで', 'interested_field_content' => 'JAVA',
            'pr_content' => '私は本当に上手です。',
            'recommendation_comment' => 'そうです。', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st04', 'student_no' => '4513842', 'student_code'=> 'YJC00004', 'professor_login_id'=> 'pr01', 'name' => '류호형',
            'name_eng' => 'Kimyounghi', 'name_kanji' => '柳好馨', 'name_kana' => 'キムヨン', 'email' => 'kyh@gmail.com', 'profile_photo_url' => 'http://www.pkicon.com/icons/3549/Student-256.png',
            'birth_date' => date('951109'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'cよくやった、ジャバ・できない',
            'group_id' => '3', 'group_part_content' => 'DB', 'gender' => 'm', 'credit' => 3.5, 'JPT' => 450, 'JLPT' => 3, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => 'グローバル人材になるために', 'interested_field_content' => '',
            'pr_content' => '私は上手です。',
            'recommendation_comment' => '優れています', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st05', 'student_no' => '4513842', 'student_code'=> 'YJC00005', 'professor_login_id'=> 'pr01', 'name' => '조수진',
            'name_eng' => 'jangyoungsu', 'name_kanji' => '調秀進', 'name_kana' => 'ジャンヨン', 'email' => 'jys@gmail.com', 'profile_photo_url' => 'https://st.depositphotos.com/3441621/5120/i/950/depositphotos_51208695-stock-photo-portrait-of-woman-doctor-with.jpg',
            'birth_date' => date('970311'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'cよくやった、ジャバ・できない',
            'group_id' => '4', 'group_part_content' => 'frontend', 'gender' => 'm', 'credit' => 3.1, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => 'いこう！！', 'interested_field_content' => 'IOT',
            'pr_content' => '私は上手です。',
            'recommendation_comment' => '優れています', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st06', 'student_no' => '4513842', 'student_code'=> 'YJC00006', 'professor_login_id'=> 'pr01', 'name' => '장다연',
            'name_eng' => 'bumbuk', 'name_kanji' => '張多硏', 'name_kana' => 'ブンブク', 'email' => 'eroijil@gmail.com', 'profile_photo_url' => 'http://cfile4.uf.tistory.com/image/235BED48576D0D6C33CC87',
            'birth_date' => date('970809'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'cよくやった、ジャバ・できない',
            'group_id' => '3', 'group_part_content' => 'DB', 'gender' => 'm', 'credit' => 3.5, 'JPT' => 450, 'JLPT' => 3, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => 'グローバル人材になるために', 'interested_field_content' => '',
            'pr_content' => '私は上手です。',
            'recommendation_comment' => '優れています', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            // KNU 2명 --------------------------------------------------------
            ['login_id' => 'st07', 'student_no' => '4513842', 'student_code'=> 'KNU00001', 'professor_login_id'=> 'pr03', 'name' => '홍길동',
            'name_eng' => 'zuzu', 'name_kanji' => '洪吉動', 'name_kana' => 'ジュジュ', 'email' => 'bijem@gmail.com', 'profile_photo_url' => 'https://pbs.twimg.com/media/COl-_KIVEAECY35.jpg',
            'birth_date' => date('950420'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'cよくやった、ジャバ・できない',
            'group_id' => '4', 'group_part_content' => 'frontend', 'gender' => 'w', 'credit' => 3.1, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => 'いこう！！', 'interested_field_content' => 'IOT',
            'pr_content' => '私は上手です。',
            'recommendation_comment' => '優れています', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st08', 'student_no' => '4513842', 'student_code'=> 'KNU00002', 'professor_login_id'=> 'pr03', 'name' => '박동지',
            'name_eng' => 'dongji', 'name_kanji' => '博東指', 'name_kana' => 'ジュジュ', 'email' => 'ehnnv@gmail.com', 'profile_photo_url' => 'http://cfile23.uf.tistory.com/image/1546284B4EF0BAA933B645',
            'birth_date' => date('940611'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'cよくやった、ジャバ・できない',
            'group_id' => '4', 'group_part_content' => 'frontend', 'gender' => 'm', 'credit' => 3.1, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => 'いこう！！', 'interested_field_content' => 'IOT',
            'pr_content' => '私は上手です。',
            'recommendation_comment' => '優れています', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            // SNU 2명 ---------------------------------------------------------
            ['login_id' => 'st09', 'student_no' => '4513842', 'student_code'=> 'SNU00001', 'professor_login_id'=> 'pr04', 'name' => '박주장',
            'name_eng' => 'juzang', 'name_kanji' => '薄朱張', 'name_kana' => 'ジュジュ', 'email' => 'qmkm@gmail.com', 'profile_photo_url' => 'http://cfile24.uf.tistory.com/image/1720E7364F4705EC0A3291',
            'birth_date' => date('950420'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'cよくやった、ジャバ・できない',
            'group_id' => '4', 'group_part_content' => 'frontend', 'gender' => 'w', 'credit' => 3.1, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => 'いこう！！', 'interested_field_content' => 'IOT',
            'pr_content' => '私は上手です。',
            'recommendation_comment' => '優れています', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st10', 'student_no' => '4513842', 'student_code'=> 'SNU00002', 'professor_login_id'=> 'pr04', 'name' => '김동생',
            'name_eng' => 'dongseng', 'name_kanji' => '金銅生', 'name_kana' => 'ジュジュ', 'email' => 'zzsd@gmail.com', 'profile_photo_url' => 'http://image.chosun.com/sitedata/image/201711/28/2017112803570_0.jpg',
            'birth_date' => date('940611'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'cよくやった、ジャバ・できない',
            'group_id' => '4', 'group_part_content' => 'frontend', 'gender' => 'w', 'credit' => 3.1, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => 'いこう！！', 'interested_field_content' => 'IOT',
            'pr_content' => '私は上手です。',
            'recommendation_comment' => '優れています', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],
        ];
        /* // YJC 6명 ----------------------
            ['login_id' => 'st01', 'student_no' => '1401163', 'student_code'=> 'YJC00001', 'professor_login_id'=> 'pr01', 'name' => '손진호',
            'name_eng' => 'SonJinho', 'name_kanji' => '遜眞浩', 'name_kana' => 'ソンジンホ', 'email' => 'sjh@gmail.com', 'profile_photo_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRPgWqdzflBT9V76cAmKuBURbN-e4CixESgnJnL4n2J-c_zTToe8w',
            'birth_date' => date('950420'), 'employ_year' => '2018','graduate_date' => date('20190228'),'skill_contents'=>'c 잘함, 자바 못함', 'employ_year', 
            'group_id' => '4', 'group_part_content' => 'BACKEND', 'gender' => 'm', 'credit' => 4.3, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => '내꿈을 펼치기 위해', 'interested_field_content' => 'IOT',
            'pr_content' => '전 매우 잘합니다.',
            'recommendation_comment' => '우수합니다', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st02', 'student_no' => '131154', 'student_code'=> 'YJC00002', 'professor_login_id'=> 'pr01', 'name' => '장준수',
            'name_eng' => 'Jangjunsu', 'name_kanji' => '長俊需', 'name_kana' => 'ジャンヨンス', 'email' => 'hgd@gmail.com', 'profile_photo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Won_Bin_from_acrofan.jpg/250px-Won_Bin_from_acrofan.jpg',
            'birth_date' => date('940611'), 'employ_year' => '2018','graduate_date' => date('20190228'),'skill_contents'=>'c 잘함, 자바 못함',
            'group_id' => '1', 'group_part_content' => 'BACKEND', 'gender' => 'm', 'credit' => 4.3, 'JPT' => 400, 'JLPT' => 1, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => '일본풍이 좋아서', 'interested_field_content' => 'DB',
            'pr_content' => '전 매우 잘합니다.',
            'recommendation_comment' => '좋습니다.', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st03', 'student_no' => '160515', 'student_code'=> 'YJC00003', 'professor_login_id'=> 'pr01', 'name' => '이효진',
            'name_eng' => 'KimChulsu', 'name_kanji' => '利孝進', 'name_kana' => 'チョウス', 'email' => 'kcs@gmail.com', 'profile_photo_url' => 'http://theshow.pro/wp-content/uploads/2014/04/430_%EC%97%AC%EC%84%B1-MC_%EC%95%84%EB%82%98%EC%9A%B4%EC%84%9C_%EC%9D%B4%ED%9A%A8%EC%A7%84-1.jpg',
            'birth_date' => date('950816'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'c 잘함, ㅁㄴㅇㅁㄴ자바 못함', 'employ_ox' => 'x',
            'group_id' => '2', 'group_part_content' => 'FRONTEND', 'gender' => 'm', 'credit' => 2.1, 'JPT' => 700, 'JLPT' => 1, 'mock_TOEIC' => 200,
            'TOEIC' => 100, 'TOEFL' => 210, 'activities' => '', 'qualification_content' => '', 'motivation_content' => '일본이 좋아서', 'interested_field_content' => 'JAVA',
            'pr_content' => '전 정말정말 잘합니다.',
            'recommendation_comment' => '그렇습니다', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st04', 'student_no' => '4513842', 'student_code'=> 'YJC00004', 'professor_login_id'=> 'pr01', 'name' => '류호형',
            'name_eng' => 'Kimyounghi', 'name_kanji' => '柳好馨', 'name_kana' => 'キムヨン', 'email' => 'kyh@gmail.com', 'profile_photo_url' => 'http://www.pkicon.com/icons/3549/Student-256.png',
            'birth_date' => date('951109'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'c 잘함,$#@$ 자바 못함',
            'group_id' => '3', 'group_part_content' => 'DB', 'gender' => 'm', 'credit' => 3.5, 'JPT' => 450, 'JLPT' => 3, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => '글로벌 인재가 되기 위하여', 'interested_field_content' => '',
            'pr_content' => '전 매우 잘합니다.',
            'recommendation_comment' => '우수합니다', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st05', 'student_no' => '4513842', 'student_code'=> 'YJC00005', 'professor_login_id'=> 'pr01', 'name' => '조수진',
            'name_eng' => 'jangyoungsu', 'name_kanji' => '調秀進', 'name_kana' => 'ジャンヨン', 'email' => 'jys@gmail.com', 'profile_photo_url' => 'https://st.depositphotos.com/3441621/5120/i/950/depositphotos_51208695-stock-photo-portrait-of-woman-doctor-with.jpg',
            'birth_date' => date('970311'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'c 잘함, !!##자바 못함',
            'group_id' => '4', 'group_part_content' => 'frontend', 'gender' => 'm', 'credit' => 3.1, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => '가즈아!!', 'interested_field_content' => 'IOT',
            'pr_content' => '전 잘합니다.',
            'recommendation_comment' => '우수합니다', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st06', 'student_no' => '4513842', 'student_code'=> 'YJC00006', 'professor_login_id'=> 'pr01', 'name' => '장다연',
            'name_eng' => 'bumbuk', 'name_kanji' => '張多硏', 'name_kana' => 'ブンブク', 'email' => 'eroijil@gmail.com', 'profile_photo_url' => 'http://cfile4.uf.tistory.com/image/235BED48576D0D6C33CC87',
            'birth_date' => date('970809'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'c 잘함 자바 못함',
            'group_id' => '3', 'group_part_content' => 'DB', 'gender' => 'm', 'credit' => 3.5, 'JPT' => 450, 'JLPT' => 3, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => '글로벌 인재가 되기 위하여', 'interested_field_content' => '',
            'pr_content' => '전 매우 잘합니다.',
            'recommendation_comment' => '우수합니다', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            // KNU 2명 --------------------------------------------------------
            ['login_id' => 'st07', 'student_no' => '4513842', 'student_code'=> 'KNU00001', 'professor_login_id'=> 'pr03', 'name' => '홍길동',
            'name_eng' => 'zuzu', 'name_kanji' => '洪吉動', 'name_kana' => 'ジュジュ', 'email' => 'bijem@gmail.com', 'profile_photo_url' => 'https://pbs.twimg.com/media/COl-_KIVEAECY35.jpg',
            'birth_date' => date('950420'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'c 잘함, !!##자바 못함',
            'group_id' => '4', 'group_part_content' => 'frontend', 'gender' => 'w', 'credit' => 3.1, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => '가즈아!!', 'interested_field_content' => 'IOT',
            'pr_content' => '전 매우 잘합니다.',
            'recommendation_comment' => '우수합니다', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st08', 'student_no' => '4513842', 'student_code'=> 'KNU00002', 'professor_login_id'=> 'pr03', 'name' => '박동지',
            'name_eng' => 'dongji', 'name_kanji' => '博東指', 'name_kana' => 'ジュジュ', 'email' => 'ehnnv@gmail.com', 'profile_photo_url' => 'http://cfile23.uf.tistory.com/image/1546284B4EF0BAA933B645',
            'birth_date' => date('940611'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'c 잘함, !!##자바 못함',
            'group_id' => '4', 'group_part_content' => 'frontend', 'gender' => 'm', 'credit' => 3.1, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => '가즈아!!', 'interested_field_content' => 'IOT',
            'pr_content' => '전 매우 잘합니다.',
            'recommendation_comment' => '우수합니다', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            // SNU 2명 ---------------------------------------------------------
            ['login_id' => 'st09', 'student_no' => '4513842', 'student_code'=> 'SNU00001', 'professor_login_id'=> 'pr04', 'name' => '박주장',
            'name_eng' => 'juzang', 'name_kanji' => '薄朱張', 'name_kana' => 'ジュジュ', 'email' => 'qmkm@gmail.com', 'profile_photo_url' => 'http://cfile24.uf.tistory.com/image/1720E7364F4705EC0A3291',
            'birth_date' => date('950420'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'c 잘함, !!##자바 못함',
            'group_id' => '4', 'group_part_content' => 'frontend', 'gender' => 'w', 'credit' => 3.1, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => '가즈아!!', 'interested_field_content' => 'IOT',
            'pr_content' => '전 매우 잘합니다.',
            'recommendation_comment' => '우수합니다', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'],

            ['login_id' => 'st10', 'student_no' => '4513842', 'student_code'=> 'SNU00002', 'professor_login_id'=> 'pr04', 'name' => '김동생',
            'name_eng' => 'dongseng', 'name_kanji' => '金銅生', 'name_kana' => 'ジュジュ', 'email' => 'zzsd@gmail.com', 'profile_photo_url' => 'http://image.chosun.com/sitedata/image/201711/28/2017112803570_0.jpg',
            'birth_date' => date('940611'), 'employ_year' => '2018','graduate_date' => date('190228'),'skill_contents'=>'c 잘함, !!##자바 못함',
            'group_id' => '4', 'group_part_content' => 'frontend', 'gender' => 'w', 'credit' => 3.1, 'JPT' => 655, 'JLPT' => 2, 'mock_TOEIC' => 400,
            'TOEIC' => 350, 'TOEFL' => 320, 'activities' => 'Buddy', 'qualification_content' => '', 'motivation_content' => '가즈아!!', 'interested_field_content' => 'IOT',
            'pr_content' => '전 매우 잘합니다.',
            'recommendation_comment' => '우수합니다', 'pr_video_url' => $faker->url, 'github_url' => $faker->url, 'country_code' => 'KR'], */
        foreach($StudentSeeder as $StudentSeeders) {
            DB::table('students')->insert([
                'login_id' => $StudentSeeders['login_id'],
                'country_code' => $StudentSeeders['country_code'],
                'student_no' => $StudentSeeders['student_no'],
                'student_code' => $StudentSeeders['student_code'],
                'professor_login_id' => $StudentSeeders['professor_login_id'],
                'name' => $StudentSeeders['name'],
                'name_eng' => $StudentSeeders['name_eng'],
                'name_kanji' => $StudentSeeders['name_kanji'],
                'name_kana' => $StudentSeeders['name_kana'],
                'email' => $StudentSeeders['email'],
                'employ_year' => $StudentSeeders['employ_year'],
                'graduate_date' => $StudentSeeders['graduate_date'],
                'profile_photo_url' => $StudentSeeders['profile_photo_url'],
                'birth_date' => $StudentSeeders['birth_date'],
                'group_id' => $StudentSeeders['group_id'],
                'group_part_content' => $StudentSeeders['group_part_content'],
                'gender' => $StudentSeeders['gender'],
                'credit' => $StudentSeeders['credit'],
                'JPT' => $StudentSeeders['JPT'],
                'JLPT' => $StudentSeeders['JLPT'],
                'mock_TOEIC' => $StudentSeeders['mock_TOEIC'],
                'TOEIC' => $StudentSeeders['TOEIC'],
                'TOEFL' => $StudentSeeders['TOEFL'],
                'activities' => $StudentSeeders['activities'],
                'qualification_content' => $StudentSeeders['qualification_content'],
                'motivation_content' => $StudentSeeders['motivation_content'],
                'interested_field_content' => $StudentSeeders['interested_field_content'],
                'pr_content' => $StudentSeeders['pr_content'],
                'recommendation_comment' => $StudentSeeders['recommendation_comment'],
                'pr_video_url' => $StudentSeeders['pr_video_url'],
                'github_url' => $StudentSeeders['github_url'],
                
            ]);
        }
    }
}
