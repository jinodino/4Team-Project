<template>
  
 <!-- text-xs-center -->
  <v-container fluid section>
    <v-layout row>
     
      <!-- 정보 입력할때 선택식 항목들 출력 -->
      
    <!-- <v-flex xs12 sm12 lg12>
        <v-card>
          <v-card-title class="grey lighten-4 py-4 title">
            학교 검색
          </v-card-title>
          <v-layout>
            <v-flex xs4 sm4>
              <v-flex xs12 sm12 lg12 >
                대륙
              </v-flex>
              <v-flex 
                id="scroll-target"
                style="max-height: 300px; "
                class="scroll-y" 
              >
                <v-flex v-for="(check, bigkey) in continents" :key="bigkey"> 
                  <input type="checkbox" :value="check.key" name="continents" v-model="searchReq.continent_code"> {{check.title}}
                  <v-checkbox :label = "check.title" :value = "check.key" hide-details v-model="searchReq.continent_code"></v-checkbox>
                </v-flex>
              </v-flex>
            </v-flex>
            <v-flex xs4 sm4>
              <v-flex xs12 sm12 lg12 >
                나라
              </v-flex>
              <v-flex 
                id="scroll-target"
                style="max-height: 300px; "
                class="scroll-y" 
                v-for="(continentcheck, continentkey) in searchReq.continent_code" :key="continentkey"
              >
                <v-flex v-for="(check, countrykey) in countryList[continentcheck]" :key="countrykey"> 
                  <input type="checkbox" :value="check.country_code" name="continents" v-model="searchReq.country_code"> {{check.country_kana}}
                </v-flex>
              </v-flex>
              <v-flex v-if="searchReq.continent_code.length == 0"> 
                대륙을 선택해 주세요
              </v-flex>
            </v-flex>

            <v-flex xs4 sm4>
              <v-flex xs12 sm12 lg12 >
                대학 분류
              </v-flex>
              <v-flex 
                id="scroll-target"
                style="max-height: 300px; "
                class="scroll-y" 
              >
                <v-flex  v-for="(check, divkey) in checkBox.division" :key="divkey"> 
                
                  <input type="checkbox" name="division" :value="check.value" v-model="searchReq.college_category"> {{check.text}} 
                </v-flex>
              </v-flex>
            </v-flex>
          </v-layout>
        </v-card>
    </v-flex> -->
    

    <v-card-text> 
        
      <v-flex xs12 md12 lg12>
        
        <v-layout>
          <v-flex xs4 md2 lg2 border text-center  style ="background : #B9F6CA">
            <v-card-text>
              대륙/국가
            </v-card-text>
          </v-flex>
          <v-flex xs10 md10 lg10 border style ="background : #ffffff">
            <v-layout row>
              <v-flex xs2>              
                <v-select
                  :items="continents"
                  label="continents"
                  item-text="title"
                  item-value="key"
                  v-model="searchReq.continent_code" 
                ></v-select>
              </v-flex>
              <v-flex xs3>
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
          <v-flex xs4 md2 lg2 border text-center  style ="background : #B9F6CA">
            <v-card-text>
            대학 분류 
            <input type="checkbox" align-left @click="Check_all(0)" v-model ="allCollegeCategory">
            </v-card-text>
          </v-flex>
          <v-flex xs10 md10 lg10 border style ="background : #ffffff">
            <v-card-text>
              <v-layout row>
                <v-flex xs3 v-for="(check, Ckey) in checkBox.division" :key="Ckey">
                  <input type="checkbox" name="division" :value="check.value" v-model="searchReq.college_category"> {{check.text}} 
                </v-flex>
              </v-layout>
            </v-card-text>
          </v-flex>
        </v-layout>

      </v-flex>
      <v-flex xs12 md12 lg12 >
        <v-layout>
          <v-flex xs4 md2 lg2 border text-center  style ="background : #B9F6CA" align-center>
            <v-card-text>
            재학 년수
            <input type="checkbox" @click="Check_all(1)" v-model ="allCollegeCategorySub">
            </v-card-text>
          </v-flex>
          <v-flex xs10 md10 lg10 border style ="background : #ffffff">
            <v-card-text>
              <v-layout row>
                <v-flex xs3 v-for="(check, Ckey) in checkBox.years" :key="Ckey">           
                  <input type="checkbox" name="years" :value="check.value" v-model="searchReq.college_category_sub"> {{check.text}}
                </v-flex>
              </v-layout>
            </v-card-text>
          </v-flex>
        </v-layout>

        <v-layout>
          <v-flex xs4 md2 lg2 border text-center  style ="background : #B9F6CA">
            <v-card-text>
            전공 
            <input type="checkbox" @click="Check_all(2)" v-model ="allMajor">
            </v-card-text>
          </v-flex>
          <v-flex xs10 md10 lg10 border style ="background : #ffffff">
            <v-card-text>
              <v-layout row>
                <v-flex xs3 v-for="(check, Ckey) in checkBox.major" :key="Ckey">
                  <input type="checkbox" name="major_id" :value="check.id" v-model="searchReq.major_id"> {{check.content}}
                </v-flex>
              </v-layout>
            </v-card-text>
          </v-flex>
        </v-layout>

        <v-layout>
          <v-flex xs4 md2 lg2 border text-center  style ="background : #B9F6CA">
            <v-card-text>
            매칭 년도 
            </v-card-text>
          </v-flex>
          <v-flex xs10 md10 lg10 border style ="background : #ffffff">
            <v-card-text>
              <v-select
                :items="checkBox.matching_date"
                label="year"
                item-text="label"
                item-value="value"
                v-model="searchReq.matching_date"
              ></v-select>
            </v-card-text>
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-btn color="success" @click="searchCollege"> <v-icon left>search</v-icon> 검색하기</v-btn>
          <v-btn color="warning" @click="initializeSearchReq" >검색 조건 초기화</v-btn>
        </v-layout>

      </v-flex>
    </v-card-text>
    <v-layout row>
      <v-flex xs12>
      <v-card>
      <v-data-table
            :headers="headers"
            :items="schoolList"
            :search="Search"
            text-center
          > 
            <template slot="items" slot-scope="props">
              <tr @click="go2collegeInfo(props.item.org_college_id)">
              <!-- <tr @click="curr_college_id = props.item.org_college_id; college_info_modal = true;"> -->
                <td>{{ props.item.college_alias }}</td>
                <td class="text-xs-left">{{ props.item.org_name }}</td>
                <td class="text-xs-left">{{ props.item.org_name_kana }}</td>
                <td>{{ props.item.country_code }}</td>
                <!-- <td class="text-xs-right">{{ props.item.matching_date }}</td> -->
              </tr>
            </template>
            <template slot="no-data">
              <v-alert :value="true" color="error" icon="warning">
                There's no college list to show :(
              </v-alert>
            </template>
          </v-data-table>
      </v-card>
      </v-flex>
    </v-layout>
      <!-- 학교 리스트 -->
      <v-flex xs12 md12 lg12>
        <v-card>
          <!-- <v-card-title>
            <v-btn 
              color="primary" large router 
              :to="{name : 'collegeregist', params : {orgAgent : orgAgent, mode : 'create'}}">
              학교 등록
            </v-btn>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="Search"
              append-icon="search"
              label="검색"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title> -->
         
        </v-card>
      </v-flex>
      <label>
            AutoComplete
            <gmap-autocomplete @place_changed="setPlace">

            </gmap-autocomplete>
            <button @click="usePlace">Add</button>
          </label>
          <br/>
      <!-- "{lat: this.lat, lng: this.lng}" -->
          <GmapMap style="width: 600px; height: 300px;" :zoom=6 :center="{lat: 35.8963091, lng :128.62205110000002}">
            <GmapMarker v-for="(marker, index) in markers"
              :key="index"
              :position="marker.position"
              />
            <GmapMarker
              v-if="this.place"
              label="★"
              :position="{
                lat: this.place.geometry.location.lat(),
                lng: this.place.geometry.location.lng(),
              }"
              />
          </GmapMap>


    </v-layout>
    
    <!-- 학교 정보 모달 -->
    <!-- <v-dialog v-model="college_info_modal" scrollable>
      <v-card>
        <v-card-title class="grey lighten-4 py-4 title">학교 정보
          <v-spacer></v-spacer>
          <v-btn color="error" large @click="college_info_modal = false">X</v-btn>
        </v-card-title>
        
        <v-card-text v-if="curr_college_id != null">
          <agent-college-info :orgCollegeId="curr_college_id" :orgAgentId="orgAgent.org_agent_id" :modalBool="college_info_modal"></agent-college-info>
        </v-card-text>
        <v-card-actions></v-card-actions>
      </v-card>
    </v-dialog> -->
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

    //대륙에 전체 선택 넣기
    this.continents = [{key : 'ALL', title : '一括'}];
    this.continents = this.continents.concat(ContinentList);

    let countryList = null;
    if(this.propsCountryList == null){
      //국가 코드
      this.axios.post('/continent/getCountries')
                .then(rep=>{
                    countryList = rep.data;
                })
                .catch(ex=>{
                    console.log(ex);
                });
    }else{
      countryList = this.propsCountryList;
    }
    
    for(let index in countryList){
      let array = [{country_code : 'ALL', country_kana : '一括', country_eng : 'All'}];
      this.countryList[index] = array.concat(countryList[index]);
    }

    this.countryList.ALL = [{country_code : 'ALL', country_kana : '一括', country_eng : 'All'}];


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

    let year = new Date().getFullYear();
    //년도 리스트 업
    for(let i = year; i >= 2016; i--)
        this.checkBox.matching_date.push({ label : i , value : i });

    this.checkBox.matching_date.push({ label : '全年度' , value : 'ALL' });


    //학교 리스트 가져오기
    this.getSchoolList();



  },
  
  data : () => ({

    markers : [],
    place   : null,
    
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
      continent_code_bool : false,
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
    
    //테이블
    headers: [
      {text : '별칭', value: 'alias', width : '10%'},
      {text : '이름', value: 'name', width : '30%'},
      {text : '카나 이름', value: 'name_kana', width : '30%'},
      {text : '국가', value: 'country', width : '10%'},
      //{text : '매칭 년도', value: 'matching_date', width : '20%'},
      //{text : '분류', value: 'division'} 
      //도시, 분류 schoollist 값에 없음
    ],

    schoolList : [],


    checkBox : {
    
      //대학 분류
      division : [
        {text : "일반대", value : 'u'},
        {text : "전문대", value : 'c'}
      ],

      //재학 년수
      years : [
        {text : "2년제", value : 2},
        {text : "3년제", value : 3},
        {text : "4년제", value : 4},
        {text : "5년제", value : 5},
      ],
      //년도
      matching_date : [],

      //전공
      major : [],
    },

    allCollegeCategory: false,
    allCollegeCategorySub: false,
    allMajor: false,
  }),

  methods: {
    // 어디 호출 되는지 잘 모르겠음 -> 추후 완성 후 안쓸 떄 삭제하겠음
    setDescription(description) {
      this.description = description;
      console.log('언제 호출 될까요?');
    },

    // 주소창에서 자동완성 기능으로 주소를 넣을 때 호출
    setPlace(place) {
      this.place = place;
      
      // 만약 마커가 있을 시 전에 선택한 주소 마커 삭제
      this.markers.splice(0, 1)
      // this.lat = this.place.geometry.location.lat();
      // this.lng = this.place.geometry.location.lng();
      // this.aa  = place;
      // this.zoom = 15;
        
    },

    // ADD 버튼을 눌렀을 시 호출
    usePlace(place) {
      
      // let pos = new google.maps.LatLng(this.place.geometry.location.lat(), this.place.geometry.location.lng());
      // this.aa = pos;
      // 전에 있떤 마커 삭제 후 원형 마커 추가
      this.markers.splice(0, 1)
      if (this.place) {
        this.markers.push({
          position: {
            lat: this.place.geometry.location.lat(),
            lng: this.place.geometry.location.lng(),
          }
        })

        // ADD 눌러서 좌표 디비로 넣을 때 -> date에 정의되어있는 lat, lng변수에가 값을 넣어줘야함
        // -> 추후 학교 생성 버튼 누르면 좌표값도 넘어가고 학교 insert 해줄때 같이 들어감
        let uri = '/map';
        let loacation = {
            lat : this.place.geometry.location.lat(),
            lng : this.place.geometry.location.lng(),
        }
        this.axios.post(uri, loacation).then((res) => {
            
          
        });
      }
    },

    searchCollege(){
      let org_agent_id = this.orgAgent.org_agent_id;
      let searchReq = this.searchReq;

      this.axios.post('/agent/searchCollege', {org_agent_id, searchReq})
                .then(rep=>{
                  this.schoolList = rep.data;
                })
                .catch(ex=>{
                  console.log(ex);
                })
    },

    initializeSearchReq(){
      this.searchReq.continent_code = 'ALL';
      this.searchReq.continent_code = 'ALL';
      this.searchReq.college_category = [];
      this.searchReq.matching_date = 'ALL';
      this.searchReq.college_category_sub = [];
      this.searchReq.major_id= [];

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

    go2collegeInfo(org_college_id){
      this.$router.push({
        path:'/agent/collegeInfo', 
        query:{org_college_id : org_college_id, org_agent_id : this.orgAgent.org_agent_id}
      });
    },

    Check_all(item){
      if(item == 0){
        
        //대학 분류 전체 체크
        var division = this.checkBox.division//항목
        if (this.searchReq.college_category.length) {
          this.searchReq.college_category = []
        }
        else  {
          for(var count = 0; count < division.length; count++){
            this.searchReq.college_category[count] = this.checkBox.division[count].value
          }
        }
      }else if(item == 1){
        //재학 년수 전체 체크
        var years = this.checkBox.years//항목
        // alert(division.length);
        if (this.searchReq.college_category_sub.length) 
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
        if (this.searchReq.major_id.length) {
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

  }
}
</script>

<style>
  .section{
    margin-bottom: 1rem;
    background-color:rgb(223, 255, 223);    
   }
</style>
