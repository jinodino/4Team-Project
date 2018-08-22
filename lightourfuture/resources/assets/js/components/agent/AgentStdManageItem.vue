<template>
  <v-container  fluid grid-list-lg>  
    <v-layout row>
      <!-- 학과 리스트 -->
      <v-flex xs12>
        <v-card>
          <v-card-text>
            <v-layout row wrap justify-center>
              <v-flex xs8 md4 lg4>
                <v-select
                :items="schoolList"
                item-text="org_name"
                item-value="org_college_id" 
                label="学校選択"
                :disabled="schoolList.length == 0"
                v-model="org_college_id"
                >
                </v-select>
              </v-flex>
              <v-flex xs4 md3 lg2>
                <v-select
                label="年度選択"
                :items="yearList"
                item-text="label"
                item-value="value"
                v-model="searchYear"
                :disabled="schoolList.length == 0"
                >
                </v-select>
              </v-flex>       
            </v-layout>
            <b-table :filter="searchYear" @row-clicked="faculty" :fields="majorHeaders" responsive :items="facultyList" striped>
              <template slot="x" slot-scope="data">
                <p><v-icon v-show="data.item.faculty_id == faculty_id">check</v-icon></p>
              </template> 
              <template slot="department_name" slot-scope="data">
                <p>{{ data.item.department_name === null ? "─" : data.item.department_name}}</p>
              </template>
              <template slot="major" slot-scope="data">
                <p><v-chip  disabled>{{ data.item.major_tag === null ? "─" : data.item.major_tag}}</v-chip></p>
              </template> 
              <template slot="major_name" slot-scope="data">
                <p>{{ data.item.major_name === null ? "─" : data.item.major_name }}</p>
              </template>
              <template slot="class_name" slot-scope="data">
                <p>{{ data.item.class_name === null ? "─" : data.item.class_name }}</p>
              </template> 
              <template slot="college_category_sub" slot-scope="data">
                <p>{{ data.item.college_category_sub === null ? "─" : data.item.college_category_sub }}</p>
              </template> 
              <!-- <template slot="n" slot-scope="data">
                <p>{{++data.index}}</p>
              </template> -->
            </b-table>
            <template>
              <v-alert v-if="org_college_id == null" :value="true" color="error" icon="error">
                学校を選択してください。
              </v-alert>
            </template>

            <!-- <v-data-table
              :headers="majorHeaders1"
              :items="facultyList"
              text-center
              item-key="faculty_id"
              hide-actions
            > 
              <template slot="items" slot-scope="props">
                <tr @click="faculty_id = props.item.faculty_id; facultyInfo = props.item">
                  <td  style=" font-size: 18px; height: 60px"><v-icon v-show="props.item.faculty_id == faculty_id">check</v-icon></td>
                  <td  style=" font-size: 18px; height: 60px">{{ props.item.department_name === null ? "─" : props.item.department_name}}</td>
                  <td  style=" font-size: 18px; height: 60px"><v-chip  disabled>{{ props.item.major_tag === null ? "─" : props.item.major_tag}}</v-chip></td>
                  <td  style=" font-size: 18px; height: 60px">{{ props.item.major_name === null ? "─" : props.item.major_name }}</td>
                  <td  style=" font-size: 18px; height: 60px">{{ props.item.class_name === null ? "─" : props.item.class_name }}</td>
                  <td  style=" font-size: 18px; height: 60px">{{ props.item.college_category_sub === null ? "─" : props.item.college_category_sub }}</td>
                  <td  style=" font-size: 18px; height: 60px"></td>
                </tr>
              </template>
              <template slot="no-data">
                <v-alert :value="true" color="error" icon="warning">
                  学校を選択してください。
                </v-alert>
              </template>
            </v-data-table> -->
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>

    <v-layout row justify-end>
        <v-btn large v-show="lookMode == 'module'" icon @click="lookMode = 'list'">
          <v-icon>view_list</v-icon>
        </v-btn>
        <v-btn large v-show="lookMode == 'list'" icon @click="lookMode = 'module'">
          <v-icon>view_module</v-icon>
        </v-btn>
    </v-layout>

    <v-layout row v-show="lookMode == 'list'">
      <!-- 학생 리스트 -->
      <v-flex xs12 v-show="lookMode == 'list'">
        <v-card flat>
          <b-table hover @row-clicked="openStudentInfoDialog" :fields="studentHeaders" responsive :items="studentList" item-key="faculty_id"  striped>
            <template slot="name" slot-scope="data">
              <p>{{data.item.name}}</p>
            </template> 
            <template slot="major_grade" slot-scope="data">
              <p>{{data.item.major_grade == null ? '-' : data.item.major_grade}}</p>
            </template>
            <template slot="japanese_speaking_level" slot-scope="data">
              <p>{{data.item.japanese_speaking_level == null ? '-' : data.item.japanese_speaking_level}}</p>
            </template> 
            <template slot="sincerity_grade" slot-scope="data">
              <p>{{data.item.sincerity_grade == null ? '-' : data.item.sincerity_grade}}</p>
            </template>
            <template slot="personality_grade" slot-scope="data">
              <p>{{data.item.personality_grade == null ? '-' : data.item.personality_grade}}</p>
            </template> 
            <template slot="apply_count" slot-scope="data">
              <p>{{data.item.apply_count}}</p>
            </template>
            <template slot="acceptance_count" slot-scope="data">
              <p>{{data.item.acceptance_count}}</p>
            </template> 
            <template slot="final_company" slot-scope="data">
              <p>{{data.item.final_company_name}} ({{data.item.final_company_name_kana}})</p>
            </template>
          </b-table>
          <template>
            <v-alert v-if="faculty_id == null" :value="true" color="error" icon="warning">
              学科を選択してください。
            </v-alert>
            <v-alert v-else-if="studentList.length == 0" :value="true" color="warning" icon="warning">
              まだ登録されている学生がいません。
            </v-alert>
          </template>
          <!-- <v-card-text>
            <v-data-table
            :headers="studentHeaders1"
            :items="studentList"
            >
              <template slot="items" slot-scope="props">
                <tr @click="openStudentInfoDialog(props.item)">
                  <td  style=" font-size: 18px; height: 60px">{{props.item.name}}</td>
                  <td  style=" font-size: 18px; height: 60px">{{props.item.major_grade == null ? '-' : props.item.major_grade}}</td>
                  <td  style=" font-size: 18px; height: 60px">{{props.item.japanese_speaking_level == null ? '-' : props.item.japanese_speaking_level}}</td>
                  <td  style=" font-size: 18px; height: 60px">{{props.item.sincerity_grade == null ? '-' : props.item.sincerity_grade}}</td>
                  <td  style=" font-size: 18px; height: 60px">{{props.item.personality_grade == null ? '-' : props.item.personality_grade}}</td>
                  <td  style=" font-size: 18px; height: 60px" class="text-xs-left">{{props.item.apply_count}}</td>
                  <td  style=" font-size: 18px; height: 60px" class="text-xs-left">{{props.item.acceptance_count}}</td>
                  <td  style=" font-size: 18px; height: 60px" class="text-xs-left">{{props.item.final_company_name}} ({{props.item.final_company_name_kana}})</td>
                </tr>
              </template>

              <template slot="no-data">
                <v-alert v-if="faculty_id == null" :value="true" color="error" icon="warning">
                  学科を選択してください。
                </v-alert>
                <v-alert v-else-if="studentList.length == 0" :value="true" color="warning" icon="warning">
                  まだ登録されている学生がいません。
                </v-alert>
              </template>
            </v-data-table>
          </v-card-text> -->
        </v-card>
      </v-flex>
    </v-layout>

    
    <v-layout row v-show="lookMode == 'module'">
      <v-flex xs12>
        <v-card flat>
          <v-container fluid>
            <v-layout row>

              <v-flex xs12 v-if="faculty_id == null">
                <v-alert :value="true" type="error">
                  学科を選択してください
                </v-alert>
              </v-flex>

              <v-flex xs12 v-else-if="studentList.length == 0">
                <v-alert :value="true" type="warning">
                  まだ登録されている学生がいません。
                </v-alert>
              </v-flex>
            </v-layout>
            <v-layout row>
              <v-flex xs12 sm6 md6 lg2 v-for="student in studentList" :key="student.login_id"  @click="openStudentInfoDialog(student)">   
                  <!-- style="position : absolute; width:" -->

                <v-card hover>
                  <!-- 솔드 아웃  -->
                  <v-container fluid>
                      <v-card-media  style="position:reactive;" height="200px" :src="student.profile_photo_url">
                      </v-card-media>
                      <v-card-media style="position:absolute; " height="200px" :src="student.profile_photo_url">
                      </v-card-media>
                    <div style="position:absolute; bottom:120px; ">
                      <p v-if="student.employment_end_ox == 'o'" 
                      style="font-size:72px ; color:red; transform: rotate(-20deg); text-shadow: 2px 2px 0 #000">
                        <b>SOLD OUT</b>
                      </p>
                    </div>
                    <v-layout row>
                      <v-flex xs12>
                        {{student.name_kana}} ({{student.age}})
                        <v-chip v-if="student.acceptance_count == 0" disabled outline color="error">
                          内定なし
                        </v-chip>
                        <v-chip v-else disabled outline color="success">
                          内定あり {{student.acceptance_count}}
                        </v-chip>
                      </v-flex>
                    </v-layout>
                    <br>
                    <v-chip small disabled label outline color="dahong">
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

                    <!-- <v-chip v-if="student.employment_end_ox == 'o'" large disabled label outline color="error">
                      솔드아웃
                    </v-chip> -->
                  </v-container>
                </v-card>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card>
      </v-flex>
    </v-layout>

    <!-- 다이얼로그 -->
    <v-dialog persistent v-if="studentInfo != null && studentStatusInfo != null" v-model="studentInfoDialog" scrollable>
      <v-card>
        <v-card-title class="grey lighten-4 py-2 title">
          {{studentInfo.student_code}}
            <b class="Titlefontrad" v-if="studentInfo.employment_end_ox == 'o'"> 就活終了</b>
            <b class="Titlefontgreen" v-else >就活中</b>
          <v-spacer></v-spacer>
            <v-btn @click="studentInfoDialog = false" color="error">X</v-btn>
        </v-card-title>
        <v-card-text>
          <v-container fluid grid-list-lg>
            <v-layout row>
              <v-flex xs12>
                <v-alert  v-if="studentInfo.final_company_name != null" :value="true" type="success">
                  {{studentInfo.final_company_name}} 入社確定
                </v-alert>
                <v-alert v-else :value="true" type="warning">
                  まだ入社を確定した会社がありません。
                </v-alert>
              </v-flex>
            </v-layout>

            <v-layout row justify-space-around>
              <v-flex text-center>
              <b>志願数</b>
                <p class="resultfont">
                  {{studentInfo.apply_count}}<span class="fontmain">件</span>
                </p>    
              </v-flex>
              <div class="divheight"></div>
              <v-flex text-center>
                <b>内定数</b>
                <p class="resultfont">
                  {{studentInfo.acceptance_count}}<span class="fontmain">件</span>
                </p>   
              </v-flex>
              <div class="divheight"></div>
              <v-flex text-center>
                <b>不合格</b>
                <p class="resultfont">
                  {{studentInfo.talrack_count}}<span class="fontmain">件</span>
                </p>    
              </v-flex>
              <div class="divheight"></div>
              <v-flex text-center>
                <b>進行中</b>
                <p class="resultfont">
                  {{studentStatusInfo.length - studentInfo.talrack_count}}<span class="fontmain">件</span>
                </p>    
              </v-flex>

            </v-layout>

            <v-layout row align-center justify-space-around style="margin-bottom: 25px">
              <v-flex xs12 md12 lg3 text-xs-center>
                  <v-card flat>
                    <v-card-media 
                      :src="studentInfo.profile_photo_url"
                      height="353px"
                    > 
                    </v-card-media>
                  </v-card>
              </v-flex>

              <v-flex xs12 md12 lg9> 
                <v-container fluid>
                  <v-layout text-center row >
                    <v-flex xs4 md2 lg2 id="tableTitle">
                      学校
                    </v-flex>
                    <v-flex xs8 md4 lg4 id="tableItem">
                      {{collegeInfo.org_name}} 
                      <!-- {{schoolList[org_college_id].org_name}} -->
                    </v-flex>
                    
                    <v-flex xs4 md2 lg2 id="tableTitle">
                      専攻
                    </v-flex>
                    <v-flex xs8 md4 lg4 id="tableItem">
                      {{facultyInfo.major_tag}}
                    </v-flex>
                    
                    <v-flex xs4 md2 lg2 id="tableTitle">
                      名前
                    </v-flex>
                    <v-flex xs8 md4 lg4 id="tableItem">
                      {{studentInfo.name_eng + " (" + studentInfo.name_kana + ')'}}
                    </v-flex>

                    <v-flex xs4 md2 lg2 id="tableTitle">
                      生年月日 / 年齢
                    </v-flex>
                    <v-flex xs8 md4 lg4 id="tableItem">
                      {{studentInfo.birth_date}} / {{studentInfo.age + '歳'}}
                    </v-flex>


                    <v-flex xs4 md2 lg2 id="tableTitle">
                      国籍 / 性別
                    </v-flex>
                    <v-flex xs8 md4 lg4 id="tableItem">
                      {{studentInfo.country_code}} / {{studentInfo.gender == "m" ? "男子" : "女子"}}
                    </v-flex>
          
          
                    <v-flex xs4 md2 lg2 id="tableTitle">
                      E-mail
                    </v-flex>
                    <v-flex xs8 md4 lg4 id="tableItem">
                        {{studentInfo.email}}
                    </v-flex>

                    <v-flex xs4 md2 lg2 id="tableTitle" >
                      教授評価
                    </v-flex>
                    <v-flex xs8 md4 lg4 id="tableItem">
                      <v-chip disabled label outline color="dahong">
                        専攻 {{studentInfo.major_grade == null ? '-' : studentInfo.major_grade}}
                      </v-chip>
                      <v-chip disabled label outline color="error">
                        会話 {{studentInfo.japanese_speaking_level == null ? '-' : studentInfo.japanese_speaking_level}}
                      </v-chip>   
                      <v-chip disabled label outline color="primary">
                        誠実 {{studentInfo.sincerity_grade == null ? '-' : studentInfo.sincerity_grade}}
                      </v-chip>
                      <v-chip disabled label outline color="success" large>
                        人性 {{studentInfo.personality_grade == null ? '-' : studentInfo.personality_grade}}
                      </v-chip>
                    </v-flex>

                    <v-flex xs4 md2 lg2 id="tableTitle">
                      成績
                    </v-flex>
                    <v-flex xs8 md4 lg4 id="tableItem">
                        {{studentInfo.credit}} / {{collegeInfo.credit_total}}
                    </v-flex>

                    <v-flex xs4 md2 lg2 id="tableTitle">
                      日本語成績
                    </v-flex>
                    <v-flex xs8 md4 lg4 id="tableItem">
                        {{"JPT : " + studentInfo.JPT + " / JLPT : " + studentInfo.JLPT}}
                    </v-flex>

                    <v-flex xs4 md2 lg2 id="tableTitle">
                      英語成績
                    </v-flex>
                    <v-flex xs8 md4 lg4 id="tableItem">
                        {{"TOEIC : " + studentInfo.TOEIC + " / TOEFL : " + studentInfo.TOEFL}}
                    </v-flex>
                    
                    <v-flex xs4 md2 lg2 id="tableTitle">
                      git hub
                    </v-flex>
                    <v-flex xs8 md10 lg10 id="tableItem">
                        {{studentInfo.github_url}}
                    </v-flex>
                    <v-flex xs4 md2 lg2 align-center id="tableTitle">
                      <br>
                        コメント<br>
                        <v-btn  v-show="!recommendMode" @click="setRecommendMode" color="success">修正</v-btn>
                        <v-btn  v-show="recommendMode" @click="updateRecommendationComment" color="success">確認</v-btn>
                        <v-btn  v-show="recommendMode" color="error" text-color="white" @click="recommendMode = false">取り消し</v-btn>
                      </v-flex>

                
                      <v-flex xs8 md10 lg10 style="height:150px" id="tableItem">
                        <p v-show="!recommendMode"  style="display: table-cell; vertical-align: middle; height:140px;">{{studentInfo.recommendation_comment}}</p>
                        <v-text-field
                        label="エージェントコメント"
                        v-model="recommendation_comment_edit"
                        textarea 
                        v-show="recommendMode"
                        no-resize
                        rows="3"
                        >
                        </v-text-field>
                      </v-flex>
                  </v-layout>
                </v-container>
              </v-flex>
            </v-layout>

            <v-layout row>
              <v-flex xs12>
                <v-tabs
                  slot="extension"
                  v-model="tab"
                  grow
                  fixed-tabs
                >
                  
                  <v-layout>  
                    <v-flex xs6 style="margin-left:2px">
                        <div v-if="tab == '0'" @click="tab = '0'" id="tabon" class="title">Status Summary</div>
                        <div v-else @click="tab = '0'" id="taboff" >Status Summary</div>  
                    </v-flex>
                    <v-flex xs6 style="margin-right:2px">
                        <div v-if="tab == '1'" @click="tab = '1'" id="tabon" class="title">Skill Sheet</div>
                        <div v-else @click="tab = '1'" id="taboff" >Skill Sheet</div>
                    </v-flex>
                  </v-layout>
                    <!-- <v-tab>
                      Status Summary
                    </v-tab>
                    <v-tab>
                      Skill Sheet
                    </v-tab> -->
                </v-tabs> 
              
                <v-tabs-items v-model="tab">
                    <!-- status -->
                    <v-tab-item>
                      <v-container fluid grid-list-xs style="margin-top:-2px">
                        <b-table :fields="Statusheaders" responsive :items="studentStatusInfo" item-key="faculty_id"  striped>
                          <template slot="num" slot-scope="data">
                            <p>{{++data.index}}</p>
                          </template>
                          <template slot="desire" slot-scope="data">
                            <p style="width:130px">{{data.item.org_name === null ? "─" : data.item.org_name}}</p>
                          </template>
                          <template slot="Employname" slot-scope="data">
                            <p>{{data.item.employment_name === null ? "─" : data.item.employment_name}}</p>
                          </template>
                          <template slot="condition" slot-scope="data">
                            <p>{{ data.item.talrack_chasu === null ? data.item.next_interview_count+'/' + data.item.whole_interview_count + ' 進行' : data.item.talrack_chasu + "次に落ちる" }}</p>
                          </template>
                          <template slot="interview" slot-scope="data">
                            <p v-if="data.item.interview_date">{{ data.item.interview_date}}</p>
                            <v-chip v-else label outline>未定</v-chip>
                          </template>
                          <template slot="decision" slot-scope="data">
                            <v-chip v-if="data.item.acceptance_ox == 'o' && data.item.professor_acceptance_ox == 'o' && data.item.student_acceptance_ox =='o'" label outline disabled color="primary">
                              内定承諾
                            </v-chip>
                            <v-chip id="gray" v-else-if="data.item.acceptance_ox == 'o'">
                              内定持ち
                            </v-chip>
                            <!-- <p  v-if="data.item.interview_date">{{ data.item.talrackChasu === null ? 
                              data.item.acceptance_ox === 'o' ? 
                              data.item.professor_acceptance_ox === 'o' ? 
                              data.item.student_acceptance_ox ==='o' ? '내정 승낙' : '내정 보유'
                              : '내정 사퇴' 
                              : '내정 보유' 
                              : '-'}}</p> -->
                          </template>
                        </b-table>
                        <!-- <v-data-table
                          :headers="Statusheaders"
                          :items="studentStatusInfo"
                          text-center
                          item-key="faculty_id"
                          hide-actions
                        > 
                          <template slot="items" slot-scope="props">
                            <tr >
                              <td  style=" font-size: 18px; height: 60px">{{++props.index}}</td>
                              <td  style=" font-size: 18px; height: 60px">{{props.item.org_name === null ? "─" : props.item.org_name}}</td>
                              <td  style=" font-size: 18px; height: 60px">{{ props.item.employment_name === null ? "─" : props.item.employment_name}}</td>
                              <td  style=" font-size: 18px; height: 60px">{{ props.item.talrack_chasu === null ? props.item.interview_count+'/' + props.item.whole_interview_count + ' 진행중' : props.item.interview_count + "차 탈락" }}</td>
                              <td  style=" font-size: 18px; height: 60px"><p v-if="props.item.interview_date">{{ props.item.interview_date}}</p><p v-else>─</p></td>
                              <td  style=" font-size: 18px; height: 60px">
                                {{ props.item.talrack_chasu === null ? 
                                  props.item.acceptance_ox === 'o' ? 
                                  props.item.professor_acceptance_ox === 'o' ? 
                                  props.item.student_acceptance_ox ==='o' ? '내정 승낙' : '내정 보유'
                                  : '내정 사퇴' 
                                  : '내정 보유' 
                                  : '-'}}


                                {{ props.item.whole_interview_count === props.item.interview_count ? (props.item.acceptance_ox === 'o' ? 
                                props.item.professor_acceptance_ox === 'o' ? 
                                (props.item.student_acceptance_ox ==='o' ? '내정 승낙' : '내정 사퇴') 
                                : '내정 보유' : '-'}}
                              </td>
                            </tr>
                          </template>
                        </v-data-table> -->

                      </v-container>
                    </v-tab-item>

                    
                    <!-- 스킬 시트 -->
                    <v-tab-item>
                      <v-container fluid grid-list-xs>
                        <v-layout wrap >
                          <v-flex  xs6 lg3 v-for="field in skill_field_list" :key="field.no" v-if="field.no != 5">
                            <v-layout row style="margin-top:-2px">
                              <v-flex xs12  border class=" lighten-5 title">
                                {{field.skill_field}}
                              </v-flex>
                            </v-layout>
                            <v-layout row v-for="skill in skill_list[field.no]" :key="skill.no" >
                              <v-flex xs6  style="background: #E0E0E0; border-bottom: 1px solid #BDBDBD;">
                                {{skill.skill_name}}
                              </v-flex>
                              <v-flex xs6 text-xs-center style="border-bottom: 1px solid #BDBDBD;"> 
                                {{student_skill_match_print[skill.no] == null ? '-' : student_skill_match_print[skill.no].skill_level}}
                              </v-flex>
                            </v-layout>
                          </v-flex>
                        </v-layout>
                      </v-container>    
                    </v-tab-item>

                </v-tabs-items>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
export default {
  props: ["orgAgent", "skillListInfo"],

  created: function() {
    //년도 리스트 출력
    let thisYear = new Date().getFullYear();
    this.thisYear = thisYear;
    for (let i = thisYear; i >= 2016; i--)
      this.yearList.push({ label: i, value: i });
    this.yearList.push({ label: "全年度", value: null });

    //스킬 리스트 출력
    this.skill_field_list = this.skillListInfo.skill_field_list;
    this.skill_list = this.skillListInfo.skill_list;
    this.skill_level_list = this.skillListInfo.skill_level_list;

    this.getSchoolList();
    //this.getStudentList(1, "2018");
  },

  data: () => ({
    lookMode: "module",
    thisYear: null,

    schoolList: [],
    collegeInfo : {
      org_college_id : null,
    },
    org_college_id: null,
    

    facultyList: [],
    facultyInfo : {},
    faculty_id: null,

    yearList: [],
    searchYear: null,

    studentList: [],
    student_login_id: null,

    studentInfo: null,
    studentStatusInfo: null,
    apply_count : null,
    talrack_count : null,
    acceptance_count : null,

    studentInfoDialog: false,

    studentInfoDialog: false,
    Statusheaders:[
      {label : '順番', key: 'num'},
      {label : '志願した会社', key: 'desire'},
      {label : '募集タイトル', key: 'Employname'},
      {label : '現状態', key: 'condition'},
      {label : '次の面接', key: 'interview'},
      {label : '内定状態', key: 'decision'},
    ],
    recommendMode : false,
    recommendation_comment_edit : null,

    tab : '0',
    skill_field_list : [],
    skill_list : [],
    skill_level_list : [],

    student_skill_match : {},
    student_skill_match_print : {},


    majorHeaders1 : [
      {text : '', sortable : false, width : "72px"},
      {text : '学部', value: 'department_name'},
      {text : '専攻分類', value: 'major'},
      {text : '学科／専攻', value: 'major_name'},
      {text : '専攻／クラス', value: 'class_name'},
      {text : '卒業年数', value: 'college_category_sub'},
      {text : '学生数', value: ''},
    ],


    majorHeaders : [
      {label : '', key: 'x'},
      {label : '学部', key: 'department_name'},
      {label : '専攻分類', key: 'major'},
      {label : '学科／専攻', key: 'major_name'},
      {label : '専攻／クラス', key: 'class_name'},
      {label : '卒業年数', key: 'college_category_sub'},
      // {label : '学生数', key: 'n'},
    ],

    studentHeaders1: [
      { text: "名前", value: "name" },
      { text: "専攻", value: "major_grade" },
      { text: "日本語会話", value: "japanese_speaking_level" },
      { text: "誠実度", value: "sincerity_grade" },
      { text: "人性", value: "personality_grade" },
      { text: "志願数", value: "apply_count" },
      { text: "内定数", value: "acceptance_count" },
      { text: "入社する会社", value: "final_company" }
    ],
    studentHeaders: [
      { label: "名前", key: "name" },
      { label: "専攻", key: "major_grade" },
      { label: "日本語会話", key: "japanese_speaking_level" },
      { label: "誠実度", key: "sincerity_grade" },
      { label: "人性", key: "personality_grade" },
      { label: "志願数", key: "apply_count" },
      { label: "内定数", key: "acceptance_count" },
      { label: "入社する会社", key: "final_company" }
    ]
  }),

  methods: {
    comment_save(value) {
      alert("'" + value + "' がセーブされました。");
    },
    //학교 이름, 학과 명 보내기
    // stdinfos(){
    //   // org_college_id: null,
    // // org_college_name:null,
    // // org_college_major : null,
    //   // for (let index = 0; index < this.schoolList.length; index++) {
    //   //   if(this.schoolList[index].org_college_id == this.org_college_id){
    //   //     this.org_college_name = this.schoolList[index].org_name;
    //   //   }
    //   // }
    //   // //facultyList.major_tag
    //   // for (let index = 0; index < this.facultyList.length; index++) {
    //   //   if(this.facultyList[index].faculty_id == this.faculty_id){
    //   //     this.org_college_major = this.facultyList[index].major_tag;
    //   //   }
    //   // }
    // },

    //학교 리스트 출력
    faculty(item){
      this.faculty_id = item.faculty_id;
      this.facultyInfo = item;
    },
    getSchoolList() {
      let mode = "simple";
      let org_agent_id = this.orgAgent.org_agent_id;
      this.axios
        .post("/agent/getOrgRelColInfo", { org_agent_id, mode })
        .then(rep => {
          this.schoolList = rep.data;
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //학과 리스트 출력
    getfacultyList(org_college_id) {
      this.axios
        .post("/school/getfacultyList", { org_college_id })
        .then(rep => {
          this.facultyList = rep.data.facultyList;
          this.faculty_id = this.facultyList[0].faculty_id;
          this.collegeInfo = rep.data.collegeInfo;
          this.searchYear = new Date().getFullYear();
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //학생 리스트 출력
    getStudentList(faculty_id, year) {
      // if (year == null) 
      //   year = new Date().getFullYear();
      let org_agent_id = this.orgAgent.org_agent_id;
      this.axios
        .post("/school/getStudentList", { faculty_id, year, org_agent_id})
        .then(rep => {
          let studentArr = rep.data.studentList;

          this.studentList = [];
          for (let index in studentArr)
            this.studentList.push(studentArr[index]);
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //학생 상세 정보 모달 열기
    openStudentInfoDialog(item) {
      //this.stdinfos();
      this.student_login_id = item.login_id;
      this.recommendation_comment_edit = null;

      this.studentInfo = null;
      this.studentInfo = item;
      this.studentInfoDialog = true;
    },

    //학생 코멘트 수정하기 모드
    setRecommendMode() {
      this.recommendation_comment_edit = this.studentInfo.recommendation_comment;
      this.recommendMode = true;
    },

    //학생 코멘트 수정하기
    updateRecommendationComment() {
      let yesNo = confirm("コメントを修正しますか?");
      if (!yesNo) {
        alert("取り消しできました。");
        this.recommendMode = false;
        return;
      }

      let recommendation_comment = this.recommendation_comment_edit;
      let student_login_id = this.student_login_id;

      this.axios
        .post("/agent/updateRecommendationComment", {
          student_login_id,
          recommendation_comment
        })
        .then(rep => {
          if (rep.data.returnBool) {
            alert("登録しました。");
            this.studentInfo.recommendation_comment = recommendation_comment;
          } else alert("変更事項がありません。");

          this.recommendMode = false;
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //학생 status 가져오기
    getStudentStatus() {
      let student_login_id = this.student_login_id;
      let classification = "agent";
      this.axios
        .post("/agent/getStudentStatus", { student_login_id, classification })
        .then(rep => {
          this.studentStatusInfo = rep.data.employmentArr;

          //지원 수
          this.apply_count = rep.data.apply_count;

          //불합격 수
          this.talrack_count = rep.data.talrack_count;

          //내정 수
          this.acceptance_count = rep.data.acceptance_count;
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //학생 기술 정보 뽑기
    getSkillMatchInfo() {
      let login_id = this.student_login_id;
      this.axios
        .post("/student/getSkillMatchInfo", { login_id })
        .then(rep => {
          this.student_skill_match = null;
          this.student_skill_match_print = null;

          this.student_skill_match_print = rep.data.skill_match_print;
          this.student_skill_match = rep.data.skill_match;
        })
        .catch(ex => {
          console.log(ex);
        });
    }
  },

  watch: {
    org_college_id : function(val, oldVal) {
      this.getfacultyList(this.org_college_id);
    },

    faculty_id: function(val) {
      this.getStudentList(this.faculty_id, this.searchYear);
    },

    searchYear: function(val) {
      this.getStudentList(this.faculty_id, this.searchYear);
    },

    student_login_id: function(val) {
      this.getStudentStatus();
      this.getSkillMatchInfo();
    }
  }
};
</script>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
