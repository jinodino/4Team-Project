<template>
    <!-- 학생 상세 정보 모달 -->
    <v-card-text>
      <v-container fluid grid-list-xs>
        <v-layout row justify-center>
          <v-flex xs12 md3 lg3 border>
            <v-layout row justify-center>
              <v-flex xs12>
                  <v-card flat tile width="220px">
                  <v-card-media 
                  height="280px"
                  >
                    <img  :src="studentInfo.profile_photo_url">
                  </v-card-media>
              </v-card>
              </v-flex>
            </v-layout>
          
          </v-flex>

          <v-flex xs12 md9 lg9 border>
            <v-layout text-center row>
              <v-flex xs4 md2 lg2 border>
                이름
              </v-flex>
              <v-flex xs8 md4 lg4 border>
                {{studentInfo.name_eng + " (" + studentInfo.name_kana + ')'}}
              </v-flex>

              <v-flex xs4 md2 lg2 border>
                생년월일
              </v-flex>
              <v-flex xs8 md4 lg4 border>
                {{studentInfo.birth_date + " (" + studentInfo.age + '살)'}}
              </v-flex>

              <v-flex xs4 md2 lg2 border>
                국적
              </v-flex>
              <v-flex xs8 md4 lg4 border>
                {{studentInfo.country_code}}
              </v-flex>

              <v-flex xs4 md2 lg2 border>
                성별
              </v-flex>
              <v-flex xs8 md4 lg4 border>
                {{studentInfo.gender == "m" ? "남자" : "여자"}}
              </v-flex>
    
              <v-flex xs4 md2 lg2 border>
                일어 성적
              </v-flex>
              <v-flex xs8 md4 lg4 border>
                  {{"JPT : " + studentInfo.JPT + " / JLPT : " + studentInfo.JLPT}}
              </v-flex>
              <v-flex xs4 md2 lg2 border>
                영어 성적
              </v-flex>
              <v-flex xs8 md4 lg4 border>
                  {{"TOEIC : " + studentInfo.TOEIC + " / TOEFL : " + studentInfo.TOEFL}}
              </v-flex>
        
              <v-flex xs4 md2 lg2 border>
                E-Mail
              </v-flex>
              <v-flex xs8 md10 lg10 border>
                  {{studentInfo.email}}
              </v-flex>
              <v-flex xs12>
                <v-card flat>
                  <v-container fluid grid-list-xs>
                    <v-layout row>
                      <v-flex xs12  border v-show="!recommendMode" style="height : 198px">
                        <p>{{studentInfo.recommendation_comment}}</p>
                      </v-flex>
                      <v-flex xs12> 
                        <v-text-field
                          label="에이전트 추천 코멘트 asdsad"  
                          v-show="recommendMode"
                          v-model="recommendation_comment_edit"
                          textarea
                          
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-btn v-show="!recommendMode" color="primary" @click="setRecommendMode">추천 코멘트 수정</v-btn>
                        <v-btn v-show="recommendMode" color="primary" @click="updateRecommendationComment">확인</v-btn>
                        <v-btn v-show="recommendMode" color="error" @click="recommendMode = false">취소</v-btn>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card>
              </v-flex>
            </v-layout>
          </v-flex>

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
                    <v-container fluid grid-list-lg>
                      <!-- 지원 Status -->
                      <v-flex xs12>
                          <v-card>
                              <v-card-title  class="grey lighten-4 py-1">
                                  {{applyStatus.tableTitle}} : {{applyStatus.list.length}}건
                              </v-card-title>
                              <v-data-table
                              :headers="applyStatus.headers"
                              :items="applyStatus.list"
                              hide-actions
                              class="elevation-1"
                              >
                                  <template slot="items" slot-scope="props">
                                      <tr>
                                          <!-- <td class="text-xs-center">{{props.item.employment_id}}</td> -->
                                          <td>
                                              <v-chip v-if="props.item.acceptance_fixed_ox == 'o'" color="error" label outline disabled>사퇴불가</v-chip>
                                              <v-chip v-else-if="props.item.acceptance_fixed_ox == 'x'" color="success" label outline disabled>사퇴가능</v-chip>
                                              <v-chip v-else label outline disabled>미확정</v-chip>
                                              {{props.item.org_name}}({{props.item.org_name_kana}}) | {{props.item.employment_name}}
                                          </td>

                                          <td class="text-xs-center">
                                              <v-chip v-if="props.item.apply_permission_ox == 'o'" color="primary" label outline disabled>승낙</v-chip>
                                              <v-chip v-else-if="props.item.apply_permission_ox == 'x'" color="error" label outline disabled>거절</v-chip>
                                              <v-chip v-else label outline disabled>미승낙</v-chip>
                                          </td>
                                      </tr>
                                  </template>
                        
                                  <template slot="no-data">
                                      <v-alert :value="true" color="error" icon="warning">
                                          Nothing to display :(
                                      </v-alert>
                                  </template>
                              </v-data-table>
                          </v-card>
                      </v-flex>

                      <!-- 면접 결과 Status -->
                      <v-flex xs12>
                          <v-card>
                              <v-card-title  class="grey lighten-4 py-1">
                                  면접 결과 : {{interviewStatus.subListKeys.length}}건
                              </v-card-title>
                              <v-data-table
                              :headers="interviewStatus.headers"
                              :items="interviewStatus.list"
                              item-key="employment_id"
                              hide-actions
                              >
                                  <template slot="items" slot-scope="props">
                                      <tr @click="props.expanded = !props.expanded">
                                          <!-- <td class="text-xs-center">{{props.item.employment_id}}</td> -->
                                          <td>{{props.item.org_name}}({{props.item.org_name_kana}}) | {{props.item.employment_name}}</td>
                                          <td class="text-center" >{{interviewStatus.subList[props.item.employment_id].length}} / {{props.item.whole_interview_count}}</td>
                                          <td class="text-xs-center">
                                            <v-chip v-if="interviewStatus.subList[props.item.employment_id][interviewStatus.subList[props.item.employment_id].length -1].interview_result == 'o'" color="primary" label outline disabled>
                                              <v-icon left color="primary">card_giftcard</v-icon> 합격
                                            </v-chip>
                                            <v-chip v-else-if="interviewStatus.subList[props.item.employment_id][interviewStatus.subList[props.item.employment_id].length -1].interview_result == 'x'" color="error" label outline disabled>
                                              <v-icon left color="error">block</v-icon> 불합격
                                            </v-chip>
                                            <v-chip v-else label outline disabled>
                                              <v-icon left>update</v-icon>심사중
                                            </v-chip>
                                          </td>
                                          <td>
                                              <v-icon v-if="props.expanded" large>expand_less</v-icon>
                                              <v-icon v-else large>expand_more</v-icon>
                                          </td>
                                      </tr>
                                  </template>
                                  
                                  <template slot="expand" slot-scope="props">
                                      <v-card color="warning">
                                          <v-card-text>
                                              <v-data-table
                                              :headers="interviewStatus.subHeaders"
                                              :items="interviewStatus.subList[props.item.employment_id]"
                                              hide-actions
                                              >
                                                  <template slot="items" slot-scope="props">
                                                      <td class="text-xs-center">{{props.item.interview_count}}차</td>
                                                      <td><v-chip disabled>{{props.item.content}}</v-chip>{{props.item.schedule_title}} - {{props.item.interview_content}}</td>
                                                      <td>{{props.item.interview_place}}</td>
                                                      <td>{{props.item.interview_date}}</td>
                                                      <td class="text-xs-center">
                                                          <v-chip v-if="props.item.interview_result == 'o'" color="primary" label outline disabled>
                                                              합격
                                                          </v-chip>
                                                          <v-chip v-else-if="props.item.interview_result == 'x'" color="error" label outline disabled>
                                                              불합격
                                                          </v-chip>
                                                          <v-chip v-else disabled label outline> 
                                                              심사중
                                                          </v-chip>
                                                      </td>
                                                      <td></td>
                                                  </template>
                                              </v-data-table>
                                          </v-card-text>
                                      </v-card>
                                  </template>
                                  <template slot="no-data">
                                      <v-alert :value="true" color="error" icon="warning">
                                          Nothing to display :(
                                      </v-alert>
                                  </template>
                              </v-data-table>
                          </v-card>
                      </v-flex>

                      <!-- 내정 결과 Status -->
                      <v-flex xs12>
                          <v-card>
                              <v-card-title  class="grey lighten-4 py-1">
                                  내정 결과  : {{norminateStatus.list.length}}건
                              </v-card-title>
                              <v-data-table
                              :headers="norminateStatus.headers"
                              :items="norminateStatus.list"
                              hide-actions
                              >
                                  <template slot="items" slot-scope="props">
                                      <td class="text-xs-center">{{props.item.employment_id}}</td>
                                      <td>{{props.item.org_name}} ({{props.item.org_name_kana}}) | {{props.item.employment_name}}</td>
                                      <td class="text-xs-center">
                                          <v-chip v-if="props.item.student_acceptance_ox =='o'" label outline disabled color="primary">수락</v-chip>
                                          <v-chip v-else-if="props.item.student_acceptance_ox =='x'" label outline disabled color="error">사퇴</v-chip>
                                          <v-chip v-else label outline disabled>미결정</v-chip>
                                      </td>
                                      <td class="text-xs-center">
                                          <v-chip v-if="props.item.professor_acceptance_ox =='o'" color="primary" label outline disabled>승낙</v-chip>
                                          <v-chip v-else-if="props.item.professor_acceptance_ox == 'x'" color="error" label outline disabled>거절</v-chip>
                                          <v-chip v-else label outline disabled>미승낙</v-chip>
                                      </td>
                                      
                                      <td class="text-xs-center">
                                          <v-chip v-if="props.item.student_acceptance_ox == 'o' && props.item.professor_acceptance_ox == 'o'" color="primary" label outline disabled>
                                              <v-icon left>gavel</v-icon>내정 완료
                                          </v-chip>
                                          <v-chip v-else-if="props.item.student_acceptance_ox == 'x' && props.item.professor_acceptance_ox == 'x'" color="error" label outline disabled>
                                              <v-icon left>pan_tool</v-icon>사퇴 완료
                                          </v-chip>
                                          <v-chip v-else label outline disabled>
                                              교수 승낙 대기
                                          </v-chip>
                                      </td>
                                  </template>
                                  <template slot="no-data">
                                      <v-alert :value="true" color="error" icon="warning">
                                          Nothing to display :(
                                      </v-alert>
                                  </template>
                              </v-data-table>
                          </v-card>
                      </v-flex>
                    </v-container>
                  </v-tab-item>
                  
                  <!-- 스킬 시트 -->
                  <v-tab-item>
                    <v-container fluid>
                      <v-layout row border>
                        <v-flex  xs6 lg3 v-for="field in skillFieldList" :key="field.no" v-if="field.no != 5">
                          <v-layout row border>
                            <v-flex xs12 class="yellow lighten-5 title">
                              {{field.skill_field}}
                            </v-flex>
                          </v-layout>
                          <v-layout row v-for="skill in skillList[field.no]" :key="skill.no">
                            <v-flex xs6 border>
                              {{skill.skill_name}}
                            </v-flex>
                            <v-flex xs6 border> 
                              {{studentSkillMatchPrint[skill.no] == null ? '-' : studentSkillMatchPrint[skill.no].skill_level}}
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                    </v-container>    
                  </v-tab-item>

              </v-tabs-items>
            </v-flex>
          </v-layout>
        </v-layout>
      </v-container>
    </v-card-text>

</template>

<script>
export default {

  props : [
    'studentInfo', 
    'studentLoginId',
    'applyStatus', 
    'interviewStatus', 
    'norminateStatus', 
    'skillFieldList', 
    'skillList',
    'studentSkillMatchPrint'
  ],

  created : function(){

  },

  data : ()=> ({

    recommendMode : false,
    recommendation_comment_edit : null,
    tab : '0'

  }),


  methods : {


    //학생 코멘트 수정하기 모드
    setRecommendMode(){
      this.recommendation_comment_edit = this.studentInfo.recommendation_comment;
      this.recommendMode = true;
    },

    //학생 코멘트 수정하기
    updateRecommendationComment(){
      let yesNo = confirm('추천 코멘트 수정 사항을 등록하시겠습니까?');
      if(!yesNo){
        alert('취소 되었습니다.');
        this.recommendMode = false;
        return;
      }

      let recommendation_comment = this.recommendation_comment_edit;
      let student_login_id = this.studentLoginId;

      this.axios.post('/agent/updateRecommendationComment', {student_login_id, recommendation_comment})
                .then(rep=>{

                  if(rep.data.returnBool){
                    alert('등록이 완료되었습니다.');
                    this.studentInfo.recommendation_comment = recommendation_comment;
                  }
                  else
                    alert('변경 사항이 없습니다.');

                  this.recommendMode = false;
                })
                .catch(ex=>{
                    console.log(ex);
                });
    },


  },

}
</script>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
