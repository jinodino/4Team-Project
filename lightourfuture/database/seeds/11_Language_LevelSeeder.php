<?php

use Illuminate\Database\Seeder;

class Language_LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prjs = ['native', 'business', 'intermediate', 'junior'];
        foreach($prjs as $prj) {
            DB::table('language_levels')->insert([
                'language_level' => $prj,
            ]);
        }
    }
}
