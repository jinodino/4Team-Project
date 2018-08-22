<template>
<v-container fluid grid-list-sm>
  <v-layout row>
    <v-flex xs12>
      <v-btn block router :to="'/agent/relations'">목록으로</v-btn>
    </v-flex>
    
    <v-flex xs12>
      <v-card>
        <v-card-text>
            matching id : {{org_matchings_id}} 
        </v-card-text>
      </v-card>
    </v-flex>

    <v-flex xs12>
      <v-expansion-panel>
        <v-expansion-panel-content>
          <div slot="header">college id : {{org_college_id}}</div>
          <v-card>
            <v-card-text>
              <v-container>
                  <college-info :orgCollegeId="org_college_id"></college-info>
              </v-container>
            </v-card-text>
          </v-card>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-flex>
    <v-flex xs12>
      <v-expansion-panel>
        <v-expansion-panel-content>
          <div slot="header">company id : {{org_company_id}}</div>
          <v-card>
            <v-card-text>
              <v-container>
                <company-info :orgCompanyId="org_company_id"></company-info>
              </v-container>
            </v-card-text>
          </v-card>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-flex>

    <v-flex xs12>
      <v-card>
        <v-card-title>
          採用件リスト
          <v-btn color="primary" @click="go2RecruitRegist">
            採用件登録
          </v-btn>
        </v-card-title>
        

        <v-data-table
        :headers="header"
        :items = "employment_list"
        hide-actions
        class="elevation-1"
        >
            <template slot="items" slot-scope="props">
            
              <tr slot="header">
              <!-- <tr @click="go2RecruitInfo(props.item.employment_id)"> -->
                <td class="text-xs-center">{{ props.item.employment_id}}</td>         
                <td class="text-xs-left">{{props.item.employment_name}}</td>
                <td class="text-xs-left"> - </td>
                <td class="text-xs-center">{{props.item.acceptance_fixed_ox}}</td>
                <td class="text-xs-center">
                  <v-btn small color="success"
                   @click="go2RecruitInfo(props.item.employment_id)"
                  >
                      閲覧
                  </v-btn>
                </td>
                       
                <td class="text-xs-left">{{props.item.apply_open_date}}</td>
                <td class="text-xs-left">{{props.item.apply_deadline_date}}</td>
                <td class="text-xs-center">{{props.item.whole_interview_count}}</td>
                <!-- <td class="text-xs-center">
                    <v-btn icon @click="deleteRecruit(props.item.employment_id, org_agent_id)">
                        <v-icon color="error">delete</v-icon>
                    </v-btn>
                </td> -->
              </tr>
            </template>
            <template slot="no-data">
              <v-alert :value="true" color="error" icon="warning">
                登録されている採用件がありません。
              </v-alert>
            </template>


        </v-data-table>

      </v-card>
    </v-flex>
  </v-layout>
</v-container>
</template>

<script>
import CompanyInfo from "./AgentCompanyInfo";
import CollegeInfo from "./AgentCollegeInfo";
import EmploymentInfo from "../../shared/Recruit";
export default {  

    components : {
      'company-info' : CompanyInfo,
      'college-info' : CollegeInfo,
    },

    created : function(){

      this.user.login_id = this.$store.getters.identification;
      this.user.classification = this.$store.getters.classification;

      this.org_agent_id = this.$route.query.agent_id;
      this.org_company_id = this.$route.query.company_id;
      this.org_college_id = this.$route.query.college_id;
      this.org_matchings_id = this.$route.query.matching_id;

      //소속 에이전트 기관 가져오기
      let agent_id = this.org_agent_id;
      this.axios.post('/agent/getOrgAgentById', {agent_id})
                .then(rep=>{
                    this.orgAgent = rep.data.orgAgent;
                })
                .catch(ex=>{
                    console.log(ex);
                });

      this.getEmploymentList();

    },

    data : ()=>({
 
      user : {
        login_id : null,
        classification : null,
      },

      orgAgent : null,
    
      //employment_id : null,
      org_matchings_id : null,
      org_agent_id : null,
      org_company_id : null,
      org_college_id : null,
      employment_list : [],

      header : [
        {text : 'id', value : 'employment_id', sortable: false, align:'center'},
        {text : '채용건 이름', value : 'employment_name', },
        {text : '지원자수', value : ''},
        {text : '내정 여부', value : 'acceptance_fixed_ox', sortable: false, align:'center'},
        {text : '', sortable : false},
        {text : '지원 개시일', value : 'apply_open_date',},
        {text : '지원 마감일', value : 'apply_deadline_date',},
        {text : '총 면접수', value : 'whole_interview_count', sortable: false, align:'center'},
        {text : '削除', value : 'name', sortable:false, align:'center'}
      ],
    }),
    
    methods : {
   
      getEmploymentList(){
        let org_matchings_id = this.org_matchings_id;
        this.axios.post('/company/getEmploymentList',{org_matchings_id})
                  .then(rep=>{
                      this.employment_list = null;
                      this.employment_list = rep.data.employment_list;
                  })
                  .catch(ex=>{
                    console.log(ex);
                  });
      },

      deleteRecruit(employment_id, org_agent_id){

        this.axios.post('/company/deleteRecruit',{employment_id, org_agent_id})
                  .then(rep=>{
                    if(rep.data.returnBool){
                      alert('삭제가 완료되었습니다.');
                      this.getEmploymentList();
                    }else{
                      let code = rep.data.returnCode;
                      if(code == 1)
                        alert('등록된 면접 스케쥴이 있어 삭제가 불가합니다.');
                      else if(code == 2)
                        alert('채용에 지원한 학생이 있어 삭제가 불가합니다.');
                    }
                  
                  })
                  .catch(ex=>{
                    console.log(ex);
                  });
      },

      go2RecruitRegist(){
        // alert('asf');
        this.$router.push(
              {   
                //path : '/agent/recruitregist', 
                name : 'recruitregist',
                params : {orgAgent : this.orgAgent},
                query : {matching_id : this.org_matchings_id},
              }
            );
      },

      go2RecruitInfo(employment_id){
        this.$router.push({
          path : '/agent/recruit',
          query : {employment_id : employment_id, matching_id : this.org_matchings_id, agent_id : this.org_agent_id}
        });
      }

    }

}
</script>

<style>
  ul li{
    list-style:none
  }
</style>
