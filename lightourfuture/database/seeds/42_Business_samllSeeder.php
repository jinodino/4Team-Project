<?php

use Illuminate\Database\Seeder;

class Business_samllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // CNjung, jphalo, jpsoft, krsam, USgood
        $business_samllSeeder = [
            ['business_small_id' => 1, 'org_company_id'=> 'CNjung'],
            ['business_small_id' => 2, 'org_company_id'=> 'CNjung'],
            ['business_small_id' => 3, 'org_company_id'=> 'CNjung'],
            ['business_small_id' => 6, 'org_company_id'=> 'CNjung'],
            ['business_small_id' => 8, 'org_company_id'=> 'jphalo'],
            ['business_small_id' => 10, 'org_company_id'=> 'jphalo'],
            ['business_small_id' => 15, 'org_company_id'=> 'jpsoft'],
            ['business_small_id' => 16, 'org_company_id'=> 'jpsoft'],
            ['business_small_id' => 17, 'org_company_id'=> 'jpsoft'],
            ['business_small_id' => 20, 'org_company_id'=> 'krsam'],
            ['business_small_id' => 22, 'org_company_id'=> 'krsam'],
            ['business_small_id' => 11, 'org_company_id'=> 'USgood'],
            ['business_small_id' => 25, 'org_company_id'=> 'USgood'],
        ];

        foreach($business_samllSeeder as $business_samllSeeders) {
            DB::table('business_smalls')->insert([
                'business_small_id' => $business_samllSeeders['business_small_id'],
                'org_company_id' => $business_samllSeeders['org_company_id'],
            ]);
        }
    }
}
