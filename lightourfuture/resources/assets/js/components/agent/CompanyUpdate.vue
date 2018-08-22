<template>
  <v-container fluid grid-list-lg grid-list-xs grid-list-md text-xs-center>

    <v-layout row section>
      <v-flex xs12 md12 lg12>
        <p class="display-1">기업 정보 {{status.label}}</p>
      </v-flex>
      <v-flex xs12 md12 lg12 style="color : red">
        <p class="pull-right">*은 필수 입력사항입니다.</p>
      </v-flex>
    </v-layout> 
    
    <!-- 상장 여부 radio-->
    <v-layout row section text-lg-center>
      <v-flex xs12 md12 lg>
        <v-radio-group row v-model="info.listed_company_ox">
          <v-flex xs6 md6 lg6 v-for="radio in radioMenu.listed_company_ox" :key="radio.value">
              <v-icon x-large :color="info.listed_company_ox == radio.value ? radio.icon_color : 'grey'">{{radio.icon}}</v-icon>
              <v-radio :value="radio.value" :label="radio.label"></v-radio>
          </v-flex>
        </v-radio-group>
      </v-flex>
    </v-layout>

    <!-- 프로필 및 기본 정보 -->
    <v-layout row section justify-center>
      <!-- 프로필 이미지 -->
      <v-flex xs12 md5 lg5>
        <profile-image-uploader 
          :width ="profileImageWidth"
          :height="profileImageHeight"
          @removeProfileImage="removeProfileImage"
          @createProfileImage="createProfileImage" 
        ></profile-image-uploader>
      </v-flex>


      <v-flex xs12 md6 lg6>
        <!-- 기업명 -->
        <v-layout row>
          <v-flex xs6 md6 lg6>
            <v-text-field
              label="기업 명*"
              v-model="info.org_name"
            ></v-text-field>
          </v-flex>
          <v-flex xs6 md6 lg6>   
            <v-text-field
              label="기업 명(가나)*"
              v-model="info.org_name_kana"
            ></v-text-field>
          </v-flex>
        </v-layout>

        <!-- 사원수, 자본금 -->
        <v-layout row>
          <v-flex xs12 md6 lg6>
            <v-text-field 
              type="number"
              label="사원 수*"
              v-model="info.worker_count" 
            >
            </v-text-field>
          </v-flex>

          <v-flex xs12 md6 lg6>
            <v-text-field 
              type="number"
              label="자본금*"
              v-model="info.capital"
            >   
            </v-text-field>
          </v-flex>
        </v-layout>

        <!-- 국가, 지방, 토도부현 -->
        <v-layout row>
          <v-flex xs2>
            <v-text-field
            label="국가"
            v-model="info.country_code"
            disabled
            >
            </v-text-field>
          </v-flex>
  
          <v-flex xs5>
              <v-select
              :items="japan_regions_menu"
              label="地方"
              item-text="name_kanji"
              item-value="id"
              v-model="curr_region_code"
              single-line
              ></v-select>
          </v-flex>

          <v-flex xs5>
              <v-select
              :items="japan_prefectures_menu[curr_region_code]"
              item-text="name_kanji"
              item-value="id"
              v-model="info.address_to_dou_hu_ken"
              label="都道府県"
              single-line
              ></v-select>
          </v-flex>

        </v-layout>

        <!-- 주소, 설립일 -->
        <v-layout row>
          <v-flex xs12 md12 lg12>
            <v-text-field
              label="주소*"
              v-model="info.address"
            ></v-text-field>
          </v-flex>
          <v-flex xs12 md12 lg12>
            <v-menu
              ref="menu"
              lazy
              :close-on-content-click="false"
              v-model="datePicker"
              transition="scale-transition"
              offset-y
              full-width
              :nudge-right="40"
              min-width="290px"
              :return-value.sync="info.establish_date"
              >
                <v-text-field
                  slot="activator"
                  label="설립일"
                  v-model="info.establish_date"
                  prepend-icon="event"
                  readonly
                ></v-text-field>
                <v-date-picker v-model="info.establish_date" no-title scrollable>
                  <v-spacer></v-spacer>
                  <v-btn flat color="primary" @click="datePicker = false">Cancel</v-btn>
                  <v-btn flat color="primary" @click="$refs.menu.save(info.establish_date)">OK</v-btn>
                </v-date-picker>
              </v-menu>
          </v-flex>
        </v-layout> 
      </v-flex>

    </v-layout>
  


    <v-layout row section>

      <v-flex xs12 md12 lg12>
          <p class="headline">상세 정보 {{status.label}}</p>
      </v-flex>

      <!-- 기업 정보 -->
      <v-flex xs12 md6 lg6>
        <v-text-field 
              label="대표 이름"
              v-model="info.ceo_name"    
            >
        </v-text-field>
      </v-flex>
        
      <v-flex xs12 md6 lg6>
          <v-text-field 
            label="대표이름(가나)"
            v-model="info.ceo_name_kana"
          >
          </v-text-field>
      </v-flex>

      <v-flex xs12 md12 lg12>
          <v-text-field 
            value="homepage_url"
            label="기업 홈페이지"
            v-model="info.homepage_url"    
          >
          </v-text-field>
      </v-flex>
      

      <!-- 체크박스 1.사업 대분류/ 2.사업 소분류 -->
      <v-flex xs12 md12 lg12 v-for="item in checkBoxMenu" :key="item.table">
        <v-expansion-panel>
          <v-expansion-panel-content >
            <div slot="header">
              <v-layout row>
                <v-flex xs12>
                  {{item.label}}
                </v-flex>
                <v-flex xs12>
                  <v-chip color='light gray' v-if="checkBoxValue[item.table].length == 0" disabled>
                    There's no tag yet :)
                  </v-chip>
                  <v-chip v-else
                  color="primary"
                  text-color="white"
                  v-for="pill in checkBoxValue[item.table]" 
                  :key="pill" 
                  >
                  <v-avatar class="primary">{{checkBoxMenu[item.table].menus[pill-1].id}}</v-avatar>
                    {{checkBoxMenu[item.table].menus[pill-1].content}}
                  </v-chip>
                </v-flex>
                
              </v-layout>
            </div>

            <v-card>
                <v-card-text>
                    <v-layout row>
                      <v-flex xs12 sm6 lg3 v-for="(menu, key, index) in item.menus" :key="index"> 
                        <v-checkbox 
                        :label="menu.content"
                        :value="menu.id"
                        v-model="checkBoxValue[item.table]"
                        >
                        </v-checkbox>
                      </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-flex>
   
      <!-- text -->
      <v-flex xs12 md12 lg12>
              <v-text-field 
                value="business_content"
                textarea
                label="사업 내용"
                v-model="info.business_content"
                :rules="[(v) => v.length <= 500 || 'Max '+ 500 + ' characters']"    
              >
              </v-text-field>
              <v-text-field 
                value="company_atmosphere"
                textarea
                label="사풍/분위기"
                v-model="info.company_atmosphere"
                :rules="[(v) => v.length <= 500 || 'Max '+ 500 + ' characters']"    
              >
              </v-text-field>
              <v-text-field 
                value="recommendation_comment"
                textarea
                label="에이전트 추천 댓글"
                v-model="info.recommendation_comment"
                :rules="[(v) => v.length <= 500 || 'Max '+ 500 + ' characters']"    
              >
              </v-text-field>
      </v-flex>

    </v-layout>

    <!-- 사진 업로드 -->
    <v-layout row section>
      <blog-image-uploader 
      @uploadImage="uploadImage" 
      :propsImages='propsImages'>
      </blog-image-uploader>
    </v-layout>


    <v-layout row section>
      <v-flex xs12 md12 lg12>
        <v-btn large color="primary" block @click="validation">{{status.label}}</v-btn>
      </v-flex>
    </v-layout>

  </v-container>

</template>
<script>
import BlogImageUpload from '../../shared/BlogImageUpload';
import ProfileImageUpload from '../../shared/ProfileImageUpload.vue'

export default {
  
  components : {
    'profile-image-uploader' : ProfileImageUpload,
    'blog-image-uploader' : BlogImageUpload,
  },

  created : function(){

    //작성 orgAgent 정보가 없으면 이전페이지로 돌아가기
    let orgAgent = this.$route.params.orgAgent;
    if(orgAgent == null)
      this.$router.go(-1);
  
    this.orgAgent = orgAgent;

    //사업 대분류, 소분류 항목 가져오기
    let tableList = ['business_big_infos','business_small_infos'];
    this.axios.post('/item/listUp', {tableList} )
    .then(rep => {
        this.checkBoxMenu.business_bigs.menus = rep.data.business_big_infos;
        this.checkBoxMenu.business_smalls.menus= rep.data.business_small_infos;
    })
    .catch(ex => {
      console.log(ex);
    });


    //일본 행정구역 가져오기
    this.axios.post('/japan/getTodouhuken', )
    .then(rep =>{
      this.japan_regions_menu = rep.data.regions;
      this.japan_prefectures_menu = rep.data.prefectures;
    })
    .catch(ex=>{
      console.log(ex);
    });

  },

  data : ()=>({

    orgAgent : null,

    status : {
      label : "등록",
      value : 'create'
    },
    
    profileImage : {
      type : null,
      data : null,
    },
    profileImageWidth : 320,
    profileImageHeight : 300,

    propsProfileImage : null,
    

    images : [],
    propsImages : null,

    datePicker : false,

    info: {
      org_name : "",
      org_name_kana : "",
      address  : "",
      listed_company_ox : 'x',
      establish_date : "",//설립일
      country_code : "JP",
      worker_count : "",// 사원수
      capital : "",// 자본금
      ceo_name : "",// 대표이름
      ceo_name_kana : "",// 대표일름
      homepage_url : "", // 기업 홈페이지
      business_content : "", // 사업 내용
      company_atmosphere : "", // 사풍/ 분위기
      recommendation_comment : "", // 에이전트 추천 댓글
      address_to_dou_hu_ken : "",
    },

    checkBoxValue : {
        business_bigs : [],
        business_smalls : [],
    },  

    country : { 
        country_code : 'JP', country_eng : 'Japan', country_kana:'日本'
    },

   

    checkBoxMenu:{
      business_bigs:{
        label : '사업 내용 대분류 (다중 체크)', 
        menus : [], 
        table : 'business_bigs', 
        pop:false
      },
      business_smalls:{
        label : '사업 내용 소분류 (다중 체크)', 
        menus : [], 
        table : 'business_smalls', 
        pop:false
      },
    },


    radioMenu : {

      listed_company_ox : [
        //listed_company_ox
        { 
          label : '상장 기업', 
          icon : 'radio_button_checked' , 
          icon_color : 'green darken-2',
          value : 'o',
        },

        { 
          label : '비상장 기업', 
          icon : 'radio_button_unchecked', 
          icon_color : 'red darken-2',
          value : 'x',
        }
      ]
    },

    
    japan_regions_menu : '',
    japan_prefectures_menu : '',
    curr_region_code : ''


  }),

  
  methods: {

    //사진 함수
    createProfileImage(type , data){
      console.log('프로필 사진 등록');
      this.profileImage.type = type;
      this.profileImage.data = data;
    },

    removeProfileImage(){
       console.log('프로필 사진 삭제');
      this.profileImage.type = null;
      this.profileImage.data = null;
    },

    uploadImage(index, param_type, param_data){
      console.log('사진 등록');
      this.images[index] = {type : param_type, data : param_data};
    },

    removeImage(index){
      console.log('사진 삭제');
      this.images.splice(index, 1);
    },



    //유효성 검사 함수
    validation(){
      /*
      var text_count = 6;
      var text1 = this.info.worker_count;
      var text2 = this.info.capital;
      var text3 = this.info.ceo_name_kana;
      var text4 = this.info.business_content;
      var text5 = this.info.company_atmosphere;
      var text6 = this.info.recommendation_comment;
      var wordmax = new Array();
      var textnull = new Array();
      var wordCount = 0;
      for(var i = 0; i < text_count; i++){
        
        if(text4.length > 500 && text5.length > 500 && text6.length > 500){
          wordmax[wordCount] = false;
        } 
        else{
          wordmax[wordCount] = true;
        }
        wordCount++

        if (text1 === "" && text2 === "" && text3 === "" && text4 === "" && text5 === "" && text6 === "" ){
          textnull[i] = false;

        }
        else {
          textnull[i] = true;
        }
      }

      //제일 위 4개 항목 빈칸 확인
      var info1 = this.info.org_name_kana;
      var info2 = this.info.org_name_eng;
      var info3 = this.info.address;
      var info_count = 3;
      var info_arr = new Array();
      for(var a = 0; a < info_count ; a++ ){
        if(info1 === "" && info2 === "" && info3 === "" ){
          info_arr[a] = false;
        }
        else{
          info_arr[a] = true;
        }
      }
      //설립연도 빈칸확인
      // var picker;
      // if(this.DB.establish_date.value === ""){
      //   picker = false;
      // }else {
      //   picker = true;
      // }

      //호스트 에이전트 빈칸 확인
      var host_org_agent = false;
      if(this.DB.host_org_agent_id === ""){
        host_org_agent = false;
      }else{
        host_org_agent = true;
      }

      if(wordmax[0] === true && wordmax[1] === true && wordmax[2] === true  
      && textnull[0] === true && textnull[1] === true && textnull[2] === true 
      && textnull[3] === true && textnull[5] === true 
      && textnull[6] === true && textnull[7] === true && host_org_agent === true
      &&  info[1] === true && info[2] === true && info[3] === true){
        //alert('확인');
        this.create();
      }
      else if(wordmax[0] === false || wordmax[1] === false || wordmax[2] === false){
        alert('맨 아래 3항목은 500자 이하로 채워주세여');
      }else if(host_org_agent === false){
        alert('호스트 에이전트를 선택해 주세요');
      }
      else{
        alert("빈 항목을 채워주세요.");
      }
      console.log(this.images);
      */
      this.createOrgCompany();
    },
    
    //기업 생성 함수
    createOrgCompany(){    

      let org_companies = this.info;
      let profileImage = this.profileImage;
      let checkBox = this.checkBoxValue;
      let images = this.images;
      let host_org_agent_id = this.orgAgent.org_agent_id

      this.axios.post('/company/create',{org_companies, profileImage, checkBox, images, host_org_agent_id})
      .then(rep => {
        let org_company_id = rep.data.org_company_id;

        this.$router.push({
          path:'/agent/relations',
          params : {
            company_id : org_company_id,

          }
          });
      })
      .catch(ex=>{
        console.log(ex);
      })
      
    }
  }


}

</script>
<style>
  .section{
    margin-bottom: 1rem;
    background-color:rgb(223, 255, 223);
  }
  
</style> 