<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class agent_college_matching extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agent_college_matching = [
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'knu01', 'matching_date' => '2018'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'knu01', 'matching_date' => '2016'],
            ['org_agent_id' => 'orgac03', 'org_college_id'=> 'snu01', 'matching_date' => '2017'],
            ['org_agent_id' => 'orgac04', 'org_college_id'=> 'yjc01', 'matching_date' => '2018'],
            ['org_agent_id' => 'orgac04', 'org_college_id'=> 'snu01', 'matching_date' => '2018'],
            ['org_agent_id' => 'orgac04', 'org_college_id'=> 'knu01', 'matching_date' => '2018'],
        ];
        foreach($agent_college_matching as $agent_college_matchings){
            DB::table('agent_college_matchings')->insert([
                'org_agent_id'       => $agent_college_matchings['org_agent_id'],
                'org_college_id' => $agent_college_matchings['org_college_id'],
                'matching_date'    => $agent_college_matchings['matching_date'],
                
            ]);
        }
    }
}
