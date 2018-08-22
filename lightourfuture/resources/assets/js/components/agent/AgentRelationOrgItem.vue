<template>
    <v-container grid-list-lg text-xs-center fluid>
    <!-- 매칭 셀렉트 -->
    <v-layout row wrap justify-center>
        <v-flex xs12>
            <v-card>
                <v-container fluid>
                    <v-layout row justify-center>
                        <v-flex xs5 md4 lg4>
                            <v-select
                                label="今年の学校"
                                :items ="kotoshiSchoolList"
                                item-text="org_name_kana"
                                item-value="org_college_id"
                                :disabled="kotoshiSchoolList.length == 0"
                                v-model="matchingSchool">
                            </v-select>
                        </v-flex>
                        <v-flex xs5 md4 lg4>
                            <v-select
                            label="今年の企業"
                            :items ="kotoshiCompanyList"
                            item-text="org_name_kana"
                            item-value="org_company_id"
                            :disabled="kotoshiCompanyList.length == 0"
                            v-model="matchingCompany">
                            </v-select>
                        </v-flex>
                        <v-flex xs12 md3 lg2>
                            <v-btn   
                            large
                            color="primary"
                            @click="matching" :disabled="!matchingCompany || !matchingSchool">matching</v-btn>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </v-flex>
    </v-layout>

    <!-- 매칭 테이블 -->
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-container fluid>
                    <v-layout row justify-center>
                        <p class="resultfont" text-xs-center>
                            マッチングステータス
                        </p>
                    </v-layout>
                    <v-layout row justify-end>
                        <v-flex xs4>
                            <v-select
                                label="年度"
                                :items="yearList"
                                v-model="searchMatchingYear"
                                item-text="label"
                                item-value="value"
                            ></v-select>
                        </v-flex>
                    </v-layout>
                </v-container>

                <b-table :fields="matchingHeaders" :filter="searchMatchingYear" responsive small :items="matchingList" empty-filtered-text="not Data">
                    <template slot="college_name_kana" slot-scope="data">
                        <p id="tdstyle">{{data.item.college_name_kana}}</p>
                    </template> 
                    <template slot="company_name_kana" slot-scope="data">
                        <p id="tdstyle">{{data.item.company_name_kana}}</p>
                    </template>
                    <template slot="employment_count" slot-scope="data">
                        <p id="tdstyle">{{data.item.employment_count}}件</p>
                    </template>
                    <template slot="matching_date" slot-scope="data">
                        <p id="tdstyle">{{data.item.matching_date}}</p>
                    </template>
                    <template slot="employment_decision_ox" slot-scope="data">
                        <p id="tdstyle">
                            <v-switch  
                                color="primary"
                                @click.native="setMatchingDecision(data.item.org_matchings_id, data.item.employment_decision_ox, data.index)" 
                                :disabled="data.item.matching_date != thisYear" 
                                v-model="data.item.employment_decision_ox" 
                                :value="data.item.employment_decision_ox" 
                                :label="data.item.employment_decision_ox?'o':'x'"
                             >
                            </v-switch>
                        </p>
                    </template>
                    <template slot="name" slot-scope="data">
                        
                            <v-btn  small 
                                color="primary"
                                @click="data.expanded = !data.expanded;"
                                :disabled="data.item.employmentList.length == 0"
                                @click.stop="data.toggleDetails"
                            >
                                採用件閲覧
     
                            </v-btn>

                            <v-btn  small  color="success" :disabled="data.item.matching_date != thisYear"
                            @click="go2RecruitRegist(data.item.org_matchings_id)">
                                採用件登録
                            </v-btn>
                
                            <v-btn  small  color="error" @click="deleteMatchingItem(data.item.org_matchings_id)" :disabled="data.item.matching_date != thisYear">
                                マッチング削除
                            </v-btn>
                        
                    </template>
                    <template slot="x" slot-scope="data">
                        <p id="tdstyle" v-if="data.item.employmentList.length != 0" >
                            <v-icon v-if="data.detailsShowing" large>keyboard_arrow_down</v-icon>
                            <v-icon v-else large>keyboard_arrow_up</v-icon>
                        </p>
                    </template>
                    <template slot="row-details" slot-scope="data">
                        <b-table style="margin-top:15px;" :fields="employmentHeaders" responsive :items="data.item.employmentList" item-key="faculty_id"  striped>
                            <template slot="employment_id" slot-scope="data">
                                <p>{{data.item.employment_id}}</p>
                            </template> 
                            <template slot="employment_name" slot-scope="data">
                                <p>{{data.item.employment_name}}</p>
                            </template>
                            <template slot="whole_interview_count" slot-scope="data">
                                <p>{{data.item.whole_interview_count}}</p>
                            </template> 
                            <template slot="student_count" slot-scope="data">
                                <p>{{data.item.student_count}}</p>
                            </template>
                            <template slot="apply_deadline_date" slot-scope="data">
                                <p>{{data.item.apply_deadline_date}}</p>
                            </template> 
                            <template slot="actions" slot-scope="data">
                                <p>
                                    <v-btn small color="primary" @click="go2RecruitInfo(data.item.org_matching_foreign, data.item.employment_id)">
                                        閲覧
                                    </v-btn>

                                    <v-btn small v-if="data.item.student_conunt != 0" @click="deleteRecruit(data.item.employment_id)" disabled>
                                        削除
                                    </v-btn>

                                    <v-btn small v-else color="error" @click="deleteRecruit(data.item.employment_id)" :disabled="data.item.student_conunt != 0">
                                        削除
                                    </v-btn>
                                </p>
                            </template>
                        </b-table>
                    </template>
                </b-table>
                <template>
                    <v-alert v-if="!matchingList" :value="true" color="error" icon="warning">
                        There's no employment list to show :(
                    </v-alert>
                </template>
                
                <!-- <v-data-table
                    :headers="m1atchingHeaders"
                    :items="matchingList"
                    item-key="org_matchings_id"
                    :search="searchMatchingYear"
                    class="elevation-1"
                    >
                    <template slot="items" slot-scope="props">
                        <td class="text-xs-left">{{props.item.college_name_kana}}</td>
                        <td class="text-xs-left">{{props.item.company_name_kana}}</td>

                        <td class="text-xs-center">
                            {{props.item.employment_count}}件
                        </td>
                        <td class="text-xs-left">
                            {{props.item.matching_date}}
                        </td>


                        <td class="text-xs-center"> 
                            <v-switch  
                                @click="setMatchingDecision(props.item.org_matchings_id, !props.item.employment_decision_ox)" 
                                :disabled="props.item.matching_date != thisYear" 
                                v-model="props.item.employment_decision_ox" 
                                :value="props.item.employment_decision_ox" 
                                :label="props.item.employment_decision_ox?'o':'x'"
                                color="primary"
                            >
                            </v-switch>
                        </td>

                        <td class="text-xs-center">

                            <v-btn small 
                                color="primary"
                                @click="props.expanded = !props.expanded;"
                                :disabled="props.item.employmentList.length == 0"
                            >
                                採用件閲覧
     
                            </v-btn>

                            <v-btn   small    :disabled="props.item.matching_date != thisYear"
                            @click="go2RecruitRegist(props.item.org_matchings_id)">
                                採用件登録
                            </v-btn>
                
                            <v-btn   small @click="deleteMatchingItem(props.item.org_matchings_id)" :disabled="props.item.matching_date != thisYear" color="error">
                                マッチング削除
                            </v-btn>
                        </td>
                        <td v-if="props.item.employmentList.length != 0" class="text-xs-center">
                            <v-icon v-if="props.expanded" large>keyboard_arrow_down</v-icon>
                            <v-icon v-else large>keyboard_arrow_up</v-icon>
                        </td>
                        <td v-else></td>
                    </template>
                    <template slot="expand" slot-scope="props">
                        <v-card color="dahong"> 
                            <v-card-text>
                                <v-data-table
                                :headers="employmentHeaders1"
                                :items="props.item.employmentList"
                                hide-actions
                                >
                                    <template slot="items" slot-scope="props">
                                        <td class="text-xs-center">{{props.item.employment_id}}</td>
                                        <td class="text-xs-center">{{props.item.employment_name}}</td>
                                        <td class="text-xs-center">{{props.item.whole_interview_count}}回</td>
                                        <td class="text-xs-center">{{props.item.student_count}}人</td>
                                        <td class="text-xs-left">{{props.item.apply_deadline_date}}</td>
                                        <td> 
                                            <v-btn small color="primary" @click="go2RecruitInfo(props.item.org_matching_foreign, props.item.employment_id)">
                                                閲覧
                                            </v-btn>

                                            <v-btn small v-if="props.item.student_conunt != 0" @click="deleteRecruit(props.item.employment_id)" disabled>
                                                削除
                                            </v-btn>

                                            <v-btn small v-else color="error" @click="deleteRecruit(props.item.employment_id)" :disabled="props.item.student_conunt != 0">
                                                削除
                                            </v-btn>
                                        </td>
                                    </template>
                                    <template slot="no-data" slot-scope="">
                                        <v-alert :value="true" color="error" icon="warning">
                                            There's no employment list to show :(
                                        </v-alert>
                                    </template>
                                </v-data-table> 
                            </v-card-text>
                
                        </v-card>
                    </template>
                    <template slot="no-data">
                        <v-alert>
                            <v-alert :value="true" color="error" icon="warning">
                                There's no matching list to show :(
                            </v-alert>
                        </v-alert>
                    </template>
                </v-data-table> -->
            </v-card>
        </v-flex>
    </v-layout>

    </v-container>

</template>

<script>
export default {
    props :['orgAgent'],

    created : function (){
        //사용자 확인
        this.user.login_id = this.$store.getters.identification;
        this.user.classification = this.$store.getters.classification;

        let year = new Date().getFullYear();
        
        this.thisYear = year;
        this.searchSchoolYear = year,
        this.searchCompanyYear = year,
        this.searchMatchingYear = year,

        //학교 리스트 가져오기
        //this.getSchoolList(org_agent_id);
        this.getSchoolList(this.orgAgent.org_agent_id, year);

        //기업 리스트 가져오기
        //this.getCompanyList(org_agent_id);
        this.getCompanyList(this.orgAgent.org_agent_id, year);

        //매칭리스트 가져오기
        this.getMatching(this.orgAgent.org_agent_id);

        //년도 리스트 업
        for(let i = year; i >= 2016; i--)
            this.yearList.push({ label : i , value : i });
        this.yearList.push({ label : '全年度' , value : null });
    },

    data : ()=>({
        //matchingOX : true,

        // schoolList : null,
        // companyList : null,
        user : {
            login_id : null,
            classification : null,
        },

        matchingList: [],

        thisYear : '',
        yearList : [],

        searchMatchingYear:null,

        employment_decision_bool : false,

      
        m1atchingHeaders : [
            //{text : 'Id', value : '',  sortable: false, align:'center'},
            {text : '学校', value : 'college_name_kana', },
            {text : '企業', value : 'company_name_kana', },
            {text : '募集ポジション数', value : 'employment_count', sortable : false, align : 'center'},
            {text : '年度', value : 'matching_date', width : '100px'},
            {text : '採用活動の参画', value : 'employment_decision_ox', sortable:false, align:'left'},
            // {text : '内定辞退', value : 'acceptance_fixed_ox', sortable:false, align:'center'},
            {text : 'Actions', value : 'name', sortable:false, align:'center'},
            {text : ''}
        ],
        matchingHeaders : [
            {label : '学校', key : 'college_name_kana', },
            {label : '企業', key : 'company_name_kana', },
            {label : '募集ポジション数', key : 'employment_count'},
            {label : '年度', key : 'matching_date'},
            {label : '採用活動の参画', key : 'employment_decision_ox'},
            {label : 'Actions', key : 'name'},
            {label : '', key : 'x',}
        ],

        employmentHeaders1 : [
            {text : 'Id', value : 'employment_id', sortable : false, align:'center'},
            {text : '募集タイトル', value : 'employment_name', sortable : false, align:'center'},
            {text : '面接数', value : 'whole_interview_count', sortable : false, align:'center'},
            {text : '志望者数', value : 'student_count', sortable : false, align:'center'},
            {text : '志望締め切り', value : 'apply_deadline_date'},
            {text : 'Actions', value : 'actions', sortable:false, align:'center'},
        ],
        employmentHeaders : [
            {label : 'Id', key : 'employment_id'},
            {label : '募集タイトル', key : 'employment_name'},
            {label : '面接数', key : 'whole_interview_count'},
            {label : '志望者数', key : 'student_count'},
            {label : '志望締め切り', key : 'apply_deadline_date'},
            {label : 'Actions', key : 'actions'}
        ],
        
        kotoshiSchoolList : [],
        kotoshiCompanyList : [],
        matchingSchool : null,
        matchingCompany : null,
    }),

    methods : {

        getSchoolList(org_agent_id, year){
            let mode = 'simple';
            this.axios.post('/agent/getOrgRelColInfo', {org_agent_id, mode})
                        .then(rep=>{
                            if(year != null)
                                this.kotoshiSchoolList = rep.data;    
                            else
                                this.schoolList = rep.data;
                        })
                        .catch(ex=>{
                            console.log(ex);
                        });
        },

        getCompanyList(org_agent_id, year){
            this.axios.post('/agent/getOrgRelComInfo', {org_agent_id, year})
                        .then(rep=>{
                            if(year != null)
                                this.kotoshiCompanyList = rep.data;
                            else
                                this.companyList = rep.data;
                        })
                        .catch(ex=>{
                            console.log(ex);
                        });
        },

        getMatching(org_agent_id, year){
            this.axios.post('/agent/getMatching', {org_agent_id, year})
                        .then(rep=>{
                            let matchingList =  rep.data.matchingList;
                            let employmentList = rep.data.employmentList;

                            for( let index in matchingList){
                                let org_matchings_id = matchingList[index].org_matchings_id;
                                matchingList[index].employmentList = employmentList[org_matchings_id];
                            }

                            for(let index in matchingList){
                                let ox = matchingList[index].employment_decision_ox;
                                if(ox == 'o')
                                    matchingList[index].employment_decision_ox = true;
                                else
                                    matchingList[index].employment_decision_ox = false;
                            }
                            this.matchingList = null;
                            this.matchingList = matchingList;
                        })
                        .catch(ex=>{
                            console.log(ex);
                        });
        },

        matching(){
            let org_agent_id = this.orgAgent.org_agent_id;
            let org_college_id = this.matchingSchool;
            let org_company_id = this.matchingCompany;

            this.axios.post('/agent/orgMatching',{org_agent_id, org_college_id, org_company_id})
                        .then(rep=>{
                            console.log(rep.data.returnBool);
                            if(rep.data.returnBool)
                                alert('マッチングできました。');
                            else
                                alert('もうマッチングされています。');
                            this.getMatching(this.orgAgent.org_agent_id);
                            this.matchingSchool = null;
                            this.matchingCompany = null;
                        })
                        .catch(ex=>{
                            console.log(ex);
                        })
        },

        deleteMatchingItem(org_matchings_id){
            let login_id = this.user.login_id;

            this.axios.post('/agent/deleteMatching', {org_matchings_id, login_id})
                        .then(rep=>{
                            if(rep.data.returnBool)
                                alert('マッチングが削除されました。');
                            else
                                alert('登録されている採用件があってマッチングを削除できません。');

                            this.getMatching(this.orgAgent.org_agent_id);
                        })
                        .catch(ex=>{
                            console.log(ex);
                        })
        },

        setMatchingDecision(org_matchings_id, employment_decision_bool, index){
            let msg = '';
            if(employment_decision_bool)
                msg = '採用を決定しますか？';
            else 
                msg = '採用を取り消しますか？'
            

            let yesNo = confirm(msg);
            if(!yesNo){
                alert('キャンセルされました。');
                this.matchingList[index].employment_decision_ox = !employment_decision_bool;
                return;
            }

            let employment_decision_ox = employment_decision_bool ? 'o' : 'x';
            if(employment_decision_ox == 'x'){
                for( let i in this.matchingList[index].employmentList){
                    if(this.matchingList[index].employmentList[i].student_count > 0){
                        alert('採用件に志願した学生がいます。採用決定が取り消せません。');
                        this.matchingList[index].employment_decision_ox = !employment_decision_bool;
                        return;
                    }
                }
            }


            this.axios.post('/agent/setMatchingDecision', {org_matchings_id, employment_decision_ox})
                        .then(rep=>{
                            if(rep.data.employment_decision_ox == 'o')
                                alert('採用が決定されました。');
                            else
                                alert('採用がキャンセルされました。');

                            this.getMatching(this.orgAgent.org_agent_id);
                        })
                        .catch(ex=>{
                            console.log(ex);
                        })
        },
        
        go2MatchingInfo(item){
            this.$router.push({
                path:'/agent/matchingInfo', 
                //name : 'agentMatchingInfo',
                query:{matching_id : item.org_matchings_id, agent_id : this.orgAgent.org_agent_id, company_id : item.org_company_id, college_id : item.org_college_id},
            });
        },

        go2CompanyInfo(company_id){
            this.$router.push({
                name:'agentCompanyInfo',
                //path:'/agent/companyInfo',
                query : {company_id : company_id},
                params : {orgAgent : this.orgAgent},
            });
        },

        go2CollegeInfo(college_id){
            this.$router.push({
                path:'/agent/collegeInfo',
                query : {college_id : college_id},
            });
        },


        go2RecruitRegist(matchingId){
            let orgAgent = this.orgAgent;   
            
            this.$router.push(
                {   //path : '/agent/recruitregist', 
                    name : 'recruitregist',
                    query : { matching_id : matchingId },
                    params:{ orgAgent : orgAgent }
                });
        },

        go2RecruitInfo(org_matchings_id, employment_id){
            this.$router.push({
            path : '/agent/recruit',
            query : {employment_id : employment_id, matching_id : org_matchings_id, agent_id : this.orgAgent.org_agent_id}
            });
        },

        deleteRecruit(employment_id){

            let yesNo = confirm('採用件を削除しますか？');
            if(!yesNo){
                alert('キャンセルされました。');
                return;
            }
            let login_id = this.user.login_id;
            let orgAgent = this.orgAgent;

            this.axios.post('/company/deleteRecruit',{employment_id, login_id, orgAgent})
                    .then(rep=>{
                        if(rep.data.returnBool){
                            alert('削除されました。');
                            this.getEmploymentList();
                        }else{
                            let code = rep.data.returnCode;
                        if(code == 1)
                            alert('登録されているスケジュールがいて削除できません。');
                        else if(code == 2)
                            alert('さいように志願した学生がいて削除でいません。');
                        }
                    
                    })
                    .catch(ex=>{
                        console.log(ex);
                    });
        },



    },
}
</script>
<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
