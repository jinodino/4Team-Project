<?php

use Illuminate\Database\Seeder;

class Language_infoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prjs = ['Engilsh', 'Japanese', 'German', 'Spanish'];
        foreach($prjs as $prj) {
            DB::table('language_infos')->insert([
                'language' => $prj,
            ]);
        }
    }
}
