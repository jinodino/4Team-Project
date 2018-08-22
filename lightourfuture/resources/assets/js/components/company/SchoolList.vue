<template>
    <v-container fill-height grid-list-lg  fluid class="wrap-cont" pa-2>
        <!-- college infomations -->
        <v-layout row>
            <v-flex xs12 md4>
                <v-flex xs12 sm12 md12 lg12>
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

                    <v-alert v-if="collegeListItems.length == 0" :value="true" type="error">
                        サービスに登録した学校がありません。
                    </v-alert>
                </v-flex>

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
            </v-flex>

            <v-flex xs12 md8>
                <v-flex xs12>
                    <span class="sub-title">選択学校情報</span>
                </v-flex>
                
                <v-flex xs12 >
                    <v-layout row>
                        <v-flex xs12 md3 style="background:rgba(177, 177, 177, 0.1)"> 
                            <div style="height:21em; width:100%;">
                                <GmapMap style="width:100%; height:82%;" :zoom="zoom" :center="center" :language="'ja'">
                                    <GmapMarker v-for="(marker, index) in markers"
                                        :clickable="true"
                                        :key="index"
                                        :position="marker.position"
                                        @click="center=marker.position, zoom=13"  />
                                </GmapMap>

                                <v-card-text v-if="selectedCollegeInfo" style="text-align:center;">
                                    <v-chip label color="teal darken-1" text-color="white" class="subheading text-md-center font-weight-regular">
                                        {{selectedCollegeInfo.org_name}} ({{selectedCollegeInfo.address_city}})
                                    </v-chip>   
                                </v-card-text>

                                <v-card-text v-else class="justify-center" style="text-align:center;">
                                    <v-chip label color="orange" text-color="white" class="subheading text-md-center font-weight-regular">
                                        学校を選択して下さい
                                    </v-chip>   
                                </v-card-text>
                            </div>
                        </v-flex>    

                        <v-flex xs12 md9 style="background:rgba(177, 177, 177, 0.1);">
                            <v-layout column class="faculty-section">
                                <v-layout row wrap style="padding-bottom:15px 15px 0 15px;">

                                    <v-flex xs12 md4 v-if="selectedCollegeInfo">
                                        <v-flex xs12 style="padding-top:0;">
                                            <span class="small-title"> クラスリスト</span>
                                        </v-flex>

                                        <v-flex xs12 >
                                            <div v-if="selectedCollegeFaculties" v-for="faculty in selectedCollegeFaculties" :key="faculty.faculty_id" >
                                                <v-chip v-if="faculty.stdSum != 0" outline small label color="teal darken-1" text-color="teal" @click="selectedFacultyId = faculty.faculty_id">
                                                    {{faculty.department_name}} &nbsp;
                                                    <b-badge variant="primary" pill> {{faculty.stdSum}} </b-badge>
                                                </v-chip>

                                                <v-chip v-else small  outline label color="grey darken-2" text-color="grey darken-2" >
                                                    <span> {{faculty.department_name}} </span>
                                                </v-chip>
                                            </div>
                                        </v-flex>                      
                                    </v-flex>

                                    <v-flex xs12 md8 v-if="selectedFacultyChartData">
                                        <span class="small-title"> 日本語能力サマリー </span>
                                        <br>
                                        <jlpt-statistic v-if="selectedFacultyChartData" 
                                            :jlpt="selectedFacultyChartData"
                                            :max="selectedFacultyJlptMost"
                                            :height="150"
                                        />
                                    </v-flex>                                                                                                 
                                </v-layout>

                                <v-layout row wrap style="padding:0px 0 15px 15px;" v-if="selectedFacultyChartData">
                                    <v-flex xs12 >
                                        <span class="small-title">プロジェクトチームリスト</span>

                                        <v-card-text>
                                            <v-alert v-if="selectedFacultyTeams && selectedFacultyTeams.length == 1 && !selectedFacultyTeams[0].group_name" :value="true" type="warning" class="subheading">
                                                専攻を選んでください。
                                            </v-alert>

                                            <v-chip v-if="selectedFacultyTeams && selectedFacultyTeams.length > 1 && team.group_name" class="justify-center" small color="teal darken-1" text-color="white" v-for="team in selectedFacultyTeams" 
                                                    :key="team.group_id"  @click="mediaUrl = team.project_video_url , projectMedia = true">
                                                    {{team.group_name}}
                                            </v-chip>
                                        </v-card-text>
                                    </v-flex>
                                </v-layout>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-flex>
        </v-layout>

        <v-layout column class="title-section">
            <v-flex xs12 flat>
                <span class="sub-title">所属学生リスト</span>           
                <v-alert v-if="!selectedFacultyStds" :value="true" type="error" class="subheading">
                専攻を選んでください。
                </v-alert>
            </v-flex>            
     
            <v-flex v-if="selectedFacultyStds" flat>
                <stdList :students="selectedFacultyStds"  :component-name="'anon'" height="100%"/>
            </v-flex>
        </v-layout>   


        <v-dialog   
        v-model="projectMedia" 
        hide-overlay
        transition="dialog-bottom-transition"
        scrollable
        full-width
        >
            <v-card>
                <v-toolbar card dark color="teal darken-1">
                    <v-btn icon dark @click.native="projectMedia=false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>プロジェクトプレゼンテーション</v-toolbar-title>
                </v-toolbar>

                <v-flex xs12 md12>
                    <v-card-media >
                        <iframe :src="mediaUrl" frameborder="0" height="600px" width="100%" allow="autoplay; excrypted-media"></iframe>
                    </v-card-media>
                </v-flex>
            </v-card>
      </v-dialog>

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
                 * @desc project pre media
                 */
                projectMedia  : false,
                mediaUrl      : null,

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

                    console.log(this.selectedFacultyTeams);
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
                    console.log(collegeData);
                    //반별 학생 취직 활동 active 상태 학생 숫자 삽입
                    for(let i = 0 ; i < collegeData.faculty.length ; i++){
                        this.selectedCollegeFaculties[i].stdSum = collegeData.stdList[i];
                    }


                    //set google maps maker position => new google maps object with ( latitude , longitude ) 
                    let selectedCollegeLocation = new google.maps.LatLng( collegeData.info.latitude, collegeData.info.longitude );
                    console.log(selectedCollegeLocation);
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
                console.log(value)
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
