<?php

use Illuminate\Database\Seeder;

class Work_matchings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $work_matching = [
            ['work_id' => 1, 'employment_id'=> 1],
            ['work_id' => 2, 'employment_id'=> 2],
            ['work_id' => 3, 'employment_id'=> 3],
            ['work_id' => 4, 'employment_id'=> 2],
            ['work_id' => 5, 'employment_id'=> 4],
            ['work_id' => 1, 'employment_id'=> 3],
            ['work_id' => 9, 'employment_id'=> 3],
            ['work_id' => 2, 'employment_id'=> 3],
            ['work_id' => 4, 'employment_id'=> 1],
            ['work_id' => 2, 'employment_id'=> 5],
            ['work_id' => 4, 'employment_id'=> 5],
            ['work_id' => 1, 'employment_id'=> 6],
            ['work_id' => 2, 'employment_id'=> 7],
            ['work_id' => 3, 'employment_id'=> 8],
            ['work_id' => 4, 'employment_id'=> 6],
            ['work_id' => 5, 'employment_id'=> 7],
            ['work_id' => 5, 'employment_id'=> 8],
        ];
    
        foreach($work_matching as $work_matchings) {
            
            DB::table('work_matchings')->insert([
                'work_id' => $work_matchings['work_id'],
                'employment_id' => $work_matchings['employment_id'],
            ]);
            
        }
        
    }
}
