<template>

  <v-layout row>


    <!-- 기본 정보 -->
    <v-flex xs12 md12 lg12>
          <v-layout row wrap>
            <v-flex xs10>
              <v-layout>
                <p class="resultfont" style="margin: 10px 10px 0px 17px; text-align: left">基本情報</p>
                <p class="Titlefont" style="color:#868686; margin: 20px 15px 0px 5px; text-align: left">
                  {{student_code}}/{{status}}
                </p> 
              </v-layout>
            </v-flex>
            <v-flex xs2>
              <button right type="button" @click="openProfileDialog" style="float:right; margin:16px 15px 0px 0px">
                <img src="images/common/edit.png" > 
              </button>
            </v-flex>
          </v-layout>
          <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;">
          </div>
          <!-- <v-card-title class="grey lighten-4 py-4 display-1">
            <b>기본 정보</b>
          </v-card-title> -->
          <v-container grid-list-xl fluid>
            <v-layout row justify-space-around>      
              <!-- 이미지 -->
              <v-flex xs12 md2 lg2>
                <v-layout row justify-center>
                  <profile-image-uploader 
                    :width ="profileImageWidth"
                    :height="profileImageHeight"
                    :mode="profileImageMode"
                    :value="profileImageUrl"
                    @removeProfileImage="removeProfileImage"
                    @createProfileImage="createProfileImage"
                  ></profile-image-uploader>
                </v-layout>
                <v-layout row justify-center>
                  <v-flex xs11>
                    <v-btn block color="dahong" dark @click="changeImageMode" v-if="profileImageMode=='read'">プロフィール修正</v-btn>
                    <v-btn block color="success" @click="updateProfileImage" v-else>セーブ</v-btn>
                  </v-flex>
                </v-layout>              
              </v-flex>

              <!-- 내용 -->
              <v-flex xs12 md9 lg9 style="margin-top:1px;">
                <v-layout row style="border : 0.5px solid #BDBDBD">
                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.name.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_profile.name == null ? '-' : student_profile.name}}
                    </v-flex>

                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.name_eng.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_profile.name_eng == null ? '-' : student_profile.name_eng}}
                    </v-flex>

                    <!-- <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.name_kanji.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_profile.name_kanji == null ? '-' : student_profile.name_kanji}}
                    </v-flex> -->
                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.name_kana.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_profile.name_kana == null ? '-' : student_profile.name_kana}}
                    </v-flex>

                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.birth_date.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_profile.birth_date + ' ・ ' + age }}
                    </v-flex>

                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.country_code.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_profile.country_code == null ? '-' : student_profile.country_code}}
                    </v-flex>
                    
                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.gender.label}} ・ 軍隊</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{ student_profile.gender == 'w' ? '女' : '男'}}
                        {{ student_profile.gender　== 'w' ?　"" : student_profile.army_ox== 'o' ? ' ・ 済' : student_profile.army_ox == 'x' ? ' ・ 免除' : ' ・ 対象外'}}
                    </v-flex>

                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.admission_date.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_profile.admission_date == null ? '-' : student_profile.admission_date}}
                    </v-flex>
                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.graduate_date.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_profile.graduate_date == null ? '-' : student_profile.graduate_date}}
                    </v-flex>

                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.phone.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_profile.phone == null ? '-' : student_profile.phone}}
                    </v-flex>
                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.sub_phone.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_profile.sub_phone == null ? '-' : student_profile.sub_phone}}
                    </v-flex>
                    
                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.email.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg10 id="tableItem">
                        {{student_profile.email == null ? '-' : student_profile.email}}
                    </v-flex>
                    
                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.address.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg10 id="tableItem">
                        {{student_profile.address == null ? '-' : student_profile.address}}
                    </v-flex>

                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.github_url.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg10 id="tableItem">
                        {{student_profile.github_url == null ? '-' : student_profile.github_url}}
                    </v-flex>
                    
                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_affiliation_label.professor_name}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_affiliation.professor_name}}
                    </v-flex>

                    <v-flex xs4 lg2 id="tableTitle">
                      <b>{{student_profile_label.credit.label}}</b>
                    </v-flex>
                    <v-flex xs8 lg4 id="tableItem">
                        {{student_profile.credit == null ? '-' : student_profile.credit}}
                    </v-flex>
                    
                    <v-flex xs4 lg2 id="tableTitle">
                      <b>所属</b>
                    </v-flex>
                    <v-flex xs8 lg10 id="tableItem">
                        {{student_affiliation.org_name + " " + student_affiliation.department_name + " " + student_affiliation.major_name + " " + student_affiliation.class_name}}
                    </v-flex>
                
                </v-layout>
              </v-flex>
            </v-layout>
          </v-container>
    </v-flex>

    <!-- 소속 정보 -->
    <!-- <v-flex xs12>
        <p class="resultfont" style="margin: 10px 15px 0px 15px; text-align: left">所属情報</p>
        <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;">
        </div>
        <v-container grid-list-xl fluid>
          <v-layout row border>
            <v-flex v-for="(item,key) in student_affiliation_label" :key="key">
              <v-layout row >
                <v-flex xs12 style="background: #E0E0E0; border: 1px solid #BDBDBD;">
                  {{item}}
                </v-flex>
              </v-layout>
              <v-layout row border>
                <v-flex xs12>
                  {{student_affiliation[key]}}
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-container>
    </v-flex> -->

    <!-- 졸업 프로젝트 -->  
    <v-flex xs12 v-show="student_affiliation.group_num != 0">
        <v-layout row wrap  style="margin: 20px 15px 0px 15px; text-align: left">
          <v-flex xs10>
            <p class="resultfont">卒業プロジェクト
            </p>
          </v-flex>
          <v-flex xs1 style="margin-top: 10px;" hidden-sm-and-down>
          </v-flex>
          <v-flex style="margin-top: 10px;">
            <v-checkbox
              style="float:right;"
              label="あり"
              v-model="groupProjectBool"
              >
            </v-checkbox>
          </v-flex>
        </v-layout>
        
        <div style="border-bottom: 3px solid #008080; margin: -20px 15px 0px 15px;">
        </div>
        <!-- <v-card-title class="grey lighten-4 py-4 display-1">
          <b>卒業プロジェクト</b>
        </v-card-title> -->
        <v-container grid-list-xl fluid>
          <v-layout row>
            <!-- <v-flex xs4>
              <v-checkbox
              label="あり"
              v-model="groupProjectBool"
              >
              </v-checkbox>
            </v-flex> -->
            <v-flex xs4>
              <v-select
              v-if="group_list != null && groupProjectBool"
              label="グループを選択してください。"
              :items="group_list"
              item-text="group_num"
              item-value="group_id"
              v-model="student_affiliation.group_id"
              >
              </v-select>
            </v-flex>
            <v-flex xs4 v-if="groupProjectBool" @click="updateGroupId">
              <v-btn color="success">確認</v-btn>
            </v-flex>
          </v-layout>

          <v-layout row v-if="groupProjectBool">
            <v-flex xs4 md2 lg2 style="background: #E0E0E0; border: 1px solid #BDBDBD;">
              <v-layout row><v-flex xs12 border>所属グループ</v-flex></v-layout>
            </v-flex>
            <v-flex xs8 md4 lg4 style="background: #E0E0E0; border: 1px solid #BDBDBD;">
               <v-layout row><v-flex xs12 border>グループ名</v-flex></v-layout>
            </v-flex> 
            <v-flex xs12 md6 lg6 style="background: #E0E0E0; border: 1px solid #BDBDBD;">
              <v-layout row><v-flex xs12 border>プロジェクト説明</v-flex></v-layout>
            </v-flex>
          </v-layout>

          <v-layout row v-if="groupProjectBool">
            <v-flex xs4 md2 lg2 border>
              {{student_affiliation.group_num}}
            </v-flex>
            <v-flex xs8 md4 lg4 border>
              {{student_affiliation.group_name}}
            </v-flex>
            <v-flex xs12 md6 lg6 border>
              <h5><b>{{student_affiliation.project_title + ' ('+ student_affiliation.showcase_year +')'}}</b></h5>
              {{student_affiliation.project_content}}
            </v-flex>
          </v-layout>
        </v-container>
        
        <v-container v-if="groupProjectBool" fluid>
            <v-card>
              <v-layout row wrap>
                <v-flex xs10>
                  <p class="Titlefont" style="margin: 10px 15px 0px 13px; text-align: left">プロジェクトで自分が扱った部分</p>
                </v-flex>
                <v-flex xs2>
                  <button right type="button" @click="openGroupDialog" style="float:right; margin:14px 15px 0px 0px">
                    <img src="images/common/edit.png" width="80%" height="80%"> 
                  </button>
                </v-flex>
              </v-layout>

              <!-- <p class="Titlefont" style="margin: 10px 15px 0px 15px;">プロジェクトで自分が扱った部分
              <button right type="button" @click="openGroupDialog">
                <img src="images/common/edit.png" width="80%" height="80%"> 
              </button></p> -->
              <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;">
              </div>
              <!-- <p class="grey lighten-4 py-3 title">
                プロジェクトで自分が扱った部分
              </p> -->
              <v-card-text>
                <v-flex class="grey" style="min-height:150px">
                  <p class="text-left">{{student_profile.group_part_content}}</p>
                </v-flex>
              </v-card-text>
              <!-- <v-card-actions>
                <v-spacer></v-spacer>
                  <v-btn color="dahong" dark style="margin-right:10px" @click="openGroupDialog">修正</v-btn>
              </v-card-actions> -->
          </v-card>
        </v-container>
    </v-flex>

    <!-- 기본 정보 모달 -->
    <v-dialog v-model="profileDialog" scrollable width="600px" persistent>
      <v-card>
        
        <v-card-title class="Titlefont grey lighten-4 py-4">
            基本情報
          <v-spacer></v-spacer>
          <v-btn large color="error" @click="profileDialog = false">X</v-btn>
        </v-card-title>

        <v-card-text>
          <v-container fluid grid-list-sm  class="pa-4">
            <v-layout row v-for="(item,key) in student_profile_label" :key="key">

              <v-flex xs12 v-if="item.type == 'field'">
                <v-text-field
                  :label="item.label"
                  v-model="profile_edit_info[key]"
                  required
                >  
                </v-text-field> 
              </v-flex>

              <v-layout v-else-if="item.type == 'radio'">
                <v-flex xs2>
                  <v-subheader>{{item.label}}</v-subheader>
                </v-flex>

                <v-flex>
                  <v-radio-group row v-model="profile_edit_info[key]">
                    <v-flex xs4 v-for="(op,key) in item.options" :key="key">     
                      <v-radio
                        :label="op.label"
                        :value="op.value"
                      ></v-radio> 
                    </v-flex> 
                  </v-radio-group>
                </v-flex>
              </v-layout>
          
              <v-flex xs12 v-else-if="item.type == 'date'">
                <v-menu
                  :ref="key"
                  lazy
                  :close-on-content-click="false"
                  v-model="item.menu"
                  transition="scale-transition"
                  offset-y
                  full-width
                  :nudge-right="40"
                  min-width="290px"
                  :return-value.sync="datePickerValue[key]"
                >
                  <v-text-field
                    slot="activator"
                    :label="item.label"
                    v-model="datePickerValue[key]"
                    prepend-icon="event"
                    readonly
                  ></v-text-field>
                  <v-date-picker 
                    v-model="datePickerValue[key]" 
                    no-title 
                    scrollable
                    :max="key=='graduate_date'? null : new Date().toISOString().substr(0, 10)"
                    :type="key != 'birth_date'? 'month' : 'date'"
                    min="1950-01-01"
                  >
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="item.menu = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="setDate($refs, `${key}`,datePickerValue[key])">OK</v-btn>
                  </v-date-picker>
                </v-menu>
              </v-flex>
              <v-flex xs12 v-else>
                <v-select v-if="item.options"
                :label="item.label"
                :items="item.options"
                item-text="label"
                item-value="value"
                v-model="profile_edit_info[key]"
                >
                </v-select>
                <v-layout row v-else>
                  <v-flex xs2>
                    <v-subheader>国籍</v-subheader>
                  </v-flex>
                  <v-flex xs5>
                    <v-select
                      label="continent"
                      :items="continents"
                      item-text="title"
                      item-value="key"
                      v-model="curr_continent_code"
                    >
                    </v-select>
                  </v-flex>

                  <v-flex xs5>
                    <v-select
                      label="country"
                      :items="countryList[curr_continent_code]"
                      item-text="country_eng"
                      item-value="country_code"
                      v-model="profile_edit_info.country_code"
                    >
                    </v-select>
                  </v-flex>
                </v-layout>
              </v-flex>
              
              <v-flex xs12 v-if="key=='birth_date'">
                <v-text-field
                label="年齢"
                :value="ageEdit"
                disabled
                >
                </v-text-field>
                
              </v-flex>

            </v-layout>
          </v-container>
        </v-card-text>    
        <v-card-actions>
            <v-spacer></v-spacer>  
            <v-btn color="success" @click="updateProfileInfo">セーブ</v-btn>
            <v-btn color="error"   @click="profileDialog = false">取り消す</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- 졸업 프로젝트에서 담당한 파트 모달 -->
    <v-dialog persistent v-model="groupDialog" width="800px">
      <v-card>
        <v-card-title class="grey lighten-4 py-4 Titlefont">
            プロジェクトで自分が熱がった部分
            <v-spacer></v-spacer>
        <v-btn large color="error" @click="groupDialog = false">X</v-btn>
        </v-card-title>
        

        <v-container grid-list-sm class="pa-4" fluid>
          <v-layout row wrap>
           <v-flex xs12>
                <v-text-field
                  name="input-1"
                  label = "120字以内"
                  :rules="[(v) => v.length <= 120 || 'Max '+ 120 + ' characters']"
                  :counter="120"
                  textarea
                  v-model="group_edit_info"
                >
                </v-text-field>
            </v-flex>
          </v-layout>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>  
          <v-btn color="success" @click="updateGroup">セーブ</v-btn>
          <v-btn color="error"   @click="groupDialog = false">取り消す</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-layout>

</template>

<script>
import ContinentList from "../../static/dataProvide/ContinentList.js"
import ProfileImageUpload from '../../shared/ProfileImageUpload.vue'

export default {
  props : ['user'],

  components : {
    'profile-image-uploader' : ProfileImageUpload,
  },

  created : function() {

    //this.getProfileInfo();

    //소속 뽑기 + 학생 정보 뽑기
    this.getAffiliationInfo();

    this.getProfileInfo();

    //국가 코드
    this.axios.post('/continent/getCountries')
    .then(rep=>{
        this.countryList = rep.data;
        this.continents = ContinentList;
    })
    .catch(ex=>{
        console.log(ex);
    });
  },


  data : ()=>({

    status : null,
    finalCompanyInfo : null,
    student_code : null,
    age : null,

    profileDialog : false,
    groupDialog : false,

    datePickerValue : {
      birth_date : null,
      graduate_date : null,
      admission_date : '',
    },

    profileImage : {
      type : null,
      data : null,
    },
    profileImageWidth : 213,
    profileImageHeight : 286,
    profileImageMode : "read",
    profileImageUrl : null,

    student_profile : {},
    student_affiliation : {},

    profile_edit_info : {},
    group_edit_info : null,

    student_profile_label :{

      name :{
        label : '名前',
        type : 'field',
      },

      name_eng: {
        label : '名前（英語）',
        type : 'field',
      },

      name_kanji : {
        label : '名前（漢字）',
        type : 'field',
      },
      
      name_kana : {
        label : '名前（カナ）',
        type : 'field',
      },

      country_code : {
        label : '国籍',
        type : 'select',
      },

      birth_date : {
        label : '生年月日・年齢',
        type : 'date',
        menu : false,
      },

      gender : {
        label : '性別',
        type : 'select',
        options : [
          {label : '女', value : 'w'},
          {label : '男', value : 'm'}
        ]
      },

      army_ox : {
        label : '兵役',
        type : 'radio',
        options : [
          {label : '済', value : 'o'},
          {label : '免除　', value : 'x'},
          {label : '対象外', value : null}
        ]
      },
      
      admission_date : {
        label : '入学年度',
        type : 'date',
        menu : false,
      },

      graduate_date : {
        label : '卒業年度',
        type : 'date',
      },

      sub_phone : {
        label : '連絡先',
        type : 'field',
      },

      phone : {
        label : '携帯番号',
        type : 'field',
      },

      email : {
        label : 'メールアドレス',
        type : 'field',
      },

      address : {
        label : '住所',
        type : 'field',
      },

      credit : {
        label : '成績',
        type : 'field',
      },

      github_url : {
        label : 'github url',
        type : 'field'
      },

    },

    student_affiliation_label : {
      org_name : '学校',
      department_name : '学府',
      major_name : '学科・専攻',
      class_name : '専攻・クラス',
      professor_name : '担当教授',
    },

    group_list : null,
    groupProjectBool : false,


    //국가 코드 
    continents : [],
    countryList : {},
    curr_continent_code : null,

       notices : [
      {'label' :'志願' ,
       'key': "apply",
       'value' :6
       },
       {'label' :'面接中' ,
       'key': "inteview",
       'value' :5
       },
        {'label' :'内定数' ,
       'key': "acceptance",
       'value' :0
       }
    ],


  }),

  methods : {

    //사진 함수
    createProfileImage(type , data){
      console.log('');
      this.profileImage.type = type;
      this.profileImage.data = data;
    },

    removeProfileImage(){
      console.log('プロフィール写真削除');
      this.profileImage.type = null;
      this.profileImage.data = null;
    },

    changeImageMode(){
      alert('プロフィール写真修正.');
      this.profileImageMode = "update";
    },

    //
    getProfileInfo(){
      let login_id = this.user.login_id;
      let classification = this.user.classification;
      this.axios.post('/student/getProfileInfo',{login_id, classification})
      .then(rep=>{
          this.student_profile = null;
          this.student_profile = rep.data.student_profile;
          this.profileImageUrl = this.student_profile.profile_photo_url;
          this.finalCompanyInfo = rep.data.finalCompanyInfo;

          this.status = this.finalCompanyInfo == null? '就活中' : '就活終了';
          this.student_code = this.student_affiliation.country_code + this.student_affiliation.college_alias + this.student_profile.employ_year.substring(2) + this.student_profile.student_code;
          let birthYear = this.student_profile['birth_date'].split('-')[0];
          let thisYear = new Date().getFullYear();
          this.age =  Number(thisYear) - Number(birthYear);
          this.setEditInfo();
      })
      .catch(ex=>{
        console.log(ex);
      })
    },

    getAffiliationInfo(){
      let login_id = this.user.login_id;
      let classification = this.user.classification;
      this.axios.post('/student/getAffiliationInfo',{login_id, classification})
      .then(rep=>{
          this.student_affiliation = null;
          this.student_affiliation = rep.data.affiliation_info;
          if(this.student_affiliation.group_num != null)
            this.groupProjectBool = true;
          else
            this.groupProjectBool = false;

          this.group_list = rep.data.group_list;
          //this.getProfileInfo();
      })
      .catch(ex=>{
        console.log(ex);
      })
    },


    setEditInfo(){

      for( let key in this.student_profile_label){
          if(key != 'birth_date'&& key != 'admission_date'&& key != 'graduate_date')
            this.profile_edit_info[key] = this.student_profile[key];
      } 
      
      this.datePickerValue.birth_date = this.student_profile.birth_date;
      this.datePickerValue.graduate_date = this.student_profile.graduate_date;
      this.datePickerValue.admission_date = this.student_profile.admission_date;


    //학생 대륙, 국가 설정
      this.curr_continent_code = this.student_profile.continent;

    },


    setDate(where, key, value){
      where[key][0].save(value);
    },

    openProfileDialog(){
      this.setEditInfo();
      this.profileDialog = true;
    },

    openGroupDialog(){
      this.group_edit_info = this.student_profile.group_part_content;
      this.groupDialog = true;
    },

    updateProfileInfo(){

      let login_id = this.user.login_id;
      let profile_edit_info = this.profile_edit_info;
      profile_edit_info.birth_date = this.datePickerValue.birth_date;
      profile_edit_info.admission_date = new Date(this.datePickerValue.admission_date);
      profile_edit_info.graduate_date = new Date(this.datePickerValue.graduate_date);
     

      this.axios.post('/student/updateProfileInfo',{login_id, profile_edit_info})
      .then(rep=>{
          let returnBool =  rep.data.returnBool;
          if(returnBool){
            alert('修正されました。');
            this.getProfileInfo();
          }else{
            alert("前と変わった事項がありません。");
          }       

          this.profileDialog = false;
      })
      .catch(ex=>{
        console.log(ex);
      });

    },

    updateGroup(){

      let login_id = this.user.login_id;
      let profile_edit_info = { group_part_content : this.group_edit_info };

      this.axios.post('/student/updateProfileInfo',{login_id, profile_edit_info})
        .then(rep=>{
            let returnBool =  rep.data.returnBool;
            if(returnBool){
              alert('修正されました。');
              this.getProfileInfo();
            }else{
              alert("前と変わった事項がありません。");
            }    
            this.groupDialog = false;
        })
        .catch(ex=>{
          console.log(ex);
        });
    },

    updateProfileImage(){
      let login_id = this.user.login_id;
      let profileImage = this.profileImage;
      let photo_url = this.profileImageUrl;

      alert("プロフィール写真を登録します。");
      this.axios.post('/student/updateProfileImage',{login_id, profileImage, photo_url})
      .then(rep=>{
        if(rep.data.returnBool){
          alert('変更されました。');    

          this.profileImageMode = 'read';
          this.profileImageUrl = null;
        }else
          alert('写真を選んでください。');
        
      })
      .catch(ex=>{
        console.log(ex);
      });
    },

    updateGroupId(){

      let login_id = this.user.login_id;
      let profile_edit_info = { group_id : this.student_affiliation.group_id };

      this.axios.post('/student/updateProfileInfo',{login_id, profile_edit_info})
        .then(rep=>{
            let returnBool =  rep.data.returnBool;
            if(returnBool){
              alert('変更されました。');
              this.getAffiliationInfo();
            }else{
              alert("前と変わった事項がありません。");
            }    
            this.groupDialog = false;
        })
        .catch(ex=>{
          console.log(ex);
        });
    }

   
  },

  computed : {
    ageEdit : function(){
      if(this.datePickerValue.birth_date == null)
        return null;
      else{
        let birthYear = this.datePickerValue.birth_date.split('-')[0];
        let thisYear = new Date().getFullYear();

        return Number(thisYear) - Number(birthYear);
      }
    },
  },

  // watch : { 
  //   profileDialog(val){
  //     val || this.closeDialog();
  //   }
  // }

}
</script>
<style>
.tedoori {
  border: 0.5px gray solid;
}
</style>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>