<?php

use Illuminate\Database\Seeder;

class ApplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applySeeder = [
            // 1 -> 0401 ~ 0502 YJC / Krsam
            ['employment_id' => 1, 'student_login_id'=> 'st01', 'apply_permission_ox'=> 'o', 'acceptance_ox' => 'o', 'student_acceptance_ox' => 'o', 'professor_acceptance_ox' => 'o', 'resign_comment' => null, 'resign_id' => null],
            ['employment_id' => 1, 'student_login_id'=> 'st02', 'apply_permission_ox'=> 'o', 'acceptance_ox' => 'x', 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],
            // 떨어진 경우 st03
            ['employment_id' => 1, 'student_login_id'=> 'st03', 'apply_permission_ox'=> 'o', 'acceptance_ox' => 'x', 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],
            ['employment_id' => 1, 'student_login_id'=> 'st06', 'apply_permission_ox'=> 'o', 'acceptance_ox' => 'o', 'student_acceptance_ox' => 'o', 'professor_acceptance_ox' => 'o', 'resign_comment' => null, 'resign_id' => null],
            // 2 -> 0402 ~ 0501 YJC / jphalo
            ['employment_id' => 2, 'student_login_id'=> 'st01', 'apply_permission_ox'=> 'o', 'acceptance_ox' => 'o', 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],
            ['employment_id' => 2, 'student_login_id'=> 'st02', 'apply_permission_ox'=> 'o', 'acceptance_ox' => 'o', 'student_acceptance_ox' => 'o', 'professor_acceptance_ox' => 'o', 'resign_comment' => null, 'resign_id' => null],
            ['employment_id' => 2, 'student_login_id'=> 'st03', 'apply_permission_ox'=> 'o', 'acceptance_ox' => 'o', 'student_acceptance_ox' => 'o', 'professor_acceptance_ox' => 'o', 'resign_comment' => null, 'resign_id' => null],
            ['employment_id' => 2, 'student_login_id'=> 'st04', 'apply_permission_ox'=> 'o', 'acceptance_ox' => 'o', 'student_acceptance_ox' => 'x', 'professor_acceptance_ox' => 'x', 'resign_comment' => null, 'resign_id' => 2],
            ['employment_id' => 2, 'student_login_id'=> 'st05', 'apply_permission_ox'=> 'o', 'acceptance_ox' => 'x', 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],
            // 3 -> 0401 ~ 0605 SNU / jphalo
            ['employment_id' => 3, 'student_login_id'=> 'st09', 'apply_permission_ox'=> 'x', 'acceptance_ox' => 'o', 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],
            ['employment_id' => 3, 'student_login_id'=> 'st10', 'apply_permission_ox'=> 'o', 'acceptance_ox' => 'o', 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],

            // 4 -> 0401 ~ 0515 KNU / jphalo
            ['employment_id' => 4, 'student_login_id'=> 'st07', 'apply_permission_ox'=> 'o', 'acceptance_ox' => null, 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],
            ['employment_id' => 4, 'student_login_id'=> 'st08', 'apply_permission_ox'=> 'o', 'acceptance_ox' => null, 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],

            // 5 -> 0401 ~ 0529 KNU / jpsoft
            ['employment_id' => 5, 'student_login_id'=> 'st07', 'apply_permission_ox'=> 'x', 'acceptance_ox' => null, 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],
            ['employment_id' => 5, 'student_login_id'=> 'st08', 'apply_permission_ox'=> 'o', 'acceptance_ox' => null, 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],

            // 6 -> 0401 ~ 0529 KNU / CNjung
            ['employment_id' => 6, 'student_login_id'=> 'st07', 'apply_permission_ox'=> 'o', 'acceptance_ox' => null, 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],
            ['employment_id' => 6, 'student_login_id'=> 'st08', 'apply_permission_ox'=> 'x', 'acceptance_ox' => null, 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],

            // 7 -> 0601 ~ 0810 KNU / jphalo -> 아직 지원 불가
            

            // 8 -> 0401 ~ 0529 SNU / USgood
            ['employment_id' => 8, 'student_login_id'=> 'st09', 'apply_permission_ox'=> 'o', 'acceptance_ox' => null, 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],
            ['employment_id' => 8, 'student_login_id'=> 'st10', 'apply_permission_ox'=> 'o', 'acceptance_ox' => null, 'student_acceptance_ox' => null, 'professor_acceptance_ox' => null, 'resign_comment' => null, 'resign_id' => null],
            
        ];
        foreach($applySeeder as $applySeeders) {
            DB::table('applies')->insert([
                'employment_id' => $applySeeders['employment_id'],
                'student_login_id' => $applySeeders['student_login_id'],
                'apply_permission_ox' => $applySeeders['apply_permission_ox'],
                'acceptance_ox' => $applySeeders['acceptance_ox'],
                'student_acceptance_ox' => $applySeeders['student_acceptance_ox'],
                'professor_acceptance_ox' => $applySeeders['professor_acceptance_ox'],
                'resign_comment' => $applySeeders['resign_comment'],
                'resign_id' => $applySeeders['resign_id'],
            ]);
        }
    }
}
