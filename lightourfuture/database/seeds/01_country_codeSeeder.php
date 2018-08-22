<?php

use Illuminate\Database\Seeder;

class country_codeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = [
            ['country_code' => 'JP', 'country_num'=> '392', 'continent' => 'AS', 'country_eng' => 'JAPAN', 'country_kana' => '日本'],
            ['country_code' => 'KR', 'country_num'=> '410', 'continent' => 'AS', 'country_eng' => 'KOREA', 'country_kana' => ' 韓国'],
            ['country_code' => 'US', 'country_num'=> '840', 'continent' => 'NA', 'country_eng' => 'UNITED STATES', 'country_kana' => 'アメリカ'],
            ['country_code' => 'GB', 'country_num'=> '826', 'continent' => 'EU', 'country_eng' => 'UNITED KINGDOM'],
            ['country_code' => 'IN', 'country_num'=> '356', 'continent' => 'AS', 'country_eng' => 'INDIA'],
        ];
        
        foreach($country as $country_code) {
            
            DB::table('country_codes')->insert([
                'country_code' => $country_code['country_code'],
                'country_num' => $country_code['country_num'],
                'continent' => $country_code['continent'],
                'country_eng' => $country_code['country_eng']
            ]);
            
        }
    }
}
