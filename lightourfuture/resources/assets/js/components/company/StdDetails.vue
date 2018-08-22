 <template>
    <v-container fluid>
        <v-layout height="100%">  
            <v-flex xs12 sm12 md3 lg3>
                <v-card flat>
                    <v-card-media :src="student.profile_photo_url" height="24em"/>
                </v-card>          
            </v-flex>    

            <v-flex xs12 sm12 md5 lg5 style="padding-left:1.5em;">
                <!-- Student Data Table -->
                    <table class="std-info-table">
                        <tr>
                            <th scope="row">名前</th>
                            <td>{{student.name_kanji}}</td>
                            <td>{{student.name_eng}}</td>
                            <td>{{student.name_kana}}</td>
                        </tr>

                        <tr>
                            <th scope="row">言語能力</th>
                            <td colspan="3" >
                                <v-chip label outline color="teal darken-1" text-color="black" style="padding:0;" small>
                                    JLPT&nbsp;{{student.JLPT}}
                                </v-chip>

                                <v-chip label outline color="teal darken-1" text-color="black"  small style="padding:0;">
                                    JPT&nbsp;{{student.JPT}}
                                </v-chip >

                                <v-chip label outline color="teal darken-1" text-color="black"  small style="padding:0;">
                                    TOEIC&nbsp;{{student.TOEIC}}
                                </v-chip>

                                <v-chip label outline color="teal darken-1" text-color="black"  small style="padding:0;">
                                    TOEFL&nbsp;{{student.TOEFL}}
                                </v-chip>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">メール</th>
                            <td>{{student.email}}</td>

                            <th scope="row">Git</th>
                            <td> 
                                <v-chip label text-color="white" color="teal darken-1" small @click="redirectPage(student.github_url)">  
                                    移動
                                </v-chip> 
                            </td>                                
                        </tr>
                        
                        <tr>
                            <th scope="row">チーム</th>
                            <td>
                                {{student.group_name}}
                            </td>
                            <th scope="row">担当分野</th>
                            <td>
                                {{student.group_part_content}}
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">関心分野</th>
                            <td colspan>{{student.interested_field_content}}</td>
                            <th scope="row">活動</th>
                            <td colspan>{{student.activities}}</td>
                        </tr>

                        <tr>
                            <th scope="row">エーゼット</th>
                            <td colspan="3">{{student.recommendation_comment}}</td>
                        </tr>

                        <tr>
                            <th scope="row"> 自己PR </th>
                            <td colspan="3">{{student.pr_content}}</td>
                        </tr>
                    </table>
            </v-flex>

            <v-flex xs12 sm12 md4 lg4 style="text-align:center; padding-left:1.5em">
                <v-card flat>
                    <v-card-media height="24.3em" contain>
                        <iframe :src="student.pr_video_url" frameborder="0" width="100%" allow="autoplay; encrypted-media"></iframe>
                    </v-card-media>
                </v-card>
            </v-flex>
        </v-layout> <br>

        <v-layout>
            <v-flex xs12 sm12 md12 lg12>
                <v-tabs
                    v-model="active"
                    color="teal"
                    dark
                    centered
                    slider-color="white"
                >
                    <v-tab
                        v-for="menu in menues"
                        :key="menu.key"
                        ripple
                        class="title"
                    >
                        {{ menu.title }}
                    </v-tab>

                        <v-tab-item>
                            <v-card flat>
                                <v-card-text>
                                    <v-card-media>
                                        <embed :src="student.entrysheet" type="application/pdf" width="100%" height="600px">
                                    </v-card-media>
                                </v-card-text>
                            </v-card>
                        </v-tab-item>

                        <v-tab-item>
                            <v-card flat>
                                <v-card-text>
                                    <v-card-media>
                                        <embed :src="student.porportfolio" type="application/pdf" width="100%" height="600px">
                                    </v-card-media>
                                </v-card-text>
                            </v-card>
                        </v-tab-item>

                    </v-tabs>
                </v-flex>
        </v-layout>
    </v-container>
 </template>


<style scoped lang="css" src="../../static/css/Company/StdDetails.css"></style>

 <script>
    import skillChart    from "./skillChart.vue";
    import { Radar }     from 'vue-chartjs';
    export default {
        
        props : [
            "student",
            "skills",
            // "employment"
        ],

        components : {
            "skill-sheet" : skillChart
        },

        data () {
            return {
                text: "sdfasdfasdfasdfasdf",
                active : null,
                menues : 
                [
                    { title : "履歴書"          , key :1 },
                    { title : "ポートフォリオ"  , key :2 },
                ]
            }
        },

        methods : {
            redirectPage ( gitUrl ) {
                window.location.replace(gitUrl);
            },
        },
        watch : {
            student () {

            }
        }
    }

 </script>