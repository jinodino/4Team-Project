<template>
  <div class="container-fluid">

    <div class="row text-center ">
      <div class="col-xs-12  col-md-12">
        <h1>{{status}}</h1>
      </div>      
    </div>
    <div class="row text-center ">
      <div class="col-xs-12  col-md-12">
        <v-select
          label="매칭"
          required
          v-model="interview.matching"                                                      
          :items="matchings"  
          :disabled = "interview_data_disabled"    
        ></v-select>
      </div>
      <div class="col-xs-12  col-md-12">
        <v-select
          label="채용 타이틀"
          required
          v-model="interview.employment_title"                                                      
          :items="employments"  
          :disabled = "interview_data_disabled"    
        ></v-select>
      </div>
      <div class="col-xs-12  col-md-12" >
        <v-menu
            ref="menu"
            lazy
            :close-on-content-click="false"
            v-model="menu"
            transition="scale-transition"
            offset-y
            full-width
            :nudge-right="40"
            min-width="290px"
            :return-value.sync="interview.interview_date"
          >
          <v-text-field
            slot="activator"
            label="면접 가능 일"
            v-model="interview.interview_date"
            prepend-icon="event"
            readonly
          ></v-text-field>
          <v-date-picker v-model="interview.interview_date" no-title scrollable>
            <v-spacer></v-spacer>
            <v-btn flat color="primary" @click="menu = false">Cancel</v-btn>
            <v-btn flat color="primary" @click="$refs.menu.save(interview.interview_date)">OK</v-btn>
          </v-date-picker>
        </v-menu>
      </div>
      <div class="col-xs-12  col-md-5" >
        <v-menu
          ref="menu4"
          :close-on-content-click="false"
          v-model="menu2"
          :nudge-right="40"
          :return-value.sync="interview.interview_start_time"
          lazy
          transition="scale-transition"
          offset-y
          full-width
          max-width="290px"
          min-width="290px"
        >
          <v-text-field
            slot="activator"
            v-model="interview.interview_start_time"
            label="면접 시작 시간"
            prepend-icon="access_time"
            readonly
          ></v-text-field>
          <v-time-picker v-model="interview.interview_start_time" @change="$refs.menu4.save(interview.interview_start_time)"></v-time-picker>
        </v-menu>
      </div>
      <div class="col-xs-12  col-md-5" >
        <v-menu
          ref="menu3"
          :close-on-content-click="false"
          v-model="menu3"
          :nudge-right="40"
          :return-value.sync="interview.interview_end_time"
          lazy
          transition="scale-transition"
          offset-y
          full-width
          max-width="290px"
          min-width="290px"
        >
          <v-text-field
            slot="activator"
            v-model="interview.interview_end_time"
            label="면접 끝 시간"
            prepend-icon="access_time"
            readonly
          ></v-text-field>
          <v-time-picker v-model="interview.interview_end_time" @change="$refs.menu3.save(interview.interview_end_time)"></v-time-picker>
        </v-menu>
      </div>
      <div class="col-xs-12  col-md-2 text-left">
        <button type="button" class="btn btn-primary pull-right col-md-1" @click="pushadd">일정 추가</button>
      </div>
      <div v-for="entervalue in print_enter_dates" :key="entervalue" class="col-xs-12  col-md-12">
        <v-card-text>
          매칭 : {{entervalue.matching}}, 채용 타이틀 : {{entervalue.employment_title}}, 날짜 : {{entervalue.interview_date}}, 시간 : {{entervalue.interview_start_time}} ~ {{entervalue.interview_end_time}}
        </v-card-text>
      </div>
       <!-- {{enter_dates.matching}}
      {{enter_dates}}<br>
      {{print_enter_dates}} -->
    </div>
    
  </div>

</template>
<script>
export default {
  props: [
    "employments",//일정
    "matchings", //유저구분
  ],
  data : ()=>({
    // matchings : ["a+b", "adf + adf"],
    // employments : ["2018 신졸 채용!", "2018년 일본 IT 신졸 "],
    status : '면접 일정',
    menu : null,
    menu2 : null,
    menu3 : null,

    interview : {
      matching : "",
      employment_title : "",
      interview_date : "", // 면접 일자
      interview_start_time : null,//면접 시작 시간
      interview_end_time : null,//면접 끝 시간
    },
    //타이틀, 매칭 정보 고정
    interview_data_disabled : false,
    enter_dates : [ ],
    print_enter_dates : []
  }),

  methods : {
    
     pushadd(){
      var matching = this.interview.matching;
      var title = this.interview.employment_title;
      var date = this.interview.interview_date; // 면접 일자
      var startTime = this.interview.interview_start_time;//면접 시작 시간 
      var endTime = this.interview.interview_end_time;//면접 끝 시간
      var add = Array();
      if(date  !== "" && startTime !== null && endTime !== null && matching !== "" && title !== ""){
        
        //this.enter_dates.push(this.interview);
        this.enter_dates = JSON.parse(JSON.stringify( this.interview )); // 배열 넣기
        this.print_enter_dates.push(this.enter_dates); // 배열 추가
        
        alert( "추가 되었습니다.");
      }else {
        alert("빈칸을 채워 주세요.");
      }
          this.interview.interview_date = "";
          this.interview.interview_start_time = null;
          this.interview.interview_end_time = null;
          //this.interview_data_disabled = true;
     },
     
    // DateInput(){
    //   var inputdata = this.interview_schedule.interview_date;
    //   var count = this.enter_date.length
    //   var overlap = false;
    //   for(var i = 0; i < count; i++){
    //     if(inputdata === this.enter_date[i]){
    //       alert("이미 있는 날짜입니다.");
    //       overlap = false;
    //     } else {
    //       overlap = true;
    //       break;
    //     }
    //   }
    //   if(overlap === true){
    //     if(inputdata === ""){
    //         alert("날짜를 선택 해 주세요.");
    //     } else{
    //       alert("날짜 : " + inputdata +  ", 갯수 : " + count);
    //       this.enter_date[count] = inputdata;
    //     }
    //   }
    // }
  } 
}

</script>
<style>
  .section{
    margin-bottom: 1rem;
    background-color:rgb(223, 255, 223);
  }

</style>
