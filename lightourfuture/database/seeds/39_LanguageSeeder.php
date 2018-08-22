<?php

use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languageSeeder = [
            ['student_login_id' => 'st01', 'language_code'=> 1, 'language_level_code'=> 1],
            ['student_login_id' => 'st01', 'language_code'=> 2, 'language_level_code'=> 2],
            ['student_login_id' => 'st01', 'language_code'=> 3, 'language_level_code'=> 3],
            ['student_login_id' => 'st02', 'language_code'=> 1, 'language_level_code'=> 1],
            ['student_login_id' => 'st02', 'language_code'=> 2, 'language_level_code'=> 2],
            ['student_login_id' => 'st03', 'language_code'=> 4, 'language_level_code'=> 4],
            ['student_login_id' => 'st04', 'language_code'=> 3, 'language_level_code'=> 3],
            ['student_login_id' => 'st05', 'language_code'=> 2, 'language_level_code'=> 2],
            ['student_login_id' => 'st05', 'language_code'=> 1, 'language_level_code'=> 1],
            ['student_login_id' => 'st06', 'language_code'=> 2, 'language_level_code'=> 2],
            ['student_login_id' => 'st06', 'language_code'=> 3, 'language_level_code'=> 3],
            //
            ['student_login_id' => 'st07', 'language_code'=> 1, 'language_level_code'=> 1],
            ['student_login_id' => 'st07', 'language_code'=> 2, 'language_level_code'=> 2],
            //
            ['student_login_id' => 'st08', 'language_code'=> 4, 'language_level_code'=> 4],
            ['student_login_id' => 'st08', 'language_code'=> 3, 'language_level_code'=> 3],
            //
            ['student_login_id' => 'st09', 'language_code'=> 1, 'language_level_code'=> 1],
            ['student_login_id' => 'st09', 'language_code'=> 2, 'language_level_code'=> 2],
            //
            ['student_login_id' => 'st10', 'language_code'=> 4, 'language_level_code'=> 4],
            ['student_login_id' => 'st10', 'language_code'=> 3, 'language_level_code'=> 3],
        ];

        foreach($languageSeeder as $languageSeeders) {
            DB::table('languages')->insert([
                'student_login_id' => $languageSeeders['student_login_id'],
                'language_code' => $languageSeeders['language_code'],
                'language_level_code' => $languageSeeders['language_level_code'],
            ]);
        }
    }
}
