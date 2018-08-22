
<template>
  <v-container fluid style="height:100%">
    <div class="bodySt gap">
      <div class="shadow">
        <v-tabs v-if="selectedStandard == '0'" grow class="tabsSt"  v-model="selectedTabsStdent" slider-color="teal">
          <v-tab v-for="(sort, key) in stdentSort" :key="key">
            {{ sort }}
          </v-tab>

          <v-tabs-items> 
            <v-tab-item style="height:100%; padding:1em; overflow-y:scroll; max-height:100%">
                <v-layout row style="width:100%; padding-left:3%">
                    <v-flex xs4 md4 lg4  v-for="student in studentList" :key="student.login_id"  style="padding:1%">
                        <v-card>
                            <v-card-media
                            :src="student.profile_photo_url ? student.profile_photo_url : '/images/common/p.png'" @click="resultInterview(student)"
                            height="200px"
                            ></v-card-media>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-tab-item>
            <v-tab-item style="height:100%; padding:1em; overflow-y:scroll; max-height:100%">
               <v-layout row style="width:100%; padding-left:3%">
                     <v-flex xs4 md4 lg4  v-for="student in studentList" :key="student.login_id"  style="padding:1%">
                        <v-card>
                            <v-card-media
                            :src="student.profile_photo_url ? student.profile_photo_url : '/images/common/p.png'" @click="resultInterview(student)"
                            height="200px"
                            ></v-card-media>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-tab-item>
            <v-tab-item style="height:100%; padding:1em; overflow-y:scroll; max-height:100%">
               <v-layout row style="width:100%; padding-left:3%">
                     <v-flex xs4 md4 lg4  v-for="student in studentList" :key="student.login_id"  style="padding:1%">
                        <v-card>
                            <v-card-media
                            :src="student.profile_photo_url ? student.profile_photo_url : '/images/common/p.png'" @click="resultInterview(student)"
                            height="200px"
                            ></v-card-media>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-tab-item>
            <v-tab-item style="height:100%; padding:1em; overflow-y:scroll; max-height:100%">
              <v-layout row style="width:100%; padding-left:3%">
                 <v-flex xs4 md4 lg4  v-for="student in studentList" :key="student.login_id"  style="padding:1%">
                        <v-card>
                            <v-card-media
                            :src="student.profile_photo_url ? student.profile_photo_url : '/images/common/p.png'" @click="resultInterview(student)"
                            height="200px"
                            ></v-card-media>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-tab-item>
          </v-tabs-items>
        </v-tabs>

        <v-tabs v-else grow class="tabsSt" v-model="SelectedTabsCompany">
          <v-tab v-for="(sort, key) in progressSort" :key="key">
            {{ sort }}
          </v-tab>

          <v-tabs-items> 
            <v-tab-item style="height:100%; padding:1em; overflow-y:scroll; max-height:100%">
                <v-layout row>
                    <v-flex xs6 sm6 md6 lg6  v-for="(company, key) in companyInfo" :key="key" style="padding:1%">
                        <v-card hover>
                            <v-card-media height="150px" :src="company.photo_url" @click="companyMoreInformation(company, company.employment_id)"/>
                            <div style="text-align:center">
                                <h3>{{company.org_name}}</h3>
                            </div>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-tab-item>
            <v-tab-item style="height:100%; padding:1em; overflow-y:scroll; max-height:100%">
               <v-layout row>
                    <v-flex xs6 sm6 md6 lg6  v-for="(company, key) in companyInfo" :key="key" style="padding:1%">
                        <v-card hover>
                            <v-card-media height="150px" :src="company.photo_url" @click="companyMoreInformation(company, company.employment_id)"/>
                            <div style="text-align:center">
                                <h3>{{company.org_name}}</h3>
                            </div>
                        </v-card>
                    </v-flex>
               </v-layout>
            </v-tab-item>
            <v-tab-item style="height:100%; padding:1em; overflow-y:scroll; max-height:100%">
                <v-layout row>
                    <v-flex xs6 sm6 md6 lg6  v-for="(company, key) in companyInfo" :key="key" style="padding:1%">
                        <v-card hover>
                            <v-card-media height="150px" :src="company.photo_url" @click="companyMoreInformation(company)"/>
                            <div style="text-align:center">
                                <h3>{{company.org_name}}</h3>
                            </div>
                        </v-card>
                    </v-flex>
               </v-layout>
            </v-tab-item>
          </v-tabs-items>
        </v-tabs>
      
      </div>

      <div class="shadow">
        <v-tabs class="tabsSt" v-model="selectedStandard">
          <!-- <v-tab v-for="(standard, key) in standard" :key="key">
            {{ standard }}
          </v-tab> -->

            <v-layout>
                <v-flex xs10></v-flex>
                <v-flex xs1>
                    <div v-if="selectedStandard == '0'" @click="selectedStandard = '0'" id="tabon">{{$t('Professor_statusManagement.student')}}</div>
                    <div v-if="selectedStandard == '1'" @click="selectedStandard = '0'" id="taboff">{{$t('Professor_statusManagement.student')}}</div>
                </v-flex>
                <v-flex xs1>
                    <div v-if="selectedStandard == '1'" @click="selectedStandard = '1'" id="tabon">{{$t('Professor_statusManagement.company')}}</div>
                    <div v-if="selectedStandard == '0'" @click="selectedStandard = '1'" id="taboff">{{$t('Professor_statusManagement.company')}}</div>
                </v-flex>
            </v-layout>

          <v-tabs-items v-model="selectedStandard"> 
            <!-- 학생 -->
            <v-tab-item style="height:100%">
              <div class="stdBodySt" style="height:100%; grid-column-gap: 1em;">
                <div class="resultLeft">
                <!-- 진행 -->
                  <div class="resultLeftTitle" style="padding-left:3%">
                      <div style="padding-left:2%; padding-top:5px">
                        <v-layout row>
                            <h3 style="color:#008080;">{{$t('Professor_statusManagement.progress')}}</h3>
                        </v-layout>
                      </div>
                    <div style="border-top:1px solid black ; overflow-y:scroll;" >
                        <div v-for="(info, key) in resultInterviewInfo.proceed_company_info" :key="key" style="padding:1%">
                            <v-layout row style="padding-left:1em;">
                                <v-flex xs2 sm2 md2 lg2>
                                    <v-avatar size="55px">
                                        <img :src="info.photo_url">
                                    </v-avatar>
                                </v-flex>
                                <v-flex xs9 sm9 md9 lg9>
                                    <div hover style="padding:7%">
                                        <div>
                                            {{info.org_name}}<br>
                                        </div>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </div>
                    </div>
                  </div>
                  <!-- 불합격 -->
                  <div class="resultLeftTitle" style="padding-left:2%">
                    <div style="padding-left:2%">
                        <v-layout row>
                            <h3 style="color:#008080;">{{$t('Professor_statusManagement.fail')}}</h3>
                        </v-layout>
                      </div>
                    <div style="border-top:1px solid black;overflow-y:scroll; padding-top:1%">
                         <div v-for="(info, key) in resultInterviewInfo.fail_company_info" :key="key" style="padding:1%">
                            <v-layout row style="padding-left:1em;">
                                <v-flex xs2 sm2 md2 lg2 style="padding-top:3%"> 
                                    <v-avatar size="55px">
                                        <img :src="info.photo_url">
                                    </v-avatar>
                                </v-flex>
                                <v-flex xs9 sm9 md9 lg9>
                                    <div hover style="padding:7%">
                                        <div >
                                            {{info.org_name}}<br>
                                            <span style="font-weight:bold;">{{$t('Professor_statusManagement.count')}}</span>: {{info.fail_interview_count}}{{$t('Professor_statusManagement.interview_count')}}
                                        </div>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="resultRight">
                  <div class="successTitle">
                   <div>
                        <v-layout row>
                            <h3 style="color:#008080; padding-top:0.5%;">{{$t('Professor_statusManagement.pass')}}</h3>
                        </v-layout>
                    </div>
                    <div style="border-bottom:1px solid black; border-top:1px solid black;">
                        <v-layout row>
                            <v-flex xs12 sm12 md12 lg12  v-for="(info, key) in resultInterviewInfo_all" :key="key" style="padding:1em">
                                <div style="padding:2%">
                                    <v-layout row>
                                        <v-flex xs2 sm2 md2 lg2>
                                            <v-avatar size="75px">
                                                <img :src="info.photo_url">
                                            </v-avatar>
                                        </v-flex>
                                        <v-flex xs7 sm7 md7 lg7 style="padding-top:2%">
                                            <h4>{{info.org_name}}</h4>
                                            <p v-if="info.content == '' && info.resign_comment == ''">내정수락</p>
                                            <p v-if="info.content == ''"></p>
                                            <p v-else>{{info.content}}</p>
                                            <p v-if="info.resign_comment == ''"></p>
                                            <p v-else>{{info.resign_comment}}</p>
                                         </v-flex>
                                         <v-flex xs3 sm3 md3 lg3 style="padding-top:3%; padding-left:2%">
                                            <v-btn @click="fixCompany(resultInterviewInfo.login_id, info.employment_id, info.content, info.resign_comment)">{{$t('Professor_stdManagement.check')}}</v-btn>
                                         </v-flex>
                                    </v-layout>
                                </div>
                            </v-flex>
                        </v-layout>
                    </div>
                  </div>
                  <div class="otherBody" style="padding-top:1em">
                    <div class="otherTitle">
                      <div class="textSt">{{$t('Professor_statusManagement.fixed')}}</div>
                      <div style="padding:2%">
                        <div v-for="(info, key) in resultInterviewInfo.be_nominated_company_info" :key="key">
                            <v-flex xs12 sm12 md12 lg12> 
                                <v-avatar size="55px">
                                    <img :src="info.photo_url">
                                </v-avatar>
                            </v-flex>
                            <v-flex xs12 sm12 md12 lg12>
                                <div hover style="padding:3%">
                                    <div >
                                        {{info.org_name}}<br>
                                    </div>
                                </div>
                            </v-flex>
                        </div>
                        </div>
                    </div>
                    <div class="otherTitle">
                      <div class="textSt">{{$t('Professor_statusManagement.resign')}}</div>
                      <div>
                        <v-layout>
                         <div v-for="(info, key) in resultInterviewInfo_resign" :key="key" style="padding:1%">
                            <v-flex xs6 sm6 md6 lg6 style="padding-top:3%"> 
                                <v-avatar size="55px">
                                    <img :src="info.photo_url">
                                </v-avatar>
                                <div hover style="padding:7%">
                                    <div >
                                        {{info.org_name}}<br>
                                    </div>
                                </div>
                            </v-flex>
                        </div>   
                        </v-layout>
                      </div>
                    </div>
                    <div class="otherTitle">
                      <div class="textSt">{{$t('Professor_statusManagement.stand')}}</div>
                      <div>
                        <v-layout>
                         <div v-for="(info, key) in resultInterviewInfo_standby" :key="key">
                            <v-flex xs10 sm10 md10 lg10 style="padding-top:3%"> 
                                <v-avatar size="55px">
                                    <img :src="info.photo_url">
                                </v-avatar>
                                <div style="text-align:center">
                                    {{info.org_name}}
                                </div>
                            </v-flex>
                        </div>   
                        </v-layout>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </v-tab-item>

            <!-- 기업 -->
            <v-tab-item style="height:100%">
                <div class="stdSt_height">
                <div class="stdSt" style="height:100%; padding-left:2%">
                    <div class="companyRight" style="height:100%;">
                      <div style="padding:2%">
                        <v-layout row>
                            <h3 style="color:#008080; padding-top:1%">{{$t('Professor_statusManagement.fail')}}</h3>
                            <v-chip outline small color="teal dark">{{appliedStdInfoCount[2]}}{{$t('Professor_statusManagement.people')}}</v-chip>
                        </v-layout>
                      </div>
                      <div style="border-right:black 1px solid; padding:2%; overflow-y:scroll; max-height:100%">
                        <div v-for="(info, key) in appliedStdInfo[2]" :key="key" style="padding:1%">
                            <v-layout row style="padding-left:1em;">
                                <v-flex xs2 sm2 md2 lg2>
                                    <v-avatar size="55px">
                                        <img :src="info.profile_photo_url">
                                    </v-avatar>
                                </v-flex>
                                <v-flex xs9 sm9 md9 lg9>
                                    <div hover style="padding-left:10%">
                                        <div>
                                            {{info.name}}<br>
                                            <span style="font-weight:bold;">{{$t('Professor_statusManagement.count')}}</span> {{info.interview_count}}{{$t('Professor_statusManagement.interview_count')}}<br>
                                            <span style="font-weight:bold;">{{$t('Professor_statusManagement.recruitment')}}</span>:<br> {{info.work_info}}
                                        </div>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </div>
                    </div>
                  </div>
                  <div class="companyRight" style="height:100%;">
                    <div style="padding:1%">
                        <v-layout row style="padding-left:2%">
                            <h3 style="color:#008080; padding-top:0.7">{{$t('Professor_statusManagement.progress')}}/{{$t('Professor_statusManagement.pass')}}</h3>
                            <v-chip outline small color="teal dark">{{appliedStdInfoCount[0] + appliedStdInfoCount[1]}}{{$t('Professor_statusManagement.people')}}</v-chip>
                        </v-layout>
                    </div>
                    <div style="padding-left:3%; overflow-y:scroll; max-height:100%">
                        <v-layout row>
                        <v-flex xs6 sm6 md6 lg6 v-for="(info, key) in appliedStdInfo[0]" :key="key" style="padding:1%;">
                            <v-layout row style="padding-left:1em;">
                                <v-flex xs2 sm2 md2 lg2>
                                    <v-avatar size="55px">
                                        <img :src="info.profile_photo_url">
                                    </v-avatar>
                                </v-flex>
                                <v-flex xs9 sm9 md9 lg9>
                                    <div hover style="padding-left:10%">
                                        <div>
                                            {{info.name}}<br>
                                            <span style="font-weight:bold;">{{$t('Professor_statusManagement.count')}}</span>: {{info.interview_count}}{{$t('Professor_statusManagement.interview_count')}}<br>
                                            <span style="font-weight:bold;">{{$t('Professor_statusManagement.recruitment')}}</span>:<br> {{info.work_info}}
                                        </div>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-flex xs6 sm6 md6 lg6 v-for="(info, key) in appliedStdInfo[1]" :key="key" style="padding:1%;" v-if="appliedStdInfo.fixed_std = []">
                            <v-layout row style="padding-left:1em;">
                                <v-flex xs2 sm2 md2 lg2>
                                    <v-avatar size="55px">
                                        <img :src="info.profile_photo_url">
                                    </v-avatar>
                                </v-flex>
                                <v-flex xs9 sm9 md9 lg9>
                                    <div hover style="padding-left:10%">
                                        <div>
                                            {{info.name}}<br>
                                            <span style="font-weight:bold;">{{$t('Professor_statusManagement.count')}}</span>: {{info.interview_count}}{{$t('Professor_statusManagement.interview_count')}}<br>
                                            <span style="font-weight:bold;">{{$t('Professor_statusManagement.recruitment')}}</span>:<br> {{info.work_info}}
                                        </div>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        </v-layout>
                       
                    </div>
                  </div>
                </div>
                <div style="text-align:right">
                    <!-- <v-btn style="color:#008080">{{$t('Professor_statusManagement.check')}}</v-btn> -->
                </div>
                </div>
            </v-tab-item>
          </v-tabs-items>
        </v-tabs>
      </div>
    </div>
  </v-container>
</template>

<style scoped lang="css" src="../../static/css/Professor/StatusManagement.css"></style>

<script>
export default {
  data() {
    return {
        stdentSort: [],
        standard  : [],

        progressSort  : [],
        studentList   : [],
        companyInfo   : [],
        resultInterviewInfo           : [],
        resultInterviewInfo_all       : [],
        resultInterviewInfo_resign    : [],
        resultInterviewInfo_standby   : [],
        appliedStdInfo                : [[], [], [], []],
        appliedStdInfoCount           : [0,0,0],

        selectedStandard              : "0",
        SelectedTabsCompany           : "",
        selectedTabsStdent            : "",
        index         : 1,
        search        : "",
        text          : "",

    };
  },
  mounted() {
    this.studentInfo();
    this.companyCondition();
    this.companyList("total");
    this.multilingual();
  },
  methods: {
    // 다국어
    multilingual: function() {
      this.progressSort = [
        this.$i18n.t("Professor_statusManagement.total"),
        this.$i18n.t("Professor_statusManagement.progress"),
        this.$i18n.t("Professor_statusManagement.end")
      ];
      this.standard = [
        this.$i18n.t("Professor_statusManagement.student"),
        this.$i18n.t("Professor_statusManagement.company")
      ];
      this.stdentSort = [
        this.$i18n.t("Professor_statusManagement.total"),
        this.$i18n.t("Professor_statusManagement.nominated"),
        this.$i18n.t("Professor_statusManagement.no"),
        this.$i18n.t("Professor_statusManagement.have")
      ];
    },

    companyList: function(type) {
      //total(전체회사), progress(진행중인 회사), end(면접 종료 회사)

      let reqHttpAddr = "/professor_interview_result_search";
      let sendData = {
        professorId: this.$store.getters.identification,
        type: type
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log("res.data");
          console.log(res.data);
          this.companyInfo = res.data;
        })
        .catch(err => {
          console.log(err.message);
        });
    },
    resultInterview: function(std) {
      this.resultInterviewInfo = std;
      this.resultInterviewInfo_all = std.pass_company_info.pass_all;
      this.resultInterviewInfo_resign = std.pass_company_info.pass_resign;
      this.resultInterviewInfo_standby = std.pass_company_info.pass_standby;

      console.log("this.resultInterviewInfo");
      console.log(this.resultInterviewInfo);
    },
    // 기업 관련 정보
    companyMoreInformation: function(arg) {
      console.log("res");
      console.log(arg);
      this.appliedStdInfo = [[], [], [], []];

      var work_info = "";

      for (let k = 0; k < arg.employment_id.length; k++) {
        for (let j = 0; j < arg.employment_id[k].work_info.length; j++) {
          work_info += arg.employment_id[k].work_info[j].content;
          if (j < arg.employment_id[k].work_info.length - 1) {
            work_info += ", ";
          }
        }

        for (let i = 0; i < arg.employment_id[k].fail_std.length; i++) {
          this.appliedStdInfo[2].push({
            name: arg.employment_id[k].fail_std[i].name,
            profile_photo_url:
              arg.employment_id[k].fail_std[i].profile_photo_url,
            interview_count: arg.employment_id[k].fail_std[i].interview_count,
            work_info: work_info
          });
        }

        for (let i = 0; i < arg.employment_id[k].progress_std.length; i++) {
          this.appliedStdInfo[0].push({
            name: arg.employment_id[k].progress_std[i].name,
            profile_photo_url:
              arg.employment_id[k].progress_std[i].profile_photo_url,
            interview_count:
              arg.employment_id[k].progress_std[i].interview_count,
            work_info: work_info
          });
        }

        for (let i = 0; i < arg.employment_id[k].pass_std.length; i++) {
          this.appliedStdInfo[1].push({
            name: arg.employment_id[k].pass_std[i].name,
            profile_photo_url:
              arg.employment_id[k].pass_std[i].profile_photo_url,
            interview_count: arg.employment_id[k].pass_std[i].interview_count,
            work_info: work_info
          });
        }

        for (let i = 0; i < arg.employment_id[k].fixed_std.length; i++) {
          this.appliedStdInfo[3].push({
            name: arg.employment_id[k].fixed_std[i].name,
            profile_photo_url:
              arg.employment_id[k].fixed_std[i].profile_photo_url,
            interview_count: arg.employment_id[k].fixed_std[i].interview_count,
            work_info: work_info
          });
        }
      }

      console.log("this.appliedStdInfo");
      console.log(this.appliedStdInfo);

      // this.appliedStdInfoCount[0] = arg.progress;
      // this.appliedStdInfoCount[1] = arg.pass;
      // this.appliedStdInfoCount[2] = arg.fail;
      // this.appliedStdInfoCount[3] = arg.fixed;

      // return : 기업상세정보(기업사진, 이름, 사원수, 주소, 사업내용, 채용분야), 해당기업 지원 중인 학생(이름, 사진)
    },

    //면접 결과 학생에게 보내기
    // SendResult: function(argEmployment_id) {
    //   let reqHttpAddr = "/";
    //   let sendData = {
    //     professorId: this.$store.getters.identification,
    //     employment_id: argEmployment_id //<-요거 말고  이거로 백에 넘기시오->employment_id
    //   };
    //   this.axios
    //     .post(reqHttpAddr, sendData)
    //     .then(res => {})
    //     .catch(err => {
    //       console.log(err.message);
    //     });
    // },

    //회사별 지원자, 합격자, 진행중, 불합격자(차수), 내정자, 내정확정자
    //학생정보 : 사진, 이름
    studentInfo: function(type) {
      //total, fix, null, be
      this.studentList = [];
      let reqHttpAddr = "/Professor_status_info";
      let sendData = {
        professorId: this.$store.getters.identification,
        type: type
      };
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.studentList = res.data;
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //기업상태
    companyCondition: function() {
      let reqHttpAddr = "/Professor_apply_progress";
      let sendData = {
        professorId: this.$store.getters.identification
      };
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          for (var i = 0; i < res.data.length; i++) {
            for (var j = 0; j < res.data[i].length; j++) {}
          }
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    // 학생 내정확정
    fixCompany: function(argStd_id, argCompany_applyid, content, stdcontent) {
      // 교수ID, 학생학번, 내정확정회사명
      let reqHttpAddr = "/professor_acceptance_ox";
      let sendData = {
        professorId: this.$store.getters.identification,
        studentId: argStd_id,
        professorId: this.$store.getters.identification,
        NominatedCompany: argCompany_applyid,
        apiKey: this.$store.getters.push_config.apiKey //apiKey FIXME: 내정확정, 사퇴 메세지를 보낸사람에게 교수가 확인했다는 알림을 보냄
      };
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          // DB table 내정확정 x로 자동변환
          // 내정확정회사 저장
        })
        .catch(err => {
          console.log(err.message);
        });
    }
  },

  watch: {
    SelectedTabsCompany: function() {
      if (this.SelectedTabsCompany == 0) {
        this.companyList("total");
      } else if (this.SelectedTabsCompany == 1) {
        this.companyList("progress");
      } else {
        this.companyList("end");
      }
    },

    selectedTabsStdent: function() {
      if (this.selectedTabsStdent == 0) {
        this.studentInfo("total");
      } else if (this.selectedTabsStdent == 1) {
        this.studentInfo("fix");
      } else if (this.selectedTabsStdent == 2) {
        this.studentInfo("null");
      } else {
        this.studentInfo("be");
      }
    }
  }
};
</script>



