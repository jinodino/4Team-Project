<template>
  <v-container fluid>
    <v-layout block wrap>
      <v-flex xs10>
      </v-flex>
      <v-flex xs2>
        <v-btn block @click="go2back">リストに戻る</v-btn>
      </v-flex>
      <!-- <v-btn block @click="go2back">뒤로</v-btn> -->
      <recruit-info :employmentId="employment_id"></recruit-info>
      <v-layout  justify-end>
        <v-btn large right bottom dark color="dahong" fixed @click="go2recruitUpdate">修正</v-btn>
      </v-layout>
      
    </v-layout>
  </v-container>
</template>


<script>
import Recruit from "../../shared/Recruit.vue"

export default {

  components : {
    'recruit-info' : Recruit,
  },

  created(){
    this.user.login_id = this.$store.getters.identification;
    this.user.classification = this.$store.getters.classification;
       
    this.employment_id = this.$route.query.employment_id;
    this.matching_id = this.$route.query.matching_id;
    this.org_agent_id = this.$route.query.agent_id;
    
    //소속 에이전트 기관 가져오기
    let agent_id = this.org_agent_id;
    this.axios.post('/agent/getOrgAgentById', {agent_id})
              .then(rep=>{
                  this.orgAgent = rep.data.orgAgent;
              })
              .catch(ex=>{
              console.log(ex);
              });

  },

  data : ()=>({
    user : {
      login_id : null,
      classification : null,
      affiliation : null,
    },
    orgAgent : null,

    employment_id : null,
    matching_id : null,
    org_agent_id : null,

  }),


  methods : {
    go2recruitUpdate(){
      this.$router.push({
        name : 'recruitUpdate',
        query : {employment_id : this.employment_id, matching_id : this.matching_id},
        params : {orgAgent : this.orgAgent},
      });
    },
    go2back(){
      this.$router.go(-1);
    }
  }
}
</script>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>