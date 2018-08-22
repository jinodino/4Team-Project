<template>
<v-container fluid>
    <v-layout row wrap width:100%>
        <v-flex xs3 sm3 md3 lg3 style="padding:1%;" class="hoverable">
            <v-card flat height="10vw" style="text-align: center;; border:2px solid black" >
                <h2>{{$t('Professor_home.total')}}</h2>
                <p class="stdCountFont, normal">{{this.studentCount[0]+this.studentCount[1]}}/{{this.stdInfo.std_all_count}}</p>
                <p class="stdCountFont, hover">{{std_percentage[0]}}%</p>
            </v-card>
        </v-flex>
        <v-flex xs3 sm3 md3 lg3 style="padding:1%;" class="hoverable"> 
            <v-card flat height="10vw" style="text-align: center;; border:2px solid green">
                <h2>{{$t('Professor_home.interview_end')}}</h2>
                <p class="stdCountFont, normal">{{this.studentCount[0]}}</p>
                <p class="stdCountFont, hover">{{std_percentage[1]}}%</p>
            </v-card>
        </v-flex>
        <v-flex  xs3 sm3 md3 lg3 style="padding:1%;" class="hoverable"> 
            <v-card flat height="10vw" style="text-align: center;; border:2px solid #084da7">
                <h2>{{$t('Professor_home.have')}}</h2>
                <p class="stdCountFont, normal">{{this.studentCount[1]}}</p>
                <p class="stdCountFont, hover">{{std_percentage[2]}}%</p>
            </v-card>
        </v-flex>

        <v-flex  xs3 sm3 md3 lg3 style="padding:1%;" class="hoverable"> 
            <v-card flat height="10vw" style="text-align: center;; border:2px solid red">
                <h2>{{$t('Professor_home.interview_no')}}</h2>
                <p class="stdCountFont, normal">{{this.studentCount[2]}}</p>
                <p class="stdCountFont, hover">{{std_percentage[3]}}%</p>
            </v-card>
        </v-flex>
        
        <v-flex xs6 sm6 md6 lg6 style="padding:1%">
            
            <v-tabs v-model="tabs_interview" fixed-tabs grow>
                <v-layout>
                    <v-flex xs6 sm6 md6 lg6>
                        <h3 v-if="tabs_interview=='0'" @click="tabs_interview='0'" id="tabon">{{$t('Professor_home.interview_date')}}</h3>
                        <h3 v-if="tabs_interview=='1'" @click="tabs_interview='0'" id="taboff">{{$t('Professor_home.interview_date')}}</h3>
                    </v-flex>
                    <v-flex xs6 sm6 md6 lg6>
                        <h3 v-if="tabs_interview=='1'" @click="tabs_interview='1'" id="tabon">{{$t('Professor_home.fix_company')}}</h3>
                        <h3 v-if="tabs_interview=='0'" @click="tabs_interview='1'" id="taboff">{{$t('Professor_home.fix_company')}}</h3>
                    </v-flex>
                </v-layout>
            </v-tabs>

            <v-tabs-items v-model="tabs_interview" style="height:90%">
                <v-tab-item style="height:100%; max-height: 500px; overflow-y:scroll;">
                    <v-card height="100%">
                        <b-table :fields="headers" responsive :items="interviewDate">
                        </b-table>
                    </v-card>
                </v-tab-item>
                <v-tab-item style="height:100%;">
                    <v-card height="100%" style="text-align:center">
                        <mainChart :width="450" :height="450" style="padding-left:15%"></mainChart>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </v-flex>


         <v-flex xs6 sm6 md6 lg6 style="padding:1%">
             <v-tabs v-model="tabs_std" fixed-tabs grow>
                <v-layout>
                    <v-flex xs6 sm6 md6 lg6>
                        <h3 v-if="tabs_std=='0'" @click="tabs_std='0'" id="tabon">{{$t('Professor_home.fix_std')}}</h3>
                        <h3 v-if="tabs_std=='1'" @click="tabs_std='0'" id="taboff">{{$t('Professor_home.fix_std')}}</h3>
                    </v-flex>
                    <v-flex xs6 sm6 md6 lg6>
                        <h3 v-if="tabs_std=='1'" @click="tabs_std='1'" id="tabon">{{$t('Professor_home.interview_no')}}</h3>
                        <h3 v-if="tabs_std=='0'" @click="tabs_std='1'" id="taboff">{{$t('Professor_home.interview_no')}}</h3>
                    </v-flex>
                </v-layout>
            </v-tabs>

            <v-tabs-items v-model="tabs_std" style="max-height: 500px; overflow-y:scroll;">
                <v-tab-item>
                    <v-card>
                        <v-flex xs2><v-chip color="blue">{{$t('Professor_home.fix')}}</v-chip></v-flex>
                        <b-table :fields="std_headers1" responsive :items="stdInfo.std_fix_list"/>
                        <v-flex xs2><v-chip color="green">{{$t('Professor_home.noFix')}}</v-chip></v-flex>
                        <b-table :fields="std_headers1" responsive :items="std_be_list">
                            <template slot="name" slot-scope="data">
                                {{data.value}}
                            </template>
                            <template slot="be_company_list" slot-scope="data">
                                {{data.value}}
                            </template>
                            <template slot="be_company_list.listed_company_ox" slot-scope="data">
                                {{data.value}}
                            </template>
                        </b-table>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card>
                        <b-table :fields="std_headers2" responsive :items="stdInfo.std_null_list">
                            <template slot="name" slot-scope="data">
                                {{data.value}}
                            </template>
                            <template slot="std_fail_company" slot-scope="data">
                                {{data.value.length}}
                            </template>
                            <template slot="look" slot-scope="data">
                                <v-btn outline color="indigo" small @click="nullStudent(stdInfo.std_null_list)">{{$t('Professor_home.view')}}</v-btn>
                            </template>
                        </b-table>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </v-flex>
    </v-layout>

    <v-dialog v-model="nullStudent_dialog" persistent max-width="500">
      <v-card>
        <v-card-title class="headline">{{$t('Professor_home.applied_company')}}</v-card-title>
        <v-card-text>
            <v-layout row>
                <v-flex xs6 style="text-align:center; border-right:1px solid black">
                    <h4>{{$t('Professor_home.progress')}}</h4>
                    <v-chip v-for="(std,key) in std_progress_company" :key="key">{{std.org_name}}:{{std.interview_count}}{{$t('Professor_home.count')}}</v-chip>
                </v-flex>
                <v-flex xs6 style="text-align:center">
                    <h4>{{$t('Professor_home.fail')}}</h4>
                    <v-chip v-for="(std,key) in std_fail_company" :key="key">{{std.org_name}}:{{std.interview_count}}{{$t('Professor_home.count')}}</v-chip>
                </v-flex>
            </v-layout>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click.native="nullStudent_dialog = false">{{$t('Professor_home.check')}}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
        
</v-container>
</template>

<style scoped lang="css" src="../../static/css/Professor/Home.css"></style>

<script>
import mainChart from "./MainChart";
import { Pie } from 'vue-chartjs'

export default {
  components: { mainChart },
  data() {
    return {
        professorId: "", //교수아이디
        companyId: [], //기업아이디 테이블
        interviewDate: [], //면접일정
        studentList: [], //면접일정에 관한 학생정보
        studentCount : [],
        stdInfo : [],
        headers : [],
        std_headers1 : [],
        std_headers2 : [],

        std_be_list : [],
        seletedStudent : [],
        std_fail_company : [],
        std_progress_company : [],
        std_percentage : [],

        tabs_interview : "0",
        tabs_std       : "0",
        nullStudent_dialog : false
    };
  },
  mounted() {
    this.callStudent();
    this.callSchedule();
    this.multilingual();
    // this.ineterviewDate();
    // this.Notice();
  },
  methods: {
    multilingual : function(){
        this.headers = [
                        //header 부분 : 기업에 나타낼 정보
                        { key: "interview_date",        label: this.$i18n.t("Professor_home.interview_date"),       'class': 'text-center'   },
                        { key: "interview_start_time",  label: this.$i18n.t("Professor_home.interview_start_time"), 'class': 'text-center' },
                        { key: "org_name",              label: this.$i18n.t("Professor_home.org_name"),             'class': 'text-center'},
                        { key: "interview_place",       label: this.$i18n.t("Professor_home.interview_place"),      'class': 'text-center'  },
                        { key: "interview_count",       label: this.$i18n.t("Professor_home.interview_count"),      'class': 'text-center'},
                    ];

        this.std_headers1 = [
                        //header 부분 : 기업에 나타낼 정보
                        { key: "name",              label: this.$i18n.t("Professor_home.std_name"),     'class': 'text-center'   },
                        { key: "org_name",          label: this.$i18n.t("Professor_home.org_name"),     'class': 'text-center'},
                        { key: "listed_company_ox", label: this.$i18n.t("Professor_home.company_size"), 'class': 'text-center'},
                    ];
                    
        this.std_headers2 = [
                        //header 부분 : 기업에 나타낼 정보
                        { key: "name",              label: this.$i18n.t("Professor_home.std_name"),         'class': 'text-center'  },
                        { key: "std_fail_company",  label: this.$i18n.t("Professor_home.interview_number"), 'class': 'text-center'},
                        { key: "look",              label: this.$i18n.t("Professor_home.company_view"),     'class': 'text-center' },
                    ];
    },

    //학생 정보 올해 총학생/ 내정확정학생 / 1사 이상 내정 학생/ 미내정 학생 --> 수
    //미내정학생 : 학생이름, 사진
    callStudent: function() {
      this.studentCount = [];
      let reqHttpAddr = "/Professor_callStudent";
      let sendData = {
        professorId: this.$store.getters.identification
      }; //교수ID

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
            this.stdInfo = res.data;
            this.studentCount.push(this.stdInfo.std_fix_list.length);
            this.studentCount.push(this.stdInfo.std_be_list.length);
            this.studentCount.push(this.stdInfo.std_null_list.length);

            for( let i = 0 ; i < this.stdInfo.std_be_list.length ; i++ ){
                for( let j = 0 ; j < this.stdInfo.std_be_list.length ; j++ ){
                    this.std_be_list.push({name:this.stdInfo.std_be_list[i].name,org_name:this.stdInfo.std_be_list[i].be_company_list[j].org_name,listed_company_ox:this.stdInfo.std_be_list[i].be_company_list[j].listed_company_ox})
                }
            }
            let count = (this.stdInfo.std_fix_list.length+this.stdInfo.std_be_list.length)/this.stdInfo.std_all_count*100;
            count = Math.round(count)
            this.std_percentage.push(count);

            count = this.stdInfo.std_fix_list.length/this.stdInfo.std_all_count*100;
            count = Math.round(count)
            this.std_percentage.push(count);

            count = this.stdInfo.std_be_list.length/this.stdInfo.std_all_count*100;
            count = Math.round(count)
            this.std_percentage.push(count);

            count = this.stdInfo.std_null_list.length/this.stdInfo.std_all_count*100;
            count = Math.round(count)
            this.std_percentage.push(count);
            

        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //스케줄 시간 순으로
    callSchedule: function() {
      let reqHttpAddr = "/Professor_home_schedule";
      let sendData = {
        professorId: this.$store.getters.identification
      }; //교수ID

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.interviewDate = res.data;
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    nullStudent : function(data){
        this.std_fail_company = [];
        this.std_progress_company = [];

        if( data[0].std_fail_company.length > 0){
            for( let j = 0 ; j < data[0].std_fail_company.length ; j++ ){
               this.std_fail_company.push({org_name:data[0].std_fail_company[j].org_name,interview_count:data[0].std_fail_company[j].interview_count});
            }
        }   

        if( data[0].std_progress_company.length > 0){
            for( let k = 0 ; k < data[0].std_progress_company.length ; k++ ){
               this.std_progress_company.push({org_name:data[0].std_progress_company[k].org_name,interview_count:data[0].std_progress_company[k].interview_count});
            }
    }
        this.seletedStudent.std_progress_company = data;
        this.nullStudent_dialog = true;
    }
  }
};
</script>