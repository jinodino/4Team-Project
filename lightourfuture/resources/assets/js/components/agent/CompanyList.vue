<template>
  <v-container fluid grid-list-xs>
        <v-tabs
          slot="extension"
          v-model="tab"
          grow
          fixed-tabs
          v-show ="user.affiliation.length != 0"
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
              <company-list-up 
              v-if="business.business_big_infos.value != null && business.business_small_infos.value != null"
              :user="user"
              :orgAgent="org_agent" 
              :japanRegionsMenu="japan_regions_menu" 
              :japanPrefecturesMenu="japan_prefectures_menu" 
              :business="business" 
              ></company-list-up>
            </v-tab-item>
        </v-tabs-items>
    </v-container>
</template>

<script>
import CompanyListUp from './CompanyListUp';
import vueSlider from 'vue-slider-component';

export default {
    components: {
      'company-list-up' : CompanyListUp,
      vueSlider
    },

    created :  function (){

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

     //검색 항목 가져오기
      let tableList = ['business_big_infos', 'business_small_infos'];
      this.axios.post('/item/listUp', {tableList})
                .then(rep =>{
                    this.business.business_big_infos.value = rep.data.business_big_infos;
                    this.business.business_small_infos.value = rep.data.business_small_infos;
                })
                .catch(ex=>{console.log(ex)});

      //일본 행정구역 가져오기
      this.axios.post('/japan/getTodouhuken', )
                .then(rep =>{
                  let arr = [{id : 'ALL', name_kanji : '全体'}];
                  this.japan_regions_menu = arr.concat(rep.data.regions);

                  let prefectures = rep.data.prefectures;
                  for(let index in prefectures){
                    let item = [{id:'ALL', name_kanji : '全体', region_id : 'ALL'}];
                    prefectures[index] = item.concat(prefectures[index]);
                  }
                  prefectures.ALL = [{id : 'ALL', name_kanji : '全体', region_id : 'ALL'}];

                  this.japan_prefectures_menu = prefectures; 
                })
                .catch(ex=>{
                  console.log(ex);
                });
      
    },

  data : () => ({

    user : {
      login_id : null,
      classification : null,
      affiliation : []
    },

    tab : "0",

    japan_regions_menu : [],
    japan_prefectures_menu : [],

    //employment : [],

    //기업 검색 항목
    business :{     
      business_big_infos : {name : 'business_big_infos', title : '사업 대분류', value : null},
      business_small_infos : {name : 'business_small_infos', title : '사업 소분류', value : null},
    },

  }),

  methods: {
    click (){
      alert("dd");
    },
    toggleAll () {
      if (this.selected.length) this.selected = []
      else this.selected = this.items.slice()
    },

    changeSort (column) {
      if (this.pagination.sortBy === column) {
        this.pagination.descending = !this.pagination.descending
      } else {
        this.pagination.sortBy = column
        this.pagination.descending = false
      }
    },

  }
}
</script>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>