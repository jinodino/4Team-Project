<template>
  <company-list-up :orgId = "user.org_college_id" v-if="user.org_college_id != null"></company-list-up>
</template>

<script>
import CompanyEmploymentListUp from "../../shared/CompanyEmploymentListUp.vue"

import vueSlider from 'vue-slider-component';
export default {
  components : {
    'company-list-up' : CompanyEmploymentListUp,
      vueSlider
  },

  created : function (){
    this.user.login_id = this.$store.getters.identification;
    this.user.classification = this.$store.getters.classification;

    let login_id = this.user.login_id;

    this.axios.post('/student/getOrgId', {login_id})
              .then(req=>{
                this.user.org_college_id = req.data.org_college_id;
              })
              .catch(ex=>{
                console.log(ex);
              });

  },

  data : ()=>({
    user : {
      login_id : null,    
      
      classification : null,
      org_college_id : null,
    },

    slider: [0, 10],
  })
  // create() {
  //   getAnonStdList();
  // },

  // methods : {
  //       getAnonStdList : function ( schoolIdentification ) {
            
  //           let reqHttpAddr = "/companyList";
  //           let sendData    = { stdId         : 'st01'};

  //           this.axios.post(reqHttpAddr , sendData).then( res => {
                
  //               if( !res )   return;
                
                
  //           }).catch( err => {
  //               console.log(err.message);
  //           })
  //       },
  // }
}
</script>

<style>
  .sectionS{
    background-color:lavender;
  }
</style>