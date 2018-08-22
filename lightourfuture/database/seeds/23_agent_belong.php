<?php

use Illuminate\Database\Seeder;

class agent_belong extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agent_belong = [//orgac04 orgac02 orgac03, orgac01 
            ['agent_id' => 'root', 'org_agent_id'=> 'orgac02'],
            ['agent_id' => 'ag02', 'org_agent_id'=> 'orgac03'],
            ['agent_id' => 'ag03', 'org_agent_id'=> 'orgac02'],
            ['agent_id' => 'root', 'org_agent_id'=> 'orgac01'],
            ['agent_id' => 'ag02', 'org_agent_id'=> 'orgac01'],
        ];
        foreach($agent_belong as $agent_belongs){
            DB::table('agent_belongs')->insert([
                'agent_id' => $agent_belongs['agent_id'],
                'org_agent_id' => $agent_belongs['org_agent_id'],
            ]);
        }
    }
}
