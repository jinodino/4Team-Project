<template>

 <v-container grid-list-lg fluid v-show="this.orgId != null">
    <v-layout row>
      <!-- 검색 조건 -->
      <v-flex xs12 v-if="business.business_big_infos.value != null && business.business_small_infos.value != null && employment.work_infos.value != null && employment.welfare_infos.value != null && employment.desired_employment_infos.value != null ">
        <v-expansion-panel>
          <v-expansion-panel-content expand-icon="mdi-menu-down">
            <div slot="header"  style="font-size:20px" class="Titlefont">検索条件開</div>
            <v-card>
              <v-container fluid>
                <v-flex xs12  text-xs-center id="main" style="height:30px; font-size: 16px">
                  企業情報
                </v-flex>
                <v-flex xs12>
                  <v-layout wrap>
                    <!-- 대분류 -->
                    <v-flex xs4 md2 lg2  text-center id="tableTitle">
                      <div style=" margin: 8px;">
                        {{business.business_big_infos.title}}
                        <input type="checkbox" align-left @click="Check_all(0)" v-model ="allbig">
                      </div>
                    </v-flex>
                    <v-flex xs8 md10 lg10 id="tableItem">
                      <div style=" margin: 8px;">
                        <v-layout row>
                          <v-flex xs6 sm3 v-for="(check, bigkey) in business.business_big_infos.value" :key="bigkey"> 
                            <input type="checkbox" :value="check.id" name="continents" v-model="Search.big_infos"> {{check.content}}
                          </v-flex>
                        </v-layout>
                      </div>
                    </v-flex>
                    <!-- 소분류 -->
                    <v-flex xs4 md2 lg2  text-center id="tableTitle">
                      <div style=" margin-top: 8px;">
                        {{business.business_small_infos.title}}
                        <input type="checkbox" align-left @click="Check_all(1)" v-model ="allsmall">
                      </div>
                    </v-flex>
                    <v-flex xs8 md10 lg10 id="tableItem">

                      <input id="itemtoggle" type="checkbox" checked style="display: none; visibility: hidden; text-align:right; text-align:right">
                        <label for="itemtoggle">
                          
                        </label>
                      <v-layout wrap style=" margin: 1px;">
                        <v-flex xs6 sm3 >
                          <input type="checkbox" :value="business.business_small_infos.value[0].id" name="small_infos" v-model="Search.small_infos"> {{business.business_small_infos.value[0].content}}
                        </v-flex>
                        <v-flex xs6 sm3>
                          <input type="checkbox" :value="business.business_small_infos.value[1].id" name="small_infos" v-model="Search.small_infos"> {{business.business_small_infos.value[1].content}}
                        </v-flex>
                        <v-flex xs6 sm3>
                          <input type="checkbox" :value="business.business_small_infos.value[2].id" name="small_infos" v-model="Search.small_infos"> {{business.business_small_infos.value[2].content}}
                        </v-flex>
                        <v-flex xs6 sm3>
                          <input type="checkbox" :value="business.business_small_infos.value[3].id" name="small_infos" v-model="Search.small_infos"> {{business.business_small_infos.value[3].content}}
                        </v-flex>
                      </v-layout>

                      <v-flex xs12 id="expand">
                        <section>
                          <v-layout row> 
                            <v-flex xs6 sm3  v-for="(check, smallkey) in business.business_small_infos.value" :key="smallkey"> 
                              <p v-if="smallkey > 3">
                                <input type="checkbox" :value="check.id" name="small_infos" v-model="Search.small_infos"> {{check.content}}
                              </p>
                            </v-flex>
                          </v-layout>
                        </section>
                      </v-flex>
                    </v-flex>

                    <!-- 사원수 -->
                    <v-flex xs4 md2 lg2  text-center id="tableTitle">
                      <div style=" margin: 8px;">
                        社員数
                      </div>
                    </v-flex>
                    <v-flex xs8 md10 lg10 id="tableItem">
                      <div style=" margin: 8px;">
                        <v-flex xs8>
                          <v-layout row>
                            <v-text-field 
                              type="number"
                              v-model="Search.worker_count_infos[0]"
                              label="최소"
                              :min=1
                              :max=5000
                            ></v-text-field >
                            <v-text-field 
                              type="number"
                              v-model="Search.worker_count_infos[1]"
                              label="최대"
                              :min=1
                              :max=5000
                            ></v-text-field >
                          </v-layout>
                        </v-flex>
                      </div>
                    </v-flex>
                  </v-layout>
                </v-flex>
                
                <v-flex xs12 text-xs-center id="main" style="height:30px; font-size: 16px">
                  採用情報                
                </v-flex>
                <v-flex xs12>
                  <v-layout wrap>

                <!-- 업무내용 -->
                    <v-flex xs4 md2 lg2  text-center id="tableTitle">
                      <div style=" margin: 8px;">
                        {{employment.work_infos.title}}
                        <input type="checkbox" align-left @click="Check_all(2)" v-model ="allwork">
                      </div>
                    </v-flex>
                    <v-flex xs8 md10 lg10 id="tableItem">
                      
                      <input id="toggle" type="checkbox" checked style="display: none; visibility: hidden; text-align:right; text-align:right">
                        <label for="toggle">
                          
                        </label>
                      <v-layout wrap style=" margin: 1px;">
                        <v-flex xs6 sm3 >
                          <input type="checkbox" :value="employment.work_infos.value[0].id" name="work_infos" v-model="Search.work_matchings_infos"> {{employment.work_infos.value[0].content}}
                        </v-flex>
                        <v-flex xs6 sm3>
                          <input type="checkbox" :value="employment.work_infos.value[1].id" name="work_infos" v-model="Search.work_matchings_infos"> {{employment.work_infos.value[1].content}}
                        </v-flex>
                        <v-flex xs6 sm3>
                          <input type="checkbox" :value="employment.work_infos.value[2].id" name="work_infos" v-model="Search.work_matchings_infos"> {{employment.work_infos.value[2].content}}
                        </v-flex>
                        <v-flex xs6 sm3>
                          <input type="checkbox" :value="employment.work_infos.value[3].id" name="work_infos" v-model="Search.work_matchings_infos"> {{employment.work_infos.value[3].content}}
                        </v-flex>
                      </v-layout>

                      <v-flex xs12 id="expand">
                        <section>
                          <v-layout row>
                            <v-flex xs6 sm3 v-for="(work_check, workkey) in employment.work_infos.value" :key="workkey"> 
                              <p v-if="workkey > 3">
                                <input type="checkbox" :value="work_check.id" name="work_infos" v-model="Search.work_matchings_infos">{{work_check.content}}
                              </p>
                            </v-flex>
                          </v-layout>
                        </section>
                      </v-flex>
                    </v-flex>

                <!-- 복리후생 -->
                    <v-flex xs4 md2 lg2  text-center id="tableTitle">
                      <div style=" margin: 8px;">
                        {{employment.welfare_infos.title}}
                        <input type="checkbox" align-left @click="Check_all(3)" v-model ="allwelfare">
                      </div>
                    </v-flex>
                    <v-flex xs8 md10 lg10 id="tableItem">
                      <div style=" margin: 8px;">
                        <v-layout row>
                          <v-flex xs6 sm2  v-for=" (welfare_check, welkey) in employment.welfare_infos.value" :key="welkey"> 
                            <input type="checkbox" :value = "welfare_check.id" v-model="Search.welfare_content_infos"> {{welfare_check.content}}
                          </v-flex>
                        </v-layout>
                      </div>
                    </v-flex>

                <!-- 원하는 인재상 -->
                    <v-flex xs4 md2 lg2  text-center id="tableTitle">
                      <div style=" margin: 8px;">
                        {{employment.desired_employment_infos.title}}
                        <input type="checkbox" align-left @click="Check_all(4)" v-model ="alldesired">
                      </div>
                    </v-flex>
                    <v-flex xs8 md10 lg10 id="tableItem">
                      
                      <input id="toggleemployment" type="checkbox" checked style="display: none; visibility: hidden; text-align:right; text-align:right">
                        <label for="toggleemployment">
                          
                        </label>
                      <v-layout wrap style=" margin: 1px;">
                        <v-flex xs6 sm3 >
                          <input type="checkbox" :value="employment.desired_employment_infos.value[0].id" name="employment" v-model="Search.desired_employments_infos"> {{employment.desired_employment_infos.value[0].content}}
                        </v-flex>
                        <v-flex xs6 sm3>
                          <input type="checkbox" :value="employment.desired_employment_infos.value[1].id" name="employment" v-model="Search.desired_employments_infos"> {{employment.desired_employment_infos.value[1].content}}
                        </v-flex>
                        <v-flex xs6 sm3>
                          <input type="checkbox" :value="employment.desired_employment_infos.value[2].id" name="employment" v-model="Search.desired_employments_infos"> {{employment.desired_employment_infos.value[2].content}}
                        </v-flex>
                        <v-flex xs6 sm3>
                          <input type="checkbox" :value="employment.desired_employment_infos.value[3].id" name="employment" v-model="Search.desired_employments_infos"> {{employment.desired_employment_infos.value[3].content}}
                        </v-flex>
                      </v-layout>

                      <v-flex xs12 id="expand">
                        <section>
                          <v-layout row>
                            <v-flex xs6 sm3    v-for=" (desired, deskey) in employment.desired_employment_infos.value" :key="deskey"> 
                              <p v-if="deskey > 3">
                                <input type="checkbox" :value = "desired.id" name="employment" v-model="Search.desired_employments_infos"> {{desired.content}}
                              </p>
                            </v-flex>
                          </v-layout>
                        </section>
                      </v-flex>
                    </v-flex>

                    <v-flex xs4 md2 lg2  text-center id="tableTitle">
                      <div style=" margin: 8px;">
                        1年給料
                      </div>
                    </v-flex>
                    <v-flex xs8 md10 lg10 id="tableItem">
                      <div style=" margin: 8px;">
                        <v-flex xs8>
                          <v-layout row>
                            <v-text-field 
                              type="number"
                              v-model="Search.pay_infos[0]"
                              label="최소"
                              :min=100
                              :max=5000000
                            ></v-text-field >
                            <v-text-field 
                              type="number"
                              v-model="Search.pay_infos[1]"
                              label="최대"
                              :min=100
                              :max=5000000
                            ></v-text-field >
                          </v-layout>
                        </v-flex>
                      </div>
                    </v-flex>
                  </v-layout>
                </v-flex>
                <v-flex xs12>
                  <v-btn large @click="searchCompanyEmploymentList" color="success"> 
                    <v-icon>search</v-icon>
                    検索する
                  </v-btn>
                  <v-btn large @click="initializeSearchReq" color="dahong" dark>
                    検索条件戻す
                  </v-btn>
                </v-flex>
              </v-container>
            </v-card>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-flex>

      <!-- 리스트 업 -->
      <v-flex xs12>
        <v-card>
            <v-card-title>
              {{tableTitle != null ? tableTitle + " : " : ''}}{{companyList.length}}건
              <v-spacer></v-spacer>
              <v-text-field
                v-model="search"
                append-icon="search"
                label="Search"
                single-line
                hide-details
              ></v-text-field>
            </v-card-title>
            <b-table 
            @row-clicked="go2companyInfo" 
            :fields="headers" :filter="search" responsive :items="companyList" striped>
              <template slot="org_name" slot-scope="data">
                <p>
                    <v-chip v-if="data.item.employment_decision_ox == 'o'" label disabled color="primary" outline small>採用確定</v-chip>
                    <v-chip v-else label outline disabled small>採用を決めていない</v-chip>
                    {{data.item.org_name}} ( {{data.item.org_name_kana}} )
                </p>
              </template> 
              <template slot="employment_name" slot-scope="data">
                <div v-if="data.item.employment_decision_ox != 'o'"></div>
                <v-chip v-else-if="data.item.acceptance_fixed_ox == 'o'" label outline disabled color="success" small>辞退可能</v-chip>
                <v-chip v-else-if="data.item.acceptance_fixed_ox == 'x'" label outline disabled color="error" small>辞退不可能</v-chip>
                <v-chip v-else label outline disabled small>決めてない</v-chip>
                <p>{{data.item.employment_name == null || data.item.employment_decision_ox != 'o'? '登録されている採用県がありません。': data.item.employment_name }}</p>
              </template> 
              <template slot="working_area" slot-scope="data">
                <p>{{data.item.working_area == null || data.item.employment_decision_ox != 'o'? '-': data.item.working_area}}</p>
              </template> 
              <template slot="student_count" slot-scope="data">
                <p>{{data.item.employment_id == null || data.item.employment_decision_ox != 'o'? '-' : data.item.student_count+'人'}}</p>
              </template> 
              <template slot="apply_open_date" slot-scope="data">
                <p>{{data.item.apply_open_date == null || data.item.employment_decision_ox != 'o'? '-': data.item.apply_open_date}}</p>
              </template> 
              <template slot="apply_deadline_date" slot-scope="data">
                <p>{{data.item.apply_deadline_date == null || data.item.employment_decision_ox != 'o'? '-': data.item.apply_deadline_date}}</p>
              </template> 
            </b-table>

            <!-- <v-data-table
              :headers="headers1"
              :items="companyList"
              :search="search"
              hide-actions
              class="elevation-1"
            >
              <template slot="items" slot-scope="props">
                <tr @click="go2companyInfo(props.item.org_matchings_id, props.item.org_company_id, props.item.employment_id, props.item.employment_decision_ox)">
                  <td class="text-xs-center">{{props.item.employment_id == null? '-' : props.item.employment_id}}</td>
                  <td>
                    <v-chip v-if="props.item.employment_decision_ox == 'o'" label disabled color="primary" outline small>採用確定</v-chip>
                    <v-chip v-else label outline disabled small>採用を決めていない</v-chip>
                    {{props.item.org_name}} ( {{props.item.org_name_kana}} )</td>
                  <td class="text-xs-left">
                    <div v-if="props.item.employment_decision_ox != 'o'"></div>
                    <v-chip v-else-if="props.item.acceptance_fixed_ox == 'o'" label outline disabled color="success" small>辞退可能</v-chip>
                    <v-chip v-else-if="props.item.acceptance_fixed_ox == 'x'" label outline disabled color="error" small>辞退不可能</v-chip>
                    <v-chip v-else label outline disabled small>決めてない</v-chip>
                    {{props.item.employment_name == null || props.item.employment_decision_ox != 'o'? '登録されている採用県がありません。': props.item.employment_name }}
                  </td>
                  <td class="text-xs-left">{{props.item.working_area == null || props.item.employment_decision_ox != 'o'? '-': props.item.working_area}}</td>
                  <td>{{props.item.employment_id == null || props.item.employment_decision_ox != 'o'? '-' : props.item.student_count+'人'}}</td>
                  <td class="text-xs-left">{{props.item.apply_open_date == null || props.item.employment_decision_ox != 'o'? '-': props.item.apply_open_date}}</td>
                  <td class="text-xs-left">{{props.item.apply_deadline_date == null || props.item.employment_decision_ox != 'o'? '-': props.item.apply_deadline_date}}</td>
                </tr>
              </template>
              <template slot="no-data">
                <v-alert :value="true" color="error" icon="warning">
                  Nothing to display :(
                </v-alert>
              </template>
            </v-data-table> -->
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import managementList from "../static/dataProvide/ManagementList"
import vueSlider from 'vue-slider-component';
export default {
    components: {
      vueSlider
    },
    props : ['orgId', 'tableTitle'],
    created : function (){

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

      this.getEmploymentList();
    },

    data : ()=>({
      //전체체크
      allbig : false,
      allsmall : false,
      allwork : false,
      allwelfare : false,
      alldesired : false,
      
      //기업 검색 항목
      business :{     
        business_big_infos : {name : 'business_big_infos', title : '事業大分類', value : null},
        business_small_infos : {name : 'business_small_infos', title : '事業小分類', value : null},
      },

      //채용 정보 검색 항목         
      employment : {
        work_infos : {name : 'work_infos', title : '業務内容', value : null},
        welfare_infos : {name : 'welfare_infos', title : '福利厚生', value : null},
        desired_employment_infos : {name : 'desired_employment_infos', title : '求める人材', value : null},
      },

      //체크 박스 검색 항목
      checkBox : {
        //면접 진행 여부
        interview : [
          {id : 1, text : "募集中"},
          {id : 2, text : "面接中"},
          {id : 3, text : "完了"}
        ], 
      
      },


      //검색 조건
      Search  : {
        //address_infos : [],//주소지
        big_infos : [],//대분류
        small_infos : [],//소분류
        pay_infos : [0, 5000000],//연봉
        //working_area_infos : [],//근무지
        desired_employments_infos : [],//인재 상
        welfare_content_infos : [],//복리후생
        work_matchings_infos : [],//업무내용
        //interview_infos : [],//면접 진행 여부
        worker_count_infos : [1, 10000],//사원수
      },

      search : null,
      
      headers1 : [
        //{text : '채용 id', value : 'employment_id', width : '80px', sortable: false, align:'center'},
        //{text : '채용 결정', value : 'employment_decision_ox',sortable:false, align:'center'},
        {text : '기업명', value : 'org_name' },
        {text : '채용건', value : 'employment_name' },
        {text : '근무지', value : 'working_area' },
        {text : '지원자', value :'student_count'},
        //{text : '사퇴 가능', value :'acceptance_fixed_ox', width : '90px', sortable: false, align:'center'},
        {text : '지원 오픈일', value : 'apply_open_date'},
        {text : '지원 마감일', value : 'apply_deadline_date', width : '128px'},
      ],
      
      headers : [
        {label : '企業名', key : 'org_name' },
        {label : '採用件', key : 'employment_name' },
        {label : '勤務地', key : 'working_area' },
        {label : '志願者', key :'student_count'},
        {label : '志願始まり日', key : 'apply_open_date'},
        {label : '志願終わり日', key : 'apply_deadline_date'},
      ],

      companyList : [],
    }),

    methods : {

      initializeSearchReq(){
        this.Search.big_infos = [];
        this.Search.small_infos = [];
        this.Search.pay_infos = [0, 5000000];

        this.Search.desired_employments_infos = [];//인재 상
        this.Search.welfare_content_infos = [];//복리후생
        this.Search.work_matchings_infos = [];//업무내용
        this.Search.worker_count_infos = [1, 5000];
        
        this.allbig = false,
        this.allsmall = false,

        this.getEmploymentList();
      },
      
      Check_all(item){
        if(item == 0){
          
          //대학 분류 전체 체크
          //alert(this.business.business_big_infos.value[0].content)
          var big = this.business.business_big_infos.value//항목
          if (this.allbig == true) {
            this.Search.big_infos = []
          }
          else  {
            for(var count = 0; count < big.length; count++){
              this.Search.big_infos[count] = this.business.business_big_infos.value[count].id
            }
          }
          
        }else if(item == 1){
          var small = this.business.business_small_infos.value//항목
          if (this.allsmall == true) {
            this.Search.small_infos = []
          }
          else  {
            for(var count = 0; count < small.length; count++){
              this.Search.small_infos[count] = this.business.business_small_infos.value[count].id
            }
          }
        }else if(item == 2){
          var work = this.employment.work_infos.value//항목
          if (this.allwork == true) {
            this.Search.work_matchings_infos = []
          }
          else  {
            for(var count = 0; count < work.length; count++){
              this.Search.work_matchings_infos[count] = this.employment.work_infos.value[count].id
            }
          }
        }else if(item == 3){
          var welfare = this.employment.welfare_infos.value//항목
          if (this.allwelfare == true) {
            this.Search.welfare_content_infos = []
          }
          else  {
            for(var count = 0; count < welfare.length; count++){
              this.Search.welfare_content_infos[count] = this.employment.welfare_infos.value[count].id
            }
          }
        }else if(item == 4){
          var desired = this.employment.desired_employment_infos.value//항목
          if (this.alldesired == true) {
            this.Search.desired_employments_infos = []
          }
          else  {
            for(var count = 0; count < desired.length; count++){
              this.Search.desired_employments_infos[count] = this.employment.desired_employment_infos.value[count].id
            }
          }
        }
      },


      go2companyInfo(item){
        var m = item.org_matchings_id;
        var c = item.org_company_id;
        var e = item.employment_id;
        var employment_decision_ox = item.employment_decision_ox;
        if(employment_decision_ox == 'o')
          this.$router.push({path : '/student/companyInfo', query:{ matching_id : m, company_id : c , employment_id : e }});
        else
          this.$router.push({path : '/student/companyInfo', query:{ matching_id : m, company_id : c}});
      },


      getEmploymentList(){
        let orgId = this.orgId;
        this.axios.post('/student/getEmploymentList',{orgId})
                  .then(rep=>{
                    this.companyList = null;
                    this.companyList = rep.data.companyList;
                  })
                  .catch(ex=>{
                    console.log(ex);
                  });
      },

      searchCompanyEmploymentList(){
        let orgId = this.orgId;
        let searchReq = this.Search;

        this.axios.post('/student/searchCompanyEmploymentList',{orgId, searchReq})
                  .then(rep=>{
                     this.companyList = rep.data;
                  })
                  .catch(ex=>{
                    console.log(ex);
                  });
      }

    }

}
</script>

<style scoped lang="css" src="../static/css/agent/agnetAndStudentStyleSheet.css"></style>
