<template>
  <!-- 스킬 시트 정보 -->
  <v-layout row>
    <v-flex xs12>
      <v-layout row wrap>
        <v-flex xs10>
          <p class="resultfont" style="margin: 10px 15px 0px 13px; text-align: left">プログラミングスキルシート</p>
        </v-flex>
        <v-flex xs2>
          <button right type="button" @click="openSkillDialog" style="float:right; margin:16px 15px 0px 0px">
            <img src="images/common/edit.png" > 
          </button>
        </v-flex>
      </v-layout>
      <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;"></div>
        
      <v-container>
        <v-card-text>
          <v-layout row border>
            <v-flex  xs6 lg3 v-for="field in skill_field_list" :key="field.no" v-if="field.no != 5">
              <v-layout row>
                <v-flex xs12  style="background: #E0E0E0; border: 1px solid #BDBDBD;">
                  {{field.skill_field}}
                </v-flex>
              </v-layout>
              <v-layout row v-for="skill in skill_list[field.no]" :key="skill.no">
                <v-flex xs6 border>
                  {{skill.skill_name}}
                </v-flex>
                <v-flex xs6 border> 
                  {{student_skill_match_print[skill.no] == null ? '-' : student_skill_match_print[skill.no].skill_level}}
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-card-text>
      </v-container>
    </v-flex>

    <!-- 스킬시트 모달 -->
    <v-dialog v-model="skillModify" scrollable width="800px" persistent>
      <v-card>
        <v-card-title  class="grey lighten-4 py-4 headline Titlefont">
            プログラミングスキルシート
            <v-spacer></v-spacer>
            <v-btn large color="error" @click="skillModify = false">X</v-btn>
        </v-card-title>
        <v-card-text>
          <v-container class="pa-1" grid-list-xs>
            <v-layout row>
              <v-flex xs6 lg3 v-for="field in skill_field_list" :key="field.no" v-if="field.no != 5">
                <v-layout row border>
                  <v-flex xs12 class="grey lighten-5 text-xs-center py-3 title">
                    {{field.skill_field}}
                  </v-flex>
                </v-layout>
                <v-layout border align-center justify-space-center v-for="skill in skill_list[field.no]" :key="skill.no">
                  <v-flex xs6>
                    <p class="text-xs-center">{{skill.skill_name}}</p>
                  </v-flex>
                  <v-flex xs5> 
                    <v-select
                      label="level"
                      :items="skill_level_list"
                      item-text="skill_level"
                      item-value="no"
                      v-model="student_skill_match_edit[skill.no].language_level_code"
                    >
                    </v-select>
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="success" @click="updateSkillMatchInfo">セーブ</v-btn>
          <v-btn color="error" @click="skillModify = false">取り消す</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-layout>

</template>





<script>

export default {
  props : ['user'],

  created : function(){
    this.getSkillMatchInfo();


    //스킬 항목
    this.axios.post('/item/skillListUp')
              .then(rep => {
                this.skill_field_list = rep.data.skill_field_list;
                this.skill_list = rep.data.skill_list;
                this.skill_level_list = rep.data.skill_level_list;
              })
              .catch(ex => {
                console.log(ex);
              });

  },

  data : ()=>({

    student_skill_match : {},
    student_skill_match_edit : {},
    student_skill_match_print : {},

    //스킬 리스트
    skill_field_list : null,
    skill_list : null,
    skill_level_list : null,

    skillModify :false,
  
  }),

  methods : {

  //학생 기술 정보 뽑기
  getSkillMatchInfo(){
    let login_id = this.user.login_id;
    this.axios.post('/student/getSkillMatchInfo',{login_id})
    .then(rep=>{
      this.student_skill_match = null;
      this.student_skill_match_print = null;
      this.student_skill_match_print = rep.data.skill_match_print;

      let student_skill_match = rep.data.skill_match;
      this.student_skill_match = student_skill_match;

    for(let i in student_skill_match)
      this.student_skill_match_edit[i] = {'language_code' : student_skill_match[i].language_code, 'language_level_code' : student_skill_match[i].language_level_code, 'student_login_id' : this.user.login_id};
      
      this.student_skill_match_print = rep.data.skill_match_print;
    })
    .catch(ex=>{
      console.log(ex);
    })
  },

  openSkillDialog(){
    this.student_skill_match_edit = {};
    let student_skill_match = this.student_skill_match;

    for(let i in student_skill_match)
      this.student_skill_match_edit[i] = {'language_code' : student_skill_match[i].language_code, 'language_level_code' : student_skill_match[i].language_level_code, 'student_login_id' : this.user.login_id};
      
    this.skillModify = true;
  },

  updateSkillMatchInfo(){
    let skill_match = this.student_skill_match_edit;
    let login_id = this.user.login_id;

    this.axios.post('/student/updateSkillMatchInfo', {skill_match, login_id})
    .then(rep=>{

        if(rep.data.returnBool)
          alert('修正されました。');
        else 
          alert('変更事項がありません。');

        this.getSkillMatchInfo();
        this.skillModify = false;
    })
    .catch(ex=>{
      console.log(ex);
    })

  }

  },


  watch : { 
    profileDialog(val){
      val || this.close();
    }
  }

}
</script>
<style>
  .section{
    background-color:lavender;
  }
</style>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
