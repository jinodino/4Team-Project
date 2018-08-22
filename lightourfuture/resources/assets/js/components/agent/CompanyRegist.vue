<template>
  <v-container text-xs-center fluid>
    <v-layout row>
      <v-flex xs12>
        <v-card style="background:#E3F2FD">
          <v-card-text>
            <v-container fluid grid-list-lg>
              <v-layout row text-xs-center>
                
                <v-flex xs12>
                  <h1>企業情報{{status.label}}</h1>
                  <div>ホスティングエージェント : {{orgAgent.org_name}}({{orgAgent.org_name_kana}})</div>
                  <div style="color:red; float:right">'☆'は必ず作成してください。</div>
                </v-flex>

              </v-layout>
            
              <!-- 프로필 및 기본 정보 -->
              <v-layout row justify-space-between wrap>
                <!-- 프로필 이미지 -->
                <v-flex xs12 md4 lg4 style="margin-top : 35px">
                  <h4>ロゴイメージ</h4>
                  <profile-image-uploader 
                    style="margin-left: auto; margin-right: auto; display: block;"
                    :width ="profileImageWidth"
                    :height="profileImageHeight"
                    :value="info.photo_url"
                    @removeProfileImage="removeProfileImage"
                    @createProfileImage="createProfileImage" 
                  ></profile-image-uploader>
                </v-flex>


                <v-flex xs12 md8 lg8>
                  <!-- 기업명 -->
                  <v-container fluid grid-list-xs>
                    <v-layout row justify-space-between>
                      <v-flex xs6 md6 lg6>
                        <v-text-field
                          label="企業名☆"
                          v-model="info.org_name"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs6 md6 lg6>   
                        <v-text-field
                          label="企業名（英語）☆"
                          v-model="info.org_name_kana"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs6 md6 lg6>
                        <v-text-field 
                              label="代表代表者名"
                              v-model="info.ceo_name"    
                            >
                        </v-text-field>
                      </v-flex>
                        
                      <v-flex xs6 md6 lg6>
                          <v-text-field 
                            label="代表代表者名（カナ）"
                            v-model="info.ceo_name_kana"
                          >
                          </v-text-field>
                      </v-flex>

                      <v-flex xs6 md2 lg2>
                        <v-text-field 
                          type="number"
                          label="社員数☆"
                          v-model="info.worker_count" 
                        >
                        </v-text-field>
                      </v-flex>

                      <v-flex xs6 md2 lg2>
                        <v-text-field 
                          type="number"
                          label="資本金"
                          v-model="info.capital"
                        >   
                        </v-text-field>
                      </v-flex>
                      <v-flex xs6 md4 lg4>
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
                              label="設立日"
                              v-model="info.establish_date"
                              prepend-icon="event"
                              readonly
                            ></v-text-field>
                            <v-date-picker v-model="info.establish_date" no-title scrollable>
                              <v-spacer></v-spacer>
                              <v-btn flat color="error" @click="datePicker = false">Cancel</v-btn>
                              <v-btn flat color="success"  @click="$refs.menu.save(info.establish_date)">OK</v-btn>
                            </v-date-picker>
                          </v-menu>
                      </v-flex>
                      <v-flex xs12 md4 lg4>
                        <v-radio-group row v-model="info.listed_company_ox">
                          <v-flex xs6 md6 lg6 v-for="radio in radioMenu.listed_company_ox" :key="radio.value">
                              <v-radio  :value="radio.value" :label="radio.label"></v-radio>
                          </v-flex>
                        </v-radio-group>
                      </v-flex>

              
                      <v-flex  xs6 md3 lg3>
                          <v-select
                          :items="japan_regions_menu"
                          label="地方"
                          item-text="name_kanji"
                          item-value="id"
                          v-model="curr_region_code"
                          single-line
                          ></v-select>
                      </v-flex>

                      <v-flex xs6 md3 lg3>
                          <v-select
                          :items="japan_prefectures_menu[curr_region_code]"
                          item-text="name_kanji"
                          item-value="id"
                          v-model="info.address_to_dou_hu_ken"
                          label="都道府県"
                          single-line
                          ></v-select>
                      </v-flex>

                      <v-flex xs12 md6 lg6>
                        <v-text-field
                          label="住所☆"
                          v-model="info.address"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs12 md12 lg12>
                        <v-text-field 
                          value="homepage_url"
                          label="企業ホームページ"
                          v-model="info.homepage_url"    
                        >
                        </v-text-field>
                      </v-flex>

                    </v-layout>
                  </v-container>
                </v-flex>
                
              </v-layout>

              <v-layout row>      
                <!-- 체크박스 1.사업 대분류/ 2.사업 소분류 -->
                <v-flex xs12 v-for="item in checkBoxMenu" :key="item.table">
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
                            {{pill}}&nbsp;&nbsp;{{checkBoxMenu[item.table].menus[pill-1].content}}
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
                <v-flex xs12>
                  <v-text-field 
                    value="business_content"
                    textarea
                    label="事業内容"
                    v-model="info.business_content"
                    :rules="[(v) => v.length <= 500 || 'Max '+ 500 + ' characters']"    
                  >
                  </v-text-field>
                  <v-text-field 
                    value="company_atmosphere"
                    textarea
                    label="社内雰囲気"
                    v-model="info.company_atmosphere"
                    :rules="[(v) => v.length <= 500 || 'Max '+ 500 + ' characters']"    
                  >
                  </v-text-field>
                  <v-text-field 
                    value="recommendation_comment"
                    textarea
                    label="エージェントコメント"
                    v-model="info.recommendation_comment"
                    :rules="[(v) => v.length <= 500 || 'Max '+ 500 + ' characters']"    
                  >
                  </v-text-field>
                </v-flex>

              </v-layout>

              <!-- 사진 업로드 -->
              <v-layout row>
                <!-- 
                <blog-image-uploader 
                  @uploadImage="uploadImage"
                  @removeImage="removeImage"
                  @removePropsImage="removePropsImage" 
                  :propsImages='propsImages'>
                </blog-image-uploader> 
                -->
              <v-flex xs12>
                <v-container grid-list-lg fluid>
                  <v-layout row justify-center>
                      <v-flex xs12 md12 lg12 text-xs-center>
                          <input id="inputFile" type="file" class="input-file" @change="uploadImage" accept=".jpg, .jpeg, .png"/> 
                          <v-btn color="success" @click="check" block :disabled="imageIndex == 3"><v-icon left>photo_library</v-icon> PHOTO UPLOAD</v-btn>
                      </v-flex> 
                  </v-layout>

                <!-- 썸네일 -->
                  <v-layout row wrap justify-space-around>
                    <v-flex xs3 md3 lg2 v-for="(img, key, index) in images" :key="index" v-show="img">
                      <v-card>
                        <v-card-media :src="img.data == null? (img.photo_url == null? noimage_url : img.photo_url) : img.data " height="120px">
                          <v-layout row wrap>
                            <v-flex xs1 md1 lg1 offset-xs5 offset-md7 offset-lg8>
                              <v-btn icon small color="error" @click="removeImage(key)" v-show="img.photo_url != null || img.data != null">
                                X
                              </v-btn>
                            </v-flex>
                          </v-layout>                 
                        </v-card-media>
                      </v-card>
                    </v-flex>
                  </v-layout>   

                  <!-- 실사 이미지 -->
                  <v-layout row justify-center>    
                    <v-flex xs8 md8 lg8 v-for="(img, key, index) in images" :key="index" > 
                      <v-card v-show="img.photo_url != null || img.data != null">
                        <img :src="img.data == null? img.photo_url : img.data" alt="" width="100%" height="auto"> 
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <span>{{img.name}}</span>
                            <v-btn color="error" @click="img.photo_url == null? removeImage(key) : removePropsImage(key, img.key)">CANCEL</v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-flex>
              </v-layout>

              <v-layout row>
                <v-flex xs12 md12 lg12>
                  <v-btn v-if="status.mode == 'create'" large color="success" block @click="validationCheck(0)">登録</v-btn>
                  <v-btn v-else large color="dahong" block @click="validationCheck(1)" dark>修正</v-btn>
                  <v-btn large color="error" block @click="go2Back">取り消す</v-btn>
                </v-flex>
              </v-layout>
            </v-container>   
          </v-card-text>
     
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
import ProfileImageUpload from '../../shared/ProfileImageUpload.vue'

export default {
  
  components : {
    'profile-image-uploader' : ProfileImageUpload,
  },

  created : function(){

    //권한 확인
    this.orgAgent = this.$route.params.orgAgent;
    let org_company_id = this.$route.query.company_id;
    let org_agent_id = this.$route.query.agent_id;
    if(this.orgAgent == null){
      if(org_company_id == null)
        this.$router.push({path : '/agent/companylist'});
      else  
        this.$router.push({path : '/agent/companyInfo', query : {company_id : org_company_id, agent_id : org_agent_id}});
    }

    //mode 확인
    if(this.$route.params.mode == 'create'){
      this.status.label = "登録";
      this.status.mode = "create";
    }else if(this.$route.params.mode == 'update'){
      this.status.label = "修正";
      this.status.mode = "update";

      if(org_company_id == null){
       // this.$router.go(-1);
      }else{
        this.getCompanyInfo(org_company_id);
      }
    }



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
      label : "登録",
      mode : 'create'
    },


    profileImage : {
      type : null,
      data : null,
      photo_url : null,
    },
    profileImageWidth : 320,
    profileImageHeight : 300,

    noimage_url : '/ImageProvider/noimage.png',
    imageIndex : 0,
    images : [
      {name : null, data : null, photo_url : null, type : null},
      {name : null, data : null, photo_url : null, type : null},
      {name : null, data : null, photo_url : null, type : null},
    ],

    deletedImages : [],

    //propsImages : null,

    datePicker : false,

    info: {
      org_name : '',
      org_name_kana : '',
      address  : '',
      listed_company_ox : 'x',
      establish_date : '',//설립일
      country_code : "JP",
      worker_count : '',// 사원수
      capital : '',// 자본금
      ceo_name : '',// 대표이름
      ceo_name_kana : '',// 대표일름
      homepage_url : '', // 기업 홈페이지
      business_content : '', // 사업 내용
      company_atmosphere : '', // 사풍/ 분위기
      recommendation_comment : '', // 에이전트 추천 댓글
      address_to_dou_hu_ken : '',
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
        label : '事業内容大分類（複数選択可）', 
        menus : [], 
        table : 'business_bigs', 
        pop:false
      },

      business_smalls:{
        label : '事業内容小分類（複数選択可）', 
        menus : [], 
        table : 'business_smalls', 
        pop:false
      },
    },


    radioMenu : {

      listed_company_ox : [
        //listed_company_ox
        { 
          label : '上場企業', 
          icon : 'radio_button_checked' , 
          icon_color : 'green darken-2',
          value : 'o',
        },

        { 
          label : '非上場企業', 
          icon : 'radio_button_unchecked', 
          icon_color : 'red darken-2',
          value : 'x',
        }
      ]
    },

    japan_regions_menu : [],
    japan_prefectures_menu : [],
    curr_region_code : '' 

  }),

  methods: {

    //프사 함수
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


    //이미지 함수
    eventOccur(evEle, evType){
      //MouseEvents가 포인트 그냥 Events는 안됨~ ??
      var mouseEvent = document.createEvent('MouseEvents');

      /* API문서 initEvent(type,bubbles,cancelable) */
      mouseEvent.initEvent(evType, true, false);
      var transCheck = evEle.dispatchEvent(mouseEvent);

      //만약 이벤트에 실패했다면
      if (!transCheck) 
          console.log("클릭 이벤트 발생 실패!");
    },

    check(){
      this.eventOccur(document.getElementById('inputFile'),'click');
        //alert(orgFile.value); //이벤트 처리가 끝나지 않은 타이밍이라 값 확인 안됨! 시간차 문제 
    },

    uploadImage(event) {
        if (this.disabled) 
          return;
        
        let files = event.target.files;
        if (files.length === 0)
          return;
        
        let reader = new FileReader();
        reader.onload = (event) => {
            //this.images.push({name : files[0].name, type : files[0].type, data : event.target.result, photo_url : null});
            //this.images = [];
            
            this.images[this.imageIndex] = {name : files[0].name, type : files[0].type, data : event.target.result, photo_url : null};
            this.images.push({});
            this.images.splice(3, 1);
            this.imageIndex++;
            // for(let i = 0; i < 3-this.imageIndex; i++){
            //   this.images.push({name : null, data : null, photo_url : null, type : null});
            // }
           
            //this.$emit('uploadImage', this.images.length - 1, files[0].type, event.target.result);
        };
        reader.readAsDataURL(files[0]);
    },

    removeImage(index){
        //alert(index);
        document.getElementById('inputFile').dispatchEvent;
        if(this.images[index].photo_url != null){
          this.deletedImages.push(this.images[index].photo_url);
        }
        this.images.splice(index, 1);
        this.imageIndex--;
        this.images.push({name : null, data : null, photo_url : null, type : null});
    },

/*
    uploadImage(index, param_type, param_data){
      console.log('写真登録');
      this.images[index] = {type : param_type, data : param_data};
    },

    removeImage(index){           
      console.log('写真削除');
      this.images.splice(index, 1);
    },

    removePropsImage(index, key){
      this.propsImages[key].check = false;
      this.removeImage(index);
    },
*/


    //유효성 검사
    validationCheck(saveOrEdit){
      var org_name = this.info.org_name;
      var org_name_kana = this.info.org_name_kana;
      var worker_count = this.info.worker_count;
      var address = this.info.address;

      if( org_name == "" || org_name_kana == "" || 
          worker_count == "" || address == ""){
        alert("'☆'が付いた欄を占めてください");
        return false;
      }
      else if(!this.eng_check(this.info.org_name_kana)){
        alert("企業名（英語）は英語だけ入力できます。");
        return false;
      }
      else if(this.checkBoxValue.business_bigs.length > 3){
        alert('事業内容大分類は最大3つまで選択可能です。');
        return false;
      }
      else if(this.checkBoxValue.business_smalls.length > 3){
        alert('事業内容小分類は最大3つまで選択可能です。');
        return false;
      }else if(saveOrEdit == 0){
        this.createCompany();
        return true;
      }else{
        this.updateOrgCompany();
        return true;
      }
   
    },

    

    go2Back(){
      this.$router.go(-1);
    },
    //영어입력 유효성 검사
    eng_check( eng ) {    
        var regex=/[a-z]/gi;
        let returnBool =  regex.test(eng); 
        return returnBool;
    },
    //기업 생성 함수
    createCompany(){    
      
      let org_companies = this.info;
      let profileImage = this.profileImage;
      let checkBox = this.checkBoxValue;
      let images = this.images;
      let host_org_agent_id = this.orgAgent.org_agent_id

      this.axios.post('/company/create',{org_companies, profileImage, checkBox, images, host_org_agent_id})
      .then(rep => {
        let org_company_id = rep.data.org_company_id;

        this.$router.push({
          path:'/agent/companyInfo',
          query : { company_id : org_company_id, agent_id : this.orgAgent.org_agent_id }
          });
      })
      .catch(ex=>{
        console.log(ex);
      })
      
    },

    //기업 수정 함수
    updateOrgCompany(){

      let org_companies = this.info;
      let profileImage = this.profileImage;
      let checkBox = this.checkBoxValue;
      let images = this.images;
      let deletedImages = this.deletedImages;

      this.axios.post('/company/update', { org_companies, profileImage, checkBox, images, deletedImages})
                .then(rep => {
                  if(rep.data.returnBool){
                    let org_company_id = rep.data.org_company_id;

                    this.$router.push({
                        path:'/agent/companyInfo', 
                        query:{company_id : org_company_id, agent_id : this.orgAgent.org_agent_id},
                    });

                  }else{

                  }
               
                })
                .catch(ex=>{
                  console.log(ex);
                })
    },

    //기업 정보 가져오기
    getCompanyInfo(org_company_id){
      
      this.axios.post('/company/getCompanyInfo',{org_company_id})
                .then(rep=>{
                  let info = rep.data.org_companies;

                  for(let i in info)
                    info[i] = (info[i] == null? '' : info[i]);
                  this.info = info;

                  let big = rep.data.business_bigs;
                  for(let i in big)
                    this.checkBoxValue.business_bigs[i] = big[i].id;
  

                  let small = rep.data.business_smalls;
                  for(let i in small)
                    this.checkBoxValue.business_smalls[i] = small[i].id;

                  this.images = null;
                  this.images = rep.data.org_imgs;
                  this.imageIndex = this.images.length;

                  for(let i = 0; i<3-this.imageIndex; i++){
                    this.images.push({name : null, data : null, photo_url : null, type : null});
                  }
                  // this.propsImages = [];

                  // for(let i in this.images)
                  //   this.propsImages[i] = {'check' : true, 'photo_url' : this.images[i].photo_url, 'key' : i};
                  
                  
                  this.curr_region_code =  rep.data.region_id;
                  let photo_url = rep.data.org_companies.photo_url;
                  this.profileImage.photo_url =  ( photo_url == '' ? null : photo_url);

                })
                .catch(ex=>{
                  console.log(ex);
                });

    }
  }


}

</script>
<style>
   .input-file {
        opacity: 0;
        width: 0;
        height: 0;
    }
</style>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>