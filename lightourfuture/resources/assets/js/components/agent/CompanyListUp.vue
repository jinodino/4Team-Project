<template>
  <v-container fluid>
    <!-- <v-layout row>
      <v-flex xs12 md12 lg12 >
        {{orgAgent.org_name}}({{orgAgent.org_name_kana}})
      </v-flex>
    </v-layout> -->
    
    <v-layout wrap>
    <v-expansion-panel>
      <v-expansion-panel-content expand-icon="mdi-menu-down">
        <div slot="header" class="Titlefont">検索条件開く</div>
        <v-card>
          <v-container fluid>
      <!-- 정보 입력할때 선택식 항목들 출력 -->
      <v-flex xs12 md12 lg12 >
        <!-- 사업 대분류 -->
        <v-layout >
          <v-flex xs4 md2 lg2  text-center id="tableTitle">
            <div>
              事業大分類
              <!-- {{business.business_big_infos.title}} -->
            <input type="checkbox" align-left @click="Check_all(0)" v-model ="allbig">
            </div>
          </v-flex>
          <v-flex xs8 md10 lg10 id="tableItem" style="padding-left:10px">
            <div>
              <v-layout row>
                <v-flex xs6 sm2 v-for="(check, bigkey) in business.business_big_infos.value" :key="bigkey"> 
                  <input type="checkbox" :value="check.id" name="continents" v-model="big_infos"> {{check.content}}
                </v-flex>
              </v-layout>
            </div>
          </v-flex>
        </v-layout>
        

        <!-- 사업 소분류 -->
        <div style="margin-top:1px;margin-bottom:1px">
          <v-layout > 
            <v-flex xs4 md2 lg2  text-center id="tableTitle">
              <div style=" margin-top: 8px;">
                事業小分類
                <!-- {{business.business_small_infos.title}} -->
                <input type="checkbox" align-left @click="Check_all(1)" v-model ="allsmall">
              </div>
            </v-flex>
            <v-flex xs8 md10 lg10 id="tableItem" style="padding-left:10px">

              <input id="itemtoggle" type="checkbox" checked style="display: none; visibility: hidden; text-align:right; text-align:right">
                <label for="itemtoggle" style="align:right">
                  <!-- 더보기 -->
                </label>
              <v-layout wrap >
                <v-flex xs6 sm2 >
                  <input type="checkbox" :value="business.business_small_infos.value[0].id" name="continents" v-model="small_infos"> {{business.business_small_infos.value[0].content}}
                </v-flex>
                <v-flex xs6 sm2>
                  <input type="checkbox" :value="business.business_small_infos.value[1].id" name="continents" v-model="small_infos"> {{business.business_small_infos.value[1].content}}
                </v-flex>
                <v-flex xs6 sm2>
                  <input type="checkbox" :value="business.business_small_infos.value[2].id" name="continents" v-model="small_infos"> {{business.business_small_infos.value[2].content}}
                </v-flex>
                <v-flex xs6 sm2>
                  <input type="checkbox" :value="business.business_small_infos.value[3].id" name="continents" v-model="small_infos"> {{business.business_small_infos.value[3].content}}
                </v-flex>
                <v-flex xs6 sm2>
                  <input type="checkbox" :value="business.business_small_infos.value[4].id" name="continents" v-model="small_infos"> {{business.business_small_infos.value[4].content}}
                </v-flex>
                <v-flex xs6 sm2>
                  <input type="checkbox" :value="business.business_small_infos.value[5].id" name="continents" v-model="small_infos"> {{business.business_small_infos.value[5].content}}
                </v-flex>
              </v-layout>

              <v-flex xs12 id="expand">
                <section>
                  <v-layout row> 
                    <v-flex xs6 sm2 v-for="(check, smallkey) in business.business_small_infos.value" :key="smallkey"> 
                      <p v-if="smallkey > 5">
                        <input type="checkbox" :value="check.id" name="continents" v-model="small_infos"> {{check.content}}
                      </p>
                    </v-flex>
                  </v-layout>
                </section>
              </v-flex>
              
            </v-flex>
          </v-layout>
        </div>

        <!-- 사원수 -->
        <!-- slider로 바꿀부분 -->
        <v-layout wrap>
          <v-flex xs4 md2 lg2  text-center id="tableTitle">
            社員数
          </v-flex>

          <v-flex  xs8 md10 lg10 id="tableItem">
            <div style=" margin-left: 8px; margin-right: 8px; ">
              <v-flex xs8>
                <v-layout row>
                  <v-text-field 
                    type="number"
                    v-model="worker_count_infos[0]"
                    label="最小"
                    :min=1
                    :max=5000
                  ></v-text-field >
                  <div style="margin-right: 8px;"></div>
                  <v-text-field 
                    type="number"
                    v-model="worker_count_infos[1]"
                    label="最大"
                    :min=1
                    :max=30000
                  ></v-text-field >
                </v-layout>
              </v-flex>
            </div>
          </v-flex>

          <v-flex xs4 md2 lg2  text-center align-center id="tableTitle">
            事業所住所
          </v-flex>
          <v-flex  xs8 md10 lg10 id="tableItem">
            <div style=" margin-left: 8px; margin-right: 8px; margin-right: 8px">
              <v-flex xs8>
                <v-layout>
                  <v-select
                  :items="japanRegionsMenu"
                  label="地方"
                  item-text="name_kanji"
                  item-value="id"
                  v-model="curr_region_id"
                  single-line
                  ></v-select>
                  <div style="margin-right: 8px;"></div>
                  <v-select
                    :items="japanPrefecturesMenu[curr_region_id]"
                    item-text="name_kanji"
                    item-value="id"
                    v-model="curr_prefecture_id"
                    label="都道府県"
                    single-line
                  ></v-select>             
                </v-layout>
              </v-flex>
            </div>
          </v-flex>
          
          <v-flex xs4 md2 lg2  text-center align-center id="tableTitle" style="border-bottom: 1px solid #BDBDBD;">
            マッチング年度
          </v-flex>
          <v-flex  xs8 md10 lg10 id="tableItem" style="border-bottom: 1px solid #BDBDBD;">
            <div style=" margin-left: 8px; margin-right: 8px; margin-right: 8px">
              <v-flex xs8>
                <v-select
                  :items="matchingYearList"
                  label="year"
                  item-text="label"
                  item-value="value"
                  v-model="matching_date"
                ></v-select> 
              </v-flex>
            </div>
          </v-flex>
        </v-layout>
          
      </v-flex>

      <v-layout row style="float:right">
        <v-btn color="success" @click="searchCompany"> <v-icon color="white" left>search</v-icon>検索</v-btn>
        <v-btn color="dahong" @click="initializeSearchReq" dark>検索条件戻す</v-btn>
      </v-layout>
     
          </v-container>
        </v-card>
      </v-expansion-panel-content>
    </v-expansion-panel>
      <v-flex xs12 md12 lg12>
        <v-card>
          <!-- <v-card-title>
            <v-text-field
              v-model="Search"
              append-icon="search"
              label="検索"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title> -->

          <b-table responsive :fields="headers" :items="companyList" item-key="faculty_id" @row-clicked="go2CompanyInfo">
            <template slot="No" slot-scope="data">
              <p id="tdstyle">{{ ++ data.index }}</p>
            </template>
            <template slot="img" slot-scope="data">
              <p id="tdstyle"><img :src="data.item.photo_url" style="height:55px; width:55px" ></p>
            </template>
            <template slot="name" slot-scope="data">
              <p id="tdstyle">
                <v-chip v-if="data.item.max_matching_year == Year" label disabled outline color="success">今年</v-chip>
                <v-chip v-else label disabled outline>{{data.item.max_matching_year}}</v-chip>
                {{ data.item.org_name }}
              </p>
            </template>

            <template slot="area" slot-scope="data">
              <p id="tdstyle">{{data.item.prefecture == null ? '-' : data.item.prefecture}}</p>
            </template>
            <template slot="crape" slot-scope="data">
              <p id="tdstyle">
                <v-chip v-if="data.item.listed_company_ox='o'" label disabled outline color="primary">上場</v-chip>
                <v-chip v-else label disabled outline>非上場</v-chip>
              </p>
            </template>
            <template slot="accumulate" slot-scope="data">
              <p id="tdstyle">{{data.item.accept_student_count}}人</p>
            </template>
            <template slot="businessStart" slot-scope="data">
              <p id="tdstyle">{{data.item.min_matching_year}}</p>
            </template>
            
          </b-table>

          <!-- <v-data-table
            :headers="headers1"
            :items="companyList"
            :search="Search"
          >
            <template slot="items" slot-scope="props">
              <tr @click="go2CompanyInfo(data.item.org_company_id);">
                <td style="font-size: 18px; height: 60px; padding-top: 10px; text-align: center; vertical-align: middle;">{{ ++ props.index }}</td>
                <td id="tdstyle"><img :src="props.item.photo_url" style="height:55px; width:55px" ></td>
                <td id="tdstyle">
                  <v-chip v-if="props.item.max_matching_year == Year" label disabled outline color="success">今年</v-chip>
                  <v-chip v-else label disabled outline>{{props.item.max_matching_year}}</v-chip>
                  {{ props.item.org_name }}
                </td>
                
                <td id="tdstyle">{{props.item.prefecture == null ? '-' : props.item.prefecture}}</td>
                <td id="tdstyle">
                  <v-chip v-if="props.item.listed_company_ox='o'" label disabled outline color="primary">상장</v-chip>
                  <v-chip v-else label disabled outline>비상장</v-chip>
                </td>
                <td id="tdstyle">{{props.item.accept_student_count}}명</td>
                <td id="tdstyle">{{props.item.min_matching_year}}</td>
              </tr>
            </template>
            <template slot="no-data">
              <v-alert :value="true" color="error" icon="warning">
                There's no company list to show :(
              </v-alert>
            </template>
          </v-data-table> -->
          <v-card-actions>
            <v-layout row wrap>
              <v-flex >
                <v-btn   
                  color="success"
                  large
                  block
                  router 
                  :to="{name : 'companyregist', params : {orgAgent : orgAgent, mode : 'create'}}">
                  新しい企業登録
                </v-btn>  
              </v-flex>
              <v-flex>
                <v-btn   
                  color="primary"
                  large
                  block
                  @click="inviteDialog = true"
                  >
                  企業会員招待
                </v-btn>  
              </v-flex>
            </v-layout>
          </v-card-actions>
        </v-card>
      </v-flex>
      <v-flex xs12>
      </v-flex>
    </v-layout>

    <!-- 기업 정보 모달 -->
    <!-- <v-dialog v-model="company_info_modal" scrollable>
      <v-card>japanRegionsMenu
        <v-card-title class="grey lighten-4 py-4 title">기업 정보
          <v-spacer></v-spacer>
          <v-btn color="error" large @click="company_info_modal = false">X</v-btn>
        </v-card-title>
        
        <v-card-text v-if="curr_company_id != null">
          <agent-company-info :orgCompanyId="curr_company_id" :orgAgentId="orgAgent.org_agent_id" :modalBool="company_info_modal"></agent-company-info>
        </v-card-text>
        <v-card-actions></v-card-actions>
      </v-card>
    </v-dialog> -->
    <v-dialog v-model="inviteDialog" width="800">
      <v-card>
        <v-card-title class="Titlefont">
          企業会員招待
          <v-spacer></v-spacer>
          <v-btn   id="red" @click="inviteDialog = false">X</v-btn>
        </v-card-title>
        <v-card-text>
          <v-container fluid grid-list-sm>
            <v-layout row justify-space-between>
              <v-flex xs3>
                <v-select
                label="企業選択"
                :items="companyList"
                item-text="org_name"
                item-value="org_company_id"
                v-model="addressItem.org_id"
                >
                </v-select>
              </v-flex>
              <v-flex xs3>
                <v-text-field
                label="名前"
                v-model="addressItem.name"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs3>
                <v-text-field
                label="メールアドレス"
                v-model="addressItem.email"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs2>
                <v-btn color="success" @click="createAddress()">企業会員登録</v-btn>
              </v-flex>
            </v-layout>
            <v-layout row>
              <v-flex xs12>
                <!-- <v-data-table
                  :headers="bookHeaders"
                  :items="registCompanysave"
                  :search="Search"
                  text-center
                >
                  <template slot="items" slot-scope="props">
                    <tr >
                      <td class="tdstyle">
                        <v-checkbox
                          :value="props.item"
                          v-model="send"
                        ></v-checkbox>
                      </td>
                      <td class="tdstyle">{{ props.item.no}}</td>
                      <td class="tdstyle">{{ props.item.name}}</td>
                      <td class="tdstyle">{{ props.item.email}}</td>
                      <td class="tdstyle"><v-btn icon @click="deleteAddress(props.item.no)"><v-icon color="error" large >delete_forever</v-icon></v-btn></td>
                    </tr>
                  </template>
                </v-data-table> -->
                <b-table responsive :fields="bookHeaders" :items="registCompanysave" item-key="faculty_id">
                  <template slot="checkbox" slot-scope="data">
                    <p id="tdstyle">
                      <v-checkbox
                        :value="data.item"
                        v-model="send"
                      ></v-checkbox>
                    </p>
                  </template>
                  <template slot="companyname" slot-scope="data">
                    <p id="tdstyle">{{ data.item.no}}</p>
                  </template>
                  <template slot="name" slot-scope="data">
                    <p id="tdstyle">{{ data.item.name}}</p>
                  </template>

                  <template slot="address" slot-scope="data">
                    <p id="tdstyle">{{ data.item.email}}</p>
                  </template>
                  <template slot="del" slot-scope="data">
                    <p><v-btn icon @click="deleteAddress(data.item.no)"><v-icon color="error" large >delete_forever</v-icon></v-btn></p>
                  </template>
                </b-table>
              </v-flex>
              <div>
                <v-btn   id="green" @click="inviteUser(send, 'c', orgAgent.org_agent_id)">招待メールを送る（企業）</v-btn>
              </div>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import vueSlider from 'vue-slider-component';

export default {
    props : ['orgAgent', 'japanRegionsMenu', 'japanPrefecturesMenu','business', 'user'],
    components: {
      vueSlider
    },
    created :  function (){

      //년도 리스트 업
      let year = new Date().getFullYear();
      this.Year = year;
      for(let i = year; i >= 2016; i--)
          this.matchingYearList.push({ label : i , value : i });

      this.matchingYearList.push({ label : '全年度' , value : 'ALL' });

      //기업 리스트 가져오기
      this.getCompanyList();

      //기업 초대 리스트 가져오기
      this.getAgentBookCompanyList();

      
    },

  data : () => ({

    //현재 년도
    thisYear : null,
    //기업 회원 초대
    inviteDialog : false,
    agentBookCompanyList : [],
    addressItem : {
      name : null,
      email : null,
      org_id : null,
    },
    registCompanysave:[],
    send:[],
    inviteHeaders : [
      {text : ''},
      {text : 'name'},
      {text : 'email'},
    ],

    curr_company_id : null,
    company_info_modal : false,

    //기업 리스트
    companyList : [],
    Search : [],
    pagination: {
      sortBy: 'name'
    },

    //올해 년도
    Year : null,
    //매칭 년도 리스트
    matchingYearList : [],

    selected: [],
    //테이블
    headers1: [
      {text : 'No', value: 'No', align:'center'},
      {text : '', value: '', sortable: false},
      {text : '企業名', value: 'name'},
      {text : '地域', value: 'area', align:'center'},
      {text : '上場可否', value: 'crape', align:'center'},
      {text : '累積採用人数', value: 'accumulate', align:'center'},
      {text : '最初営業年度', value: 'businessStart', align:'center'},
    ],
    headers: [
      {label : 'No', key: 'No'},
      {label : '', key: 'img'},
      {label : '企業名', key: 'name'},
      {label : '地域', key: 'area'},
      {label : '上場可否', key: 'crape'},
      {label : '累積採用人数', key: 'accumulate'},
      {label : '最初営業年度', key: 'businessStart'},
    ],

    registCompany : {
      company_id:null,
      name : null,
      email: null,
    },

    // bookHeaders : [
    //   {text : 'check', value: 'checkbox'},
    //   {text : '企業', value: 'companyname'},
    //   {text : '名前', value: 'name'},
    //   {text : 'メールアドレス', value: 'address'},
    //   {text : '', value: 'del'},
    // ],
    bookHeaders : [
      {label : 'check', key: 'checkbox'},
      {label : '企業', key: 'companyname'},
      {label : '名前', key: 'name'},
      {label : 'メールアドレス', key: 'address'},
      {label : '', key: 'del'},
    ],

    Search_item : { //검색 출력 체크 박스 항목 
      // company_itme : [
      //   {text : "사업 대분류", id:'big'},
      //   {text : "사업 소분류", id:'small'},
      //   {text : "자본금", id:'capital'},
      //   {text : "주소", id:'address'},
      //   {text : "사원 수", id:'worker_count'},
      // ],
      // dmployment_itme : [
      //   {text : "연봉", id:'pay'},
      //   {text : "인재상", id:'talent'},
      //   {text : "복리후생", id:'benefits'},
      //   {text : "근무지", id:'work_place'},
      //   {text : "업무내용", id:'business'},
      //   {text : "면접 진행 여부", id:'interview'},
      // ],
      // recruit : [
      //   {text : "모집 중", id:'midway'},
      //   {text : "완료", id:'complete'},
      //   {text : "전부", id:'all'},
      // ],
      // imminent : [ //면접 임박
      //   {text : "오름차순", id : 'ASC'},
      //   {text : "내림차순", id : 'DESC'}
      // ],
      // deadline : [ // 지원 마감
      //   {text : "오름차순", id : 'ASC'},
      //   {text : "내림차순", id : 'DESC'}
      // ],
    },

    // imminent : 'default',//면접 임박 내림차순
    // deadline : 'default',//지원 마감 내림차순
    // company_check : [],//기업 정보 체크
    // dmployment_check : [],//채용 정보 채크
    // recruit: [],// 모집 정보 체크

    //검색 할 항목 체크
    curr_prefecture_id : 'ALL',
    curr_region_id : 'ALL',
    big_infos : [],
    small_infos : [],
    worker_count_infos : [1, 30000],
    matching_date : 'ALL',


    checkBox : {
      //면접 진행 여부
      interview : [
        {id : 1, text : "募集中"},
        {id : 2, text : "面接進行中"},
        {id : 3, text : "完了"}
      ], 
      
      //자본금
      capital : [
        {id : 1, text : "~400"},
        {id : 2, text : "400~800"},
        {id : 3, text : "800~1000"},
        {id : 4, text : "1000~"}
      ],
     
    },
    //전체 체크
     allbig : false,
     allsmall : false,

//기업 검색 항목
    // business :{     
    //   business_big_infos : {name : 'business_big_infos', title : '사업 대분류', value : null},
    //   business_small_infos : {name : 'business_small_infos', title : '사업 소분류', value : null},
    // },

//채용 정보 검색 항목         
    // employment : {
    //   work_infos : {name : 'work_infos', title : '업무 내용', value : null},
    //   welfare_infos : {name : 'welfare_infos', title : '복리후생', value : null},
    //   desired_employment_infos : {name : 'desired_employment_infos', title : '원하는 인재상', value : null},
    // },

//일본 행정구역 항목
    // japanRegionsMenu : [],
    // japanPrefecturesMenu : [],

  }),

  methods: {

    getCompanyList(){
      let org_agent_id = this.orgAgent.org_agent_id;
      this.axios.post('/agent/getOrgRelComInfo', {org_agent_id})
                  .then(rep=>{
                    this.companyList = rep.data;
                  })
                  .catch(ex=>{
                      console.log(ex);
                  });
    },

    getAgentBookCompanyList(){
      let org_agent_id = this.orgAgent.org_agent_id;
      this.axios.post('/agent/getAgentBookCompanyList', {org_agent_id})
                .then(rep=>{
                  this.registCompanysave = rep.data.companyBookInfo;
                })
                .catch(ex=>{
                    console.log(ex);
                });
    },

    removeTag(pill, tableName){
      if(tableName == 'business_small_infos'){
        let index = this.small_infos.indexOf(pill);
        this.small_infos.splice(index, 1);
      }else if(tableName == 'business_big_infos'){
        let index = this.big_infos.indexOf(pill);
        this.big_infos.splice(index, 1);
      }
      
      return;
    },

    go2CompanyInfo(item){
      let org_company_id = item.org_company_id;
      
      this.$router.push({
                      path:'/agent/companyInfo',
                      query : { company_id : org_company_id, agent_id : this.orgAgent.org_agent_id}
                    });
    },

    initializeSearchReq(){
      this.curr_region_id = 'ALL';
      this.curr_prefecture_id = 'ALL';
      this.matching_date = 'ALL';
      this.big_infos = [];
      this.small_infos = [];

      this.allbig = false,
      this.allsmall = false,

      this.getCompanyList();
    },

    Check_all(item){
      if(item == 0){
        
        //대학 분류 전체 체크
        //alert(this.business.business_big_infos.value[0].content)
        var big = this.business.business_big_infos.value//항목
        if (this.allbig == true) {
          this.big_infos = []
        }
        else  {
          for(var count = 0; count < big.length; count++){
            this.big_infos[count] = this.business.business_big_infos.value[count].id
          }
        }
        
      }else{
        var small = this.business.business_small_infos.value//항목
        if (this.allsmall == true) {
          this.small_infos = []
        }
        else  {
          for(var count = 0; count < small.length; count++){
            this.small_infos[count] = this.business.business_small_infos.value[count].id
          }
        }
      }
    },

    //기업 검색
    searchCompany(){
      let org_agent_id = this.orgAgent.org_agent_id;
      let searchReq = {};

      searchReq.big_infos = this.big_infos;
      searchReq.small_infos = this.small_infos;
      searchReq.worker_count_infos = this.worker_count_infos;
      searchReq.region_id = this.curr_region_id;
      searchReq.prefecture_id = this.curr_prefecture_id;
      searchReq.matching_date = this.matching_date;

      this.axios.post('/agent/searchCompany', {org_agent_id, searchReq})
                .then(rep=>{
                    this.companyList = rep.data;
                })
                .catch(ex=>{
                  console.log(ex);
                })
    },
    //이메일 유효성 검사
    email_check( email ) {    
        var regex=/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        let returnBool =  regex.test(email); 
        return returnBool;
    },
    //기업 주소록 등록
    createAddress (){

      let yesNo = confirm('登録しますか？');
      
      if(!yesNo){
        alert('取り消ししまいた。');
        return;
      }
      if(!this.email_check(this.addressItem.email)){
        alert('間違ったメールアドレスです。');
        return;
      }

      let addressItem = this.addressItem;
      addressItem.org_agent_id = this.orgAgent.org_agent_id;
      let type = 'c';
      
      this.axios.post('/agent/createAddress', {addressItem, type})
                .then(rep=>{
                  if(rep.data.returnBool){
                    alert('登録されました。');
                    this.registCompanysave = rep.data.companyBookInfo;
                    this.send = [];
                  }else {
                    alert('もう登録されています。');
                  }

                  this.addressItem.email = null;
                  this.addressItem.name = null;
                })
                .catch(ex=>{
                  console.log(ex);
                });
    },

     //기업 주소록 삭제
    deleteAddress(no){
        let yeaNo = confirm('削除しますか？');
        if(!yeaNo){
          alert('取り消ししまいた。');
          return;
        }

        let classification = this.user.classification;
        let org_agent_id = this.orgAgent.org_agent_id;
        let type = 'c'

        this.axios.post('/agent/deleteAddress', {classification, org_agent_id, no, type})
                  .then(rep=>{
                    if(rep.data.returnBool){
                      alert('削除されました。');
                      this.registCompanysave = rep.data.companyBookInfo;
                      this.send = [];
                    }else {
                      alert('削除できません。');
                    }
                  })
                  .catch(ex=>{
                    console.log(ex);
                  });
    },



    //메일 발송 하기
    inviteUser(params, type, org_agent_id){

      if(params.length == 0){
        alert('会員を選択してください。');
        return ; 
      }
       
      let url = "/MailSned";
      let info = { info : params, type : type, org_agent_id : org_agent_id};
      this.axios.post(url, info).then((res) => {
              //this.result = response.data;
              if(!res.data) return;
              this.send = [];
              alert('送りました。');
          })
          .catch(ex=>{
            console.log(ex);
          });
    },

    // toggleAll () {
    //   if (this.selected.length) this.selected = []
    //   else this.selected = this.items.slice()
    // },

    // changeSort (column) {
    //   if (this.pagination.sortBy === column) {
    //     this.pagination.descending = !this.pagination.descending
    //   } else {
    //     this.pagination.sortBy = column
    //     this.pagination.descending = false
    //   }
    // }
  }
}
</script>



<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
<style>

</style>