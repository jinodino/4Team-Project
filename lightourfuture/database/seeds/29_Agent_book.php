<?php

use Illuminate\Database\Seeder;

class Agent_book extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//ac01, ac02
        $agent_book = [
            ['org_agent_id' => 'orgac04', 'name'=> 'haloman', 
            'email' => 'haloman@gmail.com', 'org_id' => 'jphalo', 'join_ox' => 'x'],
            ['org_agent_id' => 'orgac01', 'name'=> 'softman' ,
            'email' => 'softman@gmail.com', 'org_id' => 'jpsoft ', 'join_ox' => 'x'],
            ['org_agent_id' => 'orgac01', 'name'=> 'CNman' ,
            'email' => 'CNman@gmail.com', 'org_id' => 'CNjung', 'join_ox' => 'x'],
            ['org_agent_id' => 'orgac03', 'name'=> 'USman' ,
            'email' => 'USman@gmail.com', 'org_id' => 'USgood', 'join_ox' => 'x'],
            ['org_agent_id' => 'orgac04', 'name'=> 'krman' ,
            'email' => 'krman@gmail.com', 'org_id' => 'krsam', 'join_ox' => 'x'],
        ];
        
        foreach($agent_book as $agent_books) {
            
            DB::table('agent_books')->insert([
                'org_agent_id' => $agent_books['org_agent_id'],
                'name' => $agent_books['name'],
                'email' => $agent_books['email'],
                'org_id' => $agent_books['org_id'],
                'join_ox' => $agent_books['join_ox']
            ]);
            
        }
    }
}
