<template>
    <v-container fluid grid-list-xs grid-list-md grid-list-lg>
        <v-layout>
            <v-flex xs12 sm12 md12 lg12 d-flex :hover="true">
                <v-card :hover="true">
                    <calendar 
                    :dateData="dateData"
                    :userType="this.$store.getters.classification"
                    ></calendar>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>

</template>

<script>
import Calendar from "../../shared/CalendarFundamental.vue";

/**
 * TODO:
 *      - [o] get Interview Date
 */
export default {

    components : {
        "calendar" : Calendar,
    },
    
    data(){
        return {dateData  : [
            {content : "서류전형＋면접",date:"2018-05-03",employment_id:1,interview_count:1,interview_end_time:"12:30:00",interview_place:"영진 정보관",interview_start_time:"10:30:00",org_name:"三成",org_name_kana:"サンション",schedule_title:null},
                {  content : "서류전형＋면접",date:"2018-05-05",employment_id:1,interview_count:1,interview_end_time:"12:30:00",interview_place:"영진 정보관",interview_start_time:"10:30:00",org_name:"A",org_name_kana:"サンション",schedule_title:null}
],
        }
    },
    
    methods : {
        /**
         *  @desc get interview details witch seletedDate
         *  @param { Date } seletedDate 
         *  @return { Array | Object } this. 
         */
        show_interviewDetails : function (seletedDate){
            
        }
    }
}
</script>

