<template>

    <v-app style="background-color:white;">

        <v-toolbar color="transparent" flat style="margin-top:20px" fixed>
            <div style="width : 2px"  class="hidden-sm-and-down"></div>
            <v-toolbar-title>
                <img :src="logo" width="130px" height="auto">                
            </v-toolbar-title>
            <v-spacer></v-spacer>

            <v-toolbar-items class="hidden-sm-and-down">
                <v-btn flat color="success" style="font-size:16px;">KOR</v-btn>
            </v-toolbar-items>
            <div style="border-left: 2px solid #008080; height : 30px;" class="hidden-sm-and-down"></div>
            <v-toolbar-items class="hidden-sm-and-down">
                <v-btn flat color="success" style="font-size:16px;">JAP</v-btn>
            </v-toolbar-items>
            <div style="border-left: 3px solid #008080; height : 30px;" class="hidden-sm-and-down"></div>
            <v-toolbar-items class="hidden-sm-and-down">
                <v-btn flat color="success" style="font-size:16px;">ENG</v-btn>
            </v-toolbar-items>

                <div></div>
                <v-btn @click="redirectStdJoinUs()" color="success">JOIN US</v-btn>
                <div style="width : 15px" class="hidden-sm-and-down"></div>
        </v-toolbar>

        <v-content>
            <v-container fluid>


                <v-layout row justify-center>
                    <v-flex xs12>
                        <v-parallax src="/images/Index/backgroundPattern.png" style="margin-bottom : -275px;">
                        </v-parallax>
                    </v-flex>
                </v-layout>

                <v-layout row justify-center style="margin-bottom : -500px">
                    <!-- 로그인 카드 -->
                    <v-flex xs10>
                        <v-container fluid grid-list-xl>
                            <v-layout row wrap justify-center>
                                <v-flex xs6 sm6 md3 lg3>
                                    <v-card hover flat @click="redirectLogin('student')">
                                        <v-card-media src="/images/Index/student2.png" height="300px">
                                        </v-card-media>
                                        <v-card-actions>
                                            student
                                        </v-card-actions>
                                    </v-card>
                                </v-flex>

                                <v-flex xs6 sm6 md3 lg3>
                                    <v-card hover flat @click="redirectLogin('professor')">
                                        <v-card-media src="/images/Index/prof.png" height="300px">
                                        </v-card-media>
                                        <v-card-actions>
                                            professor
                                        </v-card-actions>
                                    </v-card>
                                </v-flex>

                                <v-flex xs6 sm6 md3 lg3>
                                    <v-card hover flat @click="redirectLogin('agent')">
                                        <v-card-media src="/images/Index/agent2.png" height="300px">
                                        </v-card-media>
                                        <v-card-actions>
                                            agent
                                        </v-card-actions>
                                    </v-card>
                                </v-flex>

                                <v-flex xs6 sm6 md3 lg3>
                                    <v-card hover flat @click="redirectLogin('company')">
                                        <v-card-media src="/images/Index/company2.png" height="300px">
                                        </v-card-media>
                                        <v-card-actions>
                                            company
                                        </v-card-actions>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

        <v-footer height="320px" style="background : linear-gradient(to right, rgb(35, 206, 156)  , rgb(17,80,206) 90% );">
        </v-footer>
    </v-app>

    <!-- <v-container fluid>
        <v-layout row justify-space-between>
            <v-flex xs12>
                <v-container fluid grid-list-xl>
                    <v-layout row>
                        <v-flex xs12 sm3 md3 lg3>
                            <img :src="logo" width="130px" height="auto">                
                        </v-flex>
                        <v-flex xs6 sm3 md3 lg3>
                            <v-container fluid>
                                <v-layout row align-center>
                                    <v-flex xs4  v-for="(value , key) in languagePacks" :key="key">
                                        {{key}}
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-btn @click="redirectStdJoinUs()" color="primary">JOIN US</v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
    
        </v-layout>

        <v-layout row  class="contents">
            asdf
        </v-layout>
        <v-layout row  class="footer"></v-layout>
    </v-container> -->
</template>

<script>
    /**
     * TODO: 
     *      - [x] 회원가입 페이지 redirect
     *      - [o] 다국어 적용 
     */
    export default{
        data(){
            return{
                /**
                 * @desc message & logo url
                 */
                welcome : "希望が生まれるシカケをつくる",
                logo : "/images/Index/logo_teal.png",

                /**
                 * @desc multi language pakcs
                 */
                languagePacks : {
                    KOR : "kr",
                    JAP : "ja"
                },

                /**
                 * @desc login classfication card 
                 */
                classify : {
                    student   : "/images/Index/std.png"   ,
                    company   : "/images/Index/com.png"   ,
                    professor : "/images/Index/prof.png"  ,
                    agent     : "/images/Index/agent.png" ,
                },
            }
        },

        methods : {
            redirectLogin ( selectedUserClassify ) {
                alert('asdfasdf');
                let payload = { classification : selectedUserClassify};

                this.$store.dispatch("setCurrentUserClassification", payload);
                this.$router.push("/login");
            },

            changeLanguagePack ( selectedLang ){
                this.$i18n.locale = selectedLang;
            },

            redirectStdJoinUs (){
                this.$router.push("/stdRegist");
            },                    
        },
        /**
         * @desc init vuex state
         */
        created () {
            this.$store.dispatch("initStates");
        }
    }
</script>
<style>
.mainSubTitle:last-of-type {
  width:0px;
  animation: reveal 10s infinite;
}

.mainSubTitle:last-of-type span {
  margin-left:-355px;
  animation: slidein 10s infinite;
}

@keyframes showup {
    0% {opacity:0;}
    20% {opacity:1;}
    80% {opacity:1;}
    100% {opacity:0;}
}

@keyframes slidein {
    0% { margin-left:-1000px; }
    20% { margin-left:-1000px; }
    35% { margin-left:0px; }
    100% { margin-left:0px; }
}

@keyframes reveal {
    0% {opacity:0;width:0px;}
    20% {opacity:1;width:0px;}
    30% {width:355px;}
    80% {opacity:1;}
    100% {opacity:0;width:355px;}
}
</style>
