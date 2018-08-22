<template>
    <v-container fluid grid-list-xs grid-list-md grid-list-lg>
        <v-flex d-flex xs12 sm12 md12 lg12>
            <full-calendar :events="events" ref="cal" :config="calendarConfig" @day-click="setNewEvents"
            @event-selected="viewEvent" @color="backgroudColor" @event-drop="eventDrop"></full-calendar>
        </v-flex>
        
        <!-- 에이전트  : 스케줄 등록 (회사명,  학교명, 모집분야, 면접전형, 차수, 일정) -->
         <div v-if="this.$store.getters.classification == 'agent'">
            <v-layout row justify-center>
                <v-dialog v-model="AddSchedule_Dialog" scrollable persistent max-width="500px">
                        <v-card>
                            <v-card-title style="border-bottom:1px solid gray">
                                <span class="headline">{{$t('CalendarFundamental.enrollment')}}</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                  <v-form ref="form" lazy-validation>
                                    <v-layout wrap>
                                        <v-flex xs12>
                                            <v-select
                                            :label="$t('CalendarFundamental.company_name')"
                                            required
                                            v-model="selectedCompany"
                                            :items ="company"
                                            item-text="text"
                                            item-value="org_company_id"
                                            return-object
                                            ></v-select>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-select
                                            :label="$t('CalendarFundamental.school_name')"
                                            required
                                            v-model="selectedSchool"
                                            :items ="school"
                                            item-text="text"
                                            item-value="org_school_id"
                                            return-object
                                            ></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm12>
                                            <v-select
                                                :label="$t('CalendarFundamental.areas_of_recruitment')"
                                                required
                                                v-model="selectedBranch"
                                                item-text="text"
                                                item-value="employment_id"
                                                :items ="branch"
                                                return-object
                                            ></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm12>
                                            <!-- 면접전형 -->
                                            <v-select
                                                :label="$t('CalendarFundamental.interview')"
                                                required
                                                v-model="selectedType"
                                                :items ="type"
                                                item-text="type"
                                                item-value="id"
                                                return-object
                                            ></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm12>
                                            <v-text-field
                                                v-model="selectedLevel"
                                                :label="$t('CalendarFundamental.order')"
                                                required
                                                disabled
                                            ></v-text-field>
                                            <!-- 차수  -->
                                        </v-flex>
                                        <v-flex xs12 sm4>
                                            <v-text-field
                                                v-model="selectedDay[3]"
                                                :label="$t('CalendarFundamental.year')"
                                                disabled
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm4>
                                            <v-text-field
                                                v-model="selectedDay[1]"
                                                :label="$t('CalendarFundamental.month')"
                                                disabled
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm4>
                                            <v-text-field
                                                v-model="selectedDay[2]"
                                                :label="$t('CalendarFundamental.day')"
                                                disabled
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm2>
                                            <v-select
                                                :label="$t('CalendarFundamental.h')"
                                                required
                                                v-model="addDate.startHour "                                                      
                                                :items ="time.hour"
                                            ></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm2>
                                            <v-select
                                                :label="$t('CalendarFundamental.m')"
                                                required
                                                v-model="addDate.startMinute"                                                                        
                                                :items ="time.minute"
                                            ></v-select>
                                        </v-flex>
                                        <v-flex xs2 text-xs-center>-</v-flex>
                                        <v-flex xs12 sm2> 
                                            <v-select
                                                :label="$t('CalendarFundamental.h')"
                                                required
                                                v-model="addDate.endHour"                                                      
                                                :items ="time.hour"
                                            ></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm2>
                                            <v-select
                                                :label="$t('CalendarFundamental.m')"
                                                required
                                                v-model="addDate.endMinute"                                                                        
                                                :items ="time.minute"
                                            ></v-select>
                                        </v-flex>
                                    </v-layout>
                                  </v-form>
                                </v-container>
                            <small>*indicates required field</small>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click.native="AddSchedule_Dialog = false">Close</v-btn>
                            <v-btn type ="submit" color="blue darken-1" flat @click="addItem()" >Save</v-btn>
                        </v-card-actions>
                    </v-card>
            </v-dialog>
        </v-layout>
    </div>
    <!-- //스케줄 등록 모달 -->

    <!-- 에이전트 : 스케줄 수정 -->
    <div>
     <v-layout row>
     <v-dialog v-model="changeEvent_Dialog" persistent max-width="500px" v-show="this.$store.getters.classification == 'agent'">
              <v-form ref="form" lazy-validation>
              <v-card>
                <v-card-title>
                  <span class="headline">{{$t('CalendarFundamental.change')}}</span>
                </v-card-title>
                <v-card-text>
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs12 sm12>
                      <v-text-field
                        v-model="changePickupedDate.org_name"
                        :label="$t('CalendarFundamental.company_name')"
                        required
                        disabled
                      ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12>
                      <v-text-field
                        v-model="changePickupedDate.org_college_name"
                        :label="$t('CalendarFundamental.school_name')"
                        required
                        disabled
                      ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12>
                      <v-text-field
                        v-model="changePickupedDate.branch"
                         :label="$t('CalendarFundamental.areas_of_recruitment')"
                        required
                        disabled
                      ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12>
                      <v-text-field
                        v-model="changePickupedDate.content"
                        :label="$t('CalendarFundamental.interview')"
                        required
                        disabled
                      ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12>
                      <v-text-field
                        v-model="changePickupedDate.interview_count"
                        :label="$t('CalendarFundamental.order')"
                        required
                        disabled
                      ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12>
                      <v-text-field
                        v-model="changePickupedDate.interview_place"
                        :label="$t('CalendarFundamental.place')"
                        required
                        disabled
                      ></v-text-field>
                      </v-flex>

                      <v-flex xs12 sm12>
                      <v-text-field
                        v-model="changePickupedDate.date"
                        :label="$t('CalendarFundamental.date')"
                        required
                        disabled
                      ></v-text-field>
                      </v-flex>
                        
                        <v-flex xs12 sm2>
                          <v-select
                            :label="$t('CalendarFundamental.h')"
                            required
                            v-model="changePickupedDate.startHour"                                                      
                            :items ="time.hour"
                          ></v-select>
                        </v-flex>
                        <v-flex xs12 sm2>
                          <v-select
                            :label="$t('CalendarFundamental.m')"
                            required
                            v-model="changePickupedDate.startMinute"                                                                        
                            :items ="time.minute"
                          ></v-select>
                        </v-flex>
                        <v-flex xs2 text-xs-center>-</v-flex>
                        <v-flex xs12 sm2> 
                          <v-select
                            :label="$t('CalendarFundamental.h')"
                            required
                            v-model="changePickupedDate.endHour"                                                      
                            :items ="time.hour"
                          ></v-select>
                        </v-flex>
                        <v-flex xs12 sm2>
                          <v-select
                            :label="$t('CalendarFundamental.m')"
                            required
                            v-model="changePickupedDate.endMinute"                                                                        
                            :items ="time.minute"
                          ></v-select>
                        </v-flex>
                    </v-layout>
                  </v-container>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" flat @click.native="changeEvent_Dialog = false">Close</v-btn>
                  <v-btn type ="submit" color="blue darken-1" flat @click="eventChange(changePickupedDate)" >Save</v-btn>
                </v-card-actions>
              </v-card>
            </v-form>
          </v-dialog>
        </v-layout>
   </div>
    <!-- // 스케줄 수정 모달 -->

    <!-- 모든 유저 : 스케줄 확인 -->
    <div>
        <v-dialog v-model="schedule" persistent max-width="500px">
             <v-tabs v-model="active" fixed-tabs grow >
              <v-layout>  
                  <v-flex xs6 style="margin-left:2px">
                      <div v-if="active == '0'" @click="active = '0'" id="tabon" style="height:48px">{{$t('CalendarFundamental.company')}}</div>
                      <div v-if="active == '1'" @click="active = '0'" id="taboff">{{$t('CalendarFundamental.company')}}</div>
                  </v-flex>
                  <v-flex xs6 style="margin-right:2px">
                      <div v-if="active == '1'" @click="active = '1'" id="tabon" style="height:48px;">{{$t('CalendarFundamental.school')}}</div>
                      <div v-if="active == '0'" @click="active = '1'" id="taboff">{{$t('CalendarFundamental.school')}}</div>
                  </v-flex>
              </v-layout>
              
            <v-tabs-items v-model="active"> 
              <v-tab-item>
                <v-card flat>
                  <v-form ref="register" lazy-validation>
                        <v-card>
                            <v-card-text>
                                <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs2 text-xs-center>
                                        {{$t('CalendarFundamental.company')}}({{pickupedData.interview_count}}{{$t('CalendarFundamental.count')}})
                                    </v-flex>
                                    <v-flex xs10>
                                    <b-form-input 
                                        type="text"
                                        disabled
                                        v-model="pickupedData.title"
                                    >
                                    </b-form-input>
                                    </v-flex>
                                    
                                    <v-flex xs2 text-xs-center v-show="this.$store.getters.classification == 'agent'">
                                        {{$t('CalendarFundamental.school')}}
                                    </v-flex>
                                    <v-flex xs10 v-show="this.$store.getters.classification == 'agent'">
                                    <b-form-input  
                                        type="text"
                                        disabled
                                        v-model="pickupedData.org_college_name">
                                    </b-form-input>
                                    </v-flex>

                                    <v-flex xs2 text-xs-center>
                                        {{$t('CalendarFundamental.date')}}
                                    </v-flex>
                                    <v-flex xs10>
                                    <b-form-input 
                                        type="text"
                                        disabled
                                        v-model="pickupedData.date">
                                    </b-form-input>
                                    </v-flex> 

                                    <v-flex xs2 text-xs-center>
                                        {{$t('CalendarFundamental.time')}}
                                    </v-flex>
                                    <v-flex xs4>
                                    <b-form-input 
                                        type="text"
                                        disabled
                                        v-model="pickupedData.interview_start_time">
                                    </b-form-input>
                                    </v-flex>
                                    <v-flex xs2 text-xs-center>-</v-flex>
                                    <v-flex xs4>
                                    <b-form-input 
                                        type="text"
                                        disabled
                                        v-model="pickupedData.interview_end_time">
                                    </b-form-input>
                                    </v-flex>
 
                                    <v-flex xs2 text-xs-center>
                                        {{$t('CalendarFundamental.place')}}
                                    </v-flex>
                                    <v-flex xs10>
                                    <b-form-input 
                                        type   ="text"
                                        disabled                    
                                        v-model="pickupedData.interview_place">
                                    </b-form-input>
                                    </v-flex>

                                    <v-flex xs2 text-xs-center>
                                        {{$t('CalendarFundamental.interview')}}
                                    </v-flex>
                                    <v-flex xs10>
                                    <b-form-input 
                                        type   = "text"
                                        disabled                    
                                        v-model="pickupedData.content">
                                    </b-form-input>
                                    </v-flex>

                                    <v-flex xs2 text-xs-center>
                                        {{$t('CalendarFundamental.areas_of_recruitment')}}
                                    </v-flex>
                                    <v-flex xs10>
                                    <b-form-input 
                                        type   = "text"
                                        disabled                    
                                        v-model="pickupedData.branch">
                                    </b-form-input>
                                    </v-flex>
                                    <v-flex xs2 text-xs-center v-show="!this.$store.getters.classification == 'student' || !this.$store.getters.classification == 'company'">
                                        {{$t('CalendarFundamental.professor')}}<br> {{$t('CalendarFundamental.acceptance')}}
                                    </v-flex>
                                    <v-flex xs10 v-show="!this.$store.getters.classification == 'student' || !this.$store.getters.classification == 'company'">
                                    <b-form-input 
                                        type   = "text"
                                        disabled                    
                                        v-model="pickupedData.interview_check_ox">
                                    </b-form-input>
                                    </v-flex>
                                </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>  
                                <v-btn color="blue darken-1" flat v-show="this.$store.getters.classification == 'professor' && !(pickupedData.interview_check_ox == 'o')" @click="agree(pickupedData), schedule=false">agree</v-btn>
                                <v-btn color="blue darken-1" flat @click="deleteItem(pickupedData), schedule=false" v-show="this.$store.getters.classification == 'agent'">delete</v-btn>            
                                <v-btn color="blue darken-1" flat @click="changeEventDialogOpen(pickupedData), schedule = false" v-show="this.$store.getters.classification == 'agent'">change</v-btn>
                                <v-btn color="blue darken-1" flat @click="changeSchedule = true, schedule = false" v-show="this.$store.getters.classification == 'professor' && pickupedData.interview_check_ox == 'o'">change</v-btn>
                                <v-btn color="blue darken-1" flat @click="schedule = false">Close</v-btn>                      
                            </v-card-actions>
                        </v-card>
                    </v-form>
                </v-card>
              </v-tab-item>
              <v-tab-item>
                  <v-card v-if="applied">
                        <v-flex xs6 sm6 v-if="this.$store.getters.classification == 'professor'"> 
                            <v-select
                                v-model="interval"
                                :items="intervalItem"
                                :label="$t('CalendarFundamental.interview_time')"
                                required
                            ></v-select>
                        </v-flex>
                    
                        <v-layout row style="padding-left:10%" v-if="this.$store.getters.classification == 'professor'">
                            <v-flex xs5 sm5>
                                <b-alert :show="true" variant="light" v-for="(std,key) in appliedStd_time_o" :key="key">{{std}}</b-alert>                                
                                <b-alert :show="true" variant="light" v-for="(time,key) in interview_time" :key="key">{{time}}</b-alert>
                            </v-flex>
                            <v-flex xs5 sm5>
                                <draggable :list="appliedStd">
                                    <b-alert :show="true" variant="warning" v-for="std in appliedStd" :key="std.login_id">{{std.name}}</b-alert>                                
                                </draggable>
                            </v-flex>
                        </v-layout>
                        <v-layout row v-else>
                          <v-flex xs4 sm4>
                            <b-alert :show="true" variant="warning" v-for="std in appliedStd" :key="std.login_id">{{std.interview_time[0].interview_start_time}}</b-alert>                                
                          </v-flex> 
                          <v-flex xs5 sm5>
                            <b-alert :show="true" variant="warning" v-for="std in appliedStd" :key="std.login_id">{{std.name}}</b-alert>                                
                          </v-flex> 
                        </v-layout>
                          <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click.native="schedule = false, interval = null">Close</v-btn>                      
                            <v-btn color="blue darken-1" flat @click="schedule = false, addStdInterval(), interval = null" v-show="this.$store.getters.classification == 'professor'">save</v-btn>                      
                        </v-card-actions>
                    </v-card>

                    <v-card v-else>
                        <v-alert :value="true" type="error" style="width:100%">
                            {{$t('CalendarFundamental.message1')}}
                        </v-alert>
                        <v-btn color="blue darken-1" flat @click.native="schedule = false" @click="applied = true">Close</v-btn>                      
                    </v-card>
              </v-tab-item>
              </v-tabs-items>
            </v-tabs>
        </v-dialog>
    </div> <!-- Schedule Details Modal -->
    
    <div>
        <v-dialog v-model="changeSchedule" persistent max-width="500px"> 
            <v-form ref="register" lazy-validation>
                <v-card>
                    <v-card-title>
                        <span class="headline">change</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs2 text-xs-center>
                                    {{$t('CalendarFundamental.company')}}({{pickupedData.interview_count}}{{$t('CalendarFundamental.count')}})
                                </v-flex>

                                <v-flex xs10>
                                    <b-form-input 
                                        type="text"
                                        disabled
                                        v-model="pickupedData.title"
                                        >
                                    </b-form-input>
                                </v-flex>
                                
                                <v-flex xs2 text-xs-center v-show="this.$store.getters.classification == 'agent'">
                                    {{$t('CalendarFundamental.school')}}
                                </v-flex>

                                <v-flex xs10 v-show="this.$store.getters.classification == 'agent'">
                                    <b-form-input 
                                        type="text"
                                        disabled
                                        v-model="pickupedData.school">
                                    </b-form-input>
                                </v-flex>

                                <v-flex xs2 text-xs-center>
                                    {{$t('CalendarFundamental.date')}}
                                </v-flex>
                                <v-flex xs10>
                                    <b-form-input 
                                        type="text"
                                        disabled
                                        v-model="pickupedData.date">
                                    </b-form-input>
                                </v-flex> 

                                <v-flex xs2 text-xs-center>
                                    {{$t('CalendarFundamental.time')}}
                                </v-flex>

                                <v-flex xs4>
                                    <b-form-input 
                                        type="text"
                                        disabled
                                        v-model="pickupedData.interview_start_time">
                                    </b-form-input>
                                </v-flex>

                                <v-flex xs2 text-xs-center>-</v-flex>

                                <v-flex xs4>
                                    <b-form-input 
                                        type="text"
                                        disabled
                                        v-model="pickupedData.interview_end_time">
                                    </b-form-input>
                                </v-flex>

                                <v-flex xs2 text-xs-center>
                                    {{$t('CalendarFundamental.place')}}
                                </v-flex>
                                
                                <v-flex xs10>
                                    <b-form-input 
                                        type   ="text"
                                        v-model="pickupedData.interview_place">
                                    </b-form-input>
                                </v-flex>

                                <v-flex xs2 text-xs-center>
                                    {{$t('CalendarFundamental.interview')}}
                                </v-flex>
                                <v-flex xs10>
                                    <b-form-input 
                                        type   = "text"
                                        disabled                    
                                        v-model="pickupedData.content">
                                    </b-form-input>
                                </v-flex>

                                <v-flex xs2 text-xs-center>
                                    {{$t('CalendarFundamental.areas_of_recruitment')}}
                                </v-flex>

                                <v-flex xs10>
                                    <b-form-input 
                                        type   = "text"
                                        disabled                    
                                        v-model="pickupedData.branch">
                                    </b-form-input>
                                </v-flex>

                                <v-flex xs2 text-xs-center>
                                    {{$t('CalendarFundamental.professor')}}<br> {{$t('CalendarFundamental.acceptance')}}
                                </v-flex>

                                <v-flex xs10>
                                    <b-form-input 
                                        type   = "text"
                                        disabled                    
                                        v-model="pickupedData.interview_check_ox">
                                    </b-form-input>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>  
                        <v-btn color="blue darken-1" flat @click.native="changeSchedule = false">Close</v-btn>                      
                        <v-btn color="blue darken-1" flat @click="professorPlaceSave(pickupedData), changeSchedule = false">save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
   </div>
</v-container>

</template>

<style scoped lang="css" src="../static/css/agent/agnetAndStudentStyleSheet.css"></style>


<script>
import { FullCalendar } from "vue-full-calendar";
import draggable from "vuedraggable";

export default {
  components: {
    FullCalendar,
    draggable
  },

  props: [
    //모든 스케줄
    "events"

    //에이전트
    //"company", //스케줄 등록 시 select 목록 회사
  ],

  data() {
    return {
      //모달 창
      AddSchedule_Dialog: false,
      changeEvent_Dialog: false,
      schedule: false,
      changeSchedule: false,

      active: "0",

      backgroudColor: [
        "#2B2E4A",
        "#521262",
        "#903749",
        "#53354A",
        "#40514E",
        "#537780"
      ],
      calendarConfig: {
        locale: "jp",
        nowIndicator: true,
        isLismit: true,
        eventLimit: 5,
        defaultView: "month"
        // editable    : false,
      },

      //에이전트 스케줄 등록시 필요한 정보
      data: {
        selectedCompany: null, //선택 회사명
        selectedSchool: null //선택 학교명
      },

      addDate: {
        startHour: null, //시작시간
        startMinute: null,
        endHour: null, //종료시간
        endMinute: null,
        selectedType: null, //선택 면접전형
        selectedLevel: null, //선택 면접차수
        branch: null
      },

      //일정 등록 시 시간 선택 select item
      time: {
        year: [],
        month: [],
        day: [],
        hour: [],
        minute: []
      },

      //Schedule to save
      school: [],
      branch: [],
      type: [],
      selectedCompany: "",
      selectedSchool: "",
      selectedBranch: "",
      selectedType: "",
      selectedLevel: "",
      selectedDate: "",
      selectedDay: [],
      company: [],

      // 교수 스케줄 수정 : 장소 수정
      pickupedData: {},

      // 에이전트 스케줄 수정
      changePickupedDate: [],

      //학생 스케줄 등록
      intervalItem: [],
      interval: null,
      appliedStd: [],
      appliedStd_time_o: [],
      appliedStd_time_x: [],
      interview_time: [],
      applied: true
    };
  },

  created() {
    this.createSelectDate();
    this.callCompany();
    this.multilingual();
  },

  watch: {
    active() {
      console.log(this.active);
    },
    //기업 선택 시 기업에 물린 학교를 불러온다.
    selectedCompany() {
      this.selectedSchool = "";
      this.data.selectedCompany = this.selectedCompany.org_company_id;
      //선택된 기업에 물린 학교 리스트 { org_school_id:'yjc01', text:'영진전문대' }
      let reqHttpAddr = "/calendarFundamental_interview_college_list";
      let sendData = {
        id: this.$store.getters.identification, //userID
        companyid: this.selectedCompany.org_company_id
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.school = res.data;
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //학교 선택 시 모집분야를 불러온다.
    selectedSchool() {
      let data = "";
      this.data.selectedSchool = this.selectedSchool.org_school_id;
      this.branch = [];

      if (this.selectedSchool == "") {
        return;
      }

      let reqHttpAddr = "/calendarFundamental_interview_employment_id";
      let sendData = {
        id: this.$store.getters.identification, //userID
        company_id: this.selectedCompany.org_company_id,
        school_id: this.selectedSchool.org_school_id
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          for (let i = 0; i < res.data.length; i++) {
            for (let j = 0; j < res.data[i].branch.length; j++) {
              if (j == 0) {
                data = res.data[i].branch[j].content;
              } else {
                data += "+" + res.data[i].branch[j].content;
              }
            }
            let employment_id = res.data[i].employment_id;

            // console.log(res.data[0].branch[branchList].content);
            //채용 공고 id
            this.branch.push({ text: data, employment_id: employment_id });
          }
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //모집분야 선택 시 면접전형을 불러온다.
    selectedBranch() {
      this.addDate.branch = this.selectedBranch.employment_id;

      if (this.selectedBranch == "") {
        return;
      }

      let reqHttpAddr = "/calendarFundamental_interview_content_list";
      let sendData = {
        id: this.$store.getters.identification, //userID
        company_id: this.selectedCompany.org_company_id,
        school_id: this.selectedSchool.org_school_id,
        branch: this.selectedBranch.employment_id
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.type = res.data;
          //type : [ "면접", "spi"], => 면접전형
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //면접전형 선택 시 차수를 받아온다.
    selectedType() {
      this.addDate.selectedType = this.selectedType.id;

      if (this.selectedType == "") {
        return;
      }

      let reqHttpAddr = "/calendarFundamental_interview_count";
      let sendData = {
        id: this.$store.getters.identification, //userID
        company_id: this.selectedCompany.org_company_id,
        school_id: this.selectedSchool.org_school_id,
        branch: this.selectedBranch.employment_id
      };
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          if (res.data == "") {
            this.selectedLevel = "1";
            this.addDate.selectedLevel = this.selectedLevel;
          } else {
            this.selectedLevel = res.data;
            this.addDate.selectedLevel = this.selectedLevel;
          }
          console.log("selectedLevel");
          console.log(this.selectedLevel);
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //학생 당 면접시간 간격 변경 시 호출
    interval() {
      this.time_allocation("interval");
    }
  },

  methods: {
    //-----------------------------------------공용-----------------------------------------------------------

    // 다국어
    multilingual: function() {
      this.intervalItem = [
        this.$i18n.t("CalendarFundamental.minutes_30"),
        this.$i18n.t("CalendarFundamental.hours_1"),
        this.$i18n.t("CalendarFundamental.minutes_90"),
        this.$i18n.t("CalendarFundamental.hours_2")
      ];
    },

    //스케줄 일정 보기 전 : 날짜, 시간
    viewEvent: function(date) {
      //p = dateData
      console.log("date");
      console.log(date);

      let a = date.start._i.toString().split(" ");
      let b = date.end._i.toString().split(" ");
      this.appliedStd_time_o = [];
      this.appliedStd_time_x = [];

      date.date = a[0];
      date.interview_start_time = a[1];
      date.interview_end_time = b[1];
      this.pickupedData = date;

      let reqHttpAddr = "/schedule_Registration_By_Student";
      let sendData = {
        id: this.$store.getters.identification, //userID
        interview_id: this.pickupedData.interview_id, //interview_id,
        classification: this.$store.getters.classification
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log(res.data);

          //면접에 지원한 학생이 없을 때
          if (res.data.length == 0) {
            this.applied = false;
          } else {
            //면접에 지원한 학생이 있을 때
            this.appliedStd = res.data;
            for (let i = 0; i < res.data.length; i++) {
              if (res.data[i].interview_start_time == null) {
                this.appliedStd_time_x.push(res.data[i].interview_start_time);
              } else {
                this.appliedStd_time_o.push(res.data[i].interview_start_time);
              }
            }
            this.time_allocation("remainder");
          }
        })
        .catch(err => {
          console.log(err.message);
        });

      this.schedule = true;
    },

    //-----------------------------------------에이전트--------------------------------------------------------
    //캘린더 날짜 클릭 시 일정 추가
    setNewEvents: function(date) {
      this.selectedDate = date._d;
      this.selectedDay = this.convert(this.selectedDate);
      this.selectedCompany = "";
      this.selectedSchool = "";
      this.selectedBranch = "";
      this.selectedType = "";
      this.selectedLevel = "";
      this.selectedDate = "";
      this.AddSchedule_Dialog = true;
    },
    //일정 등록 시 날짜 변경 영어 -> 숫자
    convert: function(date) {
      let d = date.toString();
      d = d.split(" ");

      switch (d[1]) {
        case "Jan":
          d[1] = 1;
          break;
        case "Feb":
          d[1] = 2;
          break;
        case "Mar":
          d[1] = 3;
          break;
        case "Apr":
          d[1] = 4;
          break;
        case "May":
          d[1] = 5;
          break;
        case "Jun":
          d[1] = 6;
          break;
        case "Jul":
          d[1] = 7;
          break;
        case "Aug":
          d[1] = 8;
          break;
        case "Sep":
          d[1] = 9;
          break;
        case "Oct":
          d[1] = 10;
          break;
        case "Nov":
          d[1] = 11;
          break;
        case "Dec":
          d[1] = 12;
          break;
      }

      return d;
    },

    //일정추가 시 년, 월, 일, 시간 지정가능
    createSelectDate: function() {
      for (let hour = 1; hour < 25; hour++) {
        this.time.hour.push(hour);
      }
      for (let minute = 0; minute < 60; minute++) {
        if (minute == 0) {
          this.time.minute.push("00");
        } else if (minute < 10) {
          this.time.minute.push("0" + minute);
        } else {
          this.time.minute.push(minute);
        }
      }
    },

    //스케줄 등록
    addItem: function() {
      console.log("this.data");
      console.log(this.data);
      console.log("this.addDate");
      console.log(this.addDate);
      let data = this.validate_nullFields(this.data);
      let addDate = this.validate_nullFields(this.addDate);
      console.log("addItemaksdjf;alskd;alskd");
      console.log(data);
      console.log(addDate);
      if (!data || !addDate) {
        this.$swal({
          type: "error",
          title: "Oops...",
          text: this.$i18n.t("CalendarFundamental.message2")
        });
        return;
      }

      if (this.addDate.endHour < this.addDate.startHour) {
        this.$swal({
          type: "error",
          title: "Oops...",
          text: this.$i18n.t("CalendarFundamental.message3")
        });
        return;
      } else if (this.addDate.endHour == this.addDate.startHour) {
        if (this.addDate.endMinute < this.addDate.startMinute) {
          this.$swal({
            type: "error",
            title: "Oops...",
            text: this.$i18n.t("CalendarFundamental.message3")
          });
          return;
        }
      }

      this.addDate.year = this.selectedDay[3];
      this.addDate.month = this.selectedDay[1];
      this.addDate.day = this.selectedDay[2];

      let reqHttpAddr = "/calendarFundamental_register";
      let sendData = {
        id: this.$store.getters.identification, //userID
        data: this.data, //추가할 스케줄 정보 (기업명, 학교명, 면접전형, 차수),
        addDate: this.addDate, //추가할 스케줄 날짜 (날짜, 시작시간, 끝시간) : 배열
        apiKey: this.$store.getters.push_config.apiKey //apiKey FIXME: 교수에게 스케줄 등록 메세지를 보낸다.
      };
      //console.log(sendData);
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log(res.data);
          if (res.data == "success") {
            this.AddSchedule_Dialog = false;
            this.$emit("callDate");
            // window.location.reload();
          }
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //스케줄 등록 시 회사명 select item
    callCompany: function() {
      let reqHttpAddr = "/calendarFundamental_interview_company_list";
      let sendData = {
        agentId: this.$store.getters.identification //에이전트ID
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.company = res.data;
          console.log("this.company");
          console.log(res.data);
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //에이전트 : 스케줄 수정
    eventChange: function(info) {
      console.log("info");
      console.log(info);

      if (
        info.startHour == "" ||
        info.startMinute == "" ||
        info.endHour == "" ||
        info.endMinute == ""
      ) {
        this.$swal({
          type: "error",
          title: "Oops...",
          text: this.$i18n.t("CalendarFundamental.message2")
        });
        return;
      }

      let reqHttpAddr = "/calendarFundamental_schedule_change";
      let sendData = {
        id: this.$store.getters.identification, //userID
        data: info,
        apiKey: this.$store.getters.push_config.apiKey //apiKey FIXME: 교수에게 스케줄 수정 메세지를 보낸다.(수락한 스케줄을 수정할때만)
      };
      console.log(sendData);
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log(res.data);
          this.changeEvent_Dialog = false;
          this.$emit("callDate");
          // window.location.reload();
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    changeEventDialogOpen: function(date) {
      let year,
        month,
        day,
        startHour,
        startMinute,
        endHour,
        endMinute,
        branch = "";

      let data = date.date.split("-");

      year = data[0];
      month = data[1];
      day = data[2];

      data = date.interview_start_time.split(":");

      startHour = data[0] * 1;

      console.log(startHour);

      startMinute = data[1];

      data = date.interview_end_time.split(":");
      endHour = data[0] * 1;
      endMinute = data[1];

      this.changePickupedDate = {
        org_college_name: date.org_college_name,
        interview_id: date.interview_id,
        employment_id: date.employment_id,
        content: date.content,
        school: date.school,
        interview_place: date.interview_place,
        org_name: date.title,
        employment_id: date.employment_id,
        interview_check_ox: date.interview_check_ox,
        interview_count: date.interview_count,
        date: date.date,
        startHour: startHour,
        startMinute: startMinute,
        endHour: endHour,
        endMinute: endMinute,
        branch: date.branch
      };

      this.changeEvent_Dialog = true;
    },

    //delete schedule (에이전트)
    deleteItem: function(info) {
      let reqHttpAddr = "/calendarFundamental_interview_schedule_delete";
      let sendData = {
        id: this.$store.getters.identification, //userID
        interview_id: info.interview_id, //인터뷰 ID
        apiKey: this.$store.getters.push_config.apiKey //apiKey FIXME: 교수에게 스케줄 삭제 메세지를 보낸다.
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log(res.data);
          this.$emit("callDate");
          // window.location.reload();
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //이벤트 드래그 시 날짜 변경
    eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) {
      if (this.$store.getters.classification == "agent") {
        console.log("drag!!!");
        console.log(event);

        console.log(event, delta, revertFunc, jsEvent, ui, view);
        let d_e = this.convert(event.end._d);
        event.date = d_e[3] + "-" + d_e[1] + "-" + d_e[2];
        let reqHttpAddr = "/calendarFundamental_schedule_change_year";
        let sendData = {
          id: this.$store.getters.identification, //userID
          date: event.date, //변경된 날짜
          interview_id: event.interview_id,
          apiKey: this.$store.getters.push_config.apiKey //apiKey
        };

        this.axios
          .post(reqHttpAddr, sendData)
          .then(res => {
            console.log(res.data);
            this.$emit("callDate");
          })
          .catch(err => {
            console.log(err.message);
          });
      } else {
        this.$emit("callDate");
      }
    },

    //입력 값에 빈 값이 있는지 체크
    validate_nullFields: function(data) {
      console.log("Dafsdafsdfasdfasdfasdfasdf");

      for (let key in data) {
        console.log(data[key]);
        if (!data[key]) return false;
      }
      return true;
    },

    //-----------------------------------------교수-----------------------------------------------------------
    //시간할당
    time_allocation: function(type) {
      let startTime = [];
      let result = "";
      let count = "";
      this.interview_time = [];
      // if(this.appliedStd_time_o.length > 0){
      if (type == "remainder") {
        startTime = this.appliedStd_time_o[this.appliedStd_time_o.length - 1];
        startTime = startTime.split(" ");
        startTime = startTime[1];
        count = this.appliedStd.length - this.appliedStd_time_o.length;
      } else {
        this.appliedStd_time_o = [];
        startTime = this.pickupedData.interview_start_time;
        this.interview_time.push(this.pickupedData.date + " " + startTime);
        count = this.appliedStd.length;
      }

      startTime = startTime.split(":");
      startTime = startTime.map(Number);

      for (let i = 1; i < count; i++) {
        switch (this.interval) {
          case "30분":
            startTime[1] = startTime[1] + 30;
            if (startTime[1] > 119) {
              startTime[0] = startTime[0] + 2;
              startTime[1] = startTime[1] - 120;
            } else if (startTime[1] > 59) {
              startTime[0] = startTime[0] + 1;
              startTime[1] = startTime[1] - 60;
            }
            result =
              this.pickupedData.date +
              " " +
              startTime[0] +
              ":" +
              startTime[1] +
              ":" +
              "00";
            this.interview_time.push(result);

            break;
          case "1시간":
            startTime[0] = startTime[0] + 1;
            result =
              this.pickupedData.date +
              " " +
              startTime[0] +
              ":" +
              startTime[1] +
              ":" +
              "00";
            this.interview_time.push(result);

            break;
          case "90분":
            startTime[0] = startTime[0] + 1;
            startTime[1] = startTime[1] + 30;

            if (startTime[1] > 119) {
              startTime[0] = startTime[0] + 2;
              startTime[1] = startTime[1] - 120;
            } else if (startTime[1] > 59) {
              startTime[0] = startTime[0] + 1;
              startTime[1] = startTime[1] - 60;
            }

            result =
              this.pickupedData.date +
              " " +
              startTime[0] +
              ":" +
              startTime[1] +
              ":" +
              "00";
            this.interview_time.push(result);
            break;

          case "2시간":
            startTime[0] = startTime[0] + 2;
            result =
              this.pickupedData.date +
              " " +
              startTime[0] +
              ":" +
              startTime[1] +
              ":" +
              "00";
            this.interview_time.push(result);
            break;
        }
      }
    },

    //스케줄 수락(교수)
    agree: function(info) {
      let reqHttpAddr = "/calendarFundamental_interview_schedule_agree";
      let sendData = {
        id: this.$store.getters.identification, //userID
        interview_id: info.interview_id, //interview_id
        agree: "o", //스케줄 수락 표시
        interview_place: info.interview_place, //장소
        apiKey: this.$store.getters.push_config.apiKey //apiKey //FIXME: 에이전트에게 스케줄 수락을 보낸다.
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          // window.location.reload();
          this.$emit("callDate");
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //장소수정 (교수)
    professorPlaceSave: function(info) {
      let reqHttpAddr = "/calendarFundamental_interview_place_change";
      let sendData = {
        id: this.$store.getters.identification, //userID
        interview_id: info.interview_id, //interview_id
        interview_place: info.interview_place, //장소
        apiKey: this.$store.getters.push_config.apiKey //apiKey
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log(res.data);
          // window.location.reload();
          this.$emit("callDate");
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //학생 스케줄 할당
    addStdInterval: function() {
      let time = [];
      console.log("this.appliedStd_time_o");
      console.log(this.appliedStd_time_o);

      if (this.interview_time.length == 0) {
        for (let i = 0; i < this.appliedStd_time_o.length; i++) {
          time.push(this.appliedStd_time_o[i].split(" "));
        }
      } else {
        for (let i = 0; i < this.interview_time.length; i++) {
          time.push(this.interview_time[i].split(" "));
        }
      }

      let real_time = [];
      for (let j = 0; j < time.length; j++) {
        real_time.push(time[j][1]);
      }

      console.log(real_time);

      let reqHttpAddr = "/calendarFundamental_std_addStdInterval";
      let sendData = {
        id: this.$store.getters.identification, //userID
        appliedStd: this.appliedStd,
        interview_time: real_time,
        interval: this.interval,
        date: this.pickupedData.date,
        apiKey: this.$store.getters.push_config.apiKey //apiKey //FIXME: 학생에게 스케줄 시간을 보낸다.
      };
      console.log(sendData);
      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log(res.data);
          this.$emit("callDate");
        })
        .catch(err => {
          console.log(err.message);
        });
    }
  }
};
</script>