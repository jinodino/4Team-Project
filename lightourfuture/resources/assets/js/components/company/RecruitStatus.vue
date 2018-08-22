<template>
<v-container fluid fill-height class="wrap-cont">
        <v-layout class="recuite-list">
            <!-- header -->
            <div class="headline">
                <span class="sub-title">採用件リスト</span>

                 <div class="decision-box">
                    <v-chip label color="teal darken-1" text-color="white">
                        total : {{totalEmploy}}
                    </v-chip>
                </div>
            </div>

            <div class="list-box">
                <v-flex xs12 sm12 md12 lg12>
                    <!-- employment table -->
                    <template>
                        <b-table 
                            outlined 
                            hover 
                            bordered
                            responsive
                            striped
                            :current-page="currentPage"
                            :per-page="perPage"
                            :fields="employmentsHeader"
                            :items="employments" 
                            :filter="filter"
                            @filtered="onFiltered"
                            >

                            <template slot="interview_count" slot-scope="row">
                                <span v-if="row.value == 5">
                                    最終面接
                                </span>

                                <span v-else>
                                    {{row.value}} 回目
                                </span>
                            </template>

                            <!-- data request -->
                            <template slot="applied_student" slot-scope="row">
                                <a style="color:teal; font-weight:bold; text-decoration:underline" 
                                   @click=" selectedEmploy=row.item.employment_id , selectedInterviewStage=row.item.interview_count , selectedInterview = row.item.interview_id
                                            ,getAppliedStdList()">
                                    {{row.value}} 人
                                </a>
                                </template>

                                <template slot="actions"  slot-scope="row" >
                                    <a style="color:teal; font-weight:bold; text-decoration:underline"
                                        @click="employmentDetails(row.item.employment_id)" >
                                        詳細情報
                                    </a>
                                </template>
                            </b-table>
                        </template>
                        <v-alert v-if="employments.length == 0" :value="true" type="error" class="subheading">
                            進行中の採用件がありません。
                        </v-alert>
                        <!-- pagnation & search -->
                        <div>
                            <b-row>
                                <b-col md="5">
                                    <b-pagination :total-rows="totalEmploy" :per-page="perPage" v-model="currentPage" class="my-0" />
                                </b-col>

                                <b-col md="3" class="right">
                                    <b-form-input v-model="filter" size="md" placeholder="Search"/>
                                </b-col>                  
                            </b-row>
                        </div>
                </v-flex>
            </div>
        </v-layout>

        <!-- students list -->
        <v-layout class="std-info-box">
            <div class="headline">
                <span class="sub-title">志願者リスト</span>
                
                <!-- adjudge box -->
               
                <div class="decision-box">
                   <!-- <v-select
                    :items="downloads"
                    label="Select"
                    segmented
                    target="#dropdown-example"
                    ></v-select> -->
                    <v-btn dark class="subheading" color="blue daken-1" @click=" adjudgeState=true , sentenceStdPassStatus()">合格</v-btn>
                    <v-btn dark class="subheading" color="red darken-1" @click=" adjudgeState=false, sentenceStdPassStatus()">不合格</v-btn>
                    <v-btn dark class="subheading" color="teal daken-1" @click=" sendCloseEmploymentNoticeRequest()">次回に進む</v-btn>
                    <v-btn dark class="subheading" color="teal daken-1" @click=" adjudgeState=true , getApplyStdDocs()">志願者資料ダウンロード</v-btn>
                </div>
            </div>

            <!-- list up with card -->
            <div class="list-box">
                <v-alert v-if="!activate_applyStds" :value="true" type="error" class="subheading">
                    採用件を選んでください。
                </v-alert>

                <div class="std-list" v-if="activate_applyStds">
                    <v-flex xs12 sm6 md12 lg12 v-for=" std in activate_applyStds" :key="std.login_id" :hover="true" >
                        <v-card>
                            <!-- handle clicked showing selected student's detail info -->
                            <v-card-media contain :src="std.profile_photo_url" height="20em" @click="getSelectedStdDetails(std.login_id)"/>
                                <v-flex>
                                    <v-card-title class="justify-center title" style="padding-bottom:0; margin:0"> 
                                        <v-checkbox
                                        :label="std.name_kana +' ('+  std.age + ')'"
                                        v-model="adjudgeStds"
                                        :value="std.login_id"
                                        ></v-checkbox>
                                    </v-card-title>
                                    <!-- info from professor -->

                                    <v-card-text>
                                        <v-chip  label small color="teal darken-1" text-color="white" >
                                                誠実
                                                <span v-if="!std.sincerity_grade"> - </span>
                                                <span v-else>{{std.sincerity_grade}}</span>
                                            </v-chip>

                                            <v-chip label small color="teal darken-1" text-color="white" >
                                                人柄
                                                <span v-if="!std.personality_grade"> - </span>
                                                <span v-else>{{std.personality_grade}}</span>
                                            </v-chip> <br>
                                              <v-chip label small color="teal darken-1" text-color="white" >
                                                会話
                                                <span v-if="!std.japanese_speaking_level"> - </span>
                                                <span v-else>{{std.japanese_speaking_level}}</span>
                                            </v-chip>

                                            <v-chip label small color="teal darken-1" text-color="white" >
                                                専攻
                                                <span v-if="!std.major_grade"> - </span>
                                                <span v-else>{{std.major_grade}}</span>
                                            </v-chip>
                                    </v-card-text>
                                    
                                    <v-chip label class="title" text-color="white" color="blue darken-1" v-if="std.interview_result == 'o'">
                                        合格
                                    </v-chip>
                                    <v-chip label class="title" text-color="white"   color="green darken-1" v-else-if="!std.interview_result">
                                        未確定
                                    </v-chip>
                                    <v-chip  label class="title" text-color="white"  color="red darken-1" v-else-if="std.interview_result == 'x'">
                                        不合格
                                    </v-chip>
                                </v-flex>
                        </v-card>
                    </v-flex>
                </div>
            </div>
        </v-layout>
        
        <!-- Student Details Dialog -->
        <v-dialog
            v-model="stdDialogShow" 
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
            fullscreen
        >
        
        <v-flex xs12 sm12 md12 lg12>
            <v-card>
                <v-toolbar card dark color="teal darken-1" :clipped-right="true">
                    <v-btn icon dark @click.native="stdDialogShow=false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>詳細情報</v-toolbar-title>
                </v-toolbar>

                <studentDetail
                :student="selectedStd_personalInfo"
                :skills="skillSheetData"
                :employment="selectedEmploy"
                >
                </studentDetail>
            </v-card>
        </v-flex>
      </v-dialog>

       <v-dialog
            v-model="employDetailShow" 
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
            fullscreen
        >
        
        <v-flex xs12 sm12 md12 lg12>
            <v-card>
                <v-toolbar card dark color="teal darken-1" :clipped-right="true">
                    <v-btn icon dark @click.native="employDetailShow=false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>詳細情報</v-toolbar-title>
                </v-toolbar>

                 <employDetails :employmentId="selectedEmploy" v-if="selectedEmploy"></employDetails>
            </v-card>
        </v-flex>
      </v-dialog>
</v-container>
</template>


<style scoped lang="css" src="../../static/css/Company/RecruitStatus.css"></style>
<script>
/**
 * @author JyunS
 * @description show employment notices -> (click) -> student List -> Adjudge & show student's detail & download resumes
 * //TODO:
 *      -[ㅇ]  채용 공고 리스트 출력
 *      -[ㅇ]  채용 공고 클릭 시 지원 학생 리스트 출력
 *      -[ㅇ]  채용 공고 상세 페이지 작성 -> import
 *      -[ㅇ]  학생 정보 모달 출력
 *      -[ㅇ]  채용 공고 별 이력서 / 포트폴리오 다운로드 
 */

    import employDetails from "../../shared/Recruit.vue";
    import studentDetail from "./StdDetails.vue";
 
    export default {
        components : {
            employDetails,
            studentDetail
        },

        data () {
            return {
                
                /**
                 * @desc employments table dataset
                 */
                filter      : null,
                perPage     : 5,
                currentPage : 1,
                totalEmploy : null,
                employments : null,
                employmentsHeader : 
                [
                    { key : "org_name"         ,label : "学校" ,sortable:true },
                    { key : "org_agent_name"   ,label : "エージェント"   ,sortable:true },
                    { key : "working_sort"     ,label : "仕事"  ,sortable:true },
                    { key : "working_area"     ,label : "勤務地域"  },
                    { key : "interview_count"  ,label : "進行度" ,sortable:true },
                    { key : "applied_student"  ,label : "志願者"   ,sortable:true },
                    { key : "actions"          ,label : "採用件"    },
                ],

                /**
                 * @desc applied students
                 */
                selectedInterview  : null,
                activate_applyStds : null,
                activate_applyStdSum  : null,

                /**
                 * @desc selected employment notice id & dialog status
                 */
                selectedEmploy    : null,
                employDetailShow  : false,
                selectedInterviewStage : null,

                /**
                 * @desc pass student lists
                 */
                adjudgeState : null,
                adjudgeStds  : [],

                /**
                 * @desc selected student details
                 */
                stdDialogShow : false,
                selectedStd_skillInfo    : null,
                selectedStd_personalInfo : null,
                skillSheetData : 
                {
                    list  : [],
                    levels : [],
                },

                downloads : 
                [
                    { text : "Resume" , callback : () =>  this.test()},
                    { text : "Portfolio" , callback : () =>  this.test()}
                ],
                selectedDownloadDocumentType : null, 
            }
        },

        methods : {

            getApplyStdDocs() { 
                if( !this.selectedEmploy || !this.selectedInterviewStage) {
                    this.alerting("error", "失敗","まず、採用件を選んでください。");
                    return;
                }

                let reqHttpAddr = "/studentEntrySheetDownload"; 
                let sendData    = { noticeId : this.selectedEmploy , 
                                    stage    : this.selectedInterviewStage };

                this.axios.post( reqHttpAddr , sendData, { responseType: "blob" } ).then( res => {
                    
                    const url = window.URL.createObjectURL(new Blob([res.data]));
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", "student.zip");
                    document.body.appendChild(link);
                    link.click();
                    if(res.data) return;

                }).catch(err => {
                    console.log(err.message);
                })
            },

            employmentDetails (employmentId) {
                this.selectedEmploy   = employmentId;
                this.employDetailShow = true;                                      
            },

            onFiltered ( filteredItems ) {
                this.totalEmploy = filteredItems.length;
                this.currentPage = 1
            },

            /**
             * @desc get student who applied to the employment
             * @return { Array[Object] }
             */
            getAppliedStdList : function (){
                
                let reqHttpAddr = "/recruitStatus";
                let sendData    = { noticeId : this.selectedEmploy , 
                                    stage    : this.selectedInterviewStage };
                
                this.axios.post( reqHttpAddr, sendData ).then( res => {
                    
                    if( res.data == false) {
                        this.activate_applyStdList = [];
                        return;
                    };
                    
                    this.activate_applyStds    = res.data;
                    this.activate_applyStdSum  = res.data.length;
                    
                }).catch( err => {
                    console.log(err.message);
                })
            },     

            /**
             * @desc set student's interview result
             * @return { Boolean } 
             */
            sentenceStdPassStatus ( ) {
                
                let reqHttpAddr = "/studentInterviewResultDecisionNotice";
                let sendData    = { result      : this.adjudgeState,
                                    interviewId : this.selectedInterview,
                                    stdId       : this.adjudgeStds }

                this.axios.post( reqHttpAddr , sendData ).then( res => {
                    
                    if( !res.data ) {
                        this.alerting("error","失敗","エラーが発生しました。");
                        return;
                    }

                    this.alerting("success","成功","宣告できました。");
                    
                }).catch ( err => {
                    console.log(err.message)
                })
                this.getAppliedStdList();
            },

            /**
             * @desc get selected students personal info & skill info etc..
             * @return { Array [Object] }
             */
            getSelectedStdDetails : function ( stdIdentification ){

                // let reqHttpAddr = "/getStdDetails";
                let reqHttpAddr = "/getStdDetails";
                let sendData    = { requester : this.$store.getters.identification,
                                    stdId     : stdIdentification ,
                                    employId  : this.selectedEmploy };

                this.axios.post(reqHttpAddr , sendData).then( res => {
                    
                    if( !res.data ) return;
                    //FIXME: Server Setting
                    this.selectedStd_skillInfo    = res.data.skill_info;
                    this.selectedStd_personalInfo = res.data.student_info[0]; 
                    this.selectedStd_personalInfo.entrysheet   = res.data.entrysheet;
                    this.selectedStd_personalInfo.porportfolio = res.data.porportfolio;

                    this.createStdSkillData(res.data.skill_info);
                    this.stdDialogShow = true;

                }).catch( err => {
                    console.log(err.message);   
                });
            },

            /**
             * @desc send current interview stage
             * @return { Boolean }
             */
            sendCloseEmploymentNoticeRequest () {
                
                if( !this.selectedEmploy ){
                    this.alerting("error", "失敗","採用件を選んでください");
                }

                let reqHttpAddr = "/nextInterview";
                let sendData    = { noticeId : this.selectedInterview,
                                    employmentId : this.selectedEmploy,
                                    apiKey : this.$store.getters.push_config.apiKey,
                                    requester : this.$store.getters.identification };

                this.axios.post(reqHttpAddr , sendData).then( res => {                
                    
                    if(!res.data) {
                        this.alerting("error","失敗","エラーが発生しました。");
                        return;
                    }
                    
                    if(res.data == 1){
                        this.alerting("success", "成功","次の段階に進めます。");
                    }else if(res.data == 2 ){
                        this.alerting("error","失敗","学生の合格宣告が終わらせてください。");
                    }else if(res.data == 3 ){
                        this.alerting("error","失敗","次の日程がありません。エージェントと相談してください。");
                    }else if(res.data == 4 ){
                        this.alerting("success","成功","最終面接が終わり、内定管理メニューに移動します。");
                    }



                }).catch( err => {
                    console.log(err.message);
                })
            },

            /**
             *  @desc make data for skill chart 
             *  @return { Array[Object] }  
             */
            createStdSkillData : function (skillData) {
                
                this.skillSheetData.list  = [];
                this.skillSheetData.levels = [];

                let skillLevel = { "最上"   : 7 ,
                                    "上"     : 6 ,
                                    "中上"   : 5 ,
                                    "中"     : 4 ,
                                    "中下"   : 3 ,
                                    "下"     : 2 ,
                                    "最下"   : 1 , };

                for(let i = 0 ; i < skillData.length ; i++){
                    this.skillSheetData.list.push(skillData[i].skill_name);
                    this.skillSheetData.levels.push(skillLevel[skillData[i].skill_level]);
                }
            },

            alerting ( type , title, message) {
                this.$swal({
                    type: type,
                    title: title,
                    text: message
                });
            }
        },

        /**
         * @desc get employment notices
         * @return { Array[Object] }
         */
        created () {
            let reqHttpAddr = "/activateEmployment";
            let sendData    = { requester : this.$store.getters.identification };

            this.axios.post( reqHttpAddr , sendData ).then( res => {
                
                if( !res.data ) return false;

               for( let i = 0 ; i < res.data.matching_data.length ; i++ ) {
                res.data.matching_data[i].applied_student  = res.data.count[i].std;
                res.data.matching_data[i].interview_detail = res.data.interview_id[i].content;
                res.data.matching_data[i].interview_id     = res.data.interview_id[i].interview_id;
                res.data.matching_data[i].interview_count  = res.data.interview_id[i].interview_count;
            }
                this.totalEmploy = res.data.matching_data.length;
                this.employments = res.data.matching_data;

            }).catch( err => {
                console.log( err.message );
            })
        },

        watch : {
            adjudgeState () {
                this.getAppliedStdList(selectedEmploy,selectedInterviewStage);
            }
        }


    }    

</script>
