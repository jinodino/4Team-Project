<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class agent_company_matching extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//jphalo jpsoft krsam CNjung USgood
        $agent_company_matching = [
            ['org_agent_id' => 'orgac01', 'org_company_id'=> 'jpsoft', 'matching_date' => '2017'],
            ['org_agent_id' => 'orgac01', 'org_company_id'=> 'CNjung', 'matching_date' => '2018'],
            ['org_agent_id' => 'orgac02', 'org_company_id'=> 'jphalo', 'matching_date' => '2018'],
            ['org_agent_id' => 'orgac03', 'org_company_id'=> 'USgood', 'matching_date' => '2016'],
            ['org_agent_id' => 'orgac04', 'org_company_id'=> 'krsam', 'matching_date' => '2018'],
            ['org_agent_id' => 'orgac04', 'org_company_id'=> 'jphalo', 'matching_date' => '2018'],
        ];
        foreach($agent_company_matching as $agent_company_matchings){
            DB::table('agent_company_matchings')->insert([
                'org_agent_id'       => $agent_company_matchings['org_agent_id'],
                'org_company_id' => $agent_company_matchings['org_company_id'],
                'matching_date'    => $agent_company_matchings['matching_date'],
            ]);
        }
    }
}
