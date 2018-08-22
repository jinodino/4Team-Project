<template>
  <v-container fluid>
    <v-layout row>
      <v-flex xs6 border>
        <v-layout row>
          <v-flex xs12 sm12 md12 lg12 style="padding-left:5%">
            <v-layout row>
                <!-- 조 등록  -->
                <v-flex xs2 sm2 md3.5 lg3.5>
                  <div style="border: black solid 1px; text-align: center">
                    <v-btn small color="success" @click="addGroupModal=true, callGroupList()">
                      +조등록
                    </v-btn>
                  </div>
                </v-flex>
              </v-layout>
          </v-flex>

          <!-- 이력서,포트폴리오 -> 회사별 다운로드 클릭 시 모달 창 -->
          <v-dialog v-model="downloadDialog" max-width="290">
            <v-card>
              <v-card-title class="headline">회사별</v-card-title>
              <v-card-text>
                <v-layout row>
                  <v-flex xs12 sm12 md12 lg12>
                    <v-select
                      :items="company"
                      v-model="selectedCompany"
                      label="회사명"
                      class="input-group--focused"
                      item-value="text"
                    >
                    </v-select>
                  </v-flex>
                </v-layout>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" flat="flat" @click.native="downloadDialog = false">cancel</v-btn>
                <v-btn v-if="selectedType == '이력서'" color="green darken-1" flat="flat" @click.native="downloadDialog = false, download_Resume_Portfolio('이력서')">download</v-btn>
                <v-btn v-else color="green darken-1" flat="flat" @click.native="downloadDialog = false, download_Resume_Portfolio('포트폴리오')">download</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <!-- 학생 삭제 모달 창 -->
          <v-dialog v-model="deleteCheck" max-width="600">
            <v-card>
              <!-- Dialog Titles -->
              <v-card-title class="justify-center">
                <p class="headline"> <v-icon color="green darken-1"> autorenew </v-icon>&nbsp; 학생 삭제 </p>
              </v-card-title>

              <!-- Message -->
              <v-card-text class="center" v-if="selectedStd.length == 0">
                <p> 삭제할 학생을 선택해 주세요</p>
              </v-card-text>

              <v-card-text class="center" v-else>
                <p v-for="(stdName, key) in selectedStd" :key="key">
                  {{stdName.name}} 
                </p>를 삭제 하시겠습니까?
              </v-card-text>

              <v-card-actions class="justify-center">
                <v-spacer></v-spacer>
                <v-btn color="red darken-3" flat="flat" @click.native="deleteCheck = false">
                  <v-icon dark right>block</v-icon>&nbsp; Disagree
                </v-btn>

                <!-- request button -->
                <v-btn color="green darken-1" flat="flat" @click="deleteStd(selectedStd)" 
                  @click.native=" deleteCheck = false ">
                  <v-icon>autorenew</v-icon> &nbsp; delete
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <!-- 학생추가 모달 창 -->
          <!-- FIXME: 학생 엑셀로 추가 만들쟈!!!!!!!!!!!!!!!!! -->
          <v-dialog v-model="addstd" max-width="290">
            <v-card>
              <v-card-title class="headline">학생등록</v-card-title>
              <v-card-text>
               <v-flex xs12 sm12 md12 lg12>
                  <div style="margin-bottom:2%">
                      <v-flex xs12 sm12 md12 lg12 style="margin-left:2%; margin-right:2%">
                        <b-form-group label="Name:">
                          <b-form-input
                              type="text"
                              v-model="add.stdName"
                              v-validate="'required'">                            
                          </b-form-input>
                        </b-form-group>
                      </v-flex>
                      <v-layout row style="padding-left:3%">
                        <v-flex xs3 sm3 md3 lg3 style="margin-left:2%">
                          <b-form-group label="Birth Year:">
                            <b-form-input
                                type="text"
                                v-model="add.stdBirthY"
                                v-validate="'required'">                            
                            </b-form-input>
                          </b-form-group>
                        </v-flex>

                        <v-flex xs3 sm3 md3 lg3 style="margin-left:3%">
                          <b-form-group label="Month:">
                            <b-form-input
                                type="text"
                                v-model="add.stdBirthM"
                                v-validate="'required'">                            
                            </b-form-input>
                          </b-form-group>
                        </v-flex>

                        <v-flex xs3 sm3 md3 lg3 style="margin-left:3%; margin-right:2%">
                          <b-form-group label="Day:">
                            <b-form-input
                                type="text"
                                v-model="add.stdBirthN"
                                v-validate="'required'">                            
                            </b-form-input>
                          </b-form-group>
                        </v-flex>

                        <v-flex xs12 sm12 md12 lg12 style="margin-left:2%; margin-right:5%">
                          <b-form-group label="Year of employment:">
                            <b-form-select
                              v-model="add.yearOfemployment"
                              :options="selectYear"
                            >
                            </b-form-select>
                          </b-form-group>
                        </v-flex>
                      </v-layout>
                      <div style="text-align: right">
                          <v-btn color="green darken-1" flat="flat" @click.native="addstd = false">cancel</v-btn>
                          <v-btn small color="success" style="padding : 0;" @click="addStd_CheckNull(), addstd=false">학생추가</v-btn>
                      </div> 
                    </div>
                  </v-flex>
                  <!-- 학생 추가 시 모달 창으로 추가 할 학생을 한번 더 확인 후 디비로 값 전달 -->
                  <v-dialog v-model="checkAddStudent" max-width="600">
                    <v-card>
                      <!-- Dialog Titles -->
                      <v-card-title class="justify-center">
                          <p class="headline"> <v-icon color="green darken-1"> autorenew </v-icon>&nbsp; 학생 추가 </p>
                      </v-card-title>

                      <!-- Message -->
                      <v-card-text class="center">
                        <p class="subheading" >  <v-icon color="error">question_answer</v-icon> {{add.stdBirthY}}.{{add.stdBirthM}}.{{add.stdBirthN}}{{add.stdName}}을 추가 하시겠습니까? </p>
                      </v-card-text>
                      <v-card-actions class="justify-center">
                        <v-spacer></v-spacer>
                        <v-btn color="red darken-3" flat="flat" @click.native="checkAddStudent = false">
                          <v-icon dark right>block</v-icon>&nbsp; Disagree
                        </v-btn>

                        <!-- request button -->
                        <v-btn color="green darken-1" flat="flat" @click="addStd()" @click.native=" checkAddStudent = false ">
                          <v-icon>autorenew</v-icon> &nbsp; save
                        </v-btn>
                      </v-card-actions>
                  </v-card>
              </v-dialog>
            </v-card-text>
          </v-card>
        </v-dialog>

        <!-- 조 등록 모달 -->
           <v-dialog v-model="addGroupModal" max-width="800">
            <v-card>
              <v-card-title class="headline">조등록</v-card-title>
              <v-card-text>
                <v-layout row>
                  <v-flex xs8 sm8 md8 lg8 style="margin-left:2%;">
                    <b-form-group label="Group Count:">
                      <b-form-input
                        type="text"
                        v-model="addGroupCount"
                        v-validate="'required'">                            
                      </b-form-input>
                    </b-form-group>
                  </v-flex>

                  <v-flex xs5 sm5 md5 lg5 style="margin-left:2%; margin-right:5%">
                    <b-form-group label="Num:">
                      <b-form-select
                        v-model="addGroupInfo.num"
                        :options="selectTeam"
                      ></b-form-select>
                    </b-form-group>
                  </v-flex>

                  <v-flex xs5 sm5 md5 lg5 style="margin-left:2%; margin-right:5%">
                    <b-form-group label="Name:">
                      <b-form-input
                        type="text"
                        v-model="addGroupInfo.name"
                        v-validate="'required'">                            
                      </b-form-input>
                    </b-form-group>
                  </v-flex>

                  <v-flex xs12 sm12 md12 lg12 style="margin-left:2%; margin-right:7%">
                    <b-form-group label="explain:">
                      <b-form-input
                        type="text"
                        v-model="addGroupInfo.explain"
                        v-validate="'required'">                            
                      </b-form-input>
                    </b-form-group>
                    <div style="text-align:right">
                      <!-- <v-btn small color="primary" style="padding : 0;" @click="changeGroup()">조정보수정</v-btn>                                                 -->
                      <v-btn color="green darken-1" flat="flat" @click.native="addGroupModal = false">cancel</v-btn>
                      <v-btn small color="success" style="padding : 0;" @click="addGroup_CheckNull(), addGroupModal=false">조추가</v-btn>
                    </div> 

                    <!-- 추가한 조의 정보 -->
                    <b-table 
                      :items="groupList"
                      :fields="groupFields"
                    >
                    <template slot=" num" slot-scope="row">{{row.groupList.group_num}}</template>
                    <template slot="name" slot-scope="row">{{row.group_name}}</template>
                    <template slot="info" slot-scope="row">{{row.project_content}}</template>

                  </b-table>

                </v-flex>

                <!-- 그룹 추가 시 모달 창으로 추가 할 그룹을 한번 더 확인 후 디비로 값 전달 -->
            <!-- <v-dialog v-model="checkAddGroup" max-width="600">
                  <v-card>
                    <v-card-title class="justify-center">
                      <p class="headline"> <v-icon color="green darken-1"> autorenew </v-icon>&nbsp; 그룹 추가 </p>
                    </v-card-title>

                    <v-card-text class="center">
                      <p class="subheading" >  <v-icon color="error">question_answer</v-icon> {{addGroupInfo.num}}조 {{addGroupInfo.name}} : {{addGroupInfo.explain}}을 추가 하시겠습니까? </p>
                    </v-card-text>

                    <v-card-actions class="justify-center">
                      <v-spacer></v-spacer>
                      <v-btn color="red darken-3" flat="flat" @click.native="checkAddGroup = false">
                        <v-icon dark right>block</v-icon>&nbsp; Disagree
                      </v-btn>

                      <v-btn color="green darken-1" flat="flat" @click="addGroup()" @click.native=" checkAddGroup = false ">
                        <v-icon>autorenew</v-icon> &nbsp; save
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog> -->

                <!-- 그룹 추가 시 조 중복 확인 -->
                <v-dialog v-model="overlabCheckAddGroup" max-width="600">
                  <v-card>
                    <v-card-title class="justify-center">
                        <p class="headline"> <v-icon color="green darken-1"> autorenew </v-icon>&nbsp; 확인 </p>
                    </v-card-title>

                    <v-card-text class="center" v-if="overlabCheckResult">
                        <p class="subheading" >  <v-icon color="error">question_answer</v-icon> 조를 추가하셨습니다.</p>
                    </v-card-text>

                    <v-card-text class="center" v-else>
                        <p class="subheading" >  <v-icon color="error">question_answer</v-icon> 중복된 조 정보입니다. </p>
                    </v-card-text>

                    <v-card-actions class="justify-center">
                      <v-spacer></v-spacer>
                      <v-btn color="red darken-3" flat="flat" @click.native="overlabCheckAddGroup = false">
                          <v-icon dark right>block</v-icon>&nbsp; OK
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-layout>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-layout>

      <!-- 학생리스트 -->
      <v-container grid-list-lg fluid>
        <v-chip outline small color="secondary">{{studentList.length}}명</v-chip>
        <v-layout row>
          <v-flex xs6 md4 lg3 lg2 v-for="student in studentList" :key="student.login_id">
            <v-card :hover="true">
              <v-card-media height="200px" :src="student.profile_photo_url" @click="stdInfo(student)"/>
              <div style="text-align:center">
                <v-layout row>
                  <v-flex xs2>
                    <v-checkbox
                      v-model="selectedStd"
                      color="success"
                      :value="student"
                      hide-details
                    ></v-checkbox>
                  </v-flex>
                  <v-flex xs10>
                    {{student.name}}({{student.age}})
                  </v-flex>
                </v-layout>

                <v-chip outline small color="red" v-if="student.major_grade == null">전공-</v-chip>
                <v-chip outline small color="red" v-else>전공{{student.major_grade}}</v-chip>
                <v-chip outline small color="blue" v-if="student.japanese_speaking_level == null">회화-</v-chip>
                <v-chip outline small color="blue" v-else>회화{{student.japanese_speaking_level}}</v-chip>
                <v-chip outline small color="green" v-if="student.sincerity_grade == null">성실-</v-chip>
                <v-chip outline small color="green" v-else>성실{{student.sincerity_grade}}</v-chip>
                <v-chip outline small color="blue" v-if="student.personality_grade == null">인성-</v-chip>
                <v-chip outline small color="blue" v-else>인성{{student.personality_grade}}</v-chip>

              </div>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-flex>
      
    <!-- 오른쪽 화면 : 학생정보 -->
    <v-flex xs6>
      <v-flex xs12 border>
        <v-tabs fixed-tabs>
          <v-tab v-for="n in tebsItems" :key="n" @click="callView(n)">
            {{ n }}
          </v-tab>
          <v-tab-item>
            <v-layout row>
              <v-flex xs6>
                <b-table class="title-sm-left" stacked :items="studentInfo1"></b-table>
              </v-flex>
              <v-flex xs6>
                <b-table class="title-sm-left" stacked :items="studentInfo2"></b-table>
              </v-flex>
              <v-flex xs6>
                <b-table class="title-sm-left" stacked :items="grade1"></b-table>
              </v-flex>
              <v-flex xs6>
                <b-table class="title-sm-left" stacked :items="grade2"></b-table>
              </v-flex>
              <v-flex xs12>
                <v-spacer></v-spacer>
                <v-btn v-if="grade1.length > 0" color="success" block>수정</v-btn>
              </v-flex>
              <v-flex xs6>
                <b-table class="title-sm-left" stacked :items="groupInfo1"></b-table>
              </v-flex>
              <v-flex xs6>
                <b-table class="title-sm-left" stacked :items="groupInfo2"></b-table>
              </v-flex>
            </v-layout>
          </v-tab-item>

          <v-tab-item></v-tab-item>
          <v-tab-item></v-tab-item>
          <v-tab-item></v-tab-item>

        </v-tabs>
      </v-flex>
    </v-flex>
  </v-layout>
</v-container>
</template>


<script>
export default {

  components: {

  },

  created: function() {
      this.nowYear();
      this.callFacultyId();

    // if (this.skillListInfo == null) {
    //   //스킬 항목
    //   this.axios
    //     .post("/item/skillListUp")
    //     .then(rep => {
    //       let skill_list_info = rep.data;

    //       this.skill_field_list = skill_list_info.skill_field_list;
    //       this.skill_list = skill_list_info.skill_list;
    //     })
    //     .catch(ex => {
    //       console.log(ex);
    //     });
    // } else {
    //   this.skill_field_list = this.skillListInfo.skill_field_list;
    //   this.skill_list = this.skillListInfo.skill_list;
    // }


    this.companyList();
    //스킬 리스트 출력

    //this.isStudentListExist = true;
    //this.getStudentList(1,'2018');
  },

  data: () => ({
    studentList: [],
    student_login_id: null,

    studentInfo: null,
    studentInfoDialog: false,

    studentHeaders: [
      { text: "이름", value: "name" },
      { text: "학생 코드", value: "student_code" },
      { text: "지원수", value: "apply_count" },
      { text: "내정수", value: "acceptance_count" },
      { text: "입사 회사", value: "final_company" }
    ],

    skill_field_list: [],
    skill_list: [],

    student_skill_match_print: {},

    applyStatus: {
      tableTitle: "지원수",
      list: [],
      headers: [
        //{text : '채용 id', value : 'employment_id', width : '88px', sortable: false, align:'center'},
        { text: "채용건", value: "org_name", sortable: false },
        //{text : '사퇴 가능 여부', value :'acceptance_fixed_ox', width : '90px', sortable: false, align:'center'},
        {
          text: "교수 승낙",
          value: "apply_permission_ox",
          sortable: false,
          align: "center"
        }
        //{text : 'Action', value : 'name', sortable : false, align : 'center'}
      ]
    },

    interviewStatus: {
      tableTitle: "면접 결과",
      list: [],
      headers: [
        //{text : '채용 id', value : 'employment_id', width : '88px', sortable: false, align:'center'},
        { text: "채용건", value: "org_name", sortable: false },
        {
          text: "진행 면접횟수 / 전체 면접횟수",
          value: "name",
          sortable: false,
          align: "center"
        },
        { text: "결과", sortable: false, align: "center" },
        { text: "", sortable: false, width: "10px" }
      ],
      subListKeys: [],
      subList: {},
      subHeaders: [
        { sortable: false, text: "차수", align: "center", width: "30px" },
        { sortable: false, text: "인터뷰 종류" },
        { sortable: false, text: "장소" },
        { sortable: false, text: "날짜" },
        { sortable: false, text: "결과", align: "center" },
        { text: "", sortable: false, width: "80px" }
      ]
    },

    norminateStatus: {
      tableTitle: "내정수",
      list: [],
      subList: {},
      headers: [
        //{text : '채용 id', sortable : false, align : 'center'},
        { text: "채용건", sortable: false },
        { text: "수락 여부", sortable: false, align: "center" },
        { text: "교수 승낙", sortable: false, align: "center" },
        { text: "Status", sortable: false, align: "center" }
      ]
    },

    selectedStd: [],
    pdfDownloadDialog: false,
    excelDownloadDialog: false,
    tebsItems: ["프로필", "이력서", "포트폴리오", "PR페이지"],
    studentInfo1: [],
    studentInfo2: [],
    grade1: [],
    grade2: [],
    groupInfo1: [],
    groupInfo2: [],
    deleteCheck: false,
    downloadDialog : false,
    downloadSelectedType:"",
    company : [],
    selectedCompany : "",
    downloadDialog : false,
    addstd : false,
    addGroupCount   : "",
    addGroupInfo    :{ //Add Team information
                                    num     : "",
                                    name    : "",
                                    explain : ""
                             },
            overlabCheckResult   : false,
            overlabCheckAddGroup : false,
            checkAddGroup        : false, 
            selectTeam      : [], //Select value when adding team,
            checkAddStudent : false,
            add          : { //add student Basic Information
                            stdName          : "",
                            stdBirthY        : "",
                            stdBirthM        : "",
                            stdBirthN        : "",                    
                            yearOfemployment : "",
                        },
            selectYear      : [], //Selection value of employment year when team is added   
            addGroupModal : false,
            selectedType : "",
            groupFields : [ 
              {key:'num', label:'조'},
              {key:'name', label:'조명'},
              {key:'info', label:'조 정보'}
            ],
            groupList : [],
            facultyId : "",
            thisyear : ""
  }),

  methods: {
    //Duplicate select list 
            removeDuplicateAry : function(arr) {
                let hashTable = {};
                return arr.filter((el) => {
                    let key = JSON.stringify(el);
                    let alreadyExist = !!hashTable[key];
                    return (alreadyExist ? false : hashTable[key] = true);
                });
            },
            nowYear : function(){
                this.num = 1;
                //취업활동년도 받아오기 위한 현재 년도 찾기
                var year = new Date().getFullYear();
                this.thisyear = year;
                console.log("thisyear");
                console.log(this.thisyear);
                
                for(let i = year ; i >= 2016; i--){
                    let castedYear = i.toString();
                    this.selectYear.push({text : castedYear , value : i});
                }  
            },
    //회사 목록
            companyList : function(){
                let reqHttpAddr = "/Professor_company_list";
                let sendData    = {
                                    professorId : this.$store.getters.identification,
                                    };
                this.axios.post(reqHttpAddr, sendData).then((res)=>{
                    for(var i = 0 ; i < res.data.length ; i++){
                        this.company.push({text : res.data[i].org_name});
                    }
                }).catch((err)=>{
                    console.log(err.message);
                });
            },

    //학생 상세 정보 모달 열기
    openStudentInfoDialog(item) {
      console.log(item);
      this.student_login_id = item.login_id;
      this.recommendation_comment_edit = null;

      this.studentInfo = null;
      this.studentInfo = item;
    },

    //학생 리스트 출력
    getStudentList(faculty_id, year) {
      

      //이름, 사진, 일본어회화, 전공실력, 인성, 성실도, 몇조인지, 조명,
      let reqHttpAddr = "/Professor_std_listup";
      let sendData    = {
                          professorId: this.$store.getters.identification,
                          faculty_id : faculty_id,
                          year       : year
                        };
      this.axios.post(reqHttpAddr, sendData).then((res)=>{
          console.log("stdInfo");
          console.log(res.data);
          this.studentList = res.data;
      }).catch((err)=>{
          console.log(err.message);
      });


      // this.axios
      //   .post("/school/getStudentList", { faculty_id, year })
      //   .then(rep => {
      //     let studentArr = rep.data.studentList;
      //     console.log("stdInfo");
      //     console.log(res.data);
      //     this.studentList = [];
      //     for (let index in studentArr)
      //       this.studentList.push(studentArr[index]);
      //   })
      //   .catch(ex => {
      //     console.log(ex);
      //   });
    },

    
    /*
    //학생 status 가져오기
    getStudentStatus() {
      let student_login_id = this.student_login_id;
      let classification = "agent";
      this.axios
        .post("/agent/getStudentStatus", { student_login_id, classification })
        .then(rep => {
          this.applyStatus.list = rep.data.applyStatus;

          //지원한 채용건 리스트
          this.interviewStatus.subListKeys = rep.data.interviewEmploymentList;

          //면접 진행 채용건 리스트 뽑기
          this.interviewStatus.list = [];
          for (let key in this.interviewStatus.subListKeys) {
            for (let item in this.applyStatus.list) {
              if (
                this.interviewStatus.subListKeys[key] ==
                this.applyStatus.list[item].employment_id
              )
                this.interviewStatus.list.push(this.applyStatus.list[item]);
            }
          }

          //채용건 리스트 별로 인터뷰 리스트 array 생성
          for (let index in this.interviewStatus.subListKeys) {
            let employment_id = this.interviewStatus.subListKeys[index];
            this.interviewStatus.subList[employment_id] = [];
          }

          //각 채용건 리스트에 대한 인터뷰 결과 리스트 뽑기
          let subList = rep.data.interviewStatus;
          for (let index in subList) {
            let employment_id = subList[index].employment_id;
            this.interviewStatus.subList[employment_id].push(subList[index]);
          }

          this.norminateStatus.list = rep.data.norminateStatus;
          for (let index in this.norminateStatus.list) {
            let apply_id = this.norminateStatus.list[index].apply_id;
            this.norminateStatus.subList[apply_id] = this.norminateStatus.list[
              index
            ];
          }
          this.finalCompanyInfo = rep.data.finalCompanyInfo;
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //학생 기술 정보 뽑기
    getSkillMatchInfo() {
      let login_id = this.student_login_id;
      this.axios
        .post("/student/getSkillMatchInfo", { login_id })
        .then(rep => {
          this.student_skill_match_print = null;
          this.student_skill_match_print = rep.data.skill_match_print;
        })
        .catch(ex => {
          console.log(ex);
        });
    },
    */

    pdfDownload: function() {},

    stdInfo: function(studentInfo) {
      this.student_login_id = studentInfo.login_id;
      this.studentInfo = studentInfo;
      this.studentInfo1 = [];
      this.studentInfo2 = [];
      this.grade1 = [];
      this.grade2 = [];

      this.groupInfo1 = [];
      this.groupInfo2 = [];

      console.log(studentInfo);
      var jlpt,
        jpt,
        email,
        phone,
        birth,
        addr = "";
      jlpt = studentInfo.JLPT + "급";
      jpt = studentInfo.JPT + "점";
      email = studentInfo.email;
      phone = studentInfo.phone;
      birth = studentInfo.birth_date;
      addr = studentInfo.address;

      this.studentInfo1.push({ jlpt: jlpt, jpt: jpt, email: email });
      this.studentInfo2.push({ phone: phone, birth: birth, address: addr });

      this.grade1.push({
        전공실력: studentInfo.major_grade,
        인성: studentInfo.personality_grade
      });
      this.grade2.push({
        일본어회화: studentInfo.japanese_speaking_level,
        성실성: studentInfo.sincerity_grade
      });

      this.groupInfo1.push({
        조: studentInfo.group_num,
        조소개: studentInfo.project_content
      });
      this.groupInfo2.push({
        조명: studentInfo.group_name,
        프로젝트_역할: studentInfo.group_part_content
      });
    },

    // 리스트에서 학생삭제
    deleteStd: function(argStd) {
      console.log(argStd);
      let name   = [];
      let std_id = [];
      let birth  = [];

        for( let i = 0 ; i < argStd.length ; i++ ){
          name.push(argStd[i].name);
          if (argStd.login_id == null){
            birth.push(argStd[i].birth_date);
          }else{
            std_id.push(argStd[i].login_id);
          }
        }
      console.log(std_id);
      console.log(birth);
      //교수ID, 생년월일, 이름
      let reqHttpAddr = "/Professor_std_delete";
      let sendData = {
        professorId: this.$store.getters.identification,
        std_name: argStd[0].name,
        std_id: std_id,
        std_birth: birth
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log(res.data);
        })
        .catch(err => {
          console.log(err.message);
        });

      // 학생리스트 받아오는 function호출
    },

    callView: function(std) {
      console.log(this.student_login_id);
      console.log(std);
      if (std == "이력서") {
        let reqHttpAddr = "/studentPortfolio_list";
        let sendData = {
          std_id: this.student_login_id,
          type: std
        };
        console.log(sendData);
        this.axios
          .post(reqHttpAddr, sendData)
          .then(res => {
            console.log(res.data);
          })
          .catch(err => {
            console.log(err.message);
          });
      } else if (std == "포트폴리오") {
        let reqHttpAddr = "/studentPortfolio_list";
        let sendData = {
          std_id: this.student_login_id,
          type: std
        };
        console.log(sendData);
        this.axios
          .post(reqHttpAddr, sendData)
          .then(res => {
            console.log(res.data);
          })
          .catch(err => {
            console.log(err.message);
          });
      } else if (std == "PR페이지") {
        let reqHttpAddr = "/studentPRVideo";
        let sendData = {
          std_id: this.student_login_id,
          type: std
        };
        console.log(sendData);
        this.axios
          .post(reqHttpAddr, sendData)
          .then(res => {
            console.log("학생", res.data);
          })
          .catch(err => {
            console.log(err.message);
          });
      }
    },

    // student information excelfile download
    downloadExcel : function() {
       
    let reqHttpAddr = "/export";
    let sendData    = { 
                        professorId     : this.$store.getters.identification, //교수ID
                      } 
    
    this.axios.post(reqHttpAddr, sendData, {responseType: 'blob'}).then((res)=>{
    console.log(this.selectedItem);
        const url = window.URL.createObjectURL(new Blob([res.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'file.csv');
        document.body.appendChild(link);
        link.click();
        
    }).catch((err)=>{
        console.log(err.message);
    });
    this.studentList();
},

//FIXME: 원하는 기업에 지원한 학생들의 포트폴리오, 이력서를 한번에 다운한다.
        download_Resume_Portfolio :function(type){
            let reqHttpAddr = "/studentViewResumePortfolio";
            let sendData    = { 
                                professorId     : this.$store.getters.identification,             //교수ID
                                type            : type,    //다운로드 하고싶은 이력서/포트폴리오
                                selectedCompany : this.selectedCompany          // 다운로드 하고싶은 기업
                                } 

            this.axios.post(reqHttpAddr, sendData, {responseType: 'blob'}).then((res)=>{
            
                //alert(res.data);
                console.log(res.data);
        
                    const url = window.URL.createObjectURL(new Blob([res.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'file.zip');
                    document.body.appendChild(link);
                    link.click();
                
            
                //파일다운로드
            }).catch((err)=>{
                console.log(err.message);
            });
       
        },
        
      addGroup_CheckNull : function(){
                 if( !this.addGroupInfo.num || !this.addGroupInfo.name || !this.addGroupInfo.explain){                    
                    alert("모두 입력해주세요.");
                    return;
                }

                // this.checkAddGroup = true;
                this.addGroup();
            },

            //Add Group
            addGroup : function(){
               

                let reqHttpAddr = "/Professor_group_add";
                let sendData    = { 
                                    professor_id    : this.$store.getters.identification,               //professor_id
                                    group           : this.addGroupInfo     // Group info
                                } 
                this.axios.post(reqHttpAddr, sendData).then((res)=>{
                    // console.log(res.data);
                    if(res.data == "이미 존재하는 그룹입니다."){
                        console.log("fail");
                        this.overlabCheckResult   = false;
                        this.overlabCheckAddGroup = true;
                    }else{
                        console.log("pass");     
                        this.overlabCheckResult   = true;
                        this.overlabCheckAddGroup = true;                   
                    }
                }).catch((err)=>{
                    console.log(err.message);
                });
            },

            callGroupList : function(){
              let reqHttpAddr = "/Professor_group_list";
              let sendData    = { 
                                  professor_id    : this.$store.getters.identification,  //professor_id
                                } 
              this.axios.post(reqHttpAddr, sendData).then((res)=>{
                console.log(res.data);

                  this.groupList = res.data;
                console.log(this.groupList);

              }).catch((err)=>{
                  console.log(err.message);
              });
            },

            callFacultyId: function() {
      let reqHttpAddr = "/Professor_callFacultyId";
      let sendData = {
        professorId: this.$store.getters.identification
      };
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.facultyId = res.data[0].faculty_id;
          console.log("facultyId");

          console.log(this.facultyId);

          this.getStudentList(this.facultyId, this.thisyear);

        })
        .catch(err => {
          console.log(err.message);
        });
    }
    

  },

  watch: {

    // student_login_id: function(val) {
    //   this.getStudentStatus();
    //   this.getSkillMatchInfo();
    // },

    selectedStd: function() {
      console.log(this.selectedStd);
    },

    addGroupCount : function(){
                for(let i = 0 ; i < this.addGroupCount ; i++){
                    let num = (i+1).toString();
                    this.selectTeam.push({text : num, value : num});
                }
                
                var a = this.removeDuplicateAry(this.selectTeam);
                if(this.addGroupCount < a.length){
                    a = a.slice(0,this.addGroupCount);
                }
                this.selectTeam = a;
            },


    
  }
};
</script>

<style>
</style> 
