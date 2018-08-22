<template >
    <v-app >
        <v-container fluid style="background-image:url('/images/Index/mainColorBar.png'); background-size: 100% auto; background-repeat:repeat-y">
        <v-toolbar color="white" flat fixed height="80px">
            <v-toolbar-title>
                <img :src="logo" width="140px" height="auto">                
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down">
                <v-btn flat small color="success" style="font-size:16px;"><b>KOR</b></v-btn>
                <div style="border-left: 2px solid #008080; margin:30px 1px 20px 1px"></div>
                <v-btn flat small color="success" style="font-size:16px;"><b>JAP</b></v-btn>
                <div style="border-left: 2px solid #008080; margin:30px 1px 20px 1px"></div>
                <v-btn flat small color="success" style="font-size:16px;"><b>ENG</b></v-btn>
                <div style="margin:30px 0px 20px 1px"></div>
            </v-toolbar-items>
                <div></div>
                <v-btn @click="redirectStdJoinUs()" color="success" style="margin-top:15px">JOIN US</v-btn>
                <div style="width : 15px" class="hidden-sm-and-down"></div>
        </v-toolbar>
        <v-layout row wrap height="100%">
            
            <v-flex xs12 class="topimage">
                
                <div class="hidden-xs-only titleText">
                    <div class="flipInX animated" id="animationSandbox" style="display: block;"><b class="site__title mega ">
                        希望が生まれるシカケをつくる</b>
                    </div>
                    <div style="font-size:20px;">ログイン<br>
                    <marquee height="35" direction="up" scrolldelay="600" behavior="alternate">
                        <img src="images/Index/login_arrow.png" style="display:block; margin-left:auto; margin-right: auto;">
                    </marquee>
                    </div>
                </div>
                <v-layout justify-center >
                    <!-- 로그인 카드 -->
                    <v-flex xs10 style="">
                        <v-container fluid grid-list-xl >
                            <v-layout row wrap justify-center>
                                <v-flex xs12 sm6 md5 lg3>
                                    <div style="margin:200px 0 0 0" class="hidden-xs-only"></div>
                                    <div style="margin:20px 0 0 0"></div>
                                    <v-card flat class="mainCardTextHover">
                                        <button type="button" @click="redirectLogin('student')" >
                                            <v-card-media src="/images/Index/student2.png" height="300px" class="hidden-xs-only" style="margin-bottom: -40px;">
                                            </v-card-media>
                                            <v-card-actions style="text-align: center;">
                                                <v-flex style="text-align: center; color:rgb(0,112, 240); font-size: 35px">
                                                    student
                                                </v-flex>
                                            </v-card-actions>
                                        </button>
                                    </v-card>
                                </v-flex>

                                <v-flex xs12 sm6 md5 lg3>
                                    <div style="margin:200px 0 0 0" class="hidden-xs-only"></div>
                                    <v-card flat class="mainCardTextHover">
                                        <button type="button" @click="redirectLogin('professor')" >
                                            <v-card-media src="/images/Index/prof.png" height="300px" class="hidden-xs-only" style="margin-bottom: -40px;">
                                            </v-card-media>
                                            <v-card-actions style="text-align: center;">
                                                <v-flex style="text-align: center; color:rgb(0,112, 240); font-size: 35px">
                                                    professor
                                                </v-flex>
                                            </v-card-actions>
                                        </button>
                                    </v-card>
                                </v-flex>
                            
                                <v-flex xs12 sm6 md5 lg3>
                                    <div style="margin:200px 0 0 0" class="hidden-xs-only hidden-sm-and-down hidden-md-and-down"></div>
                                    <v-card flat class="mainCardTextHover">
                                        <button type="button" @click="redirectLogin('company')" >
                                            <v-card-media src="/images/Index/company2.png" height="300px" class="hidden-xs-only" style="margin-bottom: -40px;">
                                            </v-card-media>
                                            <v-card-actions style="text-align: center;">
                                                <v-flex style="text-align: center; color:rgb(0,112, 240); font-size: 35px">
                                                    company
                                                </v-flex>
                                            </v-card-actions>
                                        </button>   
                                    </v-card>
                                </v-flex>

                                <v-flex xs12 sm6 md5 lg3 >
                                    <div style="margin:200px 0 0 0" class="hidden-xs-only hidden-sm-and-down hidden-md-and-down"></div>
                                    <v-card class="mainCardTextHover" flat>
                                        <button type="button" @click="redirectLogin('agent')" >
                                            <v-card-media src="/images/Index/agent2.png" height="300px" class="hidden-xs-only" style="margin-bottom: -40px;">
                                            </v-card-media>
                                            <v-card-actions>
                                                <v-flex style="text-align: center; color:rgb(0,112, 240); font-size: 35px">
                                                    agent
                                                </v-flex>
                                            </v-card-actions>
                                        </button>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-flex>
                </v-layout>
                <!-- <p id="contribute"> YJC College  - Halo - Global Touch </p> -->
            </v-flex>
            <v-flex xs12 style="margin-top:-122px; background: linear-gradient(to right, rgb(35, 206, 156)  , rgb(17,80,206) 90% );" class="hidden-md-and-down">
                
            </v-flex>
        </v-layout>
        </v-container>

    </v-app>

</template>

<script>
    /**
     * TODO: 
     *      - [x] 회원가입 페이지 redirect
     *      - [o] 다국어 적용 
     *      - [x] 토큰체크
     *      - [x] 브라우저 체킹
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
                alert(selectedUserClassify);
                let payload = { classification : selectedUserClassify};

                this.$store.dispatch("setCurrentUserClassification", payload);
                this.$router.push("/login");
            },

            changeLanguagePack ( selectedLang ) {
                
                if( selectedLang == "en") {
                    return this.$swal({
                        type: "warning",
                        title: "失敗",
                        text: "すみません、アップデート予定です。"
                    });
                }
                
                this.$i18n.locale = selectedLang;
            },

            redirectStdJoinUs () {
                this.$router.push("/stdRegist");
            },       
            
            checkBroswerHtml () {

    if (loginState) {
      this.$router.push("/" + this.$store.getters.classification);
    } else {
      this.$store.dispatch("initStates");
    }

    // return new Promise( resolve,reject => {

    // });
            }
  },
  created() {
    let loginState = true;

    for (let key in this.$store.getters.accessData) {
      if (!this.$store.getters.accessData[key]) {
        loginState = false;
      }
    }

    if (loginState) {
      this.$router.push("/" + this.$store.getters.classification);
    } else {
      this.$store.dispatch("initStates");
    }

    // return new Promise( resolve,reject => {

    // });
  }
};
</script>

<style lang="css" scoped src="../static/css/common/Index2.css"></style>
