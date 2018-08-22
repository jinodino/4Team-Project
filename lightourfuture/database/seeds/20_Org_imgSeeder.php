<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker; // Faker 사용 가능하도록 하는 것

class Org_imgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//jphalo jphalo krsam CNjung USgood
        // $prjs = ['jphalo', 'jpsoft', 'krsam', 'CNjung', 'USgood'];
        $prjs = [
            // jphalo
            ['org_id'    => 'jphalo',
             'photo_url' =>'http://newsroom.etomato.com/userfiles/NHN2.jpg'
            ],
            ['org_id'    => 'jphalo',
             'photo_url' =>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSsEUkxsHxcyME1VAySR2FiF_3QWnQgHkcwyZ02wqmcx5FZ8mbYNA'
            ],
            ['org_id'    => 'jphalo',
             'photo_url' =>'http://pds.joins.com/news/component/betanews/201603/11/f35b600a.jpg'
            ],
            // jpsoft
            ['org_id'    => 'jpsoft',
             'photo_url' =>'http://cfile22.uf.tistory.com/image/194BD8404F96D93238E0D2'
            ],
            ['org_id'    => 'jpsoft',
             'photo_url' =>'http://mblogthumb4.phinf.naver.net/MjAxNjExMzBfMTEw/MDAxNDgwNTE0NjQ5NzQ2.3g9wdx4EtX8OxUmNIeAklzdPU-5no8osgeM0J8nTa9Eg.RJPA_86dt2Y-FH288ZLUxPNN-NOT5aOoZp3nG1S3cwQg.JPEG.interkj87/image_2303320111480513813710.jpg?type=w800'
            ],
            ['org_id'    => 'jpsoft',
             'photo_url' =>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSOIcmf_ioXqWc8sSuEPLpDYiWbfx8gRvZHh_5KbT8WLGjIP1k6Fw'
            ],
            // krsam
            ['org_id'    => 'krsam',
             'photo_url' =>'http://magazine.hankyung.com/magazinedata/images/photo/201006/866e16b8ef6724016f7918d0851a52a2.jpg'
            ],
            ['org_id'    => 'krsam',
             'photo_url' =>'http://file2.nocutnews.co.kr/newsroom/image/2017/10/26/20171026071708503246.jpg'
            ],
            ['org_id'    => 'krsam',
             'photo_url' =>'http://t1.daumcdn.net/liveboard/ppss/0e7bbb1ebfac4938b2ca70a768f11833.jpg'
            ],
            // CNjung
            ['org_id'    => 'CNjung',
             'photo_url' =>'https://i.ytimg.com/vi/yLDDDs8BkJg/maxresdefault.jpg'
            ],
            ['org_id'    => 'CNjung',
             'photo_url' =>'http://www.jobnjoy.com/files/editor/1428541096024_1.jpg'
            ],
            ['org_id'    => 'CNjung',
             'photo_url' =>'https://www.dongkuk.com/ko/static/images/web/webzine/201510/contents2_01.jpg'
            ],
            // USgood
            ['org_id'    => 'USgood',
             'photo_url' =>'http://image.newsis.com/2018/02/13/NISI20180213_0000108149_web.jpg?rnd=20180213105645'
            ],
            ['org_id'    => 'USgood',
             'photo_url' =>'http://image.newsis.com/2018/02/28/NISI20180228_0000114495_web.jpg?rnd=20180228160116'
            ],
            ['org_id'    => 'USgood',
             'photo_url' =>'http://image.newsis.com/2017/05/17/NISI20170517_0013015404_web.jpg'
            ],
        ];
        $faker = Faker::create();
        foreach($prjs as $prj) {
            DB::table('org_imgs')->insert([
                'org_id' => $prj['org_id'],
                'photo_url' => $prj['photo_url'],
            ]);
        }
    }
}
