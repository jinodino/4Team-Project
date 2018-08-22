<?php

use Illuminate\Database\Seeder;

class Desired_employment extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//jphalo, jpsoft, krsam, CNjung, USgood
        $desired_employment = [
            ['desired_employment_id' => '1', 'employment_id'=> '1'],
            ['desired_employment_id' => '1', 'employment_id'=> '2'],

            ['desired_employment_id' => '4', 'employment_id'=> '1'],
            ['desired_employment_id' => '6', 'employment_id'=> '2'],
            ['desired_employment_id' => '5', 'employment_id'=> '3'],
            ['desired_employment_id' => '8', 'employment_id'=> '4'],

            ['desired_employment_id' => '2', 'employment_id'=> '1'],
            ['desired_employment_id' => '2', 'employment_id'=> '2'],
            ['desired_employment_id' => '2', 'employment_id'=> '3'],

  
            ['desired_employment_id' => '3', 'employment_id'=> '2'],
            ['desired_employment_id' => '3', 'employment_id'=> '3'],
            ['desired_employment_id' => '3', 'employment_id'=> '4'],

            ['desired_employment_id' => '3', 'employment_id'=> '5'],
            ['desired_employment_id' => '5', 'employment_id'=> '6'],
            ['desired_employment_id' => '6', 'employment_id'=> '7'],
            ['desired_employment_id' => '7', 'employment_id'=> '8'],
            ['desired_employment_id' => '8', 'employment_id'=> '8'],
            ['desired_employment_id' => '1', 'employment_id'=> '5'],
            ['desired_employment_id' => '2', 'employment_id'=> '6'],
            ['desired_employment_id' => '1', 'employment_id'=> '7'],
        ];
        
        foreach($desired_employment as $desired_employments) {
            
            DB::table('desired_employments')->insert([
                'desired_employment_id' => $desired_employments['desired_employment_id'],
                'employment_id' => $desired_employments['employment_id']
            ]);
            
        }
    }
}
