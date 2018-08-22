<template>
  <v-container fluid grid-list-lg grid-list-xs grid-list-md text-xs-center>
    <v-card style="background:#E3F2FD">
    <v-layout row section>
      <h1>エージェント情報登録</h1>
    </v-layout>

    <v-layout row section>
      
      <v-flex xs12 md5 lg5>
        <!-- 에이전트 프로필 사진 -->
        <v-card-text>
          <profile-image-uploader 
              :width ="profileImageWidth"
              :height="profileImageHeight"
              @removeProfileImage="removeProfileImage"
              @createProfileImage="createProfileImage" 
          ></profile-image-uploader>
        </v-card-text>
      </v-flex>

      <v-flex xs12 md6 lg6>
        <v-card-text>
          <v-layout row>
            <v-flex xs3 md3 lg3 text-center>国家選択</v-flex>
            <v-flex xs8 md8 lg8>
              <v-layout row wrap>
                <v-flex xs6>
                    <v-select
                    :items="continents"
                    label="continents"
                    item-text="title"
                    item-value="key"
                    v-model="curr_continent_code"
                    single-line
                    ></v-select>
                </v-flex>
                <v-flex xs6>
                    <v-select
                    :items="countryList[curr_continent_code]"
                    item-text="country_kana"
                    item-value="country_code"
                    v-model="curr_country_code"
                    label="country"
                    single-line
                    ></v-select>
                </v-flex>
              </v-layout>
            </v-flex>
            <v-flex xs8 md8 lg8>
              <v-layout row wrap>
                <v-flex xs6>
                    <v-select
                    :items="japan_regions"
                    label="地方"
                    item-text="name_kanji"
                    item-value="id"
                    v-model="curr_region_code"
                    single-line
                    ></v-select>
                </v-flex>
                <v-flex xs6>
                    <v-select
                    :items="japan_prefectures[curr_region_code]"
                    item-text="name_kanji"
                    item-value="id"
                    v-model="curr_prefecture_code"
                    label="都道府県"
                    single-line
                    ></v-select>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-card-text>
      </v-flex>



      <!-- Enter country information -->
      <!-- <div class="col-xs-4 col-md-12">
        <div class="col-xs-2 col-md-5">국가선택</div>
        <div class="col-xs-2 col-md-5">
          <v-layout row wrap>
            <v-flex xs6>
                <v-select
                :items="continents"
                label="continents"
                item-text="title"
                item-value="key"
                v-model="curr_continent_code"
                single-line
                ></v-select>
            </v-flex>
            <v-flex xs6>
                <v-select
                :items="countryList[curr_continent_code]"
                item-text="country_kana"
                item-value="country_code"
                v-model="curr_country_code"
                label="country"
                single-line
                ></v-select>
            </v-flex>
          </v-layout>
        </div>
      </div> -->


      <!-- 일본 행정 구역 -->
      <!-- <div class="col-xs-4 col-md-12" v-show = "curr_country_code == 'JP'">
        <div class="col-xs-2 col-md-5">행정구역</div>
        <div class="col-xs-2 col-md-5">
          <v-layout row wrap>
            <v-flex xs6>
                <v-select
                :items="japan_regions"
                label="地方"
                item-text="name_kanji"
                item-value="id"
                v-model="curr_region_code"
                single-line
                ></v-select>
            </v-flex>
            <v-flex xs6>
                <v-select
                :items="japan_prefectures[curr_region_code]"
                item-text="name_kanji"
                item-value="id"
                v-model="curr_prefecture_code"
                label="都道府県"
                single-line
                ></v-select>
            </v-flex>
          </v-layout>
        </div>
      </div> -->

      <!-- Enter information -->
      <v-card-text>
        <v-flex xs12 md12 lg12>
          <v-text-field
            v-model="info.org_name"
            label="名前"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 md12 lg12>
          <v-text-field
            v-model="info.org_name_kana"
            label="名前（カナ）"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 md12 lg12>
          <v-text-field
            v-model="info.homepage_url"
            label="ホームページurl"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 md12 lg12>
          <v-text-field
            v-model="info.address"
            label="住所"
          ></v-text-field>
        </v-flex>
      </v-card-text>
    </v-layout>
    
      

    <div class="text-left">
      <button type="button" class="btn btn-primary pull-right col-md-1" v-if="modifiedSave = !disabled" @click="create">저장</button>
      <button type="button" class="btn btn-primary pull-right col-md-1" v-else @click="disabled = !disabled; create">수정</button>
    </div>
    </v-card>
  </v-container>
</template>

<script>

import ProfileImageUpload from '../../shared/ProfileImageUpload.vue'

export default {
  components : {
    'profile-image-uploader' : ProfileImageUpload,
  },

  created : function(){
    //
    this.user.login_id = this.$store.getters.identification;
    this.user.classification = this.$store.getters.classification;

    //국가코드 가져오기
      let continent_code = ['AS','EU','NA','AF','AN','SA','OC'];
      this.axios.post('/continent/getCountries', {continent_code})
      .then(rep=>{
          this.countryList = rep.data;
      })
      .catch(ex=>{
          console.log(ex);
      })


      //일본 행정구역 가져오기
      this.axios.post('/japan/getTodouhuken', )
      .then(rep =>{
        this.japan_regions = rep.data.regions;
        this.japan_prefectures = rep.data.prefectures;
      })
      .catch(ex=>{
        console.log(ex);
      });

  },

  data : ()=>({
    user : {
       login_id : null,
       classification : null
    },

   
    classification : 'ag',
    disabled : false,
    modifiedSave : null,
    profileImage : {
      data : null,
      name : null,
      folder : 'profileImages',
      key : 'photo_url'
    },
    curr_continent_code : 'AS',
    curr_country_code : 'JP',
    countryList : {},
    continents : [
      { title : '아시아', key : 'AS' },
      { title : '유럽', key : 'EU' },
      { title : '북아메리카', key : 'NA' },
      { title : '아프리카', key : 'AF' },
      { title : '남극', key : 'AN' },
      { title : '남아메리카', key : 'SA' },
      { title : '오세아니아', key : 'OC' }
    ],

    profileImageWidth : 320,
    profileImageHeight : 300,

    japan_regions : null,
    japan_prefectures : null,
    curr_region_code : '',
    curr_prefecture_code : '',

    info : { 
      org_name : "" ,
      org_name_kana : "",
      homepage_url : "" ,
      address : "",
      country_code : "",
      address_to_dou_hu_ken : '',
      create_id : '',
    }

  }),

    methods: {

      // 사진
    createProfileImage(type , data){
      console.log('プロフィール写真登録');
      this.profileImage.type = type;
      this.profileImage.data = data;
    },

    removeProfileImage(){
       console.log('プロフィール写真削除');
      this.profileImage.type = null;
      this.profileImage.data = null;
    },
      

      //에이전트 생성
      create(){
        let org_agents = this.info;
        org_agents.country_code = this.curr_country_code;
        org_agents.address_to_dou_hu_ken = this.curr_prefecture_code;
        org_agents.create_id = this.user.login_id;

        let profileImage = this.profileImage;

        this.axios.post('/agent/create', {org_agents, profileImage})
          .then(req=>{
           
          })
          .catch(ex=>{
            console.log(ex);
          })
      },

    }
}
</script>

<style>
  .section{
    margin-bottom: 1rem;
    background-color:rgb(224, 255, 224); 
    
  }
  #border {
    border-bottom:1px black solid;
  }
  #img {
    /* 150x180 */
    width: 150px;
    height: 180px;
  }
</style>
