<?php

use Illuminate\Database\Seeder;

class Org_matchingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $Org_matchingSeeder = [// root => orgac01, orgac02      ag02 => orgac01, orgac03        ag03 => orgac02
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'yjc01', 'org_company_id'=> 'jphalo', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'yjc01', 'org_company_id'=> 'CNjung', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'yjc01', 'org_company_id'=> 'USgood', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'knu01', 'org_company_id'=> 'jphalo', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'knu01', 'org_company_id'=> 'USgood', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'yjc01', 'org_company_id'=> 'krsam', 'matching_date'=> '2018', 'employment_decision_ox' => 'x'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'yjc01', 'org_company_id'=> 'jphalo', 'matching_date'=> '2018', 'employment_decision_ox' => 'x'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'yjc01', 'org_company_id'=> 'USgood', 'matching_date'=> '2018', 'employment_decision_ox' => 'x'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'yjc01', 'org_company_id'=> 'CNjung', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'snu01', 'org_company_id'=> 'jphalo', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'snu01', 'org_company_id'=> 'USgood', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'snu01', 'org_company_id'=> 'CNjung', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'snu01', 'org_company_id'=> 'krsam', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'yjc01', 'org_company_id'=> 'krsam', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'knu01', 'org_company_id'=> 'jpsoft', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'knu01', 'org_company_id'=> 'CNjung', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'knu01', 'org_company_id'=> 'krsam', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'knu01', 'org_company_id'=> 'jpsoft', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac01', 'org_college_id'=> 'knu01', 'org_company_id'=> 'CNjung', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'snu01', 'org_company_id'=> 'jphalo', 'matching_date'=> '2018', 'employment_decision_ox' => 'x'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'snu01', 'org_company_id'=> 'USgood', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'snu01', 'org_company_id'=> 'krsam', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'snu01', 'org_company_id'=> 'CNjung', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac04', 'org_college_id'=> 'yjc01', 'org_company_id'=> 'jphalo', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac04', 'org_college_id'=> 'snu01', 'org_company_id'=> 'jphalo', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac04', 'org_college_id'=> 'yjc01', 'org_company_id'=> 'krsam', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac04', 'org_college_id'=> 'knu01', 'org_company_id'=> 'jphalo', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
            ['org_agent_id' => 'orgac02', 'org_college_id'=> 'knu01', 'org_company_id'=> 'USgood', 'matching_date'=> '2018', 'employment_decision_ox' => 'x'],
            ['org_agent_id' => 'orgac03', 'org_college_id'=> 'snu01', 'org_company_id'=> 'USgood', 'matching_date'=> '2018', 'employment_decision_ox' => 'o'],
        ];

        foreach($Org_matchingSeeder as $Org_matchingSeeders) {
            DB::table('org_matchings')->insert([
                'org_agent_id' => $Org_matchingSeeders['org_agent_id'],
                'org_college_id' => $Org_matchingSeeders['org_college_id'],
                'org_company_id' => $Org_matchingSeeders['org_company_id'],
                'matching_date' => $Org_matchingSeeders['matching_date'],
                'employment_decision_ox' => $Org_matchingSeeders['employment_decision_ox'],
            ]);
        }
    }
}

