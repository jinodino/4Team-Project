<?php

use Illuminate\Database\Seeder;

class resign_infosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resign = [
            ['content' => 'より志望度の高い企業から内定を頂いたから'],
            ['content' => '業務内容がイメージとちがったから'],
            ['content' => '企業文化や価値観がイメージとちがったから'],
            ['content' => '労働条件が希望するものではないから'],
            ['content' => '活躍するイメージが持てなかったから'],
            ['content' => '成長・キャリアアップするイメージが持てなかったから'],
            ['content' => 'その他'],
        ];
        
        foreach($resign as $resigns) {
            
            DB::table('resign_infos')->insert([
                'content' => $resigns['content'],
            ]);
            
        }
    }
}
