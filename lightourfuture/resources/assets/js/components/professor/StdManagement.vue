
<template>
  <v-container fluid style="height:100%">
    <div class="bodySt">
      <div class="leftDiv" style="height:60em">
        <div class="titleGride borderSt">
          <div style="border-right:1px solid black">
            <v-chip small outline disabled  color="green">{{$t('Professor_stdManagement.download')}}</v-chip>
            <button class="dropbtn" @click="downloadExcel()">{{$t('Professor_stdManagement.std_info')}}</button>
            <div class="dropdown">
              <button class="dropbtn">{{$t('Professor_stdManagement.resume')}}</button>
              <div class="dropdown-content">
                <a @click="download_Resume_Portfolio('Resume','total')">{{$t('Professor_stdManagement.std_total')}}</a>
                <a @click="downloadDialog=true">{{$t('Professor_stdManagement.seleted_company')}}</a>
              </div>
            </div>   
            <div class="dropdown">
              <button class="dropbtn">{{$t('Professor_stdManagement.portfolio')}}</button>
              <div class="dropdown-content">
                <a @click="download_Resume_Portfolio('Portfolio','total')">{{$t('Professor_stdManagement.std_total')}}</a>
                <a @click="downloadDialog=true">{{$t('Professor_stdManagement.seleted_company')}}</a>
              </div>
            </div>
          </div>

          <div class="typeBorder">
            <v-chip outline disabled small color="green">{{$t('Professor_stdManagement.manage')}}</v-chip>
            <button class="dropbtn" @click="deleteStd(selectedStd)">{{$t('Professor_stdManagement.std_delete')}}</button>
            <div class="dropdown">
                <button class="dropbtn">{{$t('Professor_stdManagement.std_save')}}</button>
                <div class="dropdown-content">
                    <form ref="fileform" for="file_input">
                    <label for="file_input"><v-icon>unarchive</v-icon></label>                        
                        <input v-show="false" accept=".xlsx" id="file_input" type="file" @change="onFileChange">
                    </form>
                    <a @click="addstd=true">{{$t('Professor_stdManagement.stdOne')}}</a>
                </div>
            </div>
            <button class="dropbtn" @click="addGroupModal=true, callGroupList()">{{$t('Professor_stdManagement.group_save')}}</button>
          </div>

          <!-- 이력서, 포트폴리오 다운 모달 -->
          <!-- 이력서,포트폴리오 -> 회사별 다운로드 클릭 시 모달 창 -->
          <v-dialog v-model="downloadDialog" persistent max-width="50%">
            <v-card>
                <v-card-title class="headline">{{$t('Professor_stdManagement.byCompany')}}</v-card-title>
                <v-card-text style="text-align:center">
                <v-layout row>
                  <v-flex xs2 sm2 md2 lg2></v-flex>
                  <v-flex xs8 sm8 md8 lg8>
                    <v-select
                      :items="company"
                      item-value="id"
                      v-model="selectedCompany"
                      :label="$t('Professor_stdManagement.comName')"
                      class="input-group--focused"
                    >
                    </v-select>
                  </v-flex>
                </v-layout>
                <v-layout row>
                    <v-flex xs2 sm2 md2 lg2></v-flex>
                    <v-flex xs8 sm8 md8 lg8>
                        <v-select
                        :items="recruitment"
                        v-model="selectedRecruitment"
                        :label="$t('Professor_stdManagement.recruitment')"
                        class="input-group--focused"
                        item-value="value"
                        >
                        </v-select>
                    </v-flex>
                </v-layout>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" flat="flat" @click.native="downloadDialog = false">{{$t('Professor_stdManagement.cancel')}}</v-btn>
                <v-btn v-if="selectedType == '이력서'" color="green darken-1" flat="flat" @click.native="downloadDialog = false, download_Resume_Portfolio('Resume','select')">download</v-btn>
                <v-btn v-else color="green darken-1" flat="flat" @click.native="downloadDialog = false, download_Resume_Portfolio('Portfolio','select')">download</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          
          <!-- --------------------------------- -------------학생 등록--------------------------------------------------------------------------- -->
          <!-- 학생추가 모달 창 -->
          <v-dialog v-model="addstd" max-width="290" persistent>
            <v-card>
              <v-card-title class="headline">{{$t('Professor_stdManagement.std_save')}}</v-card-title>
                <v-card-text>
                  <v-flex xs12 sm12 md12 lg12>
                    <div style="margin-bottom:2%">
                      <v-flex xs12 sm12 md12 lg12 style="margin-left:2%; margin-right:2%">
                        <v-text-field
                            v-model="add.stdName"
                            :rules="[rules.required]"
                            :label="$t('Professor_stdManagement.name')"
                        ></v-text-field>
                    </v-flex>
                     <v-flex xs12 sm12 md12 lg12 style="margin-left:2%; margin-right:2%">
                         <v-text-field
                            v-model="add.stdEmail"
                            :rules="[rules.required, rules.email]"
                            :label="$t('Professor_stdManagement.email')"
                        ></v-text-field>
                    </v-flex>
                    <v-layout row style="padding-left:3%">
                      <v-flex xs3 sm3 md3 lg3 style="margin-left:2%">
                        <v-text-field
                            v-model="add.stdBirthY"
                            :rules="[rules.required]"
                            :label="$t('Professor_stdManagement.birthY')"
                        ></v-text-field>
                      </v-flex>
                     
                      <v-flex xs3 sm3 md3 lg3 style="margin-left:3%">
                        <v-text-field
                            v-model="add.stdBirthM"
                            :rules="[rules.required]"
                            :label="$t('Professor_stdManagement.birthM')"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs3 sm3 md3 lg3 style="margin-left:3%; margin-right:2%">
                        <v-text-field
                            v-model="add.stdBirthN"
                            :rules="[rules.required]"
                            :label="$t('Professor_stdManagement.birthD')"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md12 lg12 style="margin-left:2%; margin-right:5%">
                        <v-select
                          :items="selectYear"
                          :label="$t('Professor_stdManagement.employment')"
                          v-model="add.yearOfemployment"
                        ></v-select>
                      </v-flex>
                    </v-layout>
                    <div style="text-align: right">
                      <v-btn 
                        color="green darken-1" 
                        flat="flat" 
                        @click.native="addstd = false"
                      >
                        {{$t('Professor_stdManagement.cancel')}}
                      </v-btn>
                      <v-btn 
                        small 
                        color="success" 
                        style="padding : 0;" 
                        @click="addStd_CheckNull(), 
                        addstd=false"
                      >
                        {{$t('Professor_stdManagement.add_std')}}
                      </v-btn>
                    </div> 
                  </div>
                </v-flex>
              </v-card-text>
            </v-card>
          </v-dialog>

          <!-- 학생 추가 시 모달 창으로 추가 할 학생을 한번 더 확인 후 디비로 값 전달 -->
          <v-dialog v-model="checkAddStudent" max-width="600" persistent>
            <v-card>
              <!-- Dialog Titles -->
              <v-card-title class="justify-center">
                <p class="headline"> <v-icon color="green darken-1"> add_alert </v-icon>&nbsp;{{$t('Professor_stdManagement.add_std')}}</p>
              </v-card-title>
              <!-- Message -->
              <v-card-text class="center">
                <p class="subheading" > <v-icon color="error">question_answer</v-icon> {{add.stdBirthY}}.{{add.stdBirthM}}.{{add.stdBirthN}}{{add.stdName}}{{$t('Professor_stdManagement.checkAddStd')}}</p>
              </v-card-text>
              <v-card-actions class="justify-center">
                <v-spacer></v-spacer>
                <v-btn color="red darken-3" flat="flat" @click.native="checkAddStudent = false">
                <v-icon dark right>clear</v-icon>&nbsp; Disagree
                </v-btn>
                <!-- request button -->
                <v-btn color="green darken-1" flat="flat" @click="addStd()" @click.native="checkAddStudent = false">
                <v-icon> add </v-icon>&nbsp;Save
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
         
          <!-- -----------------------------------------------조 추가, 수정, 삭제------------------------------------------------------------------ -->
          <!-- 조 등록 모달 -->
           <v-dialog v-model="addGroupModal" max-width="800" persistent>
            <v-card>
              <v-card-title class="headline">{{$t('Professor_stdManagement.add_group')}}</v-card-title>
              <v-card-text>
                <v-layout row>
                  <v-flex xs5 sm5 md5 lg5 style="margin-left:2%; margin-right:5%">
                    <b-form-group label="Num:">
                      <b-form-input
                        v-model="addGroupInfo.num"
                        v-validate="'required'"
                      ></b-form-input>
                    </b-form-group>
                  </v-flex>

                  <v-flex xs5 sm5 md5 lg5 style="margin-left:2%; margin-right:5%">
                    <b-form-group :label="$t('Professor_stdManagement.name')">
                      <b-form-input
                        type="text"
                        v-model="addGroupInfo.name"
                        v-validate="'required'">                            
                      </b-form-input>
                    </b-form-group>
                  </v-flex>

                  <v-flex xs12 sm12 md12 lg12 style="margin-left:2%; margin-right:7%">
                    <b-form-group :label="$t('Professor_stdManagement.explain')">
                      <b-form-input
                        type="text"
                        v-model="addGroupInfo.explain"
                        v-validate="'required'">                            
                      </b-form-input>
                    </b-form-group>

                    <b-form-group :label="$t('Professor_stdManagement.email')">
                      <b-form-input
                        type="text"
                        v-model="addGroupInfo.link"
                        v-validate="'required'">                            
                      </b-form-input>
                    </b-form-group>

                    <div style="text-align:right">
                      <v-btn color="green darken-1" flat="flat" @click.native="addGroupModal = false">{{$t('Professor_stdManagement.cancel')}}</v-btn>
                      <v-btn small color="success" style="padding : 0;" @click="addGroup_CheckNull()">{{$t('Professor_stdManagement.add_group')}}</v-btn>
                    </div> 

                    <!-- 추가한 조의 정보 -->
                    <b-table 
                      :items="groupList"
                      :fields="groupFields"
                    >

                      <template slot="num"  slot-scope="data">{{data.item.group_num}}</template>

                      <!-- 조 열람 -->

                      <!-- 조 수정 -->
                      <template slot="name" slot-scope="data">
                        <b-form-input v-model="data.item.group_name" type="text" v-if="groupChange"/>
                        <p v-else>{{data.item.group_name}}</p>
                      </template>

                      <template slot="info" slot-scope="data">
                        <b-form-input v-model="data.item.project_content" type="text" v-if="groupChange"/>
                        <p v-else>{{data.item.project_content}}</p>
                      </template>

                      <template slot="video_url" slot-scope="data">
                        <b-form-input v-model="data.item.project_video_url" type="text" v-if="groupChange"/>
                        <p v-else>{{data.item.project_video_url}}</p>
                      </template>

                      <template slot="delete" slot-scope="data">
                        <v-icon color="red" @click="deleteGroup(data.item)">delete</v-icon>
                      </template>
                    </b-table>
                    <div style="text-align:right">
                      <v-btn color="green darken-1" flat="flat" @click.native="addGroupModal = false">{{$t('Professor_stdManagement.cancel')}}</v-btn>
                      <v-btn small color="success" style="padding : 0;" v-if="groupChange" @click="changeGroup(groupList)">{{$t('Professor_stdManagement.save')}}</v-btn>
                      <v-btn small color="success" style="padding : 0;" v-else @click="groupChange=true">{{$t('Professor_stdManagement.change')}}</v-btn>
                    </div>
                </v-flex>


                <!-- 그룹 추가 시 조 중복 확인 / 조 추가 확인 / 학생 삭제 확인 / 학생 선택 경고창 -->
                <v-dialog v-model="alertView" max-width="600" persistent>
                  <v-card>
                    <v-card-title class="justify-center">
                        <p class="headline"> <v-icon color="green darken-1"> done </v-icon>&nbsp; {{$t('Professor_stdManagement.check')}} </p>
                    </v-card-title>

                    <v-card-text class="center" v-if="overlabCheckResult">
                        <p class="subheading" >{{$t('Professor_stdManagement.check_AddGroup')}}</p>
                    </v-card-text>

                     <v-card-text class="center" v-else-if="seletedStd">
                        <p class="subheading" >{{$t('Professor_stdManagement.check_cancelGroup')}}</p>
                    </v-card-text>

                    <v-card-text class="center" v-else>
                        <p class="subheading" >  {{$t('Professor_stdManagement.check_overfGroup')}} </p>
                    </v-card-text>
                    <v-card-actions class="justify-center">
                      <v-spacer></v-spacer>
                      <v-btn color="red darken-3" flat="flat" @click.native="alertView = false">
                           {{$t('Professor_stdManagement.check')}}
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-layout>
            </v-card-text>
          </v-card>
        </v-dialog>
        </div>

        <!-- 학생리스트 -->
        <div class="borderSt" style="overflow-y:scroll; max-height:60em">
            <v-chip outline style="margin:1%" label color='teal'>
                <v-icon left>face</v-icon>{{studentList.length}}{{$t('Professor_stdManagement.people')}}
            </v-chip>
            <v-layout row style="width:100%; padding-left:3%">
                <v-flex xs6 md4 lg3 lg2 v-for="student in studentList" :key="student.login_id"  style="padding:1%">
                    <v-card :hover="true">
                    <v-card-media height="200px" :src="student.profile_photo_url ? student.profile_photo_url : '/images/common/p.png'" @click="stdInfo(student)"/>
                    <div style="text-align:center; padding-left:7%">
                        <v-layout row>
                        <v-flex xs2 style="padding-left:5%">
                            <v-checkbox
                            v-model="selectedStd"
                            color="success"
                            :value="student"
                            hide-details
                            ></v-checkbox>
                        </v-flex>
                        <!-- 이름 출력 -->
                        <v-flex xs8>
                            {{student.name}}({{student.age}})
                        </v-flex>
                        </v-layout>
                        <v-chip outline small color="red"  v-if="student.major_grade == null">{{$t('Professor_stdManagement.major')}}-</v-chip>
                        <v-chip outline small color="red"  v-else>{{$t('Professor_stdManagement.major')}}{{student.major_grade}}</v-chip>
                        <v-chip outline small color="blue" v-if="student.japanese_speaking_level == null">{{$t('Professor_stdManagement.conversational')}}-</v-chip>
                        <v-chip outline small color="blue" v-else>{{$t('Professor_stdManagement.conversational')}}{{student.japanese_speaking_level}}</v-chip>
                        <v-chip outline small color="green" v-if="student.sincerity_grade == null">{{$t('Professor_stdManagement.faithfulness')}}-</v-chip>
                        <v-chip outline small color="green" v-else>{{$t('Professor_stdManagement.faithfulness')}}{{student.sincerity_grade}}</v-chip>
                        <v-chip outline small color="blue" v-if="student.personality_grade == null">{{$t('Professor_stdManagement.character')}}-</v-chip>
                        <v-chip outline small color="blue" v-else>{{$t('Professor_stdManagement.character')}}{{student.personality_grade}}</v-chip>
                    </div>
                    </v-card>
                </v-flex>
            </v-layout>
        </div>
    </div>
    <div class="borderSt">
      <v-flex xs12 border>
        <v-tabs fixed-tabs>
          <v-tab v-for="n in tabsItems" :key="n">
            {{ n }}
          </v-tab>

          <!-- 학생 프로필 -->
          <v-tab-item>
            <div class="titleGride" style="height:55em;" v-if="studentInfo==''">
                <div style="padding-left:10%; padding-top:30%;">
                    <v-icon size="150px" left>sentiment_satisfied</v-icon>
                    <h1>{{$t('Professor_stdManagement.join_std')}}</h1>
                    <p style="font-size:70px">{{join.o}}</p>
                </div>
                <div style="padding-top:30%; padding-right:13%;">
                    <v-icon size="150px" left>sentiment_dissatisfied</v-icon>
                      <h1>{{$t('Professor_stdManagement.notJoin_std')}}</h1>
                      <p style="font-size:70px; color:red">{{join.x.length}}</p>
                </div>
            </div>

            <div v-else>
                <v-chip outline color="black">
                    <v-icon left size="30px">directions_walk</v-icon>{{studentInfo[0].name}}
                </v-chip>
                <v-layout>
                    <v-flex xs6 md6 lg6>
                        <template>
                            <b-table style="border-right:1px solid #e5e5e5" stacked :fields="stdInfoFields1" :items="studentInfo"/>
                        </template>
                    </v-flex>
                    <v-flex  xs6 md6 lg6>
                        <template>
                            <b-table stacked :fields="stdInfoFields2" :items="studentInfo"/>
                        </template>
                    </v-flex>
                </v-layout>
              <template>
                <v-layout style="border-bottom:1px solid #e5e5e5">
                    <v-flex  xs6 md6 lg6>
                        <b-table style="border-right:1px solid #e5e5e5" stacked :fields="evaluateFields1" :items="studentInfo">
                            <template slot="major_grade" slot-scope="data">
                                <b-form-select v-model="data.item.major_grade" :options="options" class="mb-3" v-if="stdChange"/>
                                <p v-else>{{data.item.major_grade}}</p>
                            </template>
                            <template slot="japanese_speaking_level" slot-scope="data">
                                <b-form-select v-model="data.item.japanese_speaking_level" :options="options" class="mb-3" v-if="stdChange"/>
                                <p v-else>{{data.item.japanese_speaking_level}}</p>
                            </template>
                        </b-table>
                    </v-flex>
                    <v-flex  xs6 md6 lg6>
                        <b-table stacked :fields="evaluateFields2" :items="studentInfo">
                            <template slot="sincerity_grade" slot-scope="data">
                                <b-form-select v-model="data.item.sincerity_grade" :options="options" class="mb-3" v-if="stdChange"/>
                                <p v-else>{{data.item.sincerity_grade}}</p>
                            </template>
                            <template slot="personality_grade" slot-scope="data">
                                <b-form-select v-model="data.item.personality_grade" :options="options" class="mb-3" v-if="stdChange"/>
                                <p v-else>{{data.item.personality_grade}}</p>
                            </template>
                        </b-table>
                    </v-flex>
                </v-layout>
                <div style="text-align:right">
                    <v-btn small color="success" @click="stdChange=false, cancelAddStdSkill()">{{$t('Professor_stdManagement.cancel')}}</v-btn>
                    <v-btn small color="success" v-if="stdChange" @click="AddStdSkill(studentInfo), stdChange=false">{{$t('Professor_stdManagement.add')}}</v-btn>
                    <v-btn small color="success" v-else @click="stdChange=true">{{$t('Professor_stdManagement.change')}}</v-btn>
                </div> 
              </template>

              <template>
                <b-table :fields="StdGroupFields" :items="studentInfo"/>
              </template>
            </div>
          </v-tab-item>

        <!-- 이력서 -->
        <v-tab-item>
            <v-card flat>
                <v-card-text>
                    <v-card-media>
                        <embed :src="studentInfo.personal_history" type="application/pdf" width="100%" height="600px">
                    </v-card-media>
                </v-card-text>
            </v-card>
        </v-tab-item>

        <!-- 포트폴리오 -->
        <v-tab-item>
            <v-card flat>
                <v-card-text>
                    <v-card-media>
                        <embed :src="studentInfo.portfolio_link" type="application/pdf" width="100%" height="600px">
                    </v-card-media>
                </v-card-text>
            </v-card>
        </v-tab-item>

        <!-- PR영상 -->
        <v-tab-item>
            <v-card flat>
                <v-card-media height="580px">
                    <iframe :src="studentInfo.project_video_url" frameborder="0" width="100%" allow="autoplay; encrypted-media"></iframe>
                </v-card-media>
            </v-card>
        </v-tab-item>

        </v-tabs>
      </v-flex>
    </div>
  </div>
</v-container>
</template>

<style scoped lang="css" src="../../static/css/Professor/StdManagement.css"></style>

<script>
export default {
  created: function() {
    this.nowYear();
    this.callFacultyId();
    this.companyList();
    this.calljoinStd();

    this.multilingual();
  },

  data: () => ({
    //array
    studentList: [], //학생리스트
    studentInfo: [], //학생정보
    selectedStd: [], //선택한 학생정보
    groupList: [], //조 list
    backupStdInfo: [],

    //select list
    recruitment: [], //채용건
    company: [], //회사
    selectYear: [], //Selection value of employment year when team is added

    //dialog
    downloadDialog: false,
    downloadDialog: false,

    overlabCheckResult: false,
    checkAddStudent: false,
    addGroupModal: false,
    alertView: false,
    seletedStd: false,
    groupChange: false,
    stdChange: false,
    addstd: false,

    student_login_id: null,

    selectedCompany: "",
    selectedRecruitment: "",
    selectedType: "",
    facultyId: "",
    thisyear: "",
    src: "", //수정파일 업로드에서 사용.
    join: {
      o: "",
      x: []
    },

    addGroupInfo: {
      //Add Team information
      num: "",
      name: "",
      explain: "",
      link: ""
    },
    add: {},
    tabsItems: [],
    groupFields: [],

    StdGroupFields: [],

    stdInfoFields1: [
      { key: "JLPT", label: "jlpt", class: "text-center" },
      { key: "JPT", label: "jpt", class: "text-center" },
      { key: "email", label: "email", class: "text-center" }
    ],
    stdInfoFields2: [
      { key: "phone", label: "phone", class: "text-center" },
      { key: "birth_date", label: "birth", class: "text-center" },
      { key: "address", label: "address", class: "text-center" }
    ],
    evaluateFields1: [],
    evaluateFields2: [],
    options: [
      { value: "A", text: "A" },
      { value: "B", text: "B" },
      { value: "C", text: "C" }
    ],
    excelFile: {
      //Student information to download Excel file
      data: null,
      name: null,
      file_name: "excel"
    },

    rules: {
      required: value => !!value || "Required.",
      email: value => {
        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return pattern.test(value) || "Invalid e-mail.";
      }
    }
  }),

  methods: {
    //교수 전공 id
    callFacultyId: function() {
      let reqHttpAddr = "/Professor_callFacultyId";
      let sendData = {
        professorId: this.$store.getters.identification
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.facultyId = res.data[0].faculty_id;
          this.getStudentList(this.facultyId, this.thisyear);
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //학생등록 시 학생 졸업 연도 선택 --> 올해 년도를 찾는다.
    nowYear: function() {
      this.num = 1;

      //취업활동년도 받아오기 위한 현재 년도 찾기
      var year = new Date().getFullYear();
      this.thisyear = year;

      for (let i = year; i < year + 4; i++) {
        let castedYear = i.toString();
        this.selectYear.push({ text: castedYear, value: i });
      }
    },

    // 다국어
    multilingual: function() {
      //학생정보, 이력서, 포트폴리오, pr동영상 tab아이템
      this.tabsItems = [
        this.$i18n.t("Professor_stdManagement.profile"),
        this.$i18n.t("Professor_stdManagement.resume"),
        this.$i18n.t("Professor_stdManagement.portfolio"),
        this.$i18n.t("Professor_stdManagement.pr_movie")
      ];

      // 학생추가 : 값 미입력시 경고 창 메세지
      this.errormass = this.$i18n.t("Professor_stdManagement.err");

      // 추가한 조 정보 확인
      this.groupFields = [
        {
          key: "num",
          label: this.$i18n.t("Professor_stdManagement.group"),
          class: "text-center"
        },
        {
          key: "name",
          label: this.$i18n.t("Professor_stdManagement.group_name"),
          class: "text-center"
        },
        {
          key: "info",
          label: this.$i18n.t("Professor_stdManagement.group_explanation"),
          class: "text-center"
        },
        {
          key: "video_url",
          label: this.$i18n.t("Professor_stdManagement.project_video_url"),
          class: "text-center"
        },
        {
          key: "delete",
          label: this.$i18n.t("Professor_stdManagement.delete"),
          class: "text-center"
        }
      ];

      //학생 조 정보 확인
      this.StdGroupFields = [
        {
          key: "group_num",
          label: this.$i18n.t("Professor_stdManagement.group"),
          class: "text-center"
        },
        {
          key: "group_name",
          label: this.$i18n.t("Professor_stdManagement.group_name"),
          class: "text-center"
        },
        {
          key: "project_content",
          label: this.$i18n.t("Professor_stdManagement.group_explanation"),
          class: "text-center"
        },
        {
          key: "group_part_content",
          label: this.$i18n.t("Professor_stdManagement.part"),
          class: "text-center"
        }
      ];

      //학생 세부정보
      this.evaluateFields1 = [
        {
          key: "major_grade",
          label: this.$i18n.t("Professor_stdManagement.major_skills"),
          class: "text-center"
        },
        {
          key: "japanese_speaking_level",
          label: this.$i18n.t("Professor_stdManagement.conversational_skills"),
          class: "text-center"
        }
      ];
      this.evaluateFields2 = [
        {
          key: "sincerity_grade",
          label: this.$i18n.t("Professor_stdManagement.sincerity"),
          class: "text-center"
        },
        {
          key: "personality_grade",
          label: this.$i18n.t("Professor_stdManagement.tenacity"),
          class: "text-center"
        }
      ];
    },

    //회사 목록
    companyList: function() {
      let reqHttpAddr = "/Professor_company_list";
      let sendData = {
        professorId: this.$store.getters.identification
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          for (var i = 0; i < res.data.length; i++) {
            this.company.push({
              text: res.data[i].org_name,
              id: res.data[i].org_company_id
            });
          }
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //FIXME: 가입하지 않은 학생이 누군가, 가입한 학생은 몇명인가
    calljoinStd: function() {
      let reqHttpAddr = "/Professor_calljoinStd";
      let sendData = {
        professorId: this.$store.getters.identification
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          (this.join.o = res.data[0][0].join_std_count),
            (this.join.x = res.data[1]);
          console.log("asd");
          console.log(this.join.o);
          console.log(this.join.x);
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //학생 리스트 출력
    getStudentList: function(faculty_id, year) {
      //이름, 사진, 일본어회화, 전공실력, 인성, 성실도, 몇조인지, 조명,
      let reqHttpAddr = "/Professor_std_listup";
      let sendData = {
        professorId: this.$store.getters.identification,
        faculty_id: faculty_id,
        year: year
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          //[0] : 회원가입 완료, [1] : 회원가입 미완료
          this.studentList = res.data[0];

          for (let i = 0; i < res.data[1].length; i++) {
            this.studentList.push(res.data[1][i]);
          }
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //------------------------------------------ Upload excelfile -------------------------------------------------------------
    onFileChange: function(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createExcelFile(files[0]);
    },

    createExcelFile: function(file) {
      var reader = new FileReader();

      reader.onload = e => {
        let reqHttpAddr = "/import";
        let sendData = {
          professorId: this.$store.getters.identification,
          data: e.target.result,
          name: file.name,
          file_name: "excel"
        };
        this.axios
          .post(reqHttpAddr, sendData)
          .then(res => {
            this.src = res.data;
            this.getStudentList(this.facultyId, this.thisyear);
            //if(res.data) location.reload(); // 재시작
          })
          .catch(err => {
            console.log(err.message);
          });
      };
      reader.readAsDataURL(file);
    },

    //선택한 학생정보
    stdInfo: function(studentInfo) {
      this.student_login_id = studentInfo.login_id;
      this.callView();
      this.studentInfo = [];
      this.studentInfo.push(studentInfo);
      this.backupStdInfo = [];
      this.backupStdInfo.push(studentInfo);

      console.log("studentInfo.portfolio_link");
      console.log(studentInfo.portfolio_link);
    },

    //학생 스킬시트 작성 취소 button
    cancelAddStdSkill: function() {
      this.studentInfo = this.backupStdInfo;
    },

    //리스트에서 학생삭제
    deleteStd: function(argStd) {
      if (argStd.length == 0) {
        this.alertView = true;
        this.seletedStd = true;
      }

      let name = [];
      let std_id = [];
      let birth = [];

      for (let i = 0; i < argStd.length; i++) {
        name.push(argStd[i].name);
        if (argStd[i].login_id == null) {
          birth.push(argStd[i].birth_date);
          std_id.push("");
        } else {
          birth.push("");
          std_id.push(argStd[i].login_id);
        }
      }

      //교수ID, 생년월일, 이름
      let reqHttpAddr = "/Professor_std_delete";
      let sendData = {
        professorId: this.$store.getters.identification,
        std_name: name,
        std_id: std_id,
        std_birth: birth
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.getStudentList(this.facultyId, this.thisyear);
        })
        .catch(err => {
          console.log(err.message);
        });

      // 학생리스트 받아오는 function호출
    },

    callView: function() {
      // let reqHttpAddr = "/studentPortfolio_list";
      // let sendData = {
      //   std_id: this.student_login_id,
      //   type: std
      // };
      // this.axios
      //   .post(reqHttpAddr, sendData)
      //   .then(res => {
      //       console.log(res.data);
      //   })
      //   .catch(err => {
      //     console.log(err.message);
      //   });
      // let reqHttpAddr = "/studentPortfolio_list";
      // let sendData = {
      //                     std_id  : this.student_login_id,
      //                     type    : std
      //                 };
      // this.axios.post(reqHttpAddr, sendData).then(res => {
      //       console.log(res.data);
      // })
      // .catch(err => {
      //     console.log(err.message);
      // });
      // let reqHttpAddr = "/studentPRVideo";
      // let sendData = {
      //   std_id: this.student_login_id,
      //   type: std
      // };
      // this.axios
      //   .post(reqHttpAddr, sendData)
      //   .then(res => {
      //       console.log(res.data);
      //   })
      //   .catch(err => {
      //     console.log(err.message);
      //   });
    },

    // student information excelfile download
    downloadExcel: function() {
      let reqHttpAddr = "/export";
      let sendData = {
        professorId: this.$store.getters.identification //교수ID
      };

      this.axios
        .post(reqHttpAddr, sendData, { responseType: "blob" })
        .then(res => {
          const url = window.URL.createObjectURL(new Blob([res.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "file.csv");
          document.body.appendChild(link);
          link.click();
        })
        .catch(err => {
          console.log(err.message);
        });
      // this.studentList();
    },

    //원하는 기업에 지원한 학생들의 포트폴리오, 이력서를 한번에 다운한다.
    //FIXME: type : Resume/Portfolio로 변경, 전체학생의 이력서 포트폴리오 다운가능, 원하는기업, 채용분야에 따라서 이력서, 포트폴리오를 다운받을 수 있음 -> 채용분야 변수 추가
    download_Resume_Portfolio: function(type, scale) {
      let reqHttpAddr = "/studentViewResumePortfolio";
      let sendData = {};

      // 모든 학생의 이력서, 포트폴리오를 다운받고 싶을 경우
      if (scale == "total") {
        sendData = {
          professorId: this.$store.getters.identification, //교수ID
          type: type //다운로드 하고 싶은 이력서/포트폴리오
        };
      } else {
        //원하는 기업에 지원한 학생만 다운받고 싶을 경우
        sendData = {
          professorId: this.$store.getters.identification, //교수ID
          type: type, //다운로드 하고 싶은 이력서/포트폴리오
          selectedCompany: this.selectedCompany, //다운로드 하고 싶은 기업 companyid,
          selectedRecruitment: this.selectedRecruitment //다운로드 하고 싶은 채용건
        };
      }

      this.axios
        .post(reqHttpAddr, sendData, { responseType: "blob" })
        .then(res => {
          const url = window.URL.createObjectURL(new Blob([res.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "file.zip");
          document.body.appendChild(link);
          link.click();
          //파일다운로드
        })
        .catch(err => {
          console.log(err.message);
        });
    },
    // --------------------------------------------학생 추가 -------------------------------------------
    addStd_CheckNull: function() {
      if (
        !this.add.stdName ||
        !this.add.stdBirthY ||
        !this.add.stdBirthM ||
        !this.add.stdBirthN ||
        !this.add.yearOfemployment
      ) {
        this.$swal({
          type: "error",
          title: "Oops...",
          text: this.errormass
        });
        return;
      }

      this.checkAddStudent = true;
    },
    // Add Student
    addStd: function() {
      let reqHttpAddr = "/Professor_std_add";
      let sendData = {
        professorId: this.$store.getters.identification,
        stdName: this.add.stdName, //student Name
        stdNum: this.add.stdBirthY + this.add.stdBirthM + this.add.stdBirthN, //student Birth
        yearOfemployment: this.add.yearOfemployment, //student Year fo employment
        stdEmail: this.add.stdEmail
      };
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.getStudentList(this.facultyId, this.thisyear);
        })
        .catch(err => {
          console.log(err.message);
        });

      this.add = [];
      //return : true, false
    },
    // --------------------------------------------조 추가, 삭제, 수정  ----------------------------------------------------------------------------
    addGroup_CheckNull: function() {
      if (
        !this.addGroupInfo.num ||
        !this.addGroupInfo.name ||
        !this.addGroupInfo.explain ||
        !this.addGroupInfo.link
      ) {
        this.$swal({
          type: "error",
          title: "Oops...",
          text: "정보를 모두 입력하세요!!"
        });
        return;
      }
      this.addGroup();
    },

    //FIXME: Add Group this.addGroupInfo : 조 프로젝트 링크 정보 추가
    addGroup: function() {
      let splitUrl      = this.addGroupInfo.link.split('/');
      this.addGroupInfo.link = splitUrl[0] + "/" + splitUrl[1] + "/" + splitUrl[2] + "/embed/" + splitUrl[3]
      let reqHttpAddr = "/Professor_group_add";
      let sendData = {
        professor_id: this.$store.getters.identification, //professor_id
        group: this.addGroupInfo // Group info
      };
      console.log(sendData.group);
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log("이미 존재하는 그룹입니다.");
          console.log(res.data);
          if (res.data == "이미 존재하는 그룹입니다.") {
            this.overlabCheckResult = false;
            this.alertView = true;
          } else {
            this.callGroupList();
            this.overlabCheckResult = true;
            this.alertView = true;
          }
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //조 정보 불러오기
    callGroupList: function() {
      let reqHttpAddr = "/Professor_group_list";
      let sendData = {
        professor_id: this.$store.getters.identification //professor_id
      };
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.groupList = res.data;
          console.log(res.data);
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //FIXME: 그룹 정보 수정
    changeGroup: function(groupInfoData) {
      console.log("groupInfoData");
      console.log(groupInfoData);
      for( let i = 0 ; i < groupInfoData.length ; i++ ){
        groupInfoData[i].project_video_url = this.embedUrl(groupInfoData[i].project_video_url);
      }
      
      let reqHttpAddr = "/Professor_group_info_change";
      let sendData = {
        professorId: this.$store.getters.identification, //교수ID
        data: groupInfoData
      };
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.callGroupList();
        })
        .catch(err => {
          console.log(err.message);
        });

      this.groupChange = false;
    },

    //FIXME: 그룹 정보 삭제
    deleteGroup: function(groupInfoData) {
      console.log("그룹정보삭제");
      let reqHttpAddr = "/Professor_group_delete";
      let sendData = {
        professorId: this.$store.getters.identification, //교수ID
        data: groupInfoData
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.callGroupList();
        })
        .catch(err => {
          console.log(err.message);
        });
    },
    //url 변경
    embedUrl : function(teamProject_url){
      let embedData = teamProject_url;
      console.log("teamProject_url");
      console.log(teamProject_url);
      let splitUrl  = null;
      if(teamProject_url == null){}
      else{
        splitUrl      = teamProject_url.split('/');
        if(splitUrl[3] == 'embed'){
        }else{
          embedData = "https://www.youtube.com/embed/" + splitUrl[3];
        }
      }
      return embedData;
    },
    //---------------------------------------------------------------------------------------
    // FIXME: 학생평가 저장하기
    AddStdSkill: function(data) {
      let reqHttpAddr = "/Professor_std_info_change";
      let sendData = {
        studentId: data[0].login_id, //학생ID
        major_grade: data[0].major_grade,
        japanese_speaking_level: data[0].japanese_speaking_level,
        sincerity_grade: data[0].sincerity_grade,
        personality_grade: data[0].personality_grade
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {})
        .catch(err => {
          console.log(err.message);
        });
    }
  },

  watch: {
    //FIXME: 회사에 따른 채용건 리스트
    selectedCompany: function() {
      let reqHttpAddr = "/selectedCompany_employment_info";
      let sendData = {
        professorId: this.$store.getters.identification, //교수ID
        company: this.selectedCompany //선택한 기업명
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          for (let i = 0; i < res.data.length; i++) {
            this.recruitment.push({
              text: res.data[i].desired_employee_content,
              value: res.data[i].employment_id
            });
          }
        })
        .catch(err => {
          console.log(err.message);
        });
    }
  }
};
</script>

