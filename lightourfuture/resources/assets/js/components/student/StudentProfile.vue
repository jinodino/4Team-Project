<template>
  <v-container grid-list-sm text-xs-center fluid>  
      <student-gibon :user="user"></student-gibon>
      <student-lang :user="user"></student-lang>
      <!-- <student-lang-match :user="user"></student-lang-match> -->
      <student-skill :user="user"></student-skill>
      <student-appeal :user="user"></student-appeal>
  </v-container>

</template>





<script>

import studentGibon from "./StudentProfile_gibon.vue";
import studentLang from "./StudentProfile_lang.vue";
import studentLangMatch from "./StudentProfile_langMatch.vue";
import studentSkill from "./StudentProfile_skill.vue";
import studentAppeal from "./StudentProfile_appeal.vue";

export default {
  components : {
    'student-gibon' : studentGibon,
    'student-lang' : studentLang,
    'student-lang-match' : studentLangMatch,
    'student-skill' : studentSkill,
    'student-appeal' : studentAppeal,
  },

  created : function(){
    this.user.login_id = this.$store.getters.identification;
    this.user.classification = this.$store.getters.classification;
  },

  data : ()=>({

    user : {
      login_id : null,
      classification : null,
    },
    
    profileDialog: false,
 
  }),

  methods : {

    test(e){//이 메서드는 학생 프로필과 관련된 정보를 뽑아서 주는 메서드
      let url = '/user_profile';
      let test = 'test';
        this.axios.post(url, test).then(res=>{
            
            alert(res.data);
        });
    },

   
  },


  watch : { 
    profileDialog(val){
      val || this.close();
    }
  }

}
</script>
