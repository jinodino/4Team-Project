<template>
<v-app>
    <v-container fluid grid-list-xs style="">
        <v-toolbar color="transparent" flat fixed height="80px">
            <v-toolbar-title>
                <img :src="logo" width="140px" height="auto" @click="redirect('main')" class="hidden-xs-only">      
                <img :src="logo2" width="140px" height="auto" @click="redirect('main')" class="hidden-sm-and-up">                
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down">
                <v-btn flat small color="success" style="font-size:16px;"><b>KOR</b></v-btn>
                <div class="bar"></div>
                <v-btn flat small color="success" style="font-size:16px;"><b>JAP</b></v-btn>
                <div class="bar"></div>
                <v-btn flat small color="success" style="font-size:16px;"><b>ENG</b></v-btn>
                <div style="margin:30px 0px 20px 1px"></div>
            </v-toolbar-items>
                <div></div>
                <v-btn @click="redirectStdJoinUs()" color="success" style="margin-top:15px">JOIN US</v-btn>
                <div style="width : 15px" class="hidden-sm-and-down"></div>
        </v-toolbar>
        <v-layout row wrap style="height:100%; background:#fff">
            <!-- PC -->
            <v-flex class="backImgManualBox hidden-xs-only">
                <div class="middle-sort">
                    <div style="" class="classify-welcome">
                        <p> 階層別メッセージ </p>
                    </div>
                </div>
            </v-flex>
            <v-flex border class="backImgLoginBox hidden-xs-only">
                <div class="middle-sort">
                    <div style="">
                        <v-card-text>
                            <input type="text" class="textfield" v-model="loginInfo.id">
                            <img style="margin:-50px 0 0 15px" src="/images/Index/loginid.png">
                            <input type="password" class="textfield" v-model="loginInfo.pwd">
                            <img style="margin:-50px 0 0 14px" width="32px" src="/images/Index/loginpassword.png">
                            <button @click="login()" class="loginfield" style="margin-top:10px"> Log In </button>
                            <p @click="redirect('findAccount')" style="color:#008080; font-size:20px; text-align:right"> Forget ID & Password? </p>
                        </v-card-text>
                    </div>
                </div>
            </v-flex>
            <!-- 모바일 -->
            <v-flex xs12 sm6 style="height:50%; background-image : url('/images/Index/loginBackground.jpg'); background-size: 100% 100%;" class="hidden-sm-and-up backImgLoginBox">
                <div class="middle-sort">
                    <div style="">
                        <v-card-text style="margin-top:70px;">
                            <input type="text"     class="textfield" v-model="loginInfo.id">
                            <img style="margin:-50px 0 0 15px" src="/images/Index/loginid.png">
                            <input type="password" class="textfield" v-model="loginInfo.pwd">
                            <img style="margin:-50px 0 0 14px" width="32px" src="/images/Index/loginpassword.png">
                            <button @click="login()" class="loginfield"> Log In </button>
                            <p @click="redirect('findAccount')" class="forget"> Forget ID & Password? </p>
                        </v-card-text>
                    </div>
                </div>
            </v-flex>
            <v-flex xs12 sm6 class="hidden-sm-and-up mobile-manual">
                <div class="middle-sort">
                    <div style=" color:#fff">
                        <v-card-text>
                            <h2> 階層別メッセージ </h2>
                        </v-card-text>
                    </div> 
                </div>
            </v-flex>
        </v-layout>
    </v-container>
</v-app>
    <!-- <div class="wrap">
        <div class="tutorial">
              <img :src="logo" @click="redirect('main')">

            <div class="classify-welcome">
                <p> 階層別メッセージ </p>
            </div>
        </div>

        <div class="login-box">
            <div class="mask">
                <div class="lang-box">
                    <languagePacks/>
                </div>

                <div class="login-form">
                    <input type="text"     class="field" v-model="loginInfo.id">
                    <input type="password" class="field" v-model="loginInfo.pwd">
                    <button @click="login()"> Log In </button>
                    <p @click="redirect('findAccount')"> Forget ID & Password? </p>
                </div>
            </div>
        </div>
    </div> -->
</template>

<style lang="css" scoped src="../static/css/common/Login2.css"></style>
<style>
</style>

<script>
import languagePacks from "./languagePacks.vue";
export default {
  components: {
    languagePacks
  },

  data() {
    return {
      logo: "/images/Index/logo_white.png",
      logo2: "/images/Index/logo_teal.png",
      loginInfo: {
        id: null,
        pwd: null
      },
      browserSize: {}
    };
  },

  created() {},

  methods: {
    test() {
      this.$router.push("/");
    },

    redirect(targetPage) {
      if (targetPage == "main") {
        this.$router.push("/");
      } else if (targetPage == "findAccount") {
        this.$router.push("/findAccount");
      }
    },

    login() {
      if (!this.loginInfo.id || !this.loginInfo.pwd) {
        //FIXME:
        alert("필드 값 부족 디자인해야함");
        return false;
      }

      let reqHttpAddr = "/api/auth/login";
      let sendData = {
        login_id: this.loginInfo.id,
        password: this.loginInfo.pwd,
        classification: this.$store.getters.classification
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          if (res.data[0] == "error") {
            this.alerting("error", "失敗", "アカウントを確認してください");
            return;
          }

          this.alerting("success", "成功", "こんにちは！");

          // //FIXME: 토큰설정
          let payload = {
            id: this.loginInfo.id,
            classify: this.$store.getters.classification,
            accessToken: res.data
          };

          this.$store.dispatch("login", payload);
          this.$router.push("/" + this.$store.getters.classification);
        })
        .catch(err => {
          this.alerting("error", "失敗", "ユーザーのIDとPWを確認して下さい");
        });
    },

    alerting(type, title, message) {
      this.$swal({
        type: type,
        title: title,
        text: message
      });
    }
  }
};
</script>
