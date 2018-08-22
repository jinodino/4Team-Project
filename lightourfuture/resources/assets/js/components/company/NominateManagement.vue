<template>
    <v-container fill-height fluid>
        <v-layout row class="wrap-cont">
            <v-flex xs12 sm12 md12 lg12 class="notify-area" pa-3>

                <v-flex d-flex xs12 sm12 md12 lg12 class="justify-center">
                    <span class="sub-title">
                        終了採用広告リスト
                    </span>
                </v-flex>

                <v-flex xs12 ms12 md12 lg12 style="background:rgba(177, 177, 177, 0.1)">
                    <template>
                        <b-table 
                            outlined :fixed="true" 
                            hover  
                            responsive
                            striped
                            bordered
                            :current-page="currentPage"
                            :per-page="perPage"
                            :fields="overNoticeHeader"
                            :items="overNotices" 
                            :filter="filter"
                            @filtered="onFiltered"
                            @row-clicked="getNominatedInfo">

                            <template slot="applied" slot-scope="data">
                                {{data.item.applied}} / {{data.item.nominated}}
                            </template>
                        </b-table>
                     
                    </template>
                    <v-alert v-if="overNotices.length == 0" :value="true" type="error">
                        終了した採用件がありません。
                    </v-alert>
                </v-flex>

                <v-flex xs12 sm12 md12 lg12  style="background:rgba(177, 177, 177, 0.1)">
                    <b-row>
                        <b-col md="5">
                            <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
                        </b-col>

                        <b-col md="5" class="right">
                            <b-form-input v-model="filter" size="md" placeholder="Search"></b-form-input>
                        </b-col>                  
                    </b-row>
                </v-flex>
            </v-flex>

            <v-flex xs12 sm12 md12 lg12 pa-3 class="nominate-area">
                
                <v-flex xs12 sm12 md12 lg12>
                    <span class="sub-title">
                        内定状況
                    </span>
                </v-flex>

                <v-flex xs12 sm12 md12 lg12 v-if="!selectedEmploy" >
                    <v-alert :value="true" type="error" class="subheading">
                        採用件を選んでください。
                    </v-alert>
                </v-flex>

                <v-flex xs12 sm12 md12 lg12 class="nominate-list-sections" v-if="selectedEmploy">
                    <v-flex xs12 sm12 md12 lg12 pa-2>
                        <span class="small-title">
                            内定学生リスト
                        </span>

                        <v-alert v-if="nominateWait.length == 0" :value="true" type="error">
                            待機状態の学生がいません。
                        </v-alert>

                        <v-card flat v-if="nominateWait.length > 0 ">                            
                            <v-flex xs6 sm6 md6 lg6 v-for="std in nominateWait" :key="std.login_id" pa-2>
                                <v-avatar>
                                    <img :src="std.profile_photo_url">
                                </v-avatar> &nbsp;&nbsp;
                                <span class="small-title" style="color:black"> {{std.name_kana}} &nbsp; ( {{std.name_eng}} )</span>
                            </v-flex>
                        </v-card>
                    </v-flex>

                    <v-flex xs12 sm12 md12 lg12 pa-2>
                        <span class="small-title">
                            内定承諾
                        </span>

                        <v-alert v-if="nominateAccepted.length == 0" :value="true" type="error">
                            内定承諾状態の学生がいません。
                        </v-alert>
                        <v-card flat v-if="nominateAccepted.length > 0 ">
                            <v-flex xs6 sm6 md6 lg6 v-for="std in nominateAccepted" :key="std.login_id" pa-2>
                                <v-avatar>
                                    <img :src="std.profile_photo_url">
                                </v-avatar> &nbsp;&nbsp;
                                <span class="small-title" style="color:black"> {{std.name_kana}} &nbsp; ( {{std.name_eng}} )</span>
                            </v-flex>
                        </v-card>
                    </v-flex>

                    <v-flex xs12 sm12 md12 lg12 pa-2>
                        <span class="small-title">
                            内定辞退
                        </span>

                        <v-alert v-if="nominateCancel.length == 0" :value="true" type="error">
                                内定辞退状態の学生がいません。
                        </v-alert>

                        <v-card flat v-if="nominateCancel.length > 0 ">
                            <v-flex xs12 sm12 md12 lg12 v-for="std in nominateCancel" :key="std.login_id" pa-2>
                                <v-layout row pa-3>
                                <v-flex xs12 sm12 md4 lg4>
                                    <v-avatar>
                                        <img :src="std.profile_photo_url">
                                    </v-avatar> &nbsp;&nbsp;
                                    <span class="small-title" style="color:black">{{std.name_kana}}</span>
                                </v-flex>
                                
                                <v-flex xs12 sm12 md8 lg8 style="background:red;">
                                    <span class="grey--text subheading">&mdash; {{std.resign_comment}}</span>
                                </v-flex>     
                                </v-layout>                           
                            </v-flex>
                            
                        </v-card>
                    </v-flex>

                </v-flex>        
            </v-flex>
        
        </v-layout>
    </v-container>
</template>

<style scoped lang="css" src="../../static/css/Company/Nominate.css"></style>
<script>
    import Chart from "./nominateChart.vue";

    export default{
        
        components : {
            "nominated-chart" : Chart,
        },

        data(){
            return {

                /**
                 * @desc college list settings 
                 */
                filter      : null,
                perPage     : 5,
                totalRows   : null,
                collegeSum  : null,
                currentPage : 1,
                
                overNotices : null,
                collegeListItems  : [],      
                overNoticeHeader : 
                [
                    { key : "org_name"       , label : "大学" },
                    { key : "org_agent_name" , label : "エージェント"   },
                    { key : "employment_name" , label : "タイトル"   },
                    { key : "desired_employee_content" , label : "募集分野"   },
                    { key : "working_area" , label : "勤務地域" },
                    { key : "pay" , label : "給料" },
                    { key : "applied" , label : "内定者 / 承諾"   },
                ],
                selectedEmploy : null,

                /**
                 * @desc get from server
                 */
                nominateAccepted : [],
                nominateCancel : [],
                nominateWait : [],

            }
        },
        methods : {

            onFiltered ( filteredItems ) {
                this.totalRows = filteredItems.length;
                this.currentPage = 1
            },

            getNominatedInfo ( nominate ) {
                
                this.selectedEmploy = nominate.employment_id;

                let reqHttpAddr = "/nominatedStdList";
                let sendData    = { requester : this.$store.getters.identification,
                                    notice    : nominate.employment_id,
                                    interview : nominate.interview_id  };

                this.axios.post( reqHttpAddr , sendData ).then( res => {
                    
                    if(!res.data) return;

                    this.nominateAccepted = [];
                    this.nominateCancel   = [];
                    this.nominateWait     = [];

                    for( let key in res.data ) {
                        if(res.data[key].professor_acceptance_ox  == "o" && res.data[key].professor_acceptance_ox == "o") {
                            this.nominateAccepted.push(res.data[key]); 

                        }else if ( res.data[key].professor_acceptance_ox == "x" && res.data[key].professor_acceptance_ox == "o") {
                            this.nominateCancel.push(res.data[key]);

                        }else{
                            this.nominateWait.push(res.data[key]);
                        }
                    }
                    
                }).catch( err => {
                    console.log(err.message);
                })
            }
        },
        created : function () {

            let reqHttpAddr = "/nominatedInfo";
            let sendData    = { requester : this.$store.getters.identification }

            this.axios.post(reqHttpAddr , sendData).then( res => {

                if (!res.data) return;
                
                for(let i = 0 ; i < res.data.matching_data.length ; i++){
                    res.data.matching_data[i].applied   = res.data.nominated[i].std;
                    res.data.matching_data[i].nominated = res.data.acceptNominate[i].std; 
                }

                this.overNotices = res.data.matching_data;
                this.totalRows = this.overNotices.length;

            }).catch( err => {
                console.log(err.message);
            })
        }
    }
</script>
