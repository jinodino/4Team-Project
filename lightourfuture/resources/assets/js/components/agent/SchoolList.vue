<template>
    <v-container fluid grid-list-xs>
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
              <school-list-up 
                v-if="propsCountryList != null && propsMajorList != null" 
                :orgAgent="org_agent" 
                :propsCountryList="propsCountryList" 
                :propsMajorList="propsMajorList">
              </school-list-up>
            </v-tab-item>
        </v-tabs-items>
    </v-container>
</template>

<script>
import managementList from './ManagementList.js';
import schoolListUp from './SchoolListUp';

export default {

  components : {
    'school-list-up' : schoolListUp,
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

    //국가 코드
    this.axios.post('/continent/getCountries')
              .then(rep=>{
                    this.propsCountryList = rep.data;

                    for(let index in this.propsCountryList){
                        let array = [{country_code : 'ALL', country_kana : '一括', country_eng : 'All'}];
                        this.propsCountryList[index] = array.concat(this.propsCountryList[index]);
                    }

                    this.propsCountryList.ALL = [{country_code : 'ALL', country_kana : '一括', country_eng : 'All'}];

              })
              .catch(ex=>{
                  console.log(ex);
              });

    //전공 카테고리
    let tableList = ['major_infos'];
    this.axios.post('/item/listUp', {tableList})
              .then(rep=>{
                  this.propsMajorList = rep.data.major_infos;
              })
              .catch(ex=>{
                console.log(ex);
              });
  },
  
  data : () => ({

    user : {
      login_id : null,
      classification : null,
      affiliation : null
    },

    tab : "0",

    propsCountryList : null,
    propsMajorList : null,

  }),

  methods: {

  }
}
</script>


<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>