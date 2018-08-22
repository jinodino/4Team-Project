<?php

use Illuminate\Database\Seeder;

class Company_agentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Company_agentSeeder = [
            ['login_id' => 'co01', 'invite_agent_id'=> 'orgac01', 'org_company_id'=> 'jphalo', 'name'=> 'haloman', 'name_kana' => 'ハロマン', 'email' => 'haloman@gmail.com', 'birth_date' => date('850420')],
            ['login_id' => 'co02', 'invite_agent_id'=> 'orgac03', 'org_company_id'=> 'jpsoft', 'name'=> 'softman', 'name_kana' => 'ハロマン', 'email' => 'softman@gmail.com', 'birth_date' => date('840120')],
            ['login_id' => 'co03', 'invite_agent_id'=> 'orgac02', 'org_company_id'=> 'CNjung', 'name'=> 'CNman', 'name_kana' => 'ハロマン', 'email' => 'CNman@gmail.com', 'birth_date' => date('860120')],
            ['login_id' => 'co04', 'invite_agent_id'=> 'orgac02', 'org_company_id'=> 'USgood', 'name'=> 'USman', 'name_kana' => 'ハロマン', 'email' => 'USman@gmail.com', 'birth_date' => date('860120')],
            ['login_id' => 'co05', 'invite_agent_id'=> 'orgac04', 'org_company_id'=> 'krsam', 'name'=> 'krman', 'name_kana' => 'ハロマン', 'email' => 'krman@gmail.com', 'birth_date' => date('860120')],
        ];

        foreach($Company_agentSeeder as $Company_agentSeeders) {
            DB::table('company_agents')->insert([
                'login_id' => $Company_agentSeeders['login_id'],
                'invite_agent_id' => $Company_agentSeeders['invite_agent_id'],
                'org_company_id' => $Company_agentSeeders['org_company_id'],
                'name' => $Company_agentSeeders['name'],
                'name_kana' => $Company_agentSeeders['name_kana'],
                'email' => $Company_agentSeeders['email'],
                'birth_date' => $Company_agentSeeders['birth_date'],
            ]);
        }
    }
}
