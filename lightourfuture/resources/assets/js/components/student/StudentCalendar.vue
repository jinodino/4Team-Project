
<template>
  <v-container fluid grid-list-xs grid-list-md grid-list-lg>
    <calendar
      :events="events"
      @callDate="callDate()"
    >
    </calendar>
  </v-container>
</template>


<script>
import calendar from "../../shared/CalendarFundamental";
export default {
  components: { calendar },

  data() {
    return {
      //Saved schedule information
      dateData: [],
      events: []
    };
  },
  mounted() {},
  created() {
    // this.$moment(new Date()).format('YYYY-MM-DD')
    this.callDate();
  },

  methods: {
    //등록된 스케줄 불러오기
    callDate: function() {
      this.events = [];
      let data = "";

      let reqHttpAddr = "/calendarFundamental_std_interview_schedule";
      let sendData = {
        stdId: this.$store.getters.identification //학생ID
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          for (let i = 0; i < res.data.length; i++) {
            this.events.push({
              id: res.data[i].interview_id, //기업 ID
              title: res.data[i].company_name, //기업명
              start:
                res.data[i].schedule_date +
                " " +
                res.data[i].schedule_start_time, //스케줄 시작 날짜, 시간
              end:
                res.data[i].schedule_date + " " + res.data[i].schedule_end_time, //스케줄 종료 날짜, 시간
              content: res.data[i].interview_info_content, //면접 내용()
              employment_id: res.data[i].employment_id, //채용정보ID
              interview_check_ox: res.data[i].interview_check_ox, //교수님 수락 여부
              interview_count: res.data[i].interview_count, //면접 차수
              interview_id: res.data[i].interview_id, //면접 ID
              interview_place: res.data[i].interview_place //면접 장소
            }); // branch : data
            //추가로 학교ID, 학교 이름
          }
          this.dateData = res.data;

          //채용정보id 스케줄제목, 면접차수, 면접장소, 날짜, 시작시간, 끝시간, 면접종류, 면접내용, 교수허락여부
          //{title:"A"(회사명), date:"2018-04-28", space, star tTime:"03:28",  endTime, type, contents, agree}
        })
        .catch(err => {
          console.log(err.message);
        });
    }
  }
};
</script>


