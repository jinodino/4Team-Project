<template>
  <v-app>
    <!-- 상단바 -->
    <v-toolbar app color="primary" dark :clipped-left="$vuetify.breakpoint.mdAndUp" flat fixed>
        <v-toolbar-side-icon @click.stop="drawer = !drawer"/>
        <!-- Title -->
        <v-toolbar-title>
            Light Our Future
        </v-toolbar-title>        
        <v-spacer></v-spacer>

        <v-toolbar-items>
          <!-- <v-menu offset-y>

            <v-btn flat slot="activator">
              <v-flex class="container">
                <v-flex class="notification">
                </v-flex>
              </v-flex>  
            </v-btn>
              
            <v-list>
              <v-list-tile v-for="(notifyBody,key) in notifyList" :key="key">
                <v-list-tile-avatar>
                  {{notifyBody}}
                  <img :src="src">
                </v-list-tile-avatar>
              </v-list-tile>
            </v-list>

          </v-menu> -->
          
          <!-- 언어 선택 -->
          <v-menu offset-y>
              <v-btn flat slot="activator"> <img :src="currentLanguageImgSrc"></v-btn>
              <v-list>
                  <v-list-tile v-for="(src,key) in countryImgSrc" :key="key">
                      <v-list-tile-avatar>
                          {{src}}
                          <!-- <img :src="src"> -->
                      </v-list-tile-avatar>
                  </v-list-tile>
              </v-list>
          </v-menu>

            <!-- <v-btn color="red">
              {{a}}
            </v-btn> -->

          <!-- 알림 -->
          <v-menu offset-y bottom v-show="notifyList.length != 0" :close-on-content-click="false">    

            <v-btn flat slot="activator" class="hidden-xs-only">
              <v-badge left color="error"> 
                <span slot="badge">{{notifyList.length}}</span>
                <v-icon left large>notifications_active</v-icon>
              </v-badge>
             
              {{this.$store.getters.identification}}
            </v-btn> 
            <!-- <v-btn icon flat slot="activator" class="hidden-sm-and-up">
              <v-badge left color="error"> 
                <span slot="badge">{{notifyList.length}}</span>
                <v-icon left large>notifications_active</v-icon>
              </v-badge>
             
              
            </v-btn>  -->

            <v-list two-line class="notify-height">
              <v-list-tile
                v-for="(item, key, index) in notifyList"
                :key="index" class="background_color_white" style="height:120px">
                
                  <v-list>
                      <span style="width:250px; float:left">
                        {{item.notification}}
                      </span>
                      <span style="float:right">
                        <v-btn icon ripple @click="deleteNotification(item.no)">
                          <v-icon color="grey lighten-1">clear</v-icon>
                        </v-btn>
                        </span>
                      <v-list-tile-sub-title v-html="item.date"></v-list-tile-sub-title>
                  </v-list>
                  
                
              </v-list-tile>
            </v-list>

          </v-menu>

            <v-btn flat slot="activator" class="hidden-xs-only" v-show="notifyList.length == 0">
              <v-icon left >notifications</v-icon>
              {{this.$store.getters.identification}}
            </v-btn> 
            <v-btn icon flat slot="activator" class="hidden-sm-and-up" v-show="notifyList.length == 0">
              <v-icon left >notifications</v-icon>
              <!-- {{this.$store.getters.identification}} -->
            </v-btn> 
            

            <v-btn flat @click="logout()" class="hidden-xs-only">
              <v-icon left>exit_to_app</v-icon>
              Logout
            </v-btn>
            <v-btn icon flat @click="logout()" class="hidden-sm-and-up">
              <v-icon left>exit_to_app</v-icon>
              <!-- Logout -->
            </v-btn>
        </v-toolbar-items>
    </v-toolbar>

    <!-- 사이드바 -->
    <v-navigation-drawer  app back :clipped="$vuetify.breakpoint.mdAndUp" v-model="drawer" width="210">
        <!-- <v-card>
            <v-layout>
                <v-flex>
                <v-avatar>
                    <img src="/images/youngjin_logo.jpg" alt="">
                </v-avatar>
                </v-flex>
            </v-layout>
        </v-card> -->

        <v-list>
            <v-list-tile
            v-for="item in navItems"
            :key = "item.title"
            id = "list-item"
            router
            :to = "item.link"
            >
                <v-list-tile-action>
                    <v-icon>{{item.icon}}</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                  <p v-if="user.classification == 'professor'">{{$t("Professor_menu." + item.title)}}</p>
                  <p v-else>{{item.title}}</p>

                </v-list-tile-content>
            </v-list-tile>
        </v-list>
    </v-navigation-drawer>

    <!-- 내용 -->
    <v-content>
        <transition name="slide-fade">
            <!-- 에이전트 -->
            <router-view name="AgentHome"></router-view>
            <router-view name="AgentProfile"></router-view>
            <router-view name="AgentOrgProfile"></router-view>
            <router-view name="UserInvite"></router-view>
            <router-view name="EmailRegist"></router-view>
            <!-- <router-view name="SchoolRegist"></router-view> -->
            <router-view name="SchoolList"></router-view>
            <!-- <router-view name="CompanyRegist"></router-view> -->
            <router-view name="CompanyList"></router-view>
            <!-- <router-view name="RecruitRegist"></router-view> -->
            <router-view></router-view>
            <router-view name="AgentStdManage"></router-view>
            <router-view name="AgentItvManage"></router-view>
            
            <router-view name="AgentCalendar"></router-view>
            <router-view name="ListManagement"></router-view>
            <router-view name="AgentRegist"> </router-view>
            <router-view name="AgentRelations"> </router-view>

            <!-- Company -->
            <router-view name="CompanyHome"/>
            <router-view name="NominateManagement"/>
            <router-view name="CompanyOrgProfile"/>
            <router-view name="RecruitStatus"/>
            <router-view name="CompanySchoolList"/>
            <router-view name="CompanyCalendar"/>

            <!-- Professor -->
            <router-view name="ProfessorHome"></router-view>
            <router-view name="ProfessorProfile"></router-view>
            <router-view name="StdManagement"></router-view>
            <router-view name="CompanyInfo"></router-view>
            <router-view name="ProfessorCalendar"></router-view>
            <router-view name="ApplyManagement"></router-view>
            <router-view name="StatusManagement"></router-view>

            <!-- student -->
            <router-view name="StudentPR"></router-view>
            <router-view name="StudentHome"></router-view>
            <router-view name="StudentProfile"></router-view>
            <router-view name="StudentRepository"></router-view>
            <router-view name="StudentCompanyList"></router-view>
            <router-view name="StudentStatus"></router-view>
            <router-view name="StudentCalendar"></router-view>
        </transition>                
    </v-content>
    <!-- 푸터 -->
    <!-- <v-footer app fixed left>
        <v-spacer></v-spacer>
        &copy; 2018 Developed by. 장준수 이효진 장다연 손진호 조수진 류호형
    </v-footer> -->
  </v-app>
</template>
<style>
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css";
</style>
     
<script>
import SidebarList from "../static/dataProvide/SidebarList.js";
export default {
  created: function() {
    this.user.login_id = this.$store.getters.identification;
    //user의 id가 없다면 메인페이지로 돌아간다.
    if (this.user.login_id == null) this.go2Main();
    this.user.classification = this.$store.getters.classification;
    this.changeMenu(this.user.classification);

    if (this.$i18n.locale == "ja") {
      this.currentLanguageImgSrc = "/images/Index/japan.png";
    } else {
      this.currentLanguageImgSrc = "/images/Index/korea.png";
    }
  },

  data: () => ({
    user: {
      login_id: null,
      classification: null
    },
    drawer: null,
    navItems: [],
    token: "",
    logo: "/images/Index/logo_white.png",
    countryImgSrc: {
      ja: "japaness", //"/images/Index/japan.png",
      kr: "korean" //"/images/Index/korea.png"
    },
    currentLanguageImgSrc: null,
    a: null,
    notifyList: []
  }),

  mounted: function() {
    this.requestPermission();
    this.notify();
  },
  methods: {
    notify: function() {
      let reqHttpAddr = "/notify_list";
      let sendData = {
        id: this.$store.getters.identification
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.notifyList = res.data;
        })
        .catch(err => {
          console.log(err.message);
        });
    },
    //------------------푸시알림 관련-----------------------------------

    requestPermission: function() {
      var firebase = require("firebase");
      //console.log(this.$store.state.push_config);
      try {
        firebase.initializeApp(this.$store.getters.push_config);
      } catch (err) {}
      var messaging = firebase.messaging();

      //인터넷 창이 떠 있을때는 페이지로 직접 메세지 받기
      //실시간 누적 알림
      messaging.onMessage(payload => {
        var getMessage = JSON.parse(payload.notification.title);

        this.notify();
      });

      messaging
        .requestPermission()
        .then(function() {
          console.log("Notification permission granted.");
          //TODO: Retrieve an Instance ID token for use with FCM.
          // ...
          return messaging.getToken();
        })
        .then(getToken => {
          this.token = getToken;

          let reqHttpAddr = "/push_token_save";
          let sendData = {
            id: this.$store.getters.identification,
            token: this.token
          };

          this.axios
            .post(reqHttpAddr, sendData)
            .then(res => {
              //console.log(res.data);
            })
            .catch(err => {
              console.log(err.message);
            });
        })
        .catch(function(err) {
          console.log("Unable to get permission to notify.", err);
        });

      messaging.onTokenRefresh(function() {
        messaging
          .getToken()
          .then(function(refreshedToken) {
            //console.log("Token refreshed.");
            // Indicate that the new Instance ID token has not yet been sent to the
            // app server.
            setTokenSentToServer(false);
            // Send Instance ID token to app server.
            sendTokenToServer(refreshedToken);
            // [START_EXCLUDE]
            // Display new Instance ID token and clear UI of all previous messages.
            resetUI();
          })
          .catch(function(err) {
            console.log("Unable to retrieve refreshed token ", err);
            showToken("Unable to retrieve refreshed token ", err);
          });
      });
    },

    //누적 알림 삭제
    deleteNotification(no) {
      let reqHttpAddr = "/notify_select_delete";
      let sendData = {
        index_no: no
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          this.notify();
        })
        .catch(err => {
          console.log(err.message);
        });
    },

    //------------------푸시알림 관련-----------------------------------
    changeMenu: function(user) {
      switch (user) {
        case "student":
          this.navItems = SidebarList.student;
          break;
        case "professor":
          this.navItems = SidebarList.professor;
          break;
        case "agent":
          this.navItems = SidebarList.agent;
          break;
        case "company":
          this.navItems = SidebarList.company;
          break;
      }
    },
    go2Main() {
      this.$router.push({
        path: "/"
      });
    },
    logout() {
      let reqHttpAddr = "/api/auth/logout";
      let data = this.$store.getters.accessData;
      let sendData = { data };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          if (!res.data) return false;
          this.$store.dispatch("logout");
          this.$router.push("/");

          //location.href = "http://127.0.0.1:8000/";
          location.href = "https://lightourfuture.world/#/";
        })
        .catch(err => {
          console.log(err.message);
        });
    }
  }

  /*
    watch : {
        classification(classification){
            this.changeMenu(classification)
        }
    },
*/
};
</script>

<style scope>
@font-face {
  font-family: "NotoSans";
  src: url("../static/font/NotoSansCJKjp-DemiLight.otf");
}

#list-item:hover {
  background-color: gray;
}

.slide-fade-enter-active {
  transition: all 0.8s ease;
}
.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 1, 1.8, 3);
}
.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateX(30vw);
  opacity: 0;
}
.scroll-area {
  position: sticky;
  margin: 0px;
  width: 350px;
}
.notify-height {
  height: 300px;
}
.background_color_white {
  background-color: #ffffff;
}
</style>