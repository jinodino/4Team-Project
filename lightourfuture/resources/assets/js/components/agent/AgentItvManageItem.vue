<template>
  <v-container fluid grid-list-xs>
    <v-layout row>
      <v-flex xs12>
        <v-card flat style="margin-top: -2px">
          <v-card-title>
            <v-layout row wrap justify-center>
              <v-flex xs8 md4 lg4>
                <v-select
                label="学校選択"
                :items="schoolList"
                item-text="org_name"
                item-value="org_college_id"
                v-model="org_college_id"
                >
                </v-select>
              </v-flex>
              <v-flex xs4 md3 lg2 v-show="thisYear != null">
                <v-select
                label="採用年度"
                :items="[thisYear]"
                :value="thisYear"
                disabled
                >
                </v-select>
              </v-flex>  
            </v-layout>
   
          </v-card-title>
          <b-table :fields="headers" :items="employmentList" responsive striped @row-clicked="interviewListUp">
            <template slot="ck" slot-scope="data">
              <p id="tdstyle"><v-icon large v-show="data.item.employment_id == employment_id" color="primary">check</v-icon></p>
            </template> 
            <template slot="no" slot-scope="data">
              <p id="tdstyle">{{++data.index}}</p>
            </template>
            <template slot="name" slot-scope="data">
              <p id="tdstyle">{{data.item.org_name}}</p>
            </template> 
            <template slot="employment_name" slot-scope="data">
              <p id="tdstyle">
                <v-chip v-if="data.item.acceptance_fixed_ox == 'o'" outline disabled label color="success">
                  辞退可能
                </v-chip>
                <v-chip v-else-if="data.item.acceptance_fixed_ox == 'x'" outline disabled label color="error">
                  辞退不可能
                </v-chip>
                <v-chip v-else outline disabled label>
                  決めていない
                </v-chip>
                {{data.item.employment_name}}
              </p>
            </template>
            <template slot="apply_deadline_date" slot-scope="data">
              <p id="tdstyle">{{data.item.apply_open_date}} ~ <br>
                  {{data.item.apply_deadline_date}}</p>
            </template> 
            <template slot="whole_interview_count" slot-scope="data">
              <p id="tdstyle">
                <v-chip v-if="data.item.employment_owari_ox == 'o'" outline disabled label color="primary">
                  完了
                </v-chip>
                <v-chip v-else outline disabled label color="success">
                  {{data.item.interview_count}} / {{data.item.whole_interview_count}} 完了
                </v-chip>
              </p>
            </template>
          </b-table>
            <template>
              <v-alert v-if="org_college_id == null" :value="true" color="error" icon="error">
                学校を選択してください
              </v-alert>
              <v-alert v-if="org_college_id != null && !employmentList" :value="true" color="warning" icon="warning">
                採用件がありません。
              </v-alert>
            </template>

          <!-- <v-data-table
            :headers="headers1"
            :items="employmentList"
            hide-actions
          >
            <template slot="items" slot-scope="props">
              <tr @click="employmentInfo = props.item; employment_id=props.item.employment_id">
                <td>
                  <v-icon large v-show="props.item.employment_id == employment_id" color="primary">check</v-icon>
                </td>
                <td>{{++props.index}}</td>
                <td>{{props.item.org_name}}</td>
                <td>
                  <v-chip v-if="props.item.acceptance_fixed_ox == 'o'" outline disabled label color="success">
                    辞退可能
                  </v-chip>
                  <v-chip v-else-if="props.item.acceptance_fixed_ox == 'x'" outline disabled label color="error">
                    辞退不可能
                  </v-chip>
                  <v-chip v-else outline disabled label>
                    決めていまい
                  </v-chip>
                  {{props.item.employment_name}}
                </td>
                <td>
                  {{props.item.apply_open_date}} ~ <br>
                  {{props.item.apply_deadline_date}}
                </td>
                <td>
                  <v-chip v-if="props.item.employment_owari_ox == 'o'" outline disabled label color="primary">
                    完了
                  </v-chip>
                  <v-chip v-else outline disabled label color="success">
                    {{props.item.interview_count}} / {{props.item.whole_interview_count}} 完了
                  </v-chip>
                </td>
              </tr>
            </template>
           <template slot="no-data">
              <v-alert v-if="org_college_id == null" :value="true" color="error" icon="error">
                学校を選択してください
              </v-alert>
              <v-alert v-else :value="true" color="warning" icon="warning">
                採用件がありません
              </v-alert>
            </template>
          </v-data-table> -->
        </v-card>
      </v-flex>
      <v-flex xs12>
        <v-card flat>
          <b-table :fields="interviewHeaders" :items="interviewList" responsive striped  @row-clicked="openSimsaDialog">
            <template slot="no" slot-scope="data">
              <p id="tdstyle">{{data.item.interview_count}}</p>
            </template> 

            <template slot="content" slot-scope="data">
              <p id="tdstyle">
                <v-chip v-if="data.item.content != null" disabled>{{data.item.content}}</v-chip>
                {{data.item.interview_content}}
              </p>
            </template>

            <template slot="interview_place" slot-scope="data">
              <p id="tdstyle">
                {{data.item.interview_place}}
              </p>
            </template>

            <template slot="interview_date" slot-scope="data">
              <p id="tdstyle">{{data.item.interview_date}}<br> 
                    {{data.item.interview_start_time}} ~ {{data.item.interview_end_time}}
              </p>
            </template> 

            <template slot="interview_active" slot-scope="data">
              <p id="tdstyle">
                <v-chip v-if="data.item.interview_active == 'x'" label disabled outline color="primary">
                  {{data.item.interview_count == employmentInfo.whole_interview_count? '内定終わり' : '検討終わり'}}
                </v-chip>
                <v-chip v-else-if="data.item.interview_active == 'o'" label disabled outline color="success">
                  検討中
                </v-chip>
              </p>
            </template> 

            <template slot="student_count" slot-scope="data">
              <p id="tdstyle">{{data.item.student_pass_count}}人 / {{data.item.student_count}}人
              <v-chip v-if="(data.item.student_count - data.item.student_pass_count) > 0" color="red darken-4" outline disabled small>
                {{data.item.feedback_count}}人フィートバック
              </v-chip></p>
            </template>
            
          </b-table>
            <template>
              <v-alert v-if="employment_id == null && org_college_id != null" :value="true" color="warning" icon="warning">
                同録されているインタビュースケジュールがありません。
              </v-alert>
              <v-alert v-else-if="employment_id == null" :value="true" color="error" icon="error">
                採用件を選択してください
              </v-alert>
            </template>

          <!-- <v-data-table
            :headers="interviewHeaders1"
            :items="interviewList"
            hide-actions
           >
            <template slot="items" slot-scope="props">
                <tr @click="openSimsaDialog(props.item)">
                  <td>{{++props.index}}</td>
                  <td>
                    <v-chip v-if="props.item.content != null" disabled>{{props.item.content}}</v-chip>
                    {{props.item.interview_content}}
                  </td>
                  <td>{{props.item.interview_place}}</td>
                  <td>{{props.item.interview_date}}<br> 
                    {{props.item.interview_start_time}} ~ {{props.item.interview_end_time}}
                  </td>
                  <td>
                    <v-chip v-if="props.item.interview_active == 'x'" label disabled outline color="primary">
                      結果発表終わり
                    </v-chip>
                    <v-chip v-else-if="props.item.interview_active == 'o'" label disabled outline color="success">
                      検討中
                    </v-chip>
                  </td>
                  <td>
                    {{props.item.student_pass_count}}人 / {{props.item.student_count}}人
                  
                    <v-chip v-if="(props.item.student_count - props.item.student_pass_count) > 0" color="red darken-4" outline disabled small>
                      {{props.item.feedback_count}}人フィートバック
                    </v-chip>
                  </td>

                </tr>
            </template>
            <template slot="no-data">
              <v-alert v-if="employment_id == null && org_college_id != null" :value="true" color="warning" icon="warning">
                同録されているインタビュースケジュールがありません
              </v-alert>
              <v-alert v-else-if="employment_id == null" :value="true" color="error" icon="error">
                採用件を選択してください
              </v-alert>
            </template>

          </v-data-table> -->
        </v-card>
      </v-flex>
    </v-layout>

    <v-dialog scrollable v-model="simsaDialog">
      <v-card>
        <v-card-title class="Titlefont">
          {{employmentInfo.org_name}} {{employmentInfo.employment_name}} {{interviewInfo.interview_count}}차
          <v-chip disabled>{{interviewInfo.content}}</v-chip>
           <v-spacer></v-spacer>
          <v-btn large color="error" @click="simsaDialog = false">X</v-btn>
        </v-card-title>
        <v-card-text>
          <v-container fluid grid-list-xs>
            <v-layout row>
              <v-alert v-if="studentList.length == 0" :value="true" color="warning">
                評価する学生がいません。
              </v-alert>
            </v-layout>
            <!-- 심사가 끝났을 때 -->
            <v-layout row  v-if="passStudentList.length != 0">
              <v-flex xs12>
                <v-tabs
                  slot="extension"
                  v-model="tab"
                  grow
                  fixed-tabs
                >
                  <v-layout>  
                    <v-flex xs6>
                      <div v-if="tab == '0'" @click="tab = '0'" id="tabon" class="title">合格者リスト</div>
                      <div v-else @click="tab = '0'" id="taboff" >合格者リスト</div>  
                    </v-flex>
                    <v-flex xs6>
                      <div v-if="tab == '1'" @click="tab = '1'" id="tabon" class="title">不合格者リスト</div>
                      <div v-else @click="tab = '1'" id="taboff" >不合格者リスト</div>
                    </v-flex>
                  </v-layout>
                    
                    <!-- <v-tab>
                        <p class="subheading">合格者リスト</p>
                    </v-tab>
                    <v-tab>
                        <p class="subheading">不合格者リスト</p>
                    </v-tab> -->
                </v-tabs> 
                <v-tabs-items v-model="tab">
                    <!-- 합격 학생 -->
                    <v-tab-item>
                      <v-card>
                        <v-card-text>
                          <v-layout row>
                            <v-flex xs12>
                              <v-alert v-if="passStudentList.length == 0" :value="true" color="warning" icon="error">
                                合格した学生がいません。
                              </v-alert>
                            </v-flex>
                            <v-flex xs6 md4 lg3 v-for="(student, key) in passStudentList" :key="key">
                              <v-card>
                                <v-card-media :src="student.profile_photo_url" height="200px"></v-card-media>
                                <v-card-text>
                                  <v-checkbox 
                                    v-if="interviewInfo.interview_active == 'o'"
                                    v-model="checkStudentList" 
                                    :value="student"
                                    :label="student.name + '('+ student.age +')'">
                                  </v-checkbox>
                                  <div v-else>
                                    {{student.name + '('+ student.age +')'}}
                                  </div>
                                  <v-chip large v-if="student.interview_result == 'o' " color="primary" text-color="white" disabled label>
                                  <v-icon left>{{ interviewInfo.interview_count == employmentInfo.whole_interview_count && interviewInfo.interview_active == 'x' ? 'gavel' : 'card_giftcard'}}</v-icon>{{ interviewInfo.interview_count == employmentInfo.whole_interview_count && interviewInfo.interview_active == 'x' ? '内定' : '合格' }}
                                  </v-chip>
                                  <!-- <v-chip large v-else color="error" disabled>
                                    不合格
                                  </v-chip> -->
                                  <br>
                                  <v-chip small disabled label outline color="warning lignten-4">
                                    専攻 {{student.major_grade == null? '-' : student.major_grade}}
                                  </v-chip>
                                  <v-chip small disabled label outline color="pink lignten-4">
                                    会話 {{student.japanese_speaking_level == null || student.japanese_speaking_level == ''? '-' : student.japanese_speaking_level}}
                                  </v-chip>
                                  <v-chip small disabled label outline color="primary">
                                    誠実 {{student.sincerity_grade == null? '-' : student.sincerity_grade}}
                                  </v-chip>
                                  <v-chip small disabled label outline color="success">
                                    人性 {{student.personality_grade == null? '-' : student.personality_grade}}
                                  </v-chip>
                                </v-card-text>
                                <v-card-actions>
                                  <v-spacer></v-spacer>
                                  <v-btn color="success" flat :disabled="student.entrySheet.name == null" @click="miriObj = student.entrySheet; miriDialog = true;">履歴書</v-btn>
                                  <v-btn color="secondary " flat :disabled="student.portfolio.name == null" @click="miriObj = student.portfolio; miriDialog = true;">ポートフォリオ</v-btn>
                                </v-card-actions>
                              </v-card>
                            </v-flex>
                          </v-layout>
                        </v-card-text>
                      </v-card>
                      
                    </v-tab-item>

                    <!-- 불합격 학생 -->
                    <v-tab-item>
                      <v-card>
                        <v-card-text>   
                          <v-container fluid grid-list-sm>
                            <v-layout row v-if="noPassStudentList.length == 0" justify-center>
                              <v-flex xs12 >
                                <v-alert :value="true" color="warning" icon="error">
                                  不合格した学生がいません。
                                </v-alert>
                              </v-flex>
                            </v-layout>
                            <v-layout row v-else justify-center>
                              <v-flex xs1>
                                <v-btn color="error" large  @click="openFeedbackDialog">不合格者フィードバック</v-btn>
                              </v-flex>
                            </v-layout>
                            <v-layout row>
                              <v-flex xs6 md4 lg3 v-for="(student, key) in noPassStudentList" :key="key">
                                <v-card>
                                  <v-card-media :src="student.profile_photo_url" height="200px"></v-card-media>
                                  <v-card-text>
                                    <v-checkbox 
                                      v-if="interviewInfo.interview_active == 'o'"
                                      v-model="checkStudentList" 
                                      :value="student"
                                      :label="student.name + '('+ student.age +')'">
                                    </v-checkbox>  
                                    <div v-else>
                                      {{student.name + '('+ student.age +')'}}
                                    </div>                                
                                    <!-- <v-chip large v-if="student.interview_result == 'o' " color="primary" disabled label outline>
                                      <v-icon left>card_giftcard</v-icon> 合格
                                    </v-chip> -->
                                    <!-- <v-chip large color="error" disabled outline>
                                      不合格
                                    </v-chip> -->
                                    <v-chip large color="pink" disabled text-color="white" v-if="student.interview_feedback == null" >
                                      フィードバック 無
                                    </v-chip>

                                  <v-chip large v-if="student.interview_result == 'x' " color="error" text-color="white" disabled label>
                                    <v-icon left>block</v-icon>不合格
                                  </v-chip>
                                    <br>
                                    <v-chip small disabled label outline color="warning lignten-4">
                                      専攻 {{student.major_grade == null? '-' : student.major_grade}}
                                    </v-chip>
                                    <v-chip small disabled label outline color="pink lignten-4">
                                      会話 {{student.japanese_speaking_level == null || student.japanese_speaking_level == ''? '-' : student.japanese_speaking_level}}
                                    </v-chip>
                                    <v-chip small disabled label outline color="primary">
                                      誠実 {{student.sincerity_grade == null? '-' : student.sincerity_grade}}
                                    </v-chip>
                                    <v-chip small disabled label outline color="success">
                                      人性 {{student.personality_grade == null? '-' : student.personality_grade}}
                                    </v-chip>
                                  </v-card-text>
                                  <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="success" flat :disabled="student.entrySheet.name == null" @click="miriObj = student.entrySheet; miriDialog = true;">履歴書</v-btn>
                                    <v-btn color="secondary" flat :disabled="student.portfolio.name == null" @click="miriObj = student.portfolio; miriDialog = true;">ポートフォリオ</v-btn>
                                  </v-card-actions>
                                </v-card>
                              </v-flex>
                            </v-layout>
                          </v-container>            
                       
                        </v-card-text>
                      </v-card>
                    </v-tab-item>
                </v-tabs-items>
              </v-flex>
            </v-layout>

            <!-- 심사를 해야할 때 -->
            <v-layout row v-else>
              <v-flex xs6 md6 lg3 v-for="(student, key) in studentList" :key="key">
                <v-card> 
                  <v-card-media :src="student.profile_photo_url" height="200px"></v-card-media>
                  <v-card-text>
                    <v-checkbox 
                    v-model="checkStudentList" 
                    :value="student"
                    :label="student.name + '('+ student.age +')'">
                    </v-checkbox>

                    <v-chip small disabled label outline color="warning">
                      専攻 {{student.major_grade == null? '-' : student.major_grade}}
                    </v-chip>
                    <v-chip small disabled label outline color="pink lignten-4">
                      会話 {{student.japanese_speaking_level == null? '-' : student.japanese_speaking_level}}
                    </v-chip>
                    <v-chip small disabled label outline color="primary">
                      誠実 {{student.sincerity_grade == null? '-' : student.sincerity_grade}}
                    </v-chip>
                    <v-chip small disabled label outline color="success">
                      人性 {{student.personality_grade == null? '-' : student.personality_grade}}
                    </v-chip>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" flat :disabled="student.entrySheet.name == null" @click="miriObj = student.entrySheet; miriDialog = true;">이력서</v-btn>
                    <v-btn color="secondary" flat :disabled="student.portfolio.name == null" @click="miriObj = student.portfolio; miriDialog = true;">포트폴리오</v-btn>
                  </v-card-actions>
                </v-card>
              </v-flex>
            </v-layout>
          </v-container>  
        </v-card-text>

        <v-card-actions v-if="interviewInfo.interview_active == 'o'">
          <v-spacer></v-spacer>
          <v-btn large color="secondary" @click="setResult('o')" v-show="tab != '0' || passStudentList.length == 0">合格</v-btn>
          <v-btn large color="error" @click="setResult('x')" v-show="tab== '0' || passStudentList.length == 0">不合格</v-btn>
          <v-btn large color="success" v-if="interviewInfo.interview_count != employmentInfo.whole_interview_count" @click="setPassNextInterview">合格者は次の面接に</v-btn>
          <v-btn large color="success" v-else @click="setAcceptance">合格者に内定出す</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- 이력서 미리보기 다이얼로그 -->
    <v-dialog v-model="miriDialog" v-if="miriObj != null" >
      <v-card>
        <v-card-title>
          {{miriObj.name}}
          <v-spacer></v-spacer>
          <v-btn @click="miriDialog = false" color="error">X</v-btn>
        </v-card-title>
        <v-card-media>
          <embed :src="miriObj.path" type="application/pdf" width="100%" height="600px">
        </v-card-media>
      </v-card>
    </v-dialog>

    <!-- 불합격 피드백 -->
    <v-dialog v-model="feedbackDialog" scrollable width="600px">
      <v-card>
        <v-card-title>
          フィードバック
          <v-spacer></v-spacer>
          <v-btn color="error" @click="feedbackDialog = false">X</v-btn>
        </v-card-title>
        <v-card-text>
          <v-container fluid grid-list-lg text-xs-center>
            <v-layout row align-center v-for="student in editFeedbackList" :key="student.login_id">
              <v-flex xs4 justify-center>
                <v-avatar
                  size="90px"
                >
                  <img :src="student.profile_photo_url" alt="avatar">
                </v-avatar>
                <div>{{student.name + '(' + student.age + ')'}}</div>
              </v-flex>
              <v-flex xs8>
                <v-text-field
                  v-model="student.interview_feedback"
                  textarea 
                >
                </v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
         
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="setFeedbackNoPassStudent" color="primary">確認</v-btn>
          <v-btn color="error" @click="feedbackDialog = false;">取り消す</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
export default {
  props: ["orgAgent", "classification", "skillListInfo"],

  created: function() {
    let year = new Date().getFullYear();
    this.thisYear = year;
    this.getSchoolList(year);
  },

  data: () => ({
    tab: "0",
    thisYear: null,
    org_college_id: null,
    schoolList: [],

    employment_id: null,
    employmentInfo: {},
    employmentList: [],

    interview_id: null,
    interviewInfo: {},
    interviewList: [],

    studentList: [],
    checkStudentList: [],
    passStudentList: [],
    noPassStudentList: [],
    editFeedbackList: [],

    simsaDialog: false,
    miriDialog: false,
    feedbackDialog: false,
    miriObj: null,

    headers1: [
      { text: "", sortable: false },
      { text: "no", sortable: false },
      { text: "企業名" },
      { text: "募集タイトル" },
      { text: "応募期限" },
      { text: "選考進捗率" }
    ],
    interviewHeaders1: [
      { text: "no", sortable: false },
      { text: "選考内容", sortable: false },
      { text: "場所", sortable: false, value: "interview_date" },
      { text: "予定日", sortable: false },
      { text: "現状況", sortable: false },
      { text: "合格者数／全体学生数", sortable: false }
    ],
    headers: [
      { label: "", key: "ck" },
      { label: "no", key: "no" },
      { label: "企業名", key: "name" },
      { label: "募集タイトル", key: "employment_name" },
      { label: "応募期限", key: "apply_deadline_date" },
      { label: "選考進捗率", key: "whole_interview_count" }
    ],
    interviewHeaders: [
      { label: "no", key: "no" },
      { label: "選考内容", key: "content" },
      { label: "場所", key: "interview_place" },
      { label: "予定日", key: "interview_date" },
      { label: "現状況", key: "interview_active" },
      { label: "合格者数／全体学生数", key: "student_count" }
    ]
  }),

  methods: {
    //학교 리스트 출력
    getSchoolList(year) {
      let mode = "simple";
      let org_agent_id = this.orgAgent.org_agent_id;
      this.axios
        .post("/agent/getOrgRelColInfo", { org_agent_id, mode, year })
        .then(rep => {
          this.schoolList = null;
          this.schoolList = rep.data;
          if (this.schoolList.length != 0)
            this.org_college_id = this.schoolList[0].org_college_id;
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //채용 정보 리스트
    getEmploymentList() {
      let org_agent_id = this.orgAgent.org_agent_id;
      let org_college_id = this.org_college_id;
      this.axios
        .post("/agent/getEmploymentListByOrgCollegeId", {
          org_agent_id,
          org_college_id
        })
        .then(rep => {
          this.employmentList = null;
          this.employmentList = rep.data.employmentList;
          if (this.employmentList.length != 0) {
            this.employment_id = this.employmentList[0].employment_id;
            this.employmentInfo = this.employmentList[0];
          }
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //인터뷰 리스트
    getInterviewList() {
      let employment_id = this.employment_id;
      this.axios
        .post("/agent/getInterviewList", { employment_id })
        .then(rep => {
          this.interviewList = null;
          this.interviewList = rep.data.interviewList;
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //해당 인터뷰 지원 학생 뽑기
    getApplyStudentList() {
      let interview_id = this.interview_id;
      let employment_id = this.employment_id;
      this.axios
        .post("/agent/getApplyStudentListByInterviewId", {
          interview_id,
          employment_id
        })
        .then(rep => {
          this.studentList = null;
          this.studentList = rep.data.studentList;
          this.passStudentList = rep.data.passStudentList;
          this.noPassStudentList = rep.data.noPassStudentList;
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //심사 다이얼로그 열기
    openSimsaDialog(interview) {
      this.interview_id = interview.interview_id;
      this.interviewInfo = null;
      this.interviewInfo = interview;
      this.simsaDialog = true;
    },

    interviewListUp(item) {
      this.employmentInfo = item;
      this.employment_id = item.employment_id;
    },

    //피드백 다이얼로그 열기
    openFeedbackDialog() {
      for (let index in this.noPassStudentList) {
        let profile_photo_url = this.noPassStudentList[index].profile_photo_url;
        let name = this.noPassStudentList[index].name;
        let age = this.noPassStudentList[index].age;
        let login_id = this.noPassStudentList[index].login_id;
        let interview_feedback = this.noPassStudentList[index]
          .interview_feedback;
        this.editFeedbackList.push({
          profile_photo_url: profile_photo_url,
          name: name,
          age: age,
          login_id: login_id,
          interview_feedback: interview_feedback
        });
      }
      this.feedbackDialog = true;
    },

    //체크한 학생 합격 시키기
    setResult(result) {
      let checkStudentList = this.checkStudentList;
      let checkStudent_id_list = [];
      if (checkStudentList.length == 0) {
        alert("学生をチェックしてください。");
        return;
      }

      let msg = "";
      for (let index in checkStudentList) {
        msg += checkStudentList[index].name;
        checkStudent_id_list.push(checkStudentList[index].login_id);
        if (index == checkStudentList.length - 1) break;
        msg += ", ";
      }

      let subMsg = result == "o" ? "合格" : "不合格";

      let yesNo = confirm(
        msg +
          "合計" +
          checkStudent_id_list.length +
          "人を " +
          subMsg +
          "させますか？"
      );
      if (!yesNo) {
        alert("キャンセルできました");
        this.checkStudentList = [];
        return;
      }

      let interview_id = this.interview_id;
      let employment_id = this.employment_id;
      this.axios
        .post("/agent/setResult", {
          checkStudent_id_list,
          interview_id,
          employment_id,
          result
        })
        .then(rep => {
          if (rep.data.returnBool) {
            alert("面接の結果が決定されました。");
            this.getInterviewList();
            this.checkStudentList = [];
            this.studentList = rep.data.studentList;
            this.passStudentList = rep.data.passStudentList;
            this.noPassStudentList = rep.data.noPassStudentList;
          }
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //합격학생들 다음 인터뷰로 넘기기, interview_active를 다음 차수로 넘기기
    //*** 해당 인터뷰의 면접 결과를 셋업 한다. --> 교수, 학생에게 알리기
    setPassNextInterview() {
      let id = this.$store.getters.identification; //userID
      let passStudentList = this.passStudentList;
      let passStudent_id_list = [];
      let interview_id = this.interviewInfo.interview_id;
      let employment_id = this.employmentInfo.employment_id;
      let interview_date = this.interviewInfo.interview_date;
      let apiKey = this.$store.getters.push_config.apiKey;

      let msg = "";
      for (let index in passStudentList) {
        msg += passStudentList[index].name;
        passStudent_id_list.push(passStudentList[index].login_id);
        if (index == passStudentList.length - 1) break;
        msg += ", ";
      }

      let yesNo = confirm(
        msg + " 合計" + passStudentList.length + "人を次の面接に送りますか？"
      );
      if (!yesNo) {
        alert("キャンセルできました。");
        return;
      }

      this.axios
        .post("/agent/setPassNextInterview", {
          id,
          passStudent_id_list,
          interview_id,
          employment_id,
          interview_date,
          apiKey
        })
        .then(rep => {
          if (rep.data.returnBool) {
            this.interviewInfo.interview_active = "o";
            alert("完了しまいた。");
            this.getInterviewList();
            this.simsaDialog = false;
          } else {
            let returnCode = rep.data.returnCode;
            if (returnCode == 1) {
              alert(
                "次の面接スケジュールがありません。面接のスケジュールを登録してください。"
              );
            } else {
              alert("問題ができました。");
            }
          }
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    setAcceptance() {
      let passStudentList = this.passStudentList;
      let passStudent_id_list = [];
      let msg = "";

      if (passStudentList.length == 0) {
        msg = "内定する学生がいないです。この採用件を終わらせますか。";
      } else {
        for (let index in passStudentList) {
          msg += passStudentList[index].name;
          passStudent_id_list.push(passStudentList[index].login_id);
          if (index == passStudentList.length - 1) break;
          msg += ", ";
        }
        msg +=
          " 以下" + passStudentList.length + "人の学生に内定を出しますか。";
      }

      let yesNo = confirm(msg);
      if (!yesNo) {
        alert("キャンセルできました。");
        return;
      }

      let interview_id = this.interviewInfo.interview_id;
      let employment_id = this.employmentInfo.employment_id;

      this.axios
        .post("/agent/setAcceptance", {
          passStudent_id_list,
          interview_id,
          employment_id
        })
        .then(rep => {
          if (rep.data.returnBool) {
            this.interviewInfo.interview_active = "x";
            alert("完了しまいた。");
          } else {
          }
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //불합격 학생 피드백
    setFeedbackNoPassStudent() {
      let interview_id = this.interviewInfo.interview_id;
      let noPassStudentList = this.editFeedbackList;
      this.axios
        .post("/agent/setFeedbackNoPassStudent", {
          noPassStudentList,
          interview_id
        })
        .then(rep => {
          if (rep.data.returnBool) {
            alert("完了しまいた。");
            this.feedbackDialog = false;
          }
        })
        .catch(ex => {
          console.log(ex);
        });
    }
  },
  watch: {
    org_college_id: function(val) {
      this.getEmploymentList();
    },
    employment_id: function(val) {
      this.getInterviewList();
    },
    interview_id: function(val) {
      this.getApplyStudentList();
    }
  }
};
</script>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
<style>
</style>