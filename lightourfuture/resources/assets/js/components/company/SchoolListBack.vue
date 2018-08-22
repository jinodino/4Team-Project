<template>
    <v-container fluid grid-list-xs grid-list-md grid-list-lg>
        <!-- School List -->
        <v-layout row>
            <v-flex xs12 sm12 md3 lg3>
                <v-card height="100px">
                    <div style="background:red;">asdfasdf</div>                
                </v-card>
            </v-flex>
            <v-flex xs12 sm12 md9 lg9>
                <v-card>
                    <div style="background:blue;">sadfasdf</div>                
                </v-card>
            </v-flex>
        </v-layout>
       
        <v-layout row>
            <v-flex d-flex xs12 sm12 md1z   2 lg12>
                <v-card>
                    
                    

                    <v-card-title class="justify-center headline">
                        <v-icon medium color="blue">school</v-icon>&nbsp; 학교 리스트 
                    </v-card-title>

                    <v-card-title>
                        <v-text-field
                            append-icon="search"
                            label="Search"
                            single-line
                            hide-details
                            v-model="schoolSearch"
                        ></v-text-field>
                    </v-card-title>
                    
                    <v-data-table
                    :headers="schoolHeaders"
                    :items="schoolList"
                    :search="schoolSearch"
                    class="elevation-1"
                    >
                        <template slot="items" slot-scope="props" >
                            <tr @click="getAnonStdList(props.item.org_college_id)">
                                <td>{{ props.item.org_agent_name}}</td>
                                <td>{{ props.item.country_eng }}</td>
                                <td>{{ props.item.address_city}}</td>
                                <td>{{ props.item.college_category}}</td>
                                <td>{{ props.item.org_name_kana}}</td>
                                <td>
                                    <a :href="props.item.homepage_url">
                                        <v-icon color="blue">call_missed_outgoing</v-icon> Go
                                    </a>
                                </td>
                                <td>
                                    <v-icon color="teal">list</v-icon>
                                    ({{ props.item.activateStds}} / {{props.item.totalStds}})
                                </td>
                            </tr> 
                        </template>
                    </v-data-table>                    
                </v-card>
            </v-flex>
        </v-layout>

        <!-- Anonymous Student List -->
        <v-layout row wrap>
            <v-flex d-flex xs12 sm12 md12 lg12>
                <v-card>
                    <v-card-title class="justify-center headline">
                        <v-icon medium color="blue">school</v-icon>&nbsp; 익명 학생 정보
                    </v-card-title>

                    <v-card-title>
                        <v-text-field
                            append-icon="search"
                            label="Search"
                            single-line
                            hide-details
                            v-model="anonStdSearch"
                        ></v-text-field>
                    </v-card-title>

                    <v-alert :value="true" color="error" icon="new_releases" class="center" v-if="anonStdList == false">
                        Sorry, nothing to display data here :) There no Students
                    </v-alert>

                    <v-data-table v-else
                    :headers="anonStdHeaders"
                    :items="anonStdList"
                    :search="anonStdSearch"
                    class="elevation-1"
                    >
                        <template slot="items" slot-scope="props" >
                            <tr @click="getSelectedAnonStdDetails(props.item.login_id)"
                                @click.native.stop="detailShow = true">
                                <td>{{ props.item.gender}}</td>
                                <td>{{ props.item.birth_date}}</td>
                                <td>{{ props.item.email }}</td>
                                <td>{{ props.item.JPT }}</td>
                                <td>{{ props.item.JLPT}} grade</td>
                                <td>{{ props.item.recommendation_comment}}</td>
                            </tr> 
                        </template>
                    </v-data-table>                    
                </v-card>
            </v-flex>

            <!-- Seleted Student Information -->
            <v-flex d-flex xs6 sm6 md6 lg6 v-if="detailShow">
                <v-card>
                    <v-card-title primary-title class="justify-center headline titles">
                        <v-icon color="green" medium>assignment_ind</v-icon>&nbsp;DETAILS 
                    </v-card-title>
                    
                    <v-card-text  class="justify-center">
                        
                        <v-layout border>
                            <v-flex xs2 sm2 md2 lg2 border>Interested</v-flex>
                            <v-flex xs4 sm4 md4 lg4 border>{{selectedStd_personalInfo.interested_field_content}}</v-flex>

                            <v-flex xs2 sm2 md2 lg2 border>Credit</v-flex>
                            <v-flex xs4 sm4 md4 lg4 border>{{selectedStd_personalInfo.credit}}</v-flex>
                        </v-layout>

                        <v-layout border>
                            <v-flex xs2 sm2 md2 lg2 border>Graduate</v-flex>
                            <v-flex xs4 sm4 md4 lg4 border>{{selectedStd_personalInfo.graduate_date}}</v-flex>

                            <v-flex xs2 sm2 md2 lg2 border>Major</v-flex>
                            <v-flex xs4 sm4 md4 lg4 border>{{selectedStd_personalInfo.major_grade}}</v-flex>
                        </v-layout>

                        <v-layout border>
                            <v-flex xs2 sm2 md2 lg2 border>Pro Comment</v-flex>
                            <v-flex xs10 sm10 md10 lg10 border>{{selectedStd_personalInfo.recommendation_comment}}</v-flex>
                        </v-layout>

                        <v-layout border>
                            <v-flex xs2 sm2 md2 lg2 border>Self Comment</v-flex>
                            <v-flex xs10 sm10 md10 lg10 border>{{selectedStd_personalInfo.pr_content}}</v-flex>
                        </v-layout>

                        <v-layout border>
                            <v-flex xs2 sm2 md2 lg2 border>Motivation</v-flex>
                            <v-flex xs10 sm10 md10 lg10 border>{{selectedStd_personalInfo.motivation}}</v-flex>
                        </v-layout>
                    </v-card-text>
                </v-card>
            </v-flex>

            <!-- skill sheet -->
            <v-flex d-flex xs6 sm6 md6 lg6 v-if="detailShow">
                <v-card>
                    <v-card-title primary-title class="justify-center headline titles">
                        <v-icon color="green" medium>insert_chart</v-icon>&nbsp; SKILL SHEET
                    </v-card-title><hr>

                    <v-card-text>
                        <skill-sheet :labels="skills.items" :levels="skills.levels"/>
                    </v-card-text>    

                    <v-card-text>
                        <p class="subheading"> <v-icon color="green">language</v-icon> Language </p>
                        <p class="body">
                            <v-chip outline color="primary" v-if="selectedStd_personalInfo.JLPT">
                                JLPT : {{selectedStd_personalInfo.JLPT}} 
                            </v-chip>
                        
                            <v-chip outline color="primary" v-if="selectedStd_personalInfo.JPT">
                                JPT   : {{selectedStd_personalInfo.JPT}} 
                            </v-chip>
                                
                            <v-chip outline color="primary">
                                Toeic : {{selectedStd_personalInfo.TOEIC}}
                            </v-chip>

                            <v-chip outline color="primary">
                                Mock Toeic : {{selectedStd_personalInfo.mock_TOEIC}}
                            </v-chip>

                            <v-chip outline color="primary">
                                TOEFL : {{selectedStd_personalInfo.TOEFL}}
                            </v-chip>
                        </p><hr>

                        <p class="subheading"> <v-icon color="green">mdi-quadcopter</v-icon> Acivities & Qualification </p>
                        <p class="body">
                            <v-chip outline color="blue">
                                 {{selectedStd_personalInfo.activities}}
                            </v-chip>
                        </p>
                    </v-card-text>     
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import skillChart from "./skillChart.vue";
    import { Radar } from 'vue-chartjs';
  /** 
   * @author JyunSoo Jang 
   * @fileOverview 
   *    Show registrated School List
   * TODO:
   *   - [o] get school list
   *              Infomation List
   *                  -  student num
   *                  -  shcool name
   *                  -  region
   *   - [o] create Category & data processing accroding to Seleted Categories
   *   - [o] Student Details & Skill Sheet
   */ 
export default { 

    components : { "skill-sheet" : skillChart } ,
     
    data : ()=>({
        /**
         * @desc set visivility
         */
        detailShow      : false,
        anonStdListShow : false,

        /**
         * @desc get from server => school list 
         */
        schoolList  : null,
        anonStdList : null,
        selectedStd_skillInfo    : null,
        selectedStd_personalInfo : null,

        /**
         * @desc for data table that school list
         */
        schoolSearch  : null,
        schoolHeaders :
        [ 
            //  "Country", "Name(En)" , "Name(Jp)" , "city" , "address" , "homePage" , "college Type"
            { text : "Agent"             , align : "left" , value : "org_agent_name"   },
            { text : "Country"           , align : "left" , value : "country_eng"  },
            { text : "City"              , align : "left" , value : "address_city" },
            { text : "College Type"      , align : "left" , value : "college_category" },
            { text : "School Name(JP)"   , align : "left" , value : "org_name_kana" },
            { text : "HomePage"          , align : "left" , value : "homepager_url" },
            { text : "Students"          , align : "left" , value : "stdCount" },
        ],
        /**
         * @desc for data table that anonymous student list
         */
        anonStdSearch  : null,
        anonStdHeaders :
        [
            { text : "Gender"   , align : "left" , value : "gender" },
            { text : "Brith"    , align : "left" , value : "birth_date"},
            { text : "Email"    , align : "left" , value : "email" },
            { text : "JPT"      , align : "left" , value : "JPT" },
            { text : "JLPT"     , align : "left" , value : "JLPT"},
            { text : "Comment"  , align : "left" , value : "recommendation_comment" ,sortable : false},
        ],

        /**
        * @desc skill chart props
        */
        skills : {
            items  : [],
            levels : [],
        }
    }),

    methods : {
        createStdSkillData : function (skillData) {
        
            this.skills.items  = [];
            this.skills.levels = [];

            let skillLevel = { "最上"   : 7 ,
                                "上"     : 6 ,
                                "中上"   : 5 ,
                                "中"     : 4 ,
                                "中下"   : 3 ,
                                "下"     : 2 ,
                                "最下"   : 1 , };

            for(let i = 0 ; i < skillData.length ; i++){
                this.skills.items.push(skillData[i].skill_name);
                this.skills.levels.push(skillLevel[skillData[i].skill_level]);
            }
        },
        
        /**
         * @desc get selected matched school's anonymous students list 
         * @param { String } selectedStdid
         * @return { Object } anonymous student's detail informations
         */
        getSelectedAnonStdDetails : function (selectedStdId) {
            
            let reqHttpAddr = "/anonStdInfo";
            let sendData    = { stdId : selectedStdId };

            this.axios.post(reqHttpAddr , sendData).then( res => {
                
                if( !res.data ) return;

                console.log(res.data);

                this.selectedStd_skillInfo    = res.data.skill_info;
                this.selectedStd_personalInfo = res.data.student_info[0]; 

                this.createStdSkillData(res.data.skill_info);
                this.detailShow = true;

            }).catch( err => {
                console.log(err.message);
            });
            
        },

        /**
         * @desc get selected matched school's anonymous students list 
         * @param { String } schoolId
         * @return { Array[ Object ] } anonymous students list 
         */
        getAnonStdList : function ( schoolIdentification ) {
           
            let reqHttpAddr = "/anonStdList";
            let sendData    = { selectedSchool  : schoolIdentification };

            this.axios.post(reqHttpAddr , sendData).then( res => {
                
                if( !res.data ){
                    this.anonStdList = [];
                    return;
                }
                console.log(res.data);
                this.anonStdList = res.data;

            }).catch( err => {
                console.log(err.message);
            })
        }, 
    },
    /**
     *  @desc get School List 
     *  @return { Array[Object] }
     */
    created : function(){        

        let reqHttpAddr = "/schoolList";
        let sendData    = {requester : this.$store.getters.identification}
        
        this.axios.post(reqHttpAddr,sendData).then( res => {
            
            if( !res.data ) return;

            for(let i = 0 ; i < res.data.info.length ; i++){
                res.data.info[i].activateStds = res.data.activity_count[i].std_activity;
                res.data.info[i].totalStds    = res.data.total_count[i].std
            }

            this.schoolList  = res.data.info;
        }).catch( err => {
            console.log(err.message);
        });         
    },
    watch : {
        
    }
}

</script>