<template>
  
 <!-- text-xs-center -->
  <v-container fluid>
    <!-- <v-layout row>
      <v-flex xs12 md12 lg12 >
        {{orgAgent.org_name}}({{orgAgent.org_name_kana}})
      </v-flex>
    </v-layout> -->

    <v-expansion-panel>
      <v-expansion-panel-content expand-icon="mdi-menu-down">
        <div slot="header" class="Titlefont">検索条件開く</div>
        <v-card flat>
          <v-card-text>
            <!-- 검색 조건 -->
                <v-layout> 
                  <v-flex xs4 md2 lg2 text-center id="tableTitle">
                    <div >
                      大陸／国家
                    </div>
                  </v-flex>
                  <v-flex xs10 md10 lg10  id="tableItem"  style="padding-left:13px">
                    <v-layout >
                      <v-flex xs4>
                        <v-select
                          :items="continents"
                          label="continents"
                          item-text="title"
                          item-value="key"
                          v-model="searchReq.continent_code"
                          single-line
                        ></v-select>
                      </v-flex>
                      <v-flex xs4>
                        <v-select 
                        :items="countryList[searchReq.continent_code]"
                        item-text="country_kana"
                        item-value="country_code"
                        v-model="searchReq.country_code"
                        label="country"
                        :disabled="searchReq.continent_code == '0'"

                        ></v-select>
                        
                      </v-flex>
                    </v-layout>
                  </v-flex>
                </v-layout>

                <v-layout>
                  <v-flex xs4 md2 lg2  text-center id="tableTitle">
                    <div style=" padding: 8px;">
                    大学分類
                    <input type="checkbox" align-left @click="Check_all(0)" v-model ="allCollegeCategory">
                    </div>
                  </v-flex>
                  <v-flex xs10 md10 lg10 id="tableItem">
                    <div style=" padding: 8px;">
                      <v-layout row>
                        <v-flex xs6 sm3 v-for="(check, Ckey) in checkBox.division" :key="Ckey">
                          <input type="checkbox" name="division" :value="check.value" v-model="searchReq.college_category"> <label>{{check.text}}</label> 
                        </v-flex>
                      </v-layout>
                    </div>
                  </v-flex>
                </v-layout>

                <v-layout>
                  <v-flex xs4 md2 lg2  text-center  align-center id="tableTitle">
                    <div style=" padding: 8px;">
                    卒業年数
                    <input type="checkbox" @click="Check_all(1)" v-model ="allCollegeCategorySub">
                    </div>
                  </v-flex>
                  <v-flex xs10 md10 lg10 id="tableItem">
                    <div style=" padding: 8px;">
                      <v-layout row>
                        <v-flex xs6 sm3 v-for="(check, Ckey) in checkBox.years" :key="Ckey">           
                          <input type="checkbox" name="years" :value="check.value" v-model="searchReq.college_category_sub"> 
                          <label>{{check.text}}</label>
                        </v-flex>
                      </v-layout>
                    </div>
                  </v-flex>
                </v-layout>

                <v-layout>
                  <v-flex xs4 md2 lg2  text-center id="tableTitle">
                    <div style=" padding-top: 8px;">
                    専攻
                    <input type="checkbox" @click="Check_all(2)" v-model ="allMajor">
                    </div>
                  </v-flex>
                  <v-flex xs10 md10 lg10 id="tableItem">

                    
                    <v-flex xs12> 
                      <v-flex xs12 style="padding-left: 7px">
                        <input id="itemtoggle" type="checkbox" checked  style="display: none; visibility: hidden; ">
                        <label for="itemtoggle" >
                          <!-- 더보기 -->
                        </label>
                        <v-layout row>
                          <v-flex xs6 sm3 v-for="(check, Ckey) in checkBox.major" :key="Ckey">
                            <p v-if="Ckey < 4">
                              <input type="checkbox" name="major_id" :value="check.id" v-model="searchReq.major_id"> <label>{{check.content}}</label>
                            </p>
                          </v-flex>
                        </v-layout>
                        
                        <v-flex xs12 id="expand">
                          <section>
                            <v-layout row> 
                              <v-flex xs6 sm3 v-for="(check, Ckey) in checkBox.major" :key="Ckey">
                                <p v-if="Ckey >= 4">
                                  <input type="checkbox" name="major_id" :value="check.id" v-model="searchReq.major_id"> <label>{{check.content}}</label>
                                </p>
                              </v-flex>
                            </v-layout>
                          </section>
                        </v-flex >
                      </v-flex>
                    </v-flex>

                    
                  </v-flex>
                </v-layout>

                <v-layout>
                  <v-flex xs4 md2 lg2  text-center id="tableTitle" style="border-bottom: 1px solid #BDBDBD;">
                    <div style=" padding-top: 8px;">
                    マッチング年度 
                    </div>
                  </v-flex>

                  <v-flex xs10 md10 lg10 id="tableItem" style="border-bottom: 1px solid #BDBDBD;">
                    <v-flex xs8 style=" padding-left: 8px; padding-right: 8px">
                      <v-select
                        :items="checkBox.yearList"
                        label="year"
                        item-text="label"
                        item-value="value"
                        v-model="searchReq.matching_date"
                      ></v-select>
                    </v-flex>
                  </v-flex>
                </v-layout>

                <v-layout row  style="float:right">
                  <v-btn color="success" @click="searchCollege"> <v-icon color="white" left>search</v-icon> 検索</v-btn>
                  <v-btn color="dahong" @click="initializeSearchReq" dark>検索条件戻す</v-btn>
                </v-layout>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
    </v-expansion-panel>
 

    <!-- 리스트 업 -->
    <v-layout row>
      <v-flex xs12>
        <v-card>
          <!-- <b-table striped hover :items="schoolListItems"></b-table> -->
          <b-table responsive :fields="headers" :items="schoolList" item-key="org_college_id" hover @row-clicked="go2collegeInfo">
            <!-- <template slot-scope="row" @click="alert('asdasdf');"> -->
              <template slot="img" slot-scope="data" height="60px">
                <p id="tdstyle"><img  :src="data.item.photo_url" style="height:55px; width:55px" ></p>
              </template>
              <template slot="alias" slot-scope="data">
                <p id="tdstyle">{{ data.item.college_alias }}</p>
              </template>
              <template slot="name" slot-scope="data" >
                <div id="tdstyle">
                  <p v-if="data.item.max_matching_year == null"></p>
                  <v-chip v-else-if="data.item.max_matching_year == thisYear" color="success" outline disabled label>
                    今年
                  </v-chip>
                  <v-chip v-else disabled outline label>
                    {{data.item.max_matching_year}}
                  </v-chip>
                  {{ data.item.org_name }}
                </div>
              </template>

              <template slot="country_eng" slot-scope="data">
                <p id="tdstyle">{{ data.item.country_code}}</p>
              </template>
              <template slot="address_id" slot-scope="data">
                <p id="tdstyle">{{ data.item.address_city}}</p>
              </template>
              <template slot="faculty_count" slot-scope="data">
                <p id="tdstyle">{{ data.item.faculty_count}}個</p>
              </template>
              <template slot="student_count" slot-scope="data">
                <p id="tdstyle">{{ data.item.student_count}}人</p>
              </template>
              <template slot="student_end_count" slot-scope="data">
                <p id="tdstyle">{{ data.item.student_end_count}}人</p>
              </template>
              <template slot="student_notend_count" slot-scope="data">
                <p id="tdstyle">{{Number(data.item.student_count) - Number(data.item.student_end_count)}}人</p>
              </template>
              <template slot="acceptance_count" slot-scope="data">
                <p id="tdstyle">{{ data.item.acceptance_count}}個</p>
              </template>
              <template slot="student_ok_count" slot-scope="data">
                <p id="tdstyle">{{ data.item.student_ok_count}}個</p>
              </template>
            <!-- </template> -->
          </b-table>
          <!-- <v-data-table
            :headers="headers1"
            :items="schoolList"
            :search="Search"
            text-center
          > 
            <template slot="items" slot-scope="props">
              <tr @click="go2collegeInfo(props.item.org_college_id)" height="60px">
                <td>
                  <img  :src="props.item.photo_url" style="height:55px; width:55px" >
                </td>
                <td id="tdstyle">{{ props.item.college_alias }}</td>
                
                <td class="text-xs-left" id="tdstyle">
                  <p v-if="props.item.max_matching_year == null"></p>
                  <v-chip v-else-if="props.item.max_matching_year == thisYear" color="success" outline disabled label>
                    今年
                  </v-chip>
                  <v-chip v-else disabled outline label>
                    {{props.item.max_matching_year}}
                  </v-chip>
                  {{ props.item.org_name }}
                </td>
                <td id="tdstyle">{{ props.item.country_code }}</td>
                <td id="tdstyle">{{ props.item.address_city }}</td>
                <td id="tdstyle">{{props.item.faculty_count}}個</td>
                <td id="tdstyle">{{props.item.student_count}}人</td>
                <td id="tdstyle">{{props.item.student_end_count}}人</td>
                <td id="tdstyle">{{Number(props.item.student_count) - Number(props.item.student_end_count)}}人</td>
                <td id="tdstyle">{{props.item.acceptance_count}}個</td>
                <td id="tdstyle">{{props.item.student_ok_count}}個</td>
                <td id="tdstyle"></td>
              </tr>
            </template>
            <template slot="no-data">
              <v-alert :value="true" color="error" icon="warning">
                There's no college list to show :(
              </v-alert>
            </template>
          </v-data-table> -->
          <v-card-actions>
            <v-btn
              id="main"
              large router block
              :to="{name : 'collegeregist', params : {orgAgent : orgAgent, mode : 'create'}}">
              新しい学校登録
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>

</template>

<script>
import ContinentList from "../../static/dataProvide/ContinentList.js"
import AgentCollegeInfo from "./AgentCollegeInfo"
export default {
  
  props : ['orgAgent','propsCountryList', 'propsMajorList'],
  
  components : {
    'agent-college-info' : AgentCollegeInfo,
  },

  created : function(){

    this.mode="wholeYear";

    //대륙에 전체 선택 넣기
    this.continents = [{key : 'ALL', title : '一括'}];
    this.continents = this.continents.concat(ContinentList);

    if(this.propsCountryList == null){
      //국가 코드
      this.axios.post('/continent/getCountries')
                .then(rep=>{
                    this.countryList = rep.data;
                })
                .catch(ex=>{
                    console.log(ex);
                });
    }else{
      this.countryList = this.propsCountryList;
    }


    //전공 카테고리
    if(this.propsMajorList == null){
      let tableList = ['major_infos'];
      this.axios.post('/item/listUp', {tableList})
                .then(rep=>{
                    this.checkBox.major = rep.data.major_infos;
                })
                .catch(ex=>{
                  console.log(ex);
                });
    }else{
      this.checkBox.major = this.propsMajorList;
    }

    //년도 리스트 업
    let year = new Date().getFullYear();
    this.thisYear = year;
    for(let i = year; i >= 2016; i--)
        this.checkBox.yearList.push({ label : i , value : i });

    this.checkBox.yearList.push({ label : '全年度' , value : 'ALL' });


    //학교 리스트 가져오기
    this.getSchoolList();

  },
  
  data : () => ({
    mode : null,
    thisYear : null,
    curr_college_id : null,
    isThisYearCollegeBool : false,
    college_info_modal : false,


    //검색 메뉴
    //국가
    countryList : {},
    continents : [],


    //학교 이름 검색
    Search : [],


    //DB로 보내는 검색 조건
    searchReq : {
      continent_code : "ALL",
      country_code : "ALL",
      college_category : [],
      college_category_sub : [],
      major_id : [],
      matching_date : 'ALL' 
    },

    pagination: {
      sortBy: 'name'
    },

    selected: [],
    

    headers1: [
      {text : '', value: 'img', },
      {text : 'alias', value: 'alias', align:'center'},
      {text : '学校名', value: 'name', align:'center'},
      {text : '国家', value: 'country_eng', align:'center'},
      {text : '都会', value: 'address_id', align:'center'},
      {text : '学科数', value : 'faculty_count', align:'center'},
      {text : '全体就活者数', value : 'student_count', align:'center'},
      {text : '内定者', value : 'student_end_count', align:'center'},
      {text : '無内定者', value : '', align:'center'},
      {text : '内定数', value : 'acceptance_count', align:'center'},
      {text : '内定承諾者数', value : 'student_ok_count', align:'center'},
    ],
    headers: [
      {label : '', key: 'img'},
      {label : 'alias', key: 'alias'},
      {label : '学校名', key: 'name'},
      {label : '国家', key: 'country_eng'},
      {label : '都会', key: 'address_id'},
      {label : '学科数', key : 'faculty_count'},
      {label : '全体就活者数', key : 'student_count'},
      {label : '内定者', key : 'student_end_count'},
      {label : '無内定者', key : 'student_notend_count'},
      {label : '内定数', key : 'acceptance_count'},
      {label : '内定承諾者数', key : 'student_ok_count'},
    ],
    

    schoolList : [],

    checkBox : {
    
      //대학 분류
      division : [
        {text : "一般大学", value : 'u'},
        {text : "専門大学", value : 'c'}
      ],

      //재학 년수
      years : [
        {text : "2年", value : 2},
        {text : "3年", value : 3},
        {text : "4年", value : 4},
        {text : "5年", value : 5},
      ],
      //년도
      yearList : [],

      //전공
      major : [],
    },

    allCollegeCategory: false,
    allCollegeCategorySub: false,
    allMajor: false,
  }),

  methods: {
    
    searchCollege(){

      let org_agent_id = this.orgAgent.org_agent_id;
      let searchReq = this.searchReq;
      if('ALL' == searchReq.matching_date)
        this.mode = 'wholeYear';
      else 
        this.mode = null;

      this.axios.post('/agent/searchCollege', {org_agent_id, searchReq})
                .then(rep=>{
                  this.schoolList = rep.data;
                })
                .catch(ex=>{
                  console.log(ex);
                })
    },

    initializeSearchReq(){
      this.mode = 'wholeYear';
      this.searchReq.continent_code = 'ALL';
      this.searchReq.country_code = 'ALL';
      this.searchReq.college_category = [];
      this.searchReq.matching_date = 'ALL';
      this.searchReq.college_category_sub = [];
      this.searchReq.major_id= [];

      this.allCollegeCategory = false;
      this.allCollegeCategorySub = false;
      this.allMajor = false,

      this.getSchoolList();
    },

    getSchoolList(){
      let org_agent_id = this.orgAgent.org_agent_id;
      this.axios.post('/agent/getOrgRelColInfo', {org_agent_id})
                  .then(rep=>{
                    this.schoolList = rep.data;
                  })
                  .catch(ex=>{
                      console.log(ex);
                  });
    },

    go2collegeInfo(item){
      let org_college_id = item.org_college_id;
      this.$router.push({
        path:'/agent/collegeInfo', 
        query:{college_id : org_college_id, agent_id : this.orgAgent.org_agent_id}
      });
    },

    Check_all(item){
      if(item == 0){
        
        //대학 분류 전체 체크
        var division = this.checkBox.division//항목
        if (this.allCollegeCategory == true) {
          this.searchReq.college_category = [];
        }
        else  {
          for(var count = 0; count < division.length; count++){
            this.searchReq.college_category[count] = this.checkBox.division[count].value;
          }
        }
        
      }else if(item == 1){
        //재학 년수 전체 체크
        var years = this.checkBox.years//항목
        // alert(division.length);
        if (this.allCollegeCategorySub == true) 
          {this.searchReq.college_category_sub = []
        }
        else  {
          for(var count = 0; count < years.length; count++){
            this.searchReq.college_category_sub[count] = this.checkBox.years[count].value
          }
        }
        this.CategorySubDisable = !this.CategorySubDisable

      }else {
        //전공 전체 체크
        var major = this.checkBox.major//항목
        // alert(division.length);
        if (this.allMajor == true) {
          this.searchReq.major_id = []
        }
        else  {
          for(var count = 0; count < major.length; count++){
            this.searchReq.major_id[count] = this.checkBox.major[count].id
          }
        }
        this.MajjorDisable = !this.MajjorDisable

      }
    }

  },

}
</script>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
