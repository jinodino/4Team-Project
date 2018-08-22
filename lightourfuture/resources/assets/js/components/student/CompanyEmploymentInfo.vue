<template>
  <v-container fluid>

    <company-info :orgCompanyId="org_company_id"></company-info>
    <employment-info style="margin-top : -40px" v-if="employment_id != null" :employmentId="employment_id"></employment-info>
    <v-layout row v-if="employment_id != null">
     
     <v-flex xs12>
      <v-container fluid>
        <v-btn large fixed bottom right color="primary" @click="createApplyCheck" v-if="user.classification == 'student'">
          志願しに GO!
        </v-btn>
      </v-container>
     </v-flex>
    </v-layout>



    <v-dialog v-model="miriDialog" scrollable width="1300px">
      <v-card>
        <v-card-title  class="grey lighten-4 py-3 title">
          提出書類確認
          <v-spacer></v-spacer>
          <v-btn color="error" @click="miriDialog = false">X</v-btn>
        </v-card-title>
        <v-card-text>
          <v-container fluid v-if="entrySheet != null">
            <v-layout border row align-center wrap justify-space-between file-hover color="success">
              <v-flex xs12 md2 lg2>
                <v-chip label color="orange" outline disabled>履歴書</v-chip>
              </v-flex>
              <v-flex xs12 md6 lg6>
                {{entrySheet.name == null?  'NO FILE' : entrySheet.name}}
              </v-flex> 
              <v-flex xs12 md4 lg4>
                <v-btn color="success" @click="curr_file_url = entrySheet.path">先に見る</v-btn>
                <v-btn color="warning" @click="go2Repository">修正</v-btn>
              </v-flex>
            </v-layout>
            <v-layout border row align-center wrap justify-space-between file-hover color="success">
              <v-flex xs12 md2 lg2>
                <v-chip label color="orange" outline disabled>ポートフォリオ</v-chip>
              </v-flex>
              <v-flex xs12 md6 lg6>
                {{portfolio == null?  'NO FILE' : portfolio.name}}
              </v-flex>
              <v-flex xs12 md4 lg4>
                <v-btn color="success" :disabled="!portfolio" @click="portfolio != null ? curr_file_url = portfolio.path : null">先に見る</v-btn>
                <v-btn color="warning" @click="go2Repository">修正</v-btn>
              </v-flex>
            </v-layout>
            <v-layout row>
              <v-flex xs12>
                <embed :src="curr_file_url" type="application/pdf" width="100%" height="600px">
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
  
        <v-card-actions>
          <v-btn color="primary" block large @click="createApply">志願する</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import CompanyInfo from "../../shared/CompanyInfo";
import EmploymentInfo from "../../shared/Recruit";
export default {
  components: {
    "company-info": CompanyInfo,
    "employment-info": EmploymentInfo
  },

  created: function() {
    this.user.login_id = this.$store.getters.identification;
    this.user.classification = this.$store.getters.classification;

    this.org_matchings_id = this.$route.query.matching_id;
    this.org_company_id = this.$route.query.company_id;
    this.employment_id = this.$route.query.employment_id;
  },

  data: () => ({
    user: {
      login_id: null,
      classification: null
    },

    employment_id: null,
    org_company_id: null,
    org_matchings_id: null,

    miriDialog: false,
    entrySheet: null,
    portfolio: null,
    curr_file_url: null
  }),

  methods: {
    go2Repository() {
      //학생 레포지토리로 이동
      this.$router.push({
        path: "/student/repository"
      });
    },

    createApplyCheck() {
      let login_id = this.user.login_id;
      let employment_id = this.employment_id;
      let org_matchings_id = this.org_matchings_id;

      this.axios
        .post("/student/createApplyCheck", {
          login_id,
          employment_id,
          org_matchings_id
        })
        .then(rep => {
          let returnCode = rep.data.returnCode;
          let returnBool = rep.data.returnBool;

          if (returnBool) {
            //이력서, 포트폴리오 확인 모달 띄우기
            this.miriDialog = true;
            //this.entrySheet = null;
            this.entrySheet = rep.data.entrySheet;
            this.curr_file_url = this.entrySheet.path;
            //this.portfolio = null;
            this.portfolio = rep.data.portfolio;
          } else {
            if (returnCode == 0) {
              let employ_year = rep.data.employ_year;
              alert(
                "学生の就活年とは" +
                  employ_year +
                  "年で志願資格がありません。"
              );
            }
            if (returnCode == 1) {
              alert("もう志願した採用件です。");
            } else if (returnCode == 2) {
              alert("応募期限じゃありません");
            } else if (returnCode == 3) {
              alert("採用件に対する履歴書を登録してください。");
              //학생 레포지토리 페이지로 이동
              this.go2Repository();
            } else if (returnCode == 4) {
              alert("採用が決定されていません。");
            }
          }

          //   else if(){

          //   }
          //   else if(returnCode == 3)
          // }
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //*** 채용건에 학생이 지원 한다. --> 교수, 에이전트에게 알리기
    createApply() {
      let login_id = this.user.login_id;
      let employment_id = this.employment_id;
      let apiKey = this.$store.getters.push_config.apiKey;

      this.axios
        .post("/student/createApply", { login_id, employment_id, apiKey })
        .then(rep => {
          if (rep.data.returnBool) {
            alert("志願されました。");

          } else {
            alert("すみません。もう一度試してください。");
          }
          this.miriDialog = false;
        })
        .catch(ex => {
          console.log(ex);
        });
    }
  }
};
</script>

<style>
.file-hover:hover {
  background-color: #eeeeee;
}
</style>
