<style scoped>
</style>

<template>
  <v-container fluid>
    <v-flex xs12 sm12 md12 lg12 text-xs-center>
      <v-card height="100%" style="background-color:#008080;">
        <h1 style="color:white; padding:1%">{{$t('Professor_appliedManagement.applied')}}{{$t('Professor_appliedManagement.company')}}</h1>
        <!-- Btable -->
        <template>
          <b-table :fields="headers" responsive :items="applidManagement">
            <template slot="org_name" slot-scope="data">
              {{data.item.org_name}}
            </template>
            <template slot="apply_date" slot-scope="data">
              {{data.item.apply_open_date}} - {{data.item.apply_deadline_date}}
            </template>
            <template slot="org_work_info" slot-scope="data">
              <p v-for="(work,key) in data.item.work_info" :key="key">
                {{work.content}}
              </p>
            </template>
            <template slot="apply" slot-scope="data">
              {{data.item.apply}}
            </template>
            <template slot="pass" slot-scope="data">
              {{data.item.pass}}
            </template>
            <template slot="fail" slot-scope="data">
              {{data.item.fail}}
            </template>
            <template slot="progress" slot-scope="data">
              {{data.item.progress}}
            </template>
            <template slot="save" slot-scope="data">
              <v-btn flat color="primary" @click="interviewDig(data.item, 'save')">
                {{$t('Professor_appliedManagement.view')}}
              </v-btn>
            </template>
            <template slot="send" slot-scope="data">
              <v-btn flat color="primary" @click="interviewDig(data.item, 'send')">
                {{$t('Professor_appliedManagement.send')}}
              </v-btn>
            </template>
          </b-table>
        </template>
      </v-card>
    </v-flex>

    <v-dialog v-model="agreeDig" fullscreen>
      <v-card height="110%">
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="agreeDig = false">
            <v-icon>cancel</v-icon>
          </v-btn>
          <v-toolbar-title>{{selectedCompanyInfo.org_name}}{{$t('Professor_appliedManagement.interviewApplied')}}{{$t('Professor_appliedManagement.acceptance')}}{{$t('Professor_appliedManagement.refusal')}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn dark flat @click.native="agreeDig = false">{{$t('Professor_appliedManagement.cancel')}}</v-btn>
              <v-btn dark flat @click.native="agreeDig = false, save()">{{$t('Professor_appliedManagement.save')}}</v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <div class="bodyDialog">
          <!-- 대기 학생 -->
          <div class="titleDialog shadowSt">
              <div style="background-color: rgb(17,123, 206); padding-top:3%; padding-left:10px;">
                <h2 style="color:white;">{{$t('Professor_appliedManagement.stand')}}
                  <v-chip small outline disabled color="white">{{permission_stand.length}}{{$t('Professor_appliedManagement.people')}}</v-chip>
                </h2>
                
              </div>
              <div>
                <draggable v-model="permission_stand" style="width:100%; height: 100%; overflow-y:scroll; max-height:100%" :options="{group:'people'}" >
                  <div v-for="(std,key) in permission_stand" :key="key" :hover="true" style="float: left; width:120px; height:150px; margin:2px; ">
                    <v-card-media height="150px" :src="std.profile_photo_url"/>
                    <v-card-text style="text-align:center;">
                      <span style="font-weight:bold;">{{std.name}}</span><br>
                      <v-layout row style="padding-left:7px; padding-bottom:20em">
                        <v-chip small outline color="green"   @click="interviewDigOpen(std)">
                          {{$t('Professor_appliedManagement.pro')}}{{std.std_progress_company.length}}
                        </v-chip>
                        <v-chip small outline color="primary" @click="interviewDigOpen(std)">
                          {{$t('Professor_appliedManagement.pa')}}{{std.std_pass_company.length}}
                        </v-chip>
                      </v-layout>
                   </v-card-text>
                  </div>
                </draggable>
              </div>
            </div>
          <div></div>

          <!-- 수락학생 -->
          <div class="dialogRight">
            <div class="titleDialog2 shadowSt">
            <div style="background-color:#008080; padding-left:10px; padding-top:1%">
              <h3 style="color:white;">{{$t('Professor_appliedManagement.acceptance')}}
                <v-chip small outline disabled  color="white">{{permission_o.length}}{{$t('Professor_appliedManagement.people')}}</v-chip>
              </h3>
            </div>
            <draggable v-model="permission_o" style="width:100%; height: 100%; overflow-y:scroll; max-height:100%" :options="{group:'people'}"> 
              <v-flex v-for="(std,key) in permission_o" :key="key" style="float: left; width:120px; height:150px; margin:2px">
                <v-card-media height="150px" :src="std.profile_photo_url"/>
                <v-card-text style="text-align:center;">
                  <span style="font-weight:bold;">{{std.name}}</span><br>
                  <v-layout row style="padding-left:7px">
                    <v-chip small outline color="green"   @click="interviewDigOpen(std)">{{$t('Professor_appliedManagement.pro')}}{{std.std_progress_company.length}}</v-chip>
                    <v-chip small outline color="primary" @click="interviewDigOpen(std)">{{$t('Professor_appliedManagement.pa')}}{{std.std_pass_company.length}}     </v-chip>
                  </v-layout>
                </v-card-text>
              </v-flex>
            </draggable>
          </div>
          <div></div>
            
            <!-- 거절 학생 -->
            <div class="titleDialog2 shadowSt">
              <div style="background-color:#FF8405; padding-left:10px; padding-top:1%">
                <h3 style="color:white;">{{$t('Professor_appliedManagement.refusal')}} 
                  <v-chip small outline disabled  color="white">{{permission_x.length}}{{$t('Professor_appliedManagement.people')}}</v-chip>
                </h3> 
              </div>
              <draggable v-model="permission_x" style="width:100%; height: 100%; overflow-y:scroll; max-height:100%" :options="{group:'people'}"> 
                <div v-for="(std,key) in permission_x" :key="key" style="float: left;width:120px; height:150px; margin:2px">
                  <v-card-media height="150px" :src="std.profile_photo_url"/>
                  <v-card-text style="text-align:center;">
                  <span style="font-weight:bold;">{{std.name}}</span><br>
                  <v-layout row style="padding-left:7px">
                    <v-chip small outline color="green"   @click="interviewDigOpen(std)">{{$t('Professor_appliedManagement.pro')}}{{std.std_progress_company.length}}</v-chip>
                    <v-chip small outline color="primary" @click="interviewDigOpen(std)">{{$t('Professor_appliedManagement.pa')}}{{std.std_pass_company.length}}     </v-chip>
                  </v-layout>
                </v-card-text>
                </div>
              </draggable>
            </div>
          </div>
        </div> 
      </v-card>
    </v-dialog>

    <!-- 대기, 수락, 거절 저장 뒤 회사에 정보를 보내라고 알려주는 경고창 -->
    <v-alert v-model="alert" type="error" dismissible>
      {{$t('Professor_appliedManagement.mess')}}
    </v-alert>

    <!-- 선택 학생 면접 진행중인 회사, 합격한 회사의 정보를 보여주는 모달창 -->
    <v-dialog v-model="openStdInterview" max-width="700px">
      <v-card>
        <v-card-title style="border-bottom:1px solid #E0E0E0">
          <h3>{{$t('Professor_appliedManagement.progress')}}{{$t('Professor_appliedManagement.pass')}}{{$t('Professor_appliedManagement.company')}}</h3>
        </v-card-title>
        <v-card-text>
          <div class="interviewDig">
            <div style="text-align:center; border-right:1px solid black; color:#008080">
              <h3>{{$t('Professor_appliedManagement.progress')}}</h3>
              <v-chip v-for="(std, key) in openStdInterviewInfo.std_progress_company" :key="key" outline color="blue">{{std.org_name}}</v-chip>
            </div>
            <div style="text-align:center; color:#008080">
              <h3>{{$t('Professor_appliedManagement.pass')}}</h3>
              <v-chip v-for="(std, key) in openStdInterviewInfo.std_pass_company" :key="key" outline color="green">{{std.org_name}}</v-chip>
            </div>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" flat @click.stop="openStdInterview=false">{{$t('Professor_appliedManagement.cancel')}}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<style scoped lang="css" src="../../static/css/Professor/ApplyManagement.css"></style>


<script>
import draggable from "vuedraggable";

export default {
  components: {
    draggable
  },

  data() {
    return {
      tester: null,

      //table
      headers: [],
      applidManagement: [], //table body 부분 : 학교에 오는 기업

      //학생 정보
      permission_o: [], //면접 승낙 학생 정보
      permission_x: [], //면접 거절 학생 정보
      permission_stand: [], //면접 승낙, 거절을 기다리는 학생 정보
      openStdInterviewInfo: [], //선택한 학생들의 회사 면접 상황

      //모달 창
      agreeDig: false, // 학정 수락, 거절 모달
      alert: false, // 학생 수락/거절 시,
      openStdInterview: false, // 학생의 진행 중인 회사, 합격한 회사(정보를 보고 승낙, 거절을 결정)

      selectedCompanyInfo: [] // 선택한 기업의 정보
    };
  },

  created() {
    // 학교에 오는 기업의 면접일정, 모집분야, 지원현황, 교수의 수락/거절 상태를 불러옴.
    this.totalCompanyList();
    this.multilingual();
  },

  methods: {
 
    // 다국어
    multilingual : function(){
      this.headers = [
        //header 부분 : 기업에 나타낼 정보
        { key: "org_name",      label: this.$i18n.t("Professor_appliedManagement.comName")    },
        { key: "apply_date",    label: this.$i18n.t("Professor_appliedManagement.apply_date") },
        { key: "org_work_info", label: this.$i18n.t("Professor_appliedManagement.recruitment")},
        { key: "apply",         label: this.$i18n.t("Professor_appliedManagement.applied")    },
        { key: "pass",          label: this.$i18n.t("Professor_appliedManagement.acceptance") },
        { key: "fail",          label: this.$i18n.t("Professor_appliedManagement.refusal")    },
        { key: "progress",      label: this.$i18n.t("Professor_appliedManagement.stand")      },
        { key: "save",          label: this.$i18n.t("Professor_appliedManagement.std_check")  },
        { key: "send",          label: this.$i18n.t("Professor_appliedManagement.send")       }
      ];
    },

    //면접 진행하는 회사별 학생 지원 관리
    //받아올 정보 : 회사별 지원자, 면접일자, 교수 지원수락여부
    totalCompanyList: function() {
      let reqHttpAddr = "/Professor_totalCompanyList";
      let sendData = {
        professorId: this.$store.getters.identification
      };
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.date = res.data.server_time;
          this.applidManagement = res.data.employ_list;
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    // 지원한 학생들의 진행중인 회사, 합격한 회사
    interviewDigOpen: function(arg) {
      this.openStdInterviewInfo = arg;
      this.openStdInterview = true;
    },

    interviewDig: function(item, message) {
      this.selectedCompanyInfo = item;
      this.studentInfo(
        item.org_company_id,
        item.employment_id,
        item.apply_open_date,
        item.apply_deadline_date,
        message
      );
    },

    //선택회사에 지원한 학생, 수락한 학생, 거절한 학생, 수락거절 대기중인 학생
    studentInfo: function(
      company_id,
      employment_id,
      startDate,
      endDate,
      message
    ) {
      this.startTime = startDate; //면접 지원 시작 일자
      this.endTime = endDate; //면접 지원 마감 일자
      let endEnd = new Date(endDate).getTime(); //String -> Date
      let startStart = new Date(startDate).getTime(); //String -> Date
      let dateDate = new Date(this.date).getTime(); //String -> Date : 서버 현재 날짜, 시간

      //현재 서버 시간이 면접 지원 시작일을 지났거나, 마감일이 아직 안됐을  시 면접 수락/거절버튼 활성화
      if (dateDate > startStart && dateDate < endEnd) {
        this.viewButton = true;
      }

      this.employment_id = employment_id; //채용ID
      this.company_id = company_id; //기업ID

      let reqHttpAddr = "/Professor_ApplyInfo";
      let sendData = {
        professorId: this.$store.getters.identification, //교수ID
        company_id: company_id, //기업ID
        employment_id: employment_id //채용ID
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.permission_o = res.data.apply_permission_info.permission_o;
          this.permission_x = res.data.apply_permission_info.permission_x;
          this.permission_stand =
            res.data.apply_permission_info.permission_stand;

          if (message == "save") {
            this.agreeDig = true;
          } else if (message == "send") {
            this.send(this.permission_o, employment_id);
          }
        })
        .catch(err => {
          console.log(err.message);
        });
      // this.showModal();
    },

    save: function() {
      let reqHttpAddr = "/professor_apply_permission_change";
      let sendData = {
        professorId: this.$store.getters.identification,
        permission_o: this.permission_o,
        permission_x: this.permission_x,
        permission_stand: this.permission_stand,
        employment_id: this.employment_id,
        apiKey: this.$store.getters.push_config.apiKey
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.totalCompanyList();
          this.alert = true;
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //학생 지원리스트 기업으로 보내기 
    send: function(permission_o, employment_id) {
      console.log("send");
      console.log(permission_o);
      console.log(employment_id);

      let reqHttpAddr = "/Professor_real_accept";
      let sendData = {
        professorId: this.$store.getters.identification,
        permission_o: permission_o,
        permission_x: this.permission_x,
        employment_id: employment_id,
        apiKey: this.$store.getters.push_config.apiKey
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log(res.data);
        })
        .catch(err => {
          console.log(err.message);
        });
    }
  }
};
</script>

