<!--
   FIXME: 
   에이전트
      O 1. 등록
      O 2. 보기
      O 3. 수정
      O 4. 삭제
-->

<template>
  <v-container fluid grid-list-xs grid-list-md grid-list-lg style="height:100%">
    <v-layout row style="height:100%">
      <v-flex xs4  style="height:100%; border:3px solid #008080" v-show="undefinedDateIterviewList.length != 0 && openInterviewList">
        <b-table class="text-center" hover :items="print_undefinedDateIterviewList"></b-table>
      </v-flex>
      <v-flex :class="{'xs8' : (undefinedDateIterviewList.length != 0), 'xs12' : (undefinedDateIterviewList.length == 0 || !openInterviewList)}">
        <v-btn color="success" @click="openInterviewList = !openInterviewList">
          <v-icon left v-if="openInterviewList">keyboard_arrow_left</v-icon>
          <v-icon left v-else>keyboard_arrow_right</v-icon>
          未確定面接リスト {{undefinedDateIterviewList.length}}件
        </v-btn>
        <calendar 
        :events="events"
        :company ="company"
        @callDate="callDate()">
        </calendar>
      </v-flex>
    </v-layout>
  </v-container>
</template>


<script>
import calendar from "../../shared/CalendarFundamental"
export default {
  components:{ 'calendar' : calendar },
  
  data(){
    return {
      dateData : [],
      company  : [],
      events   : [],
      // fields: [key: '', label: '', 'class': 'text-center' ],
      undefinedDateIterviewList : [],      
      print_undefinedDateIterviewList : [],      
      openInterviewList : false,
    }
  },
  mounted(){
    this.callDate();
    this.callCompany();
  },

  methods : {
   
    //에이전트에 물린 회사 리스트
    callCompany : function(){
      let reqHttpAddr = "/calendarFundamental_interview_company_list"; 
      let sendData    = { 
                          agentId :this.$store.getters.identification //에이전트ID
                        };     
      
      this.axios.post(reqHttpAddr, sendData)
                .then((res) => {
                  this.company = res.data;
                  console.log("this.company");
                  console.log(res.data);
                })
                .catch( (err) => {
                    console.log(err.message);
                });
    },
    

    //등록된 스케줄 불러오기
    callDate : function() {
        let data = "";
        this.events = [];
        this.undefinedDateIterviewList = [];

        let reqHttpAddr = "/agent_calender_info"; 
        let sendData    = { 
                            agentId :"root"//this.$store.getters.identification //교수ID
                          };     
        this.axios.post(reqHttpAddr, sendData)
                  .then((res) => {
                      for( let i = 0 ; i < res.data.length ; i++ ){
                        for( let j = 0 ; j < res.data[i].work_content.length ; j++ ){
                          if(j == 0){
                            data = res.data[i].work_content[j].content;
                          }else{
                            data += ("+" +  res.data[i].work_content[j].content);
                          }
                        }

                        this.events.push({
                            id : res.data[i].interview_id, //기업 ID
                            title : res.data[i].org_name,  //기업명
                            start : res.data[i].date+" " + res.data[i].interview_start_time,  //스케줄 시작 날짜, 시간
                            end : res.data[i].date+" " + res.data[i].interview_end_time,    //스케줄 종료 날짜, 시간
                            content:res.data[i].content,         //면접 내용
                            employment_id: res.data[i].employment_id,   //채용정보ID
                            interview_check_ox: res.data[i].interview_check_ox,     //교수님 수락 여부
                            interview_count:res.data[i].interview_count,        //면접 차수
                            interview_id:res.data[i].interview_id,              //면접 ID
                            interview_place:res.data[i].interview_place,
                            org_college_name :res.data[i].org_college_name,
                            branch : data  });    //면접 장소
                            //추가로 학교ID, 학교 이름
                      }
                      this.dateData = res.data;
                      //채용정보id 스케줄제목, 면접차수, 면접장소, 날짜, 시작시간, 끝시간, 면접종류, 면접내용, 교수허락여부
                      //{title:"A"(회사명), date:"2018-04-28", space, startTime:"03:28",  endTime, type, contents, agree}
                      
                      //날짜가 정해지지 않은 인터뷰 리스트
                      for(let i in this.dateData){
                        if(this.dateData[i].date == null || this.dateData[i].date == ''){
                          this.undefinedDateIterviewList.push(this.dateData[i]);
                        }
                      }

                      for( let j = 0 ; j < this.undefinedDateIterviewList.length ; j++){
                        this.print_undefinedDateIterviewList.push({企業:this.undefinedDateIterviewList[j].org_name, 学校:this.undefinedDateIterviewList[j].org_college_name, 次数:this.undefinedDateIterviewList[j].interview_count, 内容:this.undefinedDateIterviewList[j].content});
                      }
                  })
                  .catch( (err) => {
                      console.log(err.message);
                  });
    },

  }
}
</script>
