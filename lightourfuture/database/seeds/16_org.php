<?php

use Illuminate\Database\Seeder;

class org extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $org = [//orgac04, orgac02, orgac03, yjc01, knu01, snu01, jphalo, jpsoft, krsam, CNjung, USgood
            ['org_id' => 'orgac01', 'org_classification' => 'agent'],
            ['org_id' => 'orgac02', 'org_classification' => 'agent'],
            ['org_id' => 'orgac03', 'org_classification' => 'agent'],
            ['org_id' => 'orgac04', 'org_classification' => 'agent'],
            ['org_id' => 'yjc01', 'org_classification' => 'college'],
            ['org_id' => 'knu01', 'org_classification' => 'college'],
            ['org_id' => 'snu01', 'org_classification' => 'college'],
            ['org_id' => 'jphalo', 'org_classification' => 'company'],
            ['org_id' => 'jpsoft', 'org_classification' => 'company'],
            ['org_id' => 'krsam', 'org_classification' => 'company'],
            ['org_id' => 'CNjung', 'org_classification' => 'company'],
            ['org_id' => 'USgood', 'org_classification' => 'company'],
            
        ];
        
        foreach($org as $orgs) {
            
            DB::table('orgs')->insert([
                'org_id' => $orgs['org_id'],
                'org_classification' => $orgs['org_classification']
            ]);
            
        }
    }
}
