<template>
<!-- 기존 -->
    <div class="wrap-cont">

        <!-- header -->
        <header class="header">
            <div>
                <img :src="logo">
            </div>
            <!-- language Pakcs & JOIN US Button -->
            <div class="function-box">
                <span class="language-pack" v-for="(value , key) in languagePacks" :key="key"
                        @click="changeLanguagePack(value)">
                    {{key}}
                </span>
                
                <button @click="redirectStdJoinUs()"> <span>JOIN US</span> </button>
            </div>
        </header>
        
        <!-- body -->
        <div class="contents">
            <p id="welcome-message"> {{welcome}} </p>

            <div class="login-guide">
                <p> {{$t("Index.login")}} </p>
                <img src="/images/Index/login_arrow.png">
            </div>
            
            <div class="contents-layout">
                <!-- login box -->
                <div class="classify-card" v-for="(imgSrc , classify) in classify" :key="classify"
                        @click="redirectLogin(classify)">
                    <img :src="imgSrc">
                    <span>{{classify}}</span>
                </div>              
            </div>
        </div>

        <footer class="footer">
            <p class="contribute"> YJC College  - Halo - Global Touch </p>
        </footer>
    </div>

    <!-- <v-container fluid>
        <v-layout row justify-space-between>
            <v-flex xs3>
                <img :src="logo" width="150px" height="auto" style="margin-left: 30px;">                
            </v-flex>
            <v-flex xs3>
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
        <v-layout row  class="contents">
            asdf
        </v-layout>
        <v-layout row  class="footer"></v-layout>
    </v-container> -->
</template>

<style scoped lang="css" src="../static/css/common/Index.css"></style>
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
                logo : "/images/Index/logo_main.png",

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