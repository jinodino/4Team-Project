<?php

use Illuminate\Database\Seeder;

class Business_big extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//jphalo, jpsoft, krsam, CNjung, USgood
        $business_big = [
            ['business_big_id' => '5', 'org_company_id'=> 'CNjung'],
            ['business_big_id' => '4', 'org_company_id'=> 'USgood'],
            ['business_big_id' => '3', 'org_company_id'=> 'USgood'],
            ['business_big_id' => '2', 'org_company_id'=> 'CNjung'],
            ['business_big_id' => '1', 'org_company_id'=> 'krsam'],
            ['business_big_id' => '5', 'org_company_id'=> 'jpsoft'],
            ['business_big_id' => '4', 'org_company_id'=> 'jphalo'],
        ];
        
        foreach($business_big as $business_bigs) {
            
            DB::table('business_bigs')->insert([
                'business_big_id' => $business_bigs['business_big_id'],
                'org_company_id' => $business_bigs['org_company_id'],
                
            ]);
            
        }
    }
}
