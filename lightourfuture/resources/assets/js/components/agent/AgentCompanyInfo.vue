<template>
  <v-container fluid>
      <v-layout wrap>
            <v-flex xs10>
                <v-flex xs12>
                    ホスティングエージェント : {{orgAgent.org_name}}({{orgAgent.org_name_kana}})
                </v-flex>
                <v-flex xs12>
                    営業年度： : 
                    <v-chip v-for="(item, key) in matchingYearList" :key="key" label disabled outline color="black" small>
                    {{item.matching_date}}
                    </v-chip>
                </v-flex>
            </v-flex>
            <v-flex xs2 v-if="!modalMode">
                <v-btn block router :to="'/agent/companylist'">リストに戻る</v-btn>
                <!-- <v-btn v-if="!modalMode" block right router :to="'/agent/schoollist'">목록으로</v-btn> -->
            </v-flex>
            <v-chip v-if="isThisYearCompanyBool" color="success" outline label disabled class="title">
                <v-icon left>bookmark</v-icon> 
                今年 ({{thisYear}}年度)営業締結企業 
                <!-- 올해 ({{thisYear}}년도) 영업 체결 기업 -->
            </v-chip>
            
            <company-info v-if="orgCompanyId != null" :orgCompanyId="orgCompanyId"></company-info>
            <company-info v-else :orgCompanyId="org_company_id"></company-info>
            <v-flex border>
                <v-btn  v-if="!isThisYearCompanyBool" large fixed bottom  color="success" @click="setThisYearCompany">{{thisYear}}年度営業締結する</v-btn>
            
                <v-btn large fixed bottom right color="dahong " dark @click="go2companyUpdate">修正</v-btn>
            </v-flex>
      </v-layout>
  </v-container>
</template>


<script>
import CompanyInfo from "../../shared/CompanyInfo.vue"

export default {
  props : ['orgCompanyId', 'orgAgentId', 'modalBool'],
  components : {
    'company-info' : CompanyInfo,
  },

  created(){
    this.thisYear = new Date().getFullYear();

    this.user.login_id = this.$store.getters.identification;
    this.user.classification = this.$store.getters.classification;

     //모달이 아닐 떄
    if(this.orgCompanyId == null){
          let org_company_id = this.$route.query.company_id; 
          if(org_company_id == null){
            this.$router.push({path:'/agent/companylist'});
          }else{
            this.org_company_id = org_company_id;
            this.org_agent_id = this.$route.query.agent_id;
            this.modalMode = false;

            this.getOrgAgentById(this.org_agent_id);
          }
    }
    //모달일 때
    else{
      this.getOrgAgentById(this.orgAgentId);
    }

    this.isThisYearCompany();
    /*
    let orgCompanyId = this.$route.query.company_id;
    if(orgCompanyId == null)
      this.org_company_id = this.orgCompanyId;
    else
      this.org_company_id = this.$route.query.company_id;
*/
  },

  data : ()=>({
    modalMode : true,
    thisYear : null,
    isThisYearCompanyBool : false,
    matchingYearList : [],
    user : {
      login_id : null,
      classification : null,
    },

    org_company_id : null,
    org_agent_id : null,

    orgAgent : {},

  }),


  methods : {
    //agent 정보 가져오기
    getOrgAgentById(agent_id){

      this.axios.post('/agent/getOrgAgentById', {agent_id})
                .then(rep=>{
                    this.orgAgent = rep.data.orgAgent;
                })
                .catch(ex=>{
                    console.log(ex);
                });
    },

    
    //올해 영업 체결 기업인지 확인
    isThisYearCompany(){
      let org_agent_id = null;
      let org_company_id = null;

      if(this.modalMode){
        org_agent_id = this.orgAgentId;
        org_company_id = this.orgCompanyId;
      }else{
        org_agent_id = this.org_agent_id;
        org_company_id = this.org_company_id;
      }

      this.axios.post('/agent/isThisYearCompany',{org_agent_id, org_company_id})
                .then(rep=>{
                  this.isThisYearCompanyBool = rep.data.isThisYearCompany;
                  this.matchingYearList = null;
                  this.matchingYearList = rep.data.matchingYearList;
                })
                .catch(ex=>{
                  console.log(ex);
                });
    
    },

    //기업 영업 확정
    setThisYearCompany(){
      let org_agent_id = this.orgAgent.org_agent_id;
      let org_company_id = this.orgCompanyId;

      this.axios.post('/agent/setThisYearCompany',{org_agent_id, org_company_id})
                  .then(rep=>{
                    if(rep.data.returnBool){
                      alert('締結できました。');
                      this.isThisYearCollegeBool = true;
                    }
                  })
                  .catch(ex=>{
                    console.log(ex);
                  });
    },

    //기업 정보 수정 페이지 이동
    go2companyUpdate(){
      let org_company_id = null;
      if(this.modalMode)
        org_company_id = this.orgCompanyId;
      else
        org_company_id = this.org_company_id;

      this.$router.push({
        name : 'companyregist',
        query : {company_id : org_company_id, agent_id : this.orgAgent.org_agent_id},
        params : {orgAgent : this.orgAgent, mode : 'update'},
      });
    }
  },

    
  watch : {
      orgCompanyId : function (val){
                        this.isThisYearCompany();
                      },

      modalBool : function (val){
                    this.isThisYearCompany();
                  },
  }
}
</script>



<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>