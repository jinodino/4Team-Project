<template>
  <v-container fluid grid-list-sm>  
    

     <v-tabs
        slot="extension"
        v-model="tab"
        grow
        fixed-tabs
      >
      <!-- <v-layout>  
        <v-flex xs6 style="margin-left:2px">
          <div v-if="tab == '0'" @click="tab = '0'" id="tabon" class="title">{{user.affiliation[0].org_name}}</div>
          <div v-else @click="tab = '0'" id="taboff" >{{user.affiliation[0].org_name}}</div>  
        </v-flex>
        <v-flex xs6 style="margin-right:2px">
          <div v-if="tab == '1'" @click="tab = '1'" id="tabon" class="title">{{user.affiliation[1].org_name}}</div>
          <div v-else @click="tab = '1'" id="taboff" >{{user.affiliation[1].org_name}}</div>
        </v-flex>
      </v-layout> -->
          <v-tab v-for="org_agent in user.affiliation" :key="org_agent.no">
              <p class="subheading">{{org_agent.org_name}}</p>
          </v-tab>
      </v-tabs> 
    
      <v-tabs-items v-model="tab">
          <v-tab-item v-for="org_agent in user.affiliation" :key="org_agent.no.key">
            <agent-itv-manage-item v-if="skillListInfo != null" 
            :orgAgent="org_agent" 
            :classification="user.classification"
            :skillListInfo="skillListInfo"
            ></agent-itv-manage-item>
          </v-tab-item>
      </v-tabs-items>
    </v-container>
</template>

<script>
import AgentItvManageItem from "./AgentItvManageItem";
export default {
  components : {
    'agent-itv-manage-item' : AgentItvManageItem,
  },

  created : function(){
      this.user.login_id = this.$store.getters.identification;
      this.user.classification = this.$store.getters.classification;

      //소속 에이전트 기관 가져오기
      let user = this.user;
      this.axios.post('/agent/getOrgs', {user})
                .then(rep=>{
                    this.user.affiliation = rep.data;
                })
                .catch(ex=>{
                console.log(ex);
                });

      //스킬 항목
      this.axios.post('/item/skillListUp')
                .then(rep => {
                    this.skillListInfo = rep.data;
               
                })
                .catch(ex => {
                  console.log(ex);
                });

  },

  data : ()=> ({
    user : {
      login_id : null,
      classification : null,
      affiliation : null,
    },
    tab : '0',

    skillListInfo : null,
    chipclick:'0',
  }),
  methods : {

  }
}
</script>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
<style>
  ul li {
    list-style:none
  }
</style>     
