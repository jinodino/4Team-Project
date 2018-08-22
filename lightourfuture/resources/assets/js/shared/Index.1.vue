<template>
    <v-app style="background-color:white;">
<!-- 효진 -->
        <v-toolbar color="transparent" flat style="margin-top:20px">
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
            <div style="border-left: 2px solid #008080; height : 30px;" class="hidden-sm-and-down"></div>
            <v-toolbar-items class="hidden-sm-and-down">
                <v-btn flat color="success" style="font-size:16px;">ENG</v-btn>
            </v-toolbar-items>

                <div></div>
                <v-btn @click="redirectStdJoinUs()" color="success">JOIN US</v-btn>
                <div style="width : 15px" class="hidden-sm-and-down"></div>
        </v-toolbar>

        <v-content>
            <v-container fluid>
                <v-layout row>
                    <v-flex xs12>
                        <v-card flat color="transparent">
                            <v-card-media src="/images/Index/backgroundPattern.png" height="500px">
                            </v-card-media>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

        <v-footer height="200px">

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