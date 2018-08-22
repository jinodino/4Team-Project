<template>
    <v-flex style="width:100%;">
        <v-layout row class="std-list">
            <v-flex xs12 sm6 md2 lg2 v-for=" std in students" :key="std.login_id" :hover="true" >
                <v-card>
                    <v-card-media :src="std.profile_photo_url" height="20em" @click="getSelectedStdDetails(std.login_id)"/>
                    <div class="info-box">
                        <span style="text-align:center;" v-if="componentName=='recruit'"> 
                            <v-checkbox
                            :label="std.name_kana +' ('+  std.age + ')'"
                            v-model="adjudgeStds"
                            ></v-checkbox>
                        </span>
                        
                        <span style="text-align:center;" v-else> 
                            {{std.name_kana}}({{std.age}})
                        </span>

                        <p>
                            <v-chip small color="teal darken-1" text-color="white" >
                                    誠実&nbsp;
                                    <span v-if="!std.sincerity_grade"> - </span>
                                    <span v-else>{{std.sincerity_grade}}</span>
                                </v-chip>

                                <v-chip small color="teal darken-1" text-color="white" >
                                    人柄&nbsp;
                                    <span v-if="!std.personality_grade"> - </span>
                                    <span v-else>{{std.personality_grade}}</span>
                                </v-chip>
                            </p>
                            <p>
                                <v-chip small color="teal darken-1" text-color="white" >
                                    会話&nbsp;
                                    <span v-if="!std.japanese_speaking_level"> - </span>
                                    <span v-else>{{std.japanese_speaking_level}}</span>
                                </v-chip>

                                <v-chip small color="teal darken-1" text-color="white" >
                                    専攻&nbsp;
                                    <span v-if="!std.major_grade"> - </span>
                                    <span v-else>{{std.major_grade}}</span>
                                </v-chip>
                            </p>
                    </div>
                </v-card>
            </v-flex>
        </v-layout>


         <v-dialog
            v-model="stdDialogShow" 
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
            fullwidth
        >
        
        <v-flex xs12 sm12 md12 lg12>
            <v-card>
                <v-toolbar card dark color="teal darken-1" :clipped-right="true">
                    <v-btn icon dark @click.native="stdDialogShow=false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title class="headline font-weight-light">
                        詳細情報
                    </v-toolbar-title>
                </v-toolbar>

                <v-container fluid v-if="selectedStd_personalInfo" style="max-hegith:300px; overflow-y:auto;">
                    <v-layout height="100%">  
                        <v-flex xs12 sm12 md3 lg3>
                            <v-card flat>
                                <v-card-media contain :src="selectedStd_personalInfo.profile_photo_url" height="21em"/>
                            </v-card>          
                        </v-flex>    

                        <v-flex xs12 sm12 md5 lg5 style="padding-left:1.5em;">
                            <!-- Student Data Table -->
                            <table class="std-info-table">
                                <tr>
                                    <th scope="row">名前</th>
                                    <td>{{selectedStd_personalInfo.name_kanji}}</td>
                                    <td>{{selectedStd_personalInfo.name_eng}}</td>
                                    <td>{{selectedStd_personalInfo.name_kana}}</td>
                                </tr>

                                <tr>
                                    <th scope="row">言語能力</th>
                                    <td colspan="3" >
                                        <v-chip label outline color="teal darken-1" text-color="black" style="padding:0;" small>
                                            JLPT&nbsp;{{selectedStd_personalInfo.JLPT}}
                                        </v-chip>

                                        <v-chip label outline color="teal darken-1" text-color="black"  small style="padding:0;">
                                            JPT&nbsp;{{selectedStd_personalInfo.JPT}}
                                        </v-chip >

                                        <v-chip label outline color="teal darken-1" text-color="black"  small style="padding:0;">
                                            TOEIC&nbsp;{{selectedStd_personalInfo.TOEIC}}
                                        </v-chip>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">メール</th>
                                    <td>{{selectedStd_personalInfo.email}}</td>

                                    <th scope="row">Git</th>
                                    <td> 
                                        <v-chip label text-color="white" color="teal darken-1" small @click="redirectGithub(selectedStd_personalInfo.github_url)">  
                                            移動
                                        </v-chip> 
                                    </td>                                
                                </tr>
                                
                                <tr>
                                    <th scope="row">チーム</th>
                                    <td>
                                        {{selectedStd_personalInfo.group_name}}
                                    </td>
                                    <th scope="row">担当分野</th>
                                    <td>
                                        {{selectedStd_personalInfo.group_part_content}}
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">エーゼット</th>
                                    <td colspan="3">{{selectedStd_personalInfo.recommendation_comment}}</td>
                                </tr>

                                <tr>
                                    <th scope="row"> 自己PR </th>
                                    <td colspan="3">{{selectedStd_personalInfo.pr_content}}</td>
                                </tr>
                            </table>
                        </v-flex>
                        
                        <v-flex xs12 sm12 md4 lg4>
                            <v-card flat>
                                <v-card-media height="21em" style="padding-left:1.5em">
                                    <iframe :src="selectedStd_personalInfo.pr_video_url" frameborder="0" width="100%" allow="autoplay; encrypted-media"></iframe>
                                </v-card-media>
                            </v-card>
                        </v-flex>
                    </v-layout>

                    <v-layout row pa-2>
                        <v-layout>
                            <v-flex xs12 sm12 md12 lg12  class="sheet-title">
                                日本就職の動機
                            </v-flex>
                        </v-layout>

                        <v-layout>
                            <v-flex xs12 sm12 md12 lg12  class="sheet-title">
                                スキルシート
                            </v-flex>
                        </v-layout>
                    </v-layout>

                    <v-layout row pa-2>
                        <v-flex xs12 sm12 md6 lg6 class="motivation">
                            {{selectedStd_personalInfo.motivation_content}}
                        </v-flex>
                        <v-flex xs12 sm12 md6 lg6 style="text-align:center; padding-left:18em; border:1px solid white">
                            <skill-sheet :labels="skillSheetData.list" :levels="skillSheetData.levels"/>
                        </v-flex>
                    </v-layout>
                </v-container>
                
            </v-card>
        </v-flex>
      </v-dialog>      
 </v-flex>
    
</template>
<style scoped lang="css" src="../../static/css/Company/StdList.css"></style>

<script>

    import studentDetail from "./StdDetails.vue";
    import skillChart    from "./skillChart.vue";
    import { Radar }     from 'vue-chartjs';

    export default {
        
        props : [
            "componentName",
            "students",
        ],

        components : {
            "skill-sheet" : skillChart
        },

        data () {
            return {
                stdDialogShow : false,

                componentType : "anon",
                active        : false,
                selectedStd   : null,
                checkbox      : true,
                selectedStd_skillInfo : null,
                selectedStd_personalInfo : null,
                adjudgeStds   : [],
                menu          : [
                    { title:"PR 영상" , key:1}
                ],
                skillSheetData : 
                {
                    list  : [],
                    levels : [],
                },
            }
        },
        methods : {

            redirectGithub ( gitUrl ) {
                window.location.replace(gitUrl);
            },

            getSelectedStdDetails( stdIdentification ){
            
                // let reqHttpAddr = "/getStdDetails";
                let reqHttpAddr = "/getStdDetails";
                let sendData    = { requester : this.$store.getters.identification,
                                    stdId     : stdIdentification ,
                                    employId  : this.selectedEmploy };

                this.axios.post(reqHttpAddr , sendData).then( res => {
                    
                    if( !res.data ) return;
                    //FIXME: Server Setting
                    this.selectedStd_skillInfo    = res.data.skill_info;
                    this.selectedStd_personalInfo = res.data.student_info[0]; 
                    this.selectedStd_personalInfo.entrysheet   = res.data.entrysheet;
                    this.selectedStd_personalInfo.porportfolio = res.data.porportfolio;

                    this.createStdSkillData(res.data.skill_info);
                    this.stdDialogShow = true;

                }).catch( err => {
                    console.log(err.message);   
                });
            },
        
            createStdSkillData : function (skillData) {
                console.log("skiil");
                this.skillSheetData.list  = [];
                this.skillSheetData.levels = [];

                let skillLevel = { "最上"   : 7 ,
                                    "上"     : 6 ,
                                    "中上"   : 5 ,
                                    "中"     : 4 ,
                                    "中下"   : 3 ,
                                    "下"     : 2 ,
                                    "最下"   : 1 , };

                for(let i = 0 ; i < skillData.length ; i++){
                    this.skillSheetData.list.push(skillData[i].skill_name);
                    this.skillSheetData.levels.push(skillLevel[skillData[i].skill_level]);
                }

            },
        },

        mounted () {
            console.log(this.students);
        },

        watch : {
            students (value) {},

            componentName (value) {
                
            }   
        }
    }


</script>
