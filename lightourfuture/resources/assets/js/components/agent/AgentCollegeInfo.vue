<template>
    <v-container fluid>
        <!-- 목록으로 -->
        <!-- 내용 -->
        <v-layout wrap>
            <v-flex xs10>
                <v-flex xs12>
                    ホスティングエージェント：{{orgAgent.org_name}}({{orgAgent.org_name_kana}})
                    <!-- 호스팅 에이전트 : {{orgAgent.org_name}}({{orgAgent.org_name_kana}}) -->
                </v-flex>
                <v-flex xs12>
                    営業年度：
                    <!-- 영업 년도 :  -->
                    <v-chip v-for="(item, key) in matchingYearList" label disabled outline color="black" :key="key" small>
                        {{item.matching_date}}
                    </v-chip>
                </v-flex>
            </v-flex>
            <v-flex xs2 v-if="!modalMode">
                <v-btn block router :to="'/agent/schoollist'">リストに戻る</v-btn>
            </v-flex>

            <v-chip v-if="isThisYearCollegeBool" outline label disabled class="title" color="success" large>
                <v-icon left>bookmark</v-icon> 
                今年 ({{thisYear}}年度)営業締結学校 
                <!-- 올해 ({{thisYear}}년도) 영업 체결 학교 -->
            </v-chip>

            <collegeD-info 
            :orgCollegeId="org_college_id" 
            :orgAgentId="org_agent_id" 
            :user="user"
            ></collegeD-info>
            <!-- <college-info 
            :orgCollegeId="org_college_id" 
            :orgAgentId="org_agent_id" 
            :user="user"
            ></college-info> -->

            
            <v-flex >
              <v-flex>
                <v-btn color="dahong" dark fixed bottom right large @click="go2collegeUpdate">修正</v-btn>
                <!-- <v-btn large block color="primary" text-color="white" @click="go2collegeUpdate">수정</v-btn> -->
              </v-flex>
<v-btn v-if="!isThisYearCollegeBool" color="success" dark large @click="setThisYearCollege">{{thisYear}}年度営業締結する</v-btn>
                
            </v-flex>
        </v-layout>
    </v-container>
</template>








<script>
import CollegeInfoD from "../../shared/CollegeInfoD.vue";

import CollegeInfo from "../../shared/CollegeInfo.vue";

export default {

  props : ['orgCollegeId', 'orgAgentId', 'modalBool'],
  components : {
    'college-info' : CollegeInfo,
    'collegeD-info' : CollegeInfoD,
  },

  created : function(){
    this.thisYear = new Date().getFullYear();
  
    this.user.login_id = this.$store.getters.identification;
    this.user.classification = this.$store.getters.classification;

  //모달이 아닐 떄
    // if(this.orgCollegeId == null){
          let org_college_id = this.$route.query.college_id; 
          if(org_college_id == null){
            this.$router.push({path:'/agent/schoollist'});
          }else{
            this.org_college_id = org_college_id;
            this.org_agent_id = this.$route.query.agent_id;
            this.modalMode = false;

            this.getOrgAgentById(this.org_agent_id);
          }
    // }
    // //모달일 때
    // else{
    //   this.getOrgAgentById(this.orgAgentId);
    // }

    this.isThisYearCollege();

      /*
    let org_college_id = this.$route.query.org_college_id;

    if(org_college_id == null){
      if(this.orgCollegeId == null)
        this.$router.push({path:'/agent/relations'});

        this.getOrgAgentById(this.orgAgentId);
        //올해 채용 결정 학교인지 확인
        this.isThisYearCollege(this.org);

      // else 
      //   this.org_college_id = this.orgCollegeId;
    }else{
      this.org_college_id = org_college_id;
      this.org_agent_id = this.$route.query.org_agent_id;

      this.modalMode = false;
    }

    //alert(this.orgAgentId);
*/
  },

  data : ()=>({
    modalMode : true,
    thisYear : null,
    isThisYearCollegeBool : false,
    matchingYearList : [],
    user : {
      login_id : null,
      classification : null,
    },

    org_college_id : null,
    org_agent_id : null,

    orgAgent : {},
    
    matching_date: [{label:2016},{label:2017},{label:2018}],
    info : {
      facultyInfo : [
        { "faculty_id": 1, "org_college_id": "col587109", "department_name": "겜과", "major_id": 5, "major_name": "데이터", "class_name": "1T", "college_category_sub": 3, "data_entry_time": "2018-06-16 14:51:24", "major_tag": "웹 데이터베이스" }, 
        { "faculty_id": 2, "org_college_id": "col587109", "department_name": "겜과", "major_id": 2, "major_name": "겜과", "class_name": "2B", "college_category_sub": 4, "data_entry_time": "2018-06-16 14:51:24", "major_tag": "게임" }, 
        { "faculty_id": 3, "org_college_id": "col587109", "department_name": "겜과", "major_id": 1, "major_name": "디자인", "class_name": "1D", "college_category_sub": 3, "data_entry_time": "2018-06-16 21:54:57", "major_tag": "웹디자인" }, 
        { "faculty_id": 4, "org_college_id": "col587109", "department_name": "공학과", "major_id": 6, "major_name": "공", "class_name": "1G", "college_category_sub": 5, "data_entry_time": "2018-06-16 21:59:08", "major_tag": "컴퓨터 공학" }, 
        { "faculty_id": 5, "org_college_id": "col587109", "department_name": "공학과", "major_id": 7, "major_name": "전자", "class_name": "2G", "college_category_sub": 4, "data_entry_time": "2018-06-16 21:59:08", "major_tag": "전자" } 
      ]
    }
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

    //올해 영업 체결 학교인지 확인
    isThisYearCollege(){
      let org_agent_id = null;
      let org_college_id = null;

      if(this.modalMode){
        org_agent_id = this.orgAgentId;
        org_college_id = this.orgCollegeId;
      }else{
        org_agent_id = this.org_agent_id;
        org_college_id = this.org_college_id;
      }

      this.axios.post('/agent/isThisYearCollege',{org_agent_id, org_college_id})
                .then(rep=>{
                  this.isThisYearCollegeBool = rep.data.isThisYearCollege;
                  this.matchingYearList = null;
                  this.matchingYearList = rep.data.matchingYearList;
                })
                .catch(ex=>{
                  console.log(ex);
                });
    
    },

    //학교 영업 확정
    setThisYearCollege(){
      let org_agent_id = this.orgAgent.org_agent_id;
      let org_college_id = this.orgCollegeId;

      this.axios.post('/agent/setThisYearCollege',{org_agent_id, org_college_id})
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

    //학교 정보 업데이트 페이지 이동
    go2collegeUpdate(){
      let org_college_id = null;
      if(this.modalMode)
        org_college_id = this.orgCollegeId;
      else
        org_college_id = this.org_college_id;

      this.$router.push({
        name : 'collegeregist',
        query : {college_id : org_college_id},
        params : {orgAgent : this.orgAgent, mode : 'update'},
      });
    },

  },


  
  watch : {
      orgCollegeId : function (val){
                        this.isThisYearCollege();
                      },

      modalBool : function (val){
                    this.isThisYearCollege();
                  },
  }
}
</script>

<style scoped lang="css" src="../../static/css/agent