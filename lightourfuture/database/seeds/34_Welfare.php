<?php

use Illuminate\Database\Seeder;

class Welfare extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//jphalo, jpsoft, krsam, CNjung, USgood
        $welfare = [
            ['welfare_id' => 1, 'employment_id'=> 4],
            ['welfare_id' => 2, 'employment_id'=> 3],
            ['welfare_id' => 3, 'employment_id'=> 2],
            ['welfare_id' => 4, 'employment_id'=> 1],
            ['welfare_id' => 5, 'employment_id'=> 4],
            ['welfare_id' => 1, 'employment_id'=> 3],
            ['welfare_id' => 2, 'employment_id'=> 2],
            ['welfare_id' => 3, 'employment_id'=> 4],
            ['welfare_id' => 3, 'employment_id'=> 5],
            ['welfare_id' => 2, 'employment_id'=> 5],
            ['welfare_id' => 3, 'employment_id'=> 6],
            ['welfare_id' => 3, 'employment_id'=> 7],
            ['welfare_id' => 2, 'employment_id'=> 8],
            ['welfare_id' => 4, 'employment_id'=> 6],
            ['welfare_id' => 5, 'employment_id'=> 7],
            ['welfare_id' => 5, 'employment_id'=> 8],
        ];
        
        foreach($welfare as $welfares) {
            
            DB::table('welfares')->insert([
                'welfare_id' => $welfares['welfare_id'],
                'employment_id' => $welfares['employment_id'],
                
            ]);
            
        }
    }
}
