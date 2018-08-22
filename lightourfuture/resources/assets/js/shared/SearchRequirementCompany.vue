<template>
 <v-container grid-list-lg sectionS>
    <v-layout >
      <v-flex xs12>
        <v-expansion-panel>
          <v-expansion-panel-content>
          <div slot="header">
            <h3><v-icon>search</v-icon> 검색 조건 열기</h3>
          </div>
          <v-card>
            <v-card-text>
              <!-- 검색 조건 -->
              <v-container fluid grid-list-lg>
                <v-layout row >
                  <!-- 정보 입력할때 선택식 항목들 출력 -->
                  <v-flex xs12 md12 lg12 border text-center  style ="background : #69F0AE">
                    기업 정보
                  </v-flex>

                  <!-- 사업 대분류 -->
                  <v-flex xs12 style ="background : #ffffff">
                    <v-expansion-panel>
                      <v-expansion-panel-content  style="background:#E3F2FD">
                        <div slot="header">
                          <v-layout row>
                            <v-flex xs12>
                              {{business.business_big_infos.title}}
                            </v-flex>
                            <v-flex xs12>
                              <v-chip color='light gray' v-if="Search.big_infos.length == 0" disabled>
                                There's no tag yet :)
                              </v-chip>
                              <v-chip
                                v-else-if="Search.big_infos.length != 0"
                                color="primary"
                                text-color="white"
                                v-for="pill in Search.big_infos" 
                                :key="pill"
                              >
                              {{pill}}&nbsp;&nbsp;{{business.business_big_infos.value[pill-1].content}} 
                              </v-chip>
                            </v-flex>
                          </v-layout>
                        </div>
                        <v-card>
                          <v-card-text>
                            <v-layout row>
                              <v-flex xs12 sm6 lg4  v-for="(check, bigkey) in business.business_big_infos.value" :key="bigkey"> 
                                <v-checkbox :label = "check.content" :value = "check.id" hide-details v-model="Search.big_infos"></v-checkbox>
                              </v-flex>
                            </v-layout>
                          </v-card-text>
                        </v-card>
                      </v-expansion-panel-content>
                    </v-expansion-panel>
                  </v-flex>

                  <!-- 사업 소분류 -->
                  <v-flex xs12 style ="background : #ffffff">
                    <v-expansion-panel>
                      <v-expansion-panel-content  style="background:#E3F2FD">
                        <div slot="header">
                          <v-layout row>
                            <v-flex xs12>
                          {{business.business_small_infos.title}}
                            </v-flex>
                            <v-flex xs12>
                              <v-chip color='light gray' v-if="Search.small_infos.length == 0" disabled>
                                There's no tag yet :)
                              </v-chip>
                              <v-chip
                                v-else-if="Search.small_infos.length != 0"
                                color="primary"
                                text-color="white"
                                v-for="pill in Search.small_infos" 
                                :key="pill"
                              >
                              {{pill}}&nbsp;&nbsp;{{business.business_small_infos.value[pill-1].content}} 
                              </v-chip>
                            </v-flex>
                          </v-layout>
                        </div>
                        <v-card>
                          <v-card-text>
                            <v-layout row>
                              <v-flex xs12 sm6 lg4  v-for="(check, smallkey) in business.business_small_infos.value" :key="smallkey"> 

                                <v-checkbox :label = "check.content" :value = "check.id" hide-details v-model="Search.small_infos"></v-checkbox>
                              </v-flex>
                            </v-layout>
                          </v-card-text>
                        </v-card>
                      </v-expansion-panel-content>
                    </v-expansion-panel>
                  </v-flex>

                    <!-- 사원수 -->
                    <!-- slider로 바꿀부분 -->
                  <v-flex xs12 style ="background : #ffffff">
                    <v-card style ="background : #E3F2FD">
                      <v-card-title>사원수</v-card-title>
                      <v-card-text>
                        <!-- <vue-slider v-model="worker_count_infos" :min=1 :max=5000></vue-slider> -->
                        <v-layout>
                            <b-form-input
                              type="text"
                              v-model="Search.worker_count_infos[0]"
                              label="최소"
                            ></b-form-input> ~ 
                            <b-form-input
                              type="text"
                              v-model="Search.worker_count_infos[1]"
                              label="최대"
                            ></b-form-input>
                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 md12 lg12 text-center  style ="background : #69F0AE">
                    채용 정보
                  </v-flex>
                  

                    <!-- 연봉 -->
                    <!-- slider로 바꿀 부분 -->
                    <v-flex xs12 md12 lg12 style ="background : #ffffff">
                      <v-card style ="background : #E3F2FD">
                      <v-card-title>연봉(万円)</v-card-title>
                      <v-card-text>
                        <!-- <vue-slider v-model="pay_infos" :min="100" :max="1000"></vue-slider> -->
                        <v-layout>
                            <b-form-input
                              type="text"
                              v-model="Search.pay_infos[0]"
                              label="최소"
                            ></b-form-input> ~ 
                            <b-form-input
                              type="text"
                              v-model="Search.pay_infos[1]"
                              label="최대"
                            ></b-form-input>
                        </v-layout>
                        
                      </v-card-text>
                    </v-card>
                    </v-flex>

                    <!-- 원하는 인재상 -->
                    <v-flex xs12 md12 lg12 style ="background : #ffffff">
                      <v-expansion-panel>
                        <v-expansion-panel-content  style="background:#E3F2FD">
                          <div slot="header">
                            <v-layout row>
                              <v-flex xs12>
                                {{employment.desired_employment_infos.title}}
                              </v-flex>
                              <v-flex xs12>
                                <v-chip color='light gray' v-if="Search.desired_employments_infos.length == 0" disabled>
                                  There's no tag yet :)
                                </v-chip>
                                <v-chip
                                  v-else-if="Search.desired_employments_infos.length != 0"
                                  color="primary"
                                  text-color="white"
                                  v-for="pill in Search.desired_employments_infos" 
                                  :key="pill"
                                >
                                {{pill}}&nbsp;&nbsp;{{employment.desired_employment_infos.value[pill-1].content}} 
                                </v-chip>
                              </v-flex>
                            </v-layout>
                          </div>
                          <v-card>
                            <v-card-text>
                              <v-layout row>
                                <v-flex xs12 sm6 lg4  v-for=" (desired, deskey) in employment.desired_employment_infos.value" :key="deskey"> 
                                  <v-checkbox :label = "desired.content" :value = "desired.id" hide-details v-model="Search.desired_employments_infos"></v-checkbox>
                                </v-flex>
                              </v-layout>
                            </v-card-text>
                          </v-card>
                        </v-expansion-panel-content>
                      </v-expansion-panel>
                    </v-flex>
                    
                    <!-- 복리후생 -->
                    <v-flex xs12 md12 lg12 style ="background : #ffffff">
                      <v-expansion-panel>
                        <v-expansion-panel-content  style="background:#E3F2FD">
                          <div slot="header">
                            <v-layout row>
                              <v-flex xs12>
                                {{employment.welfare_infos.title}}
                              </v-flex>
                              <v-flex xs12>
                                <v-chip color='light gray' v-if="Search.welfare_content_infos.length == 0" disabled>
                                  There's no tag yet :)
                                </v-chip>
                                <v-chip
                                  v-else-if="Search.welfare_content_infos.length != 0"
                                  color="primary"
                                  text-color="white"
                                  v-for="pill in Search.welfare_content_infos" 
                                  :key="pill"
                                >
                                {{pill}}&nbsp;&nbsp;{{employment.welfare_infos.value[pill-1].content}} 
                                </v-chip>
                              </v-flex>
                            </v-layout>
                          </div>
                          <v-card>
                            <v-card-text>
                              <v-layout row>
                                <v-flex xs12 sm6 lg4  v-for=" (welfare_check, welkey) in employment.welfare_infos.value" :key="welkey"> 
                                  <v-checkbox :label = "welfare_check.content" :value = "welfare_check.id" hide-details v-model="Search.welfare_content_infos"></v-checkbox>
                                </v-flex>
                              </v-layout>
                            </v-card-text>
                          </v-card>
                        </v-expansion-panel-content>
                      </v-expansion-panel>
                    </v-flex>

                    <!-- 업무내용 -->
                    <v-flex xs12 md12 lg12 style ="background : #ffffff">
                      <v-expansion-panel>
                        <v-expansion-panel-content  style="background:#E3F2FD">
                          <div slot="header">
                            <v-layout row>
                              <v-flex xs12>
                                {{employment.work_infos.title}}
                              </v-flex>
                              <v-flex xs12>
                                <v-chip color='light gray' v-if="Search.work_matchings_infos.length == 0" disabled>
                                  There's no tag yet :)
                                </v-chip>
                                <v-chip
                                  v-else-if="Search.work_matchings_infos.length != 0"
                                  color="primary"
                                  text-color="white"
                                  v-for="pill in Search.work_matchings_infos" 
                                  :key="pill"
                                >
                                {{pill}}&nbsp;&nbsp;{{employment.work_infos.value[pill-1].content}} 
                                </v-chip>
                              </v-flex>
                            </v-layout>
                          </div>
                          <v-card>
                            <v-card-text>
                              <v-layout row>
                                <v-flex xs12 sm6 lg4  v-for="(work_check, workkey) in employment.work_infos.value" :key="workkey"> 
                                  <v-checkbox :label = "work_check.content" :value = "work_check.id" hide-details  v-model="Search.work_matchings_infos"></v-checkbox>
                                </v-flex>
                              </v-layout>
                            </v-card-text>
                          </v-card>
                        </v-expansion-panel-content>
                      </v-expansion-panel>
                    </v-flex>


                    <v-flex xs12 md12 lg12 style ="background : #ffffff">
                      <v-expansion-panel>
                        <v-expansion-panel-content  style="background:#E3F2FD">
                          <div slot="header">
                            <v-layout row>
                              <v-flex xs12>
                                면접 진행 여부
                              </v-flex>
                              <v-flex xs12>
                                <v-chip color='light gray' v-if="Search.interview_infos.length == 0" disabled>
                                  There's no tag yet :)
                                </v-chip>
                                <v-chip
                                  v-else-if="Search.interview_infos.length != 0"
                                  color="primary"
                                  text-color="white"
                                  v-for="pill in Search.interview_infos" 
                                  :key="pill"
                                >
                                {{pill}}&nbsp;&nbsp;{{checkBox.interview[pill-1].text}} 
                                </v-chip>
                              </v-flex>
                            </v-layout>
                          </div>
                          <v-card>
                            <v-card-text>
                              <v-layout row>
                                <v-flex xs12 sm6 lg4 v-for="(interview_check, ikey) in checkBox.interview" :key="ikey"> 
                                  <v-checkbox :label = "interview_check.text" :value = "interview_check.id" hide-details  v-model="Search.interview_infos"></v-checkbox>
                                </v-flex>
                              </v-layout>
                            </v-card-text>
                          </v-card>
                        </v-expansion-panel-content>
                      </v-expansion-panel>
                    </v-flex>

              
                  <v-flex xs12>
                    <v-btn block color="primary" large>
                      <v-icon>search</v-icon>
                      조건 검색
                    </v-btn>
                  </v-flex>
                </v-layout>

              </v-container>
            </v-card-text>
          </v-card>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-flex>

      <v-flex xs12>
        <company-list-up :companyList = "companyList"></company-list-up>
      </v-flex>
    </v-layout>
  </v-container>
  

  
</template>

<script>
import managementList from "../static/dataProvide/ManagementList"
export default {
    created :  function (){
      //this.axios.get('/api/items', )
      let tableList = managementList;
      this.axios.post('/item/listUp', {tableList})
                .then(rep =>{
                    this.business.business_big_infos.value = rep.data.business_big_infos;
                    this.business.business_small_infos.value = rep.data.business_small_infos;

                    this.employment.work_infos.value = rep.data.work_infos;
                    this.employment.welfare_infos.value = rep.data.welfare_infos;
                    this.employment.desired_employment_infos.value = rep.data.desired_employment_infos;      
                })
                .catch(ex=>{console.log(ex)});
      // //일본 행정구역 가져오기
      // this.axios.post('/japan/getTodouhuken', )
      //           .then(rep =>{
      //             this.japan_regions_menu = rep.data.regions;
      //             this.japan_prefectures_menu = rep.data.prefectures;
      //           })
      //           .catch(ex=>{
      //             console.log(ex);
      //           });
    },

  data : () => ({
    Search : [],
    Move_Registration : '기업 등록',
    pagination: {
      sortBy: 'name'
    },

    selected: [],
    //테이블
    headers: [
      {text : '이름', value: 'name' },
      {text : '담당자 명', value: 'manager' },
      {text : '국가', value: 'country'},
      {text : '채용 확정 여부', value: 'employment_decision'},
      {text : '면접 날짜 임박', value: 'imminent'},
      {text : '지원 마감', value: 'deadline'},
    ],
    items: [
      {name : 'HALO', manager : 'yano', country : 'JP', employment_decision : 'O', imminent : '2017-09-02', deadline : '2017-09-05'},
      {name : 'HALO', manager : 'yelf', country : 'JP', employment_decision : 'x', imminent : '2017-06-11', deadline : '2017-07-02'},
      {name : 'AA', manager : 'yano', country : 'JP', employment_decision : 'x', imminent : '2017-05-011', deadline : '2017-06-01'},
      {name : 'HALO', manager : 'yano', country : 'JP', employment_decision : 'O', imminent : '2017-05-02', deadline : '2017-06-05'},
      {name : 'HALO', manager : 'alias', country : 'JP', employment_decision : 'x', imminent : '2017-06-31', deadline : '2017-07-20'},
      {name : 'AA', manager : 'yano', country : 'JP', employment_decision : 'x', imminent : '2017-07-11', deadline : '2017-08-01'},
    ],

    Search_item : { //검색 출력 체크 박스 항목 
      company_itme : [
        {text : "사업 대분류", id:'big'},
        {text : "사업 소분류", id:'small'},
        {text : "자본금", id:'capital'},
        {text : "주소", id:'address'},
        {text : "사원 수", id:'worker_count'},
      ],
      dmployment_itme : [
        {text : "연봉", id:'pay'},
        {text : "인재상", id:'talent'},
        {text : "복리후생", id:'benefits'},
        {text : "근무지", id:'work_place'},
        {text : "업무내용", id:'business'},
        {text : "면접 진행 여부", id:'interview'},
      ],
      recruit : [
        {text : "모집 중", id:'midway'},
        {text : "완료", id:'complete'},
        {text : "전부", id:'all'},
      ],
      imminent : [ //면접 임박
        {text : "오름차순", id : 'ASC'},
        {text : "내림차순", id : 'DESC'}
      ],
      deadline : [ // 지원 마감
        {text : "오름차순", id : 'ASC'},
        {text : "내림차순", id : 'DESC'}
      ],
    },

    imminent : 'default',//면접 임박 내림차순
    deadline : 'default',//지원 마감 내림차순
    company_check : [],//기업 정보 체크
    dmployment_check : [],//채용 정보 채크
    recruit: [],// 모집 정보 체크

    //검색 할 항목 체크
    Search  : {
      //address_infos : [],//주소지
      big_infos : [],//대분류
      small_infos : [],//소분류
      pay_infos : [100, 1000],//연봉
      //working_area_infos : [],//근무지
      desired_employments_infos : [],//인재 상
      welfare_content_infos : [],//복리후생
      work_matchings_infos : [],//업무내용
      interview_infos : [],//면접 진행 여부
      worker_count_infos : [1, 5000],//사원수
    },

    
    //curr_region_code : '',

    checkBox : {
      //면접 진행 여부
      interview : [
        {id : 1, text : "모집 중"},
        {id : 2, text : "면접 중"},
        {id : 3, text : "완료"}
      ], 
      // //연봉
      // pay : [
      //   {id : 1, text : "160円~"},
      //   {id : 2, text : "200円~"},
      //   {id : 3, text : "240円~"},
      //   {id : 4, text : "280円~"}
      // ],

      // worker_count : [
      //   {id : 1, text : "0~29"},
      //   {id : 2, text : "30~99"},
      //   {id : 3, text : "100~999"},
      //   {id : 4, text : "1000~"}
      // ],
     
    },


//기업 검색 항목
    business :{     
      business_big_infos : {name : 'business_big_infos', title : '사업 대분류', value : null},
      business_small_infos : {name : 'business_small_infos', title : '사업 소분류', value : null},
    },

//채용 정보 검색 항목         
    employment : {
      work_infos : {name : 'work_infos', title : '업무 내용', value : null},
      welfare_infos : {name : 'welfare_infos', title : '복리후생', value : null},
      desired_employment_infos : {name : 'desired_employment_infos', title : '원하는 인재상', value : null},
    },

// //일본 행정구역 항목
//     japan_regions_menu : null,
//     japan_prefectures_menu : null,

  }),

  methods: {
    click (){
      alert("dd");
    },
    toggleAll () {
      if (this.selected.length) this.selected = []
      else this.selected = this.items.slice()
    },

    changeSort (column) {
      if (this.pagination.sortBy === column) {
        this.pagination.descending = !this.pagination.descending
      } else {
        this.pagination.sortBy = column
        this.pagination.descending = false
      }
    }

  }
}
</script>

<style>
  .section{
    margin-bottom: 1rem;
    background-color:rgb(223, 255, 223);    
   }
   /*
     <v-container fluid grid-list-lg>
   

    <v-layout row >
      <!-- 정보 입력할때 선택식 항목들 출력 -->
      <v-flex xs12 md12 lg12 border text-center  style ="background : #69F0AE">
        기업 정보
      </v-flex>

      <!-- 사업 대분류 -->
      <v-flex xs12 style ="background : #ffffff">
        <v-expansion-panel>
          <v-expansion-panel-content  style="background:#E3F2FD">
            <div slot="header">
              <v-layout row>
                <v-flex xs12>
                  {{business.business_big_infos.title}}
                </v-flex>
                <v-flex xs12>
                  <v-chip color='light gray' v-if="Search.big_infos.length == 0" disabled>
                    There's no tag yet :)
                  </v-chip>
                  <v-chip
                    v-else-if="Search.big_infos.length != 0"
                    color="primary"
                    text-color="white"
                    v-for="pill in Search.big_infos" 
                    :key="pill"
                  >
                  {{pill}}&nbsp;&nbsp;{{business.business_big_infos.value[pill-1].content}} 
                  </v-chip>
                </v-flex>
              </v-layout>
            </div>
            <v-card>
              <v-card-text>
                <v-layout row>
                  <v-flex xs12 sm6 lg4  v-for="(check, bigkey) in business.business_big_infos.value" :key="bigkey"> 
                    <v-checkbox :label = "check.content" :value = "check.id" hide-details v-model="Search.big_infos"></v-checkbox>
                  </v-flex>
                </v-layout>
              </v-card-text>
            </v-card>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-flex>

      <!-- 사업 소분류 -->
      <v-flex xs12 style ="background : #ffffff">
        <v-expansion-panel>
          <v-expansion-panel-content  style="background:#E3F2FD">
            <div slot="header">
              <v-layout row>
                <v-flex xs12>
              {{business.business_small_infos.title}}
                </v-flex>
                <v-flex xs12>
                  <v-chip color='light gray' v-if="Search.small_infos.length == 0" disabled>
                    There's no tag yet :)
                  </v-chip>
                  <v-chip
                    v-else-if="Search.small_infos.length != 0"
                    color="primary"
                    text-color="white"
                    v-for="pill in Search.small_infos" 
                    :key="pill"
                  >
                  {{pill}}&nbsp;&nbsp;{{business.business_small_infos.value[pill-1].content}} 
                  </v-chip>
                </v-flex>
              </v-layout>
            </div>
            <v-card>
              <v-card-text>
                <v-layout row>
                  <v-flex xs12 sm6 lg4  v-for="(check, smallkey) in business.business_small_infos.value" :key="smallkey"> 

                    <v-checkbox :label = "check.content" :value = "check.id" hide-details v-model="Search.small_infos"></v-checkbox>
                  </v-flex>
                </v-layout>
              </v-card-text>
            </v-card>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-flex>

        <!-- 사원수 -->
        <!-- slider로 바꿀부분 -->
      <v-flex xs12 style ="background : #ffffff">
        <v-card style ="background : #E3F2FD">
          <v-card-title>사원수</v-card-title>
          <v-card-text>
            <!-- <vue-slider v-model="worker_count_infos" :min=1 :max=5000></vue-slider> -->
            <v-layout>
                <b-form-input
                  type="number"
                  v-model="Search.worker_count_infos[0]"
                  label="최소"
                  :min=1 
                  :max=4990
                ></b-form-input> ~ 
                <b-form-input
                  type="number"
                  v-model="Search.worker_count_infos[1]"
                  label="최대"
                  :min=10
                  :max=5000
                ></b-form-input>
            </v-layout>
          </v-card-text>
        </v-card>
      </v-flex>

      <v-flex xs12 md12 lg12 text-center  style ="background : #69F0AE">
        채용 정보
      </v-flex>
      

        <!-- 연봉 -->
        <!-- slider로 바꿀 부분 -->
        <v-flex xs12 md12 lg12 style ="background : #ffffff">
          <v-card style ="background : #E3F2FD">
          <v-card-title>연봉(万円)</v-card-title>
          <v-card-text>
            <!-- <vue-slider v-model="pay_infos" :min="100" :max="1000"></vue-slider> -->
            <v-layout>
                <b-form-input
                  type="number"
                  v-model="Search.pay_infos[0]"
                  label="최소"
                  :min=100
                  :max=990
                ></b-form-input> ~ 
                <b-form-input
                  type="number"
                  v-model="Search.pay_infos[1]"
                  label="최대"
                  :min=110
                  :max=1000
                ></b-form-input>
            </v-layout>
            
          </v-card-text>
        </v-card>
        </v-flex>

        <!-- 원하는 인재상 -->
        <v-flex xs12 md12 lg12 style ="background : #ffffff">
          <v-expansion-panel>
            <v-expansion-panel-content  style="background:#E3F2FD">
              <div slot="header">
                <v-layout row>
                  <v-flex xs12>
                    {{employment.desired_employment_infos.title}}
                  </v-flex>
                  <v-flex xs12>
                    <v-chip color='light gray' v-if="Search.desired_employments_infos.length == 0" disabled>
                      There's no tag yet :)
                    </v-chip>
                    <v-chip
                      v-else-if="Search.desired_employments_infos.length != 0"
                      color="primary"
                      text-color="white"
                      v-for="pill in Search.desired_employments_infos" 
                      :key="pill"
                    >
                    {{pill}}&nbsp;&nbsp;{{employment.desired_employment_infos.value[pill-1].content}} 
                    </v-chip>
                  </v-flex>
                </v-layout>
              </div>
              <v-card>
                <v-card-text>
                  <v-layout row>
                    <v-flex xs12 sm6 lg4  v-for=" (desired, deskey) in employment.desired_employment_infos.value" :key="deskey"> 
                      <v-checkbox :label = "desired.content" :value = "desired.id" hide-details v-model="Search.desired_employments_infos"></v-checkbox>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-flex>
        
        <!-- 복리후생 -->
        <v-flex xs12 md12 lg12 style ="background : #ffffff">
          <v-expansion-panel>
            <v-expansion-panel-content  style="background:#E3F2FD">
              <div slot="header">
                <v-layout row>
                  <v-flex xs12>
                    {{employment.welfare_infos.title}}
                  </v-flex>
                  <v-flex xs12>
                    <v-chip color='light gray' v-if="Search.welfare_content_infos.length == 0" disabled>
                      There's no tag yet :)
                    </v-chip>
                    <v-chip
                      v-else-if="Search.welfare_content_infos.length != 0"
                      color="primary"
                      text-color="white"
                      v-for="pill in Search.welfare_content_infos" 
                      :key="pill"
                    >
                    {{pill}}&nbsp;&nbsp;{{employment.welfare_infos.value[pill-1].content}} 
                    </v-chip>
                  </v-flex>
                </v-layout>
              </div>
              <v-card>
                <v-card-text>
                  <v-layout row>
                    <v-flex xs12 sm6 lg4  v-for=" (welfare_check, welkey) in employment.welfare_infos.value" :key="welkey"> 
                      <v-checkbox :label = "welfare_check.content" :value = "welfare_check.id" hide-details v-model="Search.welfare_content_infos"></v-checkbox>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-flex>

        <!-- 업무내용 -->
        <v-flex xs12 md12 lg12 style ="background : #ffffff">
          <v-expansion-panel>
            <v-expansion-panel-content  style="background:#E3F2FD">
              <div slot="header">
                <v-layout row>
                  <v-flex xs12>
                    {{employment.work_infos.title}}
                  </v-flex>
                  <v-flex xs12>
                    <v-chip color='light gray' v-if="Search.work_matchings_infos.length == 0" disabled>
                      There's no tag yet :)
                    </v-chip>
                    <v-chip
                      v-else-if="Search.work_matchings_infos.length != 0"
                      color="primary"
                      text-color="white"
                      v-for="pill in Search.work_matchings_infos" 
                      :key="pill"
                    >
                    {{pill}}&nbsp;&nbsp;{{employment.work_infos.value[pill-1].content}} 
                    </v-chip>
                  </v-flex>
                </v-layout>
              </div>
              <v-card>
                <v-card-text>
                  <v-layout row>
                    <v-flex xs12 sm6 lg4  v-for="(work_check, workkey) in employment.work_infos.value" :key="workkey"> 
                      <v-checkbox :label = "work_check.content" :value = "work_check.id" hide-details  v-model="Search.work_matchings_infos"></v-checkbox>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-flex>


        <v-flex xs12 md12 lg12 style ="background : #ffffff">
          <v-expansion-panel>
            <v-expansion-panel-content  style="background:#E3F2FD">
              <div slot="header">
                <v-layout row>
                  <v-flex xs12>
                    면접 진행 여부
                  </v-flex>
                  <v-flex xs12>
                    <v-chip color='light gray' v-if="Search.interview_infos.length == 0" disabled>
                      There's no tag yet :)
                    </v-chip>
                    <v-chip
                      v-else-if="Search.interview_infos.length != 0"
                      color="primary"
                      text-color="white"
                      v-for="pill in Search.interview_infos" 
                      :key="pill"
                    >
                    {{pill}}&nbsp;&nbsp;{{checkBox.interview[pill-1].text}} 
                    </v-chip>
                  </v-flex>
                </v-layout>
              </div>
              <v-card>
                <v-card-text>
                  <v-layout row>
                    <v-flex xs12 sm6 lg4 v-for="(interview_check, ikey) in checkBox.interview" :key="ikey"> 
                      <v-checkbox :label = "interview_check.text" :value = "interview_check.id" hide-details  v-model="Search.interview_infos"></v-checkbox>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-flex>

  
      <v-flex xs12>
        <v-btn block color="primary" large>
          <v-icon>search</v-icon>
          조건 검색
        </v-btn>
      </v-flex>
    </v-layout>

  </v-container>
   */
</style>


