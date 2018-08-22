<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class Employment_infoSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//jphalo, jpsoft, krsam, CNjung, USgood
        $employment_info = [
            //jphalo -> 2, 3, 4, 7 / krsam -> 1 / jpsoft -> 5 / CNjung -> 6 / Usgood -> 8
            ['org_matching_foreign' => 1,'employment_name' => 'サーバー維持保守募集', 'whole_interview_count' => 4,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(180502), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'サーバー運用可能な人', 'working_area' => '東京', 'pay' => '275000', 'pay_content' => '今後の交渉可能',
            'bonus_pay' => '400%','transport_pay' => '給与に含まれている','overtime_pay' => '時間当たりの最低手当','house_pay' => '支給',
            'dormitory_airport_support' => '全額支援',
            'working_sort' => '募集分野と同一','recruit_number' => '5人','motomeru_major_grade' => 'C+, C##,','motomeru_language_grade' => 'N1','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'サーバ運用', 'work_term' => '3年', 'insurance_content' => '4大保険', 'other_work_condition' => '',
            'business_hour' => '週30時間', 'holiday' => '年3ヵ月', 'welfare_content' => 'すごくいいこと',
            'acceptance_fixed_ox' => 'o', 'employment_owari_ox' => 'o', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 2,'employment_name' => 'ゲーム製作者募集', 'whole_interview_count' => 4,
            'apply_open_date' => date(180402), 'apply_deadline_date' => date(180501), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'ゲーム製作可能者', 'working_area' => '渋谷', 'pay' => '50000', 'pay_content' => '今後の交渉可能',
            'bonus_pay' => '500%','transport_pay' => '別途に支給','overtime_pay' => '時間当たりの最低手当','house_pay' => '支給','dormitory_airport_support' => '全額支援',
            'working_sort' => '募集分野と同一','recruit_number' => '10人','motomeru_major_grade' => 'C+, C##,Unity,Unreal','motomeru_language_grade' => 'N2','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'ゲーム製作', 'work_term' => '5年', 'insurance_content' => '4大保険', 'other_work_condition' => '',
            'business_hour' => '週30時間', 'holiday' => '年3ヵ月', 'welfare_content' => 'すごくいいこと',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'o', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 3,'employment_name' => 'セキュリティ関係者募集', 'whole_interview_count' => 4,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(180605), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'セキュリティの経験がある人', 'working_area' => '関西', 'pay' => '150000', 'pay_content' => '1年後、再契約',
            'bonus_pay' => '800%','transport_pay' => '支給X','overtime_pay' => '時間当たりの最低手当','house_pay' => '未支給','dormitory_airport_support' => '支援X',
            'working_sort' => '募集分野と同一','recruit_number' => '0人','motomeru_major_grade' => 'Java','motomeru_language_grade' => 'N2','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'セキュリティー', 'work_term' => '5年', 'insurance_content' => '4大保険', 'other_work_condition' => '',
            'business_hour' => '週30時間', 'holiday' => '年3ヵ月', 'welfare_content' => 'すごくいいこと',
            'acceptance_fixed_ox' => 'o', 'employment_owari_ox' => 'o', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 4,'employment_name' => 'サーバー管理者募集','whole_interview_count' => 4,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(180515), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'サーバー管理可能な人', 'working_area' => '北京', 'pay' => '200000', 'pay_content' => '3年後、再契約',
            'bonus_pay' => '700%','transport_pay' => '給与に含まれている','overtime_pay' => '時間当たりの最低手当','house_pay' => '未支給','dormitory_airport_support' => '支援X',
            'working_sort' => '募集分野と同一','recruit_number' => '3人','motomeru_major_grade' => 'Linux, Ubuntu, Mysql, OracleDB','motomeru_language_grade' => 'N1','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'サーバー管理', 'work_term' => '5年', 'insurance_content' => '4大保険', 'other_work_condition' => '',
            'business_hour' => '週30時間', 'holiday' => '年3ヵ月', 'welfare_content' => 'すごくいいこと',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'x', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 5,'employment_name' => 'DB設計者募集','whole_interview_count' => 3,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(180529), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'DB設計してみた人', 'working_area' => '福岡', 'pay' => '815000', 'pay_content' => '今後の交渉可能',
            'bonus_pay' => '600%','transport_pay' => '給与に含まれている','overtime_pay' => '時間当たりの最低手当','house_pay' => '支給','dormitory_airport_support' => '未支援',
            'working_sort' => '募集分野と同一','recruit_number' => '4人','motomeru_major_grade' => 'MariaDB, Mysql, OracleDB','motomeru_language_grade' => 'N1','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'DB設計や維持保守', 'work_term' => '3年', 'insurance_content' => '4大保険', 'other_work_condition' => '',
            'business_hour' => '週30時間', 'holiday' => '年3ヵ月', 'welfare_content' => 'すごくいいこと',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'x', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 6,'employment_name' => 'DB設計者募集','whole_interview_count' => 3,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(1805029), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'DB設計してみた人', 'working_area' => '福岡', 'pay' => '815000', 'pay_content' => '交通費を含む',
            'bonus_pay' => '400%','transport_pay' => '支給しない','overtime_pay' => '時間当たりの最低手当','house_pay' => '社内の宿舎提供','dormitory_airport_support' => '未支援',
            'working_sort' => '募集分野と同一','recruit_number' => '7人','motomeru_major_grade' => 'MariaDB, Mysql, OracleDB','motomeru_language_grade' => 'N3','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'DB設計や維持保守', 'work_term' => '10年', 'insurance_content' => '4大保険', 'other_work_condition' => '',
            'business_hour' => '週30時間', 'holiday' => '年3ヵ月', 'welfare_content' => 'すごくいいこと',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'x', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 7, 'employment_name' => 'DB設計者募集','whole_interview_count' => 3,
            'apply_open_date' => date(180601), 'apply_deadline_date' => date(180810), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'DB設計してみた人', 'working_area' => '福岡', 'pay' => '815000', 'pay_content' => '交通費の含まない',
            'bonus_pay' => '300%','transport_pay' => '別途に支給','overtime_pay' => '時間当たりの最低手当','house_pay' => '社内の宿舎提供','dormitory_airport_support' => '別途支援',
            'working_sort' => '募集分野と同一','recruit_number' => '0人','motomeru_major_grade' => 'MariaDB, Mysql, OracleDB','motomeru_language_grade' => 'N3','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'DB設計や維持保守', 'work_term' => '10年', 'insurance_content' => '4大保険', 'other_work_condition' => '',
            'business_hour' => '週30時間', 'holiday' => '年3ヵ月', 'welfare_content' => 'すごくいいこと',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'x', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 8,'employment_name' => 'DB設計者募集','whole_interview_count' => 3,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(180529), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'DB設計してみた人', 'working_area' => '福岡', 'pay' => '815000', 'pay_content' => '今後の交渉可能',
            'bonus_pay' => '200%','transport_pay' => '給与に含まれている','overtime_pay' => '時間当たりの最低手当','house_pay' => '社内の宿舎提供','dormitory_airport_support' => '別途支援',
            'working_sort' => '募集分野と同一','recruit_number' => '0人','motomeru_major_grade' => 'MariaDB, Mysql, OracleDB','motomeru_language_grade' => 'N2','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'DB設計や維持保守', 'work_term' => '7年', 'insurance_content' => '4大保険', 'other_work_condition' => '',
            'business_hour' => '週30時間', 'holiday' => '年3ヵ月', 'welfare_content' => 'すごくいいこと',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'x', 'expected_acceptance_date' => date('180410')
            ],
            /* ['org_matching_foreign' => 1,'employment_name' => '서버 유지보수 모집', 'whole_interview_count' => 4,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(180502), 'business_type_content' => date('181231'), 
            'desired_employee_content' => '서버 운용가능자', 'working_area' => '도쿄', 'pay' => '275000', 'pay_content' => '추후 협상가능',
            'bonus_pay' => '400%','transport_pay' => '급여에 포함되어 있음','overtime_pay' => '시간당 최저수당','house_pay' => '지급',
            'dormitory_airport_support' => '전액 지원',
            'working_sort' => '모집분야와 동일','recruit_number' => '5명','motomeru_major_grade' => 'C+, C##,','motomeru_language_grade' => 'N1','motomeru_sonohoka' => '',
            'working_naiyou_content' => '서버운용', 'work_term' => '3년', 'insurance_content' => '4대보험', 'other_work_condition' => '',
            'business_hour' => '주30시간', 'holiday' => '연3개월', 'welfare_content' => '완전 좋음',
            'acceptance_fixed_ox' => 'o', 'employment_owari_ox' => 'o', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 2,'employment_name' => '게임 제작자 모집', 'whole_interview_count' => 4,
            'apply_open_date' => date(180402), 'apply_deadline_date' => date(180501), 'business_type_content' => date('181231'), 
            'desired_employee_content' => '게임 제작가능자', 'working_area' => '시부야', 'pay' => '50000', 'pay_content' => '추후 협상가능',
            'bonus_pay' => '500%','transport_pay' => '별도 지급','overtime_pay' => '시간당 최저수당','house_pay' => '지급','dormitory_airport_support' => '전액 지원',
            'working_sort' => '모집분야와 동일','recruit_number' => '10명','motomeru_major_grade' => 'C+, C##,Unity,Unreal','motomeru_language_grade' => 'N2','motomeru_sonohoka' => '',
            'working_naiyou_content' => '게임제작', 'work_term' => '5년', 'insurance_content' => '4대보험', 'other_work_condition' => '',
            'business_hour' => '주30시간', 'holiday' => '연3개월', 'welfare_content' => '완전 좋음',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'o', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 3,'employment_name' => '보안관계자 모집', 'whole_interview_count' => 4,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(180605), 'business_type_content' => date('181231'), 
            'desired_employee_content' => '보안 경험이 있는사람', 'working_area' => '관서', 'pay' => '150000', 'pay_content' => '1년후 재계약',
            'bonus_pay' => '800%','transport_pay' => '지급X','overtime_pay' => '시간당 최저수당','house_pay' => '미지급','dormitory_airport_support' => '지원X',
            'working_sort' => '모집분야와 동일','recruit_number' => '0명','motomeru_major_grade' => 'Java','motomeru_language_grade' => 'N2','motomeru_sonohoka' => '',
            'working_naiyou_content' => '보안', 'work_term' => '5년', 'insurance_content' => '4대보험', 'other_work_condition' => '',
            'business_hour' => '주30시간', 'holiday' => '연3개월', 'welfare_content' => '완전 좋음',
            'acceptance_fixed_ox' => 'o', 'employment_owari_ox' => 'o', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 4,'employment_name' => '서버 관리자 모집','whole_interview_count' => 4,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(180515), 'business_type_content' => date('181231'), 
            'desired_employee_content' => '서버 관리 가능한 사람', 'working_area' => '베이징', 'pay' => '200000', 'pay_content' => '3년후 재계약',
            'bonus_pay' => '700%','transport_pay' => '급여에 포함','overtime_pay' => '시간당 최저수당','house_pay' => '미지급','dormitory_airport_support' => '지원X',
            'working_sort' => '모집분야와 동일','recruit_number' => '3명','motomeru_major_grade' => 'Linux, Ubuntu, Mysql, OracleDB','motomeru_language_grade' => 'N1','motomeru_sonohoka' => '',
            'working_naiyou_content' => '서버관리', 'work_term' => '5년', 'insurance_content' => '4대보험', 'other_work_condition' => '',
            'business_hour' => '주30시간', 'holiday' => '연3개월', 'welfare_content' => '완전 좋음',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'x', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 5,'employment_name' => 'DB 설계자 모집','whole_interview_count' => 3,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(180529), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'DB 설계 해본사람', 'working_area' => '후쿠오카', 'pay' => '815000', 'pay_content' => '추후 협상가능',
            'bonus_pay' => '600%','transport_pay' => '급여에 포함','overtime_pay' => '시간당 최저수당','house_pay' => '지급','dormitory_airport_support' => '미지원',
            'working_sort' => '모집분야와 동일','recruit_number' => '4명','motomeru_major_grade' => 'MariaDB, Mysql, OracleDB','motomeru_language_grade' => 'N1','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'DB설계 및 유지보수', 'work_term' => '3년', 'insurance_content' => '4대보험', 'other_work_condition' => '',
            'business_hour' => '주30시간', 'holiday' => '연3개월', 'welfare_content' => '완전 좋음',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'x', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 6,'employment_name' => 'DB 설계자 모집','whole_interview_count' => 3,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(1805029), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'DB 설계 해본사람', 'working_area' => '후쿠오카', 'pay' => '815000', 'pay_content' => '교통비 포함',
            'bonus_pay' => '400%','transport_pay' => '지급 안함','overtime_pay' => '시간당 최저수당','house_pay' => '사내 숙소 제공','dormitory_airport_support' => '미지원',
            'working_sort' => '모집분야와 동일','recruit_number' => '7명','motomeru_major_grade' => 'MariaDB, Mysql, OracleDB','motomeru_language_grade' => 'N3','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'DB설계 및 유지보수', 'work_term' => '10년', 'insurance_content' => '4대보험', 'other_work_condition' => '',
            'business_hour' => '주30시간', 'holiday' => '연3개월', 'welfare_content' => '완전 좋음',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'x', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 7, 'employment_name' => 'DB 설계자 모집','whole_interview_count' => 3,
            'apply_open_date' => date(180601), 'apply_deadline_date' => date(180810), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'DB 설계 해본사람', 'working_area' => '후쿠오카', 'pay' => '815000', 'pay_content' => '교통비 미포함',
            'bonus_pay' => '300%','transport_pay' => '별도 지급','overtime_pay' => '시간당 최저수당','house_pay' => '사내 숙소 제공','dormitory_airport_support' => '별도 지원',
            'working_sort' => '모집분야와 동일','recruit_number' => '0명','motomeru_major_grade' => 'MariaDB, Mysql, OracleDB','motomeru_language_grade' => 'N3','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'DB설계 및 유지보수', 'work_term' => '10년', 'insurance_content' => '4대보험', 'other_work_condition' => '',
            'business_hour' => '주30시간', 'holiday' => '연3개월', 'welfare_content' => '완전 좋음',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'x', 'expected_acceptance_date' => date('180410')
            ],
            ['org_matching_foreign' => 8,'employment_name' => 'DB 설계자 모집','whole_interview_count' => 3,
            'apply_open_date' => date(180401), 'apply_deadline_date' => date(180529), 'business_type_content' => date('181231'), 
            'desired_employee_content' => 'DB 설계 해본사람', 'working_area' => '후쿠오카', 'pay' => '815000', 'pay_content' => '추후 협상가능',
            'bonus_pay' => '200%','transport_pay' => '급여에 포함','overtime_pay' => '시간당 최저수당','house_pay' => '사내 숙소 제공','dormitory_airport_support' => '별도 지원',
            'working_sort' => '모집분야와 동일','recruit_number' => '0명','motomeru_major_grade' => 'MariaDB, Mysql, OracleDB','motomeru_language_grade' => 'N2','motomeru_sonohoka' => '',
            'working_naiyou_content' => 'DB설계 및 유지보수', 'work_term' => '7년', 'insurance_content' => '4대보험', 'other_work_condition' => '',
            'business_hour' => '주30시간', 'holiday' => '연3개월', 'welfare_content' => '완전 좋음',
            'acceptance_fixed_ox' => 'x', 'employment_owari_ox' => 'x', 'expected_acceptance_date' => date('180410')
            ], */
        ];
        foreach($employment_info as $employment_infos){
            $faker = Faker::create();
            DB::table('employment_infos')->insert([
                'org_matching_foreign' => $employment_infos['org_matching_foreign'],
                'employment_name' => $employment_infos['employment_name'],
                'whole_interview_count' => $employment_infos['whole_interview_count'],
                'apply_open_date' => $employment_infos['apply_open_date'],
                'apply_deadline_date' => $employment_infos['apply_deadline_date'],
                'business_type_content' => $employment_infos['business_type_content'],
                'desired_employee_content' => $employment_infos['desired_employee_content'],
                'working_area' => $employment_infos['working_area'],
                'pay' => $employment_infos['pay'],
                'pay_content' => $employment_infos['pay_content'],
                'bonus_pay' => $employment_infos['bonus_pay'],
                'transport_pay' => $employment_infos['transport_pay'],
                'overtime_pay' => $employment_infos['overtime_pay'],
                'house_pay' => $employment_infos['house_pay'],
                'dormitory_airport_support' => $employment_infos['dormitory_airport_support'],
                'working_sort' => $employment_infos['working_sort'],
                'recruit_number' => $employment_infos['recruit_number'],
                'motomeru_major_grade' => $employment_infos['motomeru_major_grade'],
                'motomeru_language_grade' => $employment_infos['motomeru_language_grade'],
                'motomeru_sonohoka' => $employment_infos['motomeru_sonohoka'],
                'working_naiyou_content' => $employment_infos['working_naiyou_content'],
                'work_term' => $employment_infos['work_term'],
                'insurance_content' => $employment_infos['insurance_content'],
                'other_work_condition' => $employment_infos['other_work_condition'],
                'business_hour' => $employment_infos['business_hour'],
                'holiday' => $employment_infos['holiday'],
                'welfare_content' => $employment_infos['welfare_content'],
                'acceptance_fixed_ox' => $employment_infos['acceptance_fixed_ox'],
                'employment_owari_ox' => $employment_infos['employment_owari_ox'],
                'expected_acceptance_date' => $employment_infos['expected_acceptance_date'],
                'file_url' => $faker->url,
            ]);
        }
    }
}
