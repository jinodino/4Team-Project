<template>
    <v-container fill-height grid-list-lg  fluid>
       <v-layout column class="wrap-cont">
           <v-layout row>

               <v-flex xs4>
                    <v-layout column class="school-section">
                        <v-flex xs12>
                            <span class="sub-title">学校リスト</span>
                        </v-flex>

                        <v-flex>
                            <template>
                                <b-table 
                                    outlined :fixed="true" 
                                    hover 
                                    responsive
                                    striped
                                    :current-page="currentPage"
                                    :per-page="perPage"
                                    :fields="collegeListHeader"
                                    :items="collegeListItems" 
                                    :filter="filter"
                                    @filtered="onFiltered"
                                    @row-clicked="setSelectedCollege">

                                    <template slot="name"  slot-scope="row"> {{row.value}} </template>
                                    <template slot="coun"  slot-scope="row"> {{row.value}} </template>
                                    <template slot="agent" slot-scope="row"> {{row.value}} </template>
                                </b-table>
                            </template>
                        </v-flex> <v-spacer></v-spacer>

                        <v-flex>
                            <b-row>
                                <b-col md="5">
                                    <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
                                </b-col>

                                <b-col md="5" class="right">
                                    <b-form-input v-model="filter" size="md" placeholder="Search"></b-form-input>
                                </b-col>                  
                            </b-row>
                        </v-flex>
                    </v-layout>
               </v-flex>

               <v-flex xs8 style="height:100%">
                    <span class="sub-title">選択学校情報</span>

                    <v-flex xs12 style="border:1px solid #008080">
                        
                        <v-flex xs3>
                            <GmapMap style="width:5em; height:10em%;" :zoom="zoom" :center="center" :language="'ja'">
                            <GmapMarker v-for="(marker, index) in markers"
                                :clickable="true" 
                                :key="index"
                                :position="marker.position"
                                @click="center=marker.position, zoom=13"  />
                            </GmapMap>

                            <v-flex style="text-align:center; font-family:'Noto Sans'">
                                <!-- <span class="subject" v-if="selectedCollegeInfo.org_name != '' "> {{selectedCollegeInfo.org_name}} </span> <br> -->
                                <!-- <span class="subject" v-if="selectedCollegeInfo.org_name != '' "> {{selectedCollegeInfo.org_agent_name}} </span> -->
                            </v-flex>
                        </v-flex>                        

                    </v-flex>
               </v-flex>
           </v-layout>

           <v-layout column pa-1>
               <span class="sub-title">所属学生リスト</span>

               <v-flex xs12 style="border:1px solid #008080">
                  
               </v-flex>
           </v-layout>
       </v-layout>
    </v-container>
</template>
<style scoped lang="css" src="../../static/css/Company/SchoolStdInfo.css"></style>

<script>
/*
    FIXME:  
        - [x] 구글 맵 div class 속성 동적으로 배치 할것 -> 학교 선택 없을 시 다른 문구 출력
        - [x] 선택 학교 리스트에서 row 색상 변경시키기 
*/
    import stdList from "./StdList.vue";
    import { Bar } from "vue-chartjs";
    import jlptStatistic from "./JapanenseStatisticChart.vue";
    
    export default{

        components : {
            jlptStatistic,
            stdList
        },

        data () {
            return {
                /**
                 * @desc google map api setting
                 */
                markers : [],
                zoom    : 6,
                center  : {lat: 36.48, lng :127.62205110000002},

                /**
                 * @desc college list settings 
                 */
                filter      : null,
                perPage     : 5,
                totalRows   : null,
                collegeSum  : null,
                currentPage : 1,
                
                selectedCollegeId : null,
                collegeListItems  : [],      
                collegeListHeader : 
                [
                    { key : "org_name"       , label : "College" },
                    { key : "country_eng"    , label : "Country" },
                    { key : "org_agent_name" , label : "Agent"   },
                ],
                          
                /**
                 * @desc selected college information
                 */
                selectedCollegeInfo : null,
                selectedCollegeInfoDivClass : null,
                selectedCollegeFaculties : null,

                /**
                 * @desc selected faculty's infomations
                 */
                selectedFacultyId    : null,
                selectedFacultyStds  : null,
                selectedFacultyTeams : null, 
                selectedFacultyJlptMost   : null,
                selectedFacultyChartData  : null,
                selectedFacultyCommonData : null,
            }
        },
        methods : {
            /**
             * @desc searching , set pagenation
             * @param { String }
             * @return { Array } 
             */
            onFiltered ( filteredItems ) {
                this.totalRows = filteredItems.length;
                this.currentPage = 1
            },

            redirectProjectViedo ( viedoUrl ) {
                console.log(viedoUrl);
                window.open(viedoUrl);
            },

            setSelectedCollege ( college ) {
                this.selectedCollegeId = college.org_college_id;
            },
            /**
             * @desc selected faculty's std,project team,gender late 
             * @param { Number } facultyId
             * @return { Array [Object] }
             */
            getSelectedFacultyInfo () {

                if(!this.selectedFacultyId) return;

                let reqHttpAddr = "/testfacultyDetailInfo";
                let sendData    = { faculty : this.selectedFacultyId };

                this.axios.post( reqHttpAddr , sendData ).then( res => {

                    if( !res.data ) return;

                    this.selectedFacultyTeams = res.data.groupList;
                    this.selectedFacultyStds  = res.data.stdList;
                    this.selectedFacultyJlptMost  = res.data.facultyInfo[0];
                    this.selectedFacultyChartData = res.data.facultyInfo.slice(4,res.data.facultyInfo.length);
                    
                    this.selectedFacultyCommonData.push( { key : "stdSum"    , sum : res.data.facultyInfo[0]} );
                    this.selectedFacultyCommonData.push( { key : "activeStd" , sum : res.data.facultyInfo[1]} );
                    this.selectedFacultyCommonData.push( { key : "genderM "  , sum : res.data.facultyInfo[2]} );
                    this.selectedFacultyCommonData.push( { key : "genderF"   , sum : res.data.facultyInfo[3]} );

                }).catch ( err => {
                     console.log(err.message);
                })
            },

            /**
             * @desc get selected college's detail
             * @return { Array [Object]}
             */
            getSelectedCollegeInfo () {
                
                //google map init
                this.markers = [];
                this.center  = {lat: 36.48, lng :127.62205110000002};
                this.zoom    = 6;

                this.selectedCollegeFaculties = [];

                let reqHttpAddr = "/testselectCollegeInfo";
                let sendData    = { collegeId : this.selectedCollegeId };

                this.axios.post( reqHttpAddr , sendData ).then( res => {
                    
                    if(!res.data) return;

                    let collegeData = res.data[0][this.selectedCollegeId];
                    
                    this.selectedCollegeInfo      = collegeData.info;
                    this.selectedCollegeFaculties = collegeData.faculty
                    
                    //반별 학생 취직 활동 active 상태 학생 숫자 삽입
                    for(let i = 0 ; i < collegeData.faculty.length ; i++){
                        this.selectedCollegeFaculties[i].stdSum = collegeData.stdList[i];
                    }

                    console.log(this.selectedCollegeInfo);
                    //set google maps maker position => new google maps object with ( latitude , longitude ) 
                    let selectedCollegeLocation = new google.maps.LatLng( collegeData.info.latitude, collegeData.info.longitude );
                    this.markers.push( { position : selectedCollegeLocation } );

                }).catch( err => {
                    console.log(err.message);
                })
            },
        }, 

        /**
         * @desc get list a college list in sales
         * @return { Array {Object} } 
         */
        created () {

            let reqHttpAddr = "/testcollegeList";
            let sendData    = {requester : this.$store.getters.identification}
        
            this.axios.post(reqHttpAddr,sendData).then( res => {
            
                if( !res.data ) return;

                this.collegeListItems  = res.data;
                this.totalRows = res.data.length;

            }).catch( err => {
                console.log(err.message);
            });    
        },

        watch : {
            selectedFacultyId ( value ) {
                this.getSelectedFacultyInfo();
            },

            /**
             * @desc data init
             */
            selectedCollegeId ( value ) {
                this.selectedFacultyId      = null,
                this.selectedFacultyStds    = null,
                this.selectedFacultyTeams   = null, 
                this.selectedFacultyCommonData  = [];
                this.selectedFacultyJlptMost    = null,
                this.selectedFacultyChartData   = null;
                this.getSelectedCollegeInfo();
            } 
        }
    }
    
</script>
