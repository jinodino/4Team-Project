<template>
  <v-container fluid>
    <v-layout row v-if="info != null" style="border-right: 1px solid #BDBDBD; border-left: 1px solid #BDBDBD;">
      <v-flex xs12 md12 lg12 id="mainbar" style="padding-bottom: 10px; padding-top: 5px;" text-center>
        採用情報
      </v-flex>

      <v-flex xs4 md2 text-center id="tableTitle">
        <v-card-text>募集タイトル</v-card-text>
      </v-flex>
      <v-flex xs8 md10 id="tableItem">
        <v-card-text >
          {{info.employment_infos.employment_name}}
        </v-card-text>
      </v-flex>

      <v-flex xs12 md12 >
        <v-layout wrap>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>応募期限
            </v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
            <v-card-text style="font-size : 13px">
              {{date  === null ? "-" : date}}
            </v-card-text>
          </v-flex>

          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>志願締め切り時間</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
            <Countdown style="margin-left: 10px; color:#ff8400" :deadline="info.employment_infos.apply_deadline_date"></Countdown>
          </v-flex>

        </v-layout>
      </v-flex>


     


      <v-flex xs12 md12 >
        <v-layout wrap>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>辞退可能可否</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
              <v-card-text>{{info.employment_infos.acceptance_fixed_ox == "o" ? "辞退不可能" : ("x" ? "辞退可能" : "知らない")}}</v-card-text>
          </v-flex>

          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>内定提示時期</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
            <v-card-text>{{info.employment_infos.expected_acceptance_date === null ? "-" : info.employment_infos.expected_acceptance_date}}</v-card-text>
          </v-flex>

        </v-layout>
      </v-flex>


      <v-flex xs12 md12 lg12>
        <v-layout>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>採用情報 url</v-card-text>
          </v-flex>
          <v-flex xs8 md10 id="tableItem">
            <v-card-text>{{info.employment_infos.file_url === null ? "-" : info.employment_infos.file_url}}</v-card-text>
          </v-flex>
        </v-layout>
      </v-flex>

      <v-flex xs12 md12 lg12 id="gray" style="padding: 5px" text-center>
        募集要綱
      </v-flex>

       <v-flex xs12 md12>
        <v-layout wrap>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>募集職種</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
              <v-card-text>{{info.employment_infos.working_sort === null ? "-" : info.employment_infos.working_sort }}</v-card-text>
          </v-flex>

          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>募集人数</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
            <v-card-text>{{info.employment_infos.recruit_number === null ? "-" : info.employment_infos.recruit_number }}</v-card-text>
          </v-flex>

        </v-layout>
      </v-flex>

      

      <v-flex xs12 md12 lg12>
        <v-layout>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>仕事内容</v-card-text>
          </v-flex>
          <v-flex xs8 md10 id="tableItem">
            <v-chip id="gray" v-for="(workvalue) in info.work_matchings" :key="workvalue.key" disabled>
              {{workvalue.content}}
            </v-chip>  
            <v-card-text>{{info.employment_infos.business_type_content}}</v-card-text>
          </v-flex>
        </v-layout>

        <v-layout>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>専攻能力</v-card-text>
          </v-flex>
          <v-flex xs8 md10 id="tableItem">
            <v-card-text>{{info.employment_infos.motomeru_major_grade}}</v-card-text>
          </v-flex>
        </v-layout>

        <v-layout>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>外国語能力</v-card-text>
          </v-flex>
          <v-flex xs8 md10 id="tableItem">
            <v-card-text>{{info.employment_infos.motomeru_language_grade}}</v-card-text>
          </v-flex>
        </v-layout>

        <v-layout>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>求める人材</v-card-text>
          </v-flex>
          <v-flex xs8 md10 id="tableItem">
            <v-chip id="gray" v-for="(desiredvalue) in info.desired_employments" :key="desiredvalue.key" disabled>
              {{desiredvalue.content}}
            </v-chip>
            <v-card-text>{{info.employment_infos.desired_employee_content}}</v-card-text>
          </v-flex>
        </v-layout>
        
        <v-layout>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>待遇/福利厚生</v-card-text>
          </v-flex>
          <v-flex xs8 md10 id="tableItem">
            <v-chip id="gray" v-for="(welfaresvalue) in info.welfares" :key="welfaresvalue.key" disabled>
              {{welfaresvalue.content}}
            </v-chip>
            <v-card-text>{{info.employment_infos.welfare_content}}</v-card-text>
          </v-flex>
        </v-layout>
        
        <v-layout>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>その他</v-card-text>
          </v-flex>
          <v-flex xs8 md10 id="tableItem">
            <v-card-text>{{info.employment_infos.motomeru_sonohoka}}</v-card-text>
          </v-flex>
        </v-layout> 

      </v-flex>
      <v-flex xs12 md12 lg12 id="gray" style="padding: 5px" text-center>
        労働条件
      </v-flex>

      <v-flex xs12 md12 >
        <v-layout wrap>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>勤務地</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
              <v-card-text>{{info.employment_infos.working_area === null ? "-" : info.employment_infos.working_area}}</v-card-text>
          </v-flex>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>契約期間</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
            <v-card-text>{{info.employment_infos.work_term}}</v-card-text>
          </v-flex>

          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>勤務時間（週）</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
            <v-card-text>{{info.employment_infos.business_hour === null ? "-" : info.employment_infos.business_hour }}</v-card-text>
          </v-flex>

          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>保険</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
            <v-card-text>{{info.employment_infos.insurance_content  === null ? "-" : info.employment_infos. insurance_content }}</v-card-text>
          </v-flex>

        </v-layout>

        <v-layout wrap>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>給与</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
              <v-card-text>{{info.employment_infos.pay === null ? "-" : info.employment_infos.pay }}</v-card-text>
          </v-flex>

          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>ボーナス</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
            <v-card-text>{{info.employment_infos.bonus_pay === null ? "-" : info.employment_infos.bonus_pay}}</v-card-text>
          </v-flex>
        </v-layout>

        <v-layout wrap>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>残業代</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
            <v-card-text>{{info.employment_infos.overtime_pay === null ? "-" : info.employment_infos.overtime_pay}}</v-card-text>
          </v-flex>
          <!-- <v-flex xs8 md4 >
              <v-card-text>{{info.employment_infos.overtime_pay === null ? "-" : info.employment_infos.overtime_pay }}</v-card-text>
          </v-flex> -->

          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>交通費</v-card-text>
          </v-flex>
          
          <v-flex xs8 md4 id="tableItem">
            <v-card-text>{{info.employment_infos.transport_pay === null ? "-" : info.employment_infos.transport_pay}}</v-card-text>
          </v-flex>
        </v-layout>

        <v-layout wrap>
          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>住宅補助金</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
              <v-card-text>{{info.employment_infos.house_pay === null ? "-" : info.employment_infos.house_pay}}</v-card-text>
          </v-flex>

          <v-flex xs4 md2  text-center id="tableTitle">
            <v-card-text>寮/航空運賃をサポート</v-card-text>
          </v-flex>
          <v-flex xs8 md4 id="tableItem">
            <v-card-text>{{info.employment_infos.dormitory_airport_support   === null ? "-" : info.employment_infos.dormitory_airport_support  }}</v-card-text>
          </v-flex>
          <!-- <v-flex xs8 md4>
            <v-card-text>{{info.employment_infos.dormitory_airport_support  === null ? "-" : info.employment_infos.dormitory_airport_support }}</v-card-text>
          </v-flex> -->
        </v-layout>
      </v-flex>
        
      <v-flex xs4 md2  text-center id="tableTitle">
        <v-card-text>休日</v-card-text>
      </v-flex>
      <v-flex xs8 md10 id="tableItem">
        <v-card-text>{{info.employment_infos.holiday === null ? "-" : info.employment_infos.holiday}}</v-card-text>
      </v-flex>

      <v-flex xs4 md2 lg2  text-center id="tableTitle">
        <v-card-text>その他</v-card-text>
      </v-flex>
      <v-flex xs8 md10 lg10 id="tableItem">
        <v-card-text>{{info.employment_infos.other_work_condition   === null ? "-" : info.employment_infos.other_work_condition  }}</v-card-text>
      </v-flex>
        
      
      <v-flex xs12 md12 lg12 style="padding-bottom: 10px; padding-top: 5px;" id="mainbar" text-center>
        インタビュースケジュール 
        <!-- {{info.interview_schedule}} -->
      </v-flex>
      <b-table responsive :fields="headers" :items="info.interview_schedule" item-key="faculty_id">
        <template slot="no" slot-scope="data">
          <p id="tdstyle">{{ ++ data.index }}</p>
        </template>
        <template slot="schedule_title" slot-scope="data">
          <p id="tdstyle">{{ data.item.schedule_title}}</p>
        </template>
        <template slot="interview_count" slot-scope="data">
          <p id="tdstyle">
            {{ data.item.interview_count }}
          </p>
        </template>

        <template slot="interview_place" slot-scope="data">
          <p id="tdstyle">{{ data.item.interview_place }}</p>
        </template>
        <template slot="interview_date" slot-scope="data">
          <p id="tdstyle">
           {{ data.item.interview_date }}
          </p>
        </template>
        <template slot="interview_time" slot-scope="data">
          <p id="tdstyle">{{ data.item.interview_start_time == null ? "─" : data.item.interview_start_time  + ' ~ ' + data.item.interview_end_time }}</p>
        </template>
        <template slot="interview_content_choice" slot-scope="data">
          <p id="tdstyle">{{ data.item.interview_content_choice }}</p>
        </template>
        
      </b-table>
      <!-- <v-data-table
              :headers="headers1"
              :items="info.interview_schedule"
              item
              text-center
              hide-actions
            > 
              <template slot="items" slot-scope="props">
                <tr>
                  <td class="text-xs-right">{{  ++props.index  }}</td>
                  <td>{{ props.item.schedule_title}}</td>
                  <td class="text-xs-left">{{ props.item.interview_count }}</td>
                  <td class="text-xs-left">{{ props.item.interview_place }}</td>
                  <td>{{ props.item.interview_date }}</td>
                  <td>{{ props.item.interview_start_time + ' ~ ' + props.item.interview_end_time }}</td>
                  <td>{{ props.item.interview_content_choice }}</td>
                </tr>
              </template>
              <template slot="no-data">
                <v-alert :value="true" color="error" icon="warning">
                  There's no college list to show :(
                </v-alert>
              </template>
            </v-data-table> -->
    </v-layout>

  </v-container>
</template>

<script>

  import Countdown from 'vuejs-countdown'

export default {
  props :['employmentId'],
  components: { Countdown },
  
  created : function(){
    let employment_id = this.$route.query.employment_id;
    
    if(employment_id == null){
      if(this.employmentId == null)
          this.$router.go(-1);
      else
        employment_id = this.employmentId;
    }
       

    this.employment_id = employment_id;
    this.axios.post('/company/getRecruit',{employment_id})
    
    .then(rep=>{
      this.info = rep.data;
      this.split();
      this.STime();
    })
    .catch(ex=>{
      console.log(ex);
    })

    
  },


  data : ()=> ({
    employment_id : null,
    info : null,
    // sever_Time : null,
    // sever_Time1 : null,
    // sever_Time2 : null,
    // sever_Time3 : null,
    date : null,
    headers1: [
      {text : 'no', value: 'no', width : '5%'},
      {text : '제목', value: 'schedule_title', width : '30%'},
      {text : '면접 횟수', value: 'interview_count', width : '5%'},
      {text : '장소', value: 'interview_place', width : '15%'},
      {text : '날짜', value: 'interview_date', width : '15%'},
      {text : '시간', value: 'interview_time', width : '20%'},
      {text : '종류', value: 'interview_content_choice', width : '20%'},
    ],
    headers: [
      {label : 'no', key: 'no'},
      {label : 'title', key: 'schedule_title', },
      {label : '面接回数', key: 'interview_count'},
      {label : '場所', key: 'interview_place'},
      {label : '予定日', key: 'interview_date'},
      {label : '時間', key: 'interview_time'},
      {label : '面接分類', key: 'interview_content_choice'},
    ],
  }),


  methods : {
    split(){
      var openData = this.info.employment_infos.apply_open_date;
      var openDataDate = openData.split('-', 3);

      var deadlineData = this.info.employment_infos.apply_deadline_date;
      var deadlineSpaces = deadlineData.split(' ', 2 );
      var deadlineDataDate = deadlineSpaces[0].split('-', 3);
      var deadlineDataTime = deadlineSpaces[1].split(':', 3);
      var addData = openDataDate[0] + "年" + openDataDate[1] + "月" + openDataDate[2] + "日 ~ " + deadlineDataDate[0] + "年" + deadlineDataDate[1] + "月" + deadlineDataDate[2] + "日 " + deadlineDataTime[0] + ":" + deadlineDataTime[1] + "(JST)";
      this.date = addData;
    },
    STime() {
      var moment = require('moment-timezone');
      this.sever_Time = new Date().toLocaleString();
      this.sever_Time1 = new Date();
      moment.tz.setDefault("Japan/Tokyo");
      this.sever_Time2 = moment().tz("korea/Seoul").format();
      //this.sever_Time3 = moment(this.sever_Time1).tz( "japan").format('YYYY-MM-DD HH:mm Z');
      //this.sever_Time1 = new Date()
    }
  }
}
</script>



<style scoped lang="css" src="../static/css/agent/agnetAndStudentStyleSheet.css"></style>