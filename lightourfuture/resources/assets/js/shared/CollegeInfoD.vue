<template>
    <v-container  v-if="info != null" fluid grid-list-xs>
        <v-flex xs12 md12 lg12 text-center id="mainbar">
            企業情報
        </v-flex>
        <v-flex>
            <v-layout row>
            <!-- :src="info.org_companies.photo_url"  -->
                <v-flex xs12 md3 lg3 color="white">
                    <v-card>
                        <v-card-media
                            contain
                            height="219px"
                            :src="info.collegeInfo.photo_url"
                        ></v-card-media>
                    </v-card>
                </v-flex>

                <v-flex xs12 md5 lg5  >
                    <v-card-text>
                        <v-layout>
                            <v-flex xs3 md3 lg3 >
                                
                                    <b> ● 学校名</b>
                                
                            </v-flex>
                            <v-flex > 
                                : {{info.collegeInfo.org_name + "(" + info.collegeInfo.org_name_kana + ")"}}
                            </v-flex>
                        </v-layout>
                        <v-layout>
                            <v-flex xs3 md3 lg3 >
                                <b> ● 国家</b>
                            </v-flex>
                            <v-flex > 
                                : {{info.collegeInfo.country_kana}}
                            </v-flex>
                        </v-layout>
                        <v-layout>
                            <v-flex xs3 md3 lg3>
                                <b> ● 大学分類</b> 
                            </v-flex>
                            <v-flex> : 
                                {{info.collegeInfo.college_category === 'c' ? "専門大学" : "一般大学"}}
                            </v-flex>
                        </v-layout>
                        <v-layout>
                            <v-flex xs3 md3 lg3 >
                                <b> ● 都市</b>
                            </v-flex>
                            <v-flex > 
                                : {{info.collegeInfo.address_city}}
                            </v-flex>
                        </v-layout>


                        <v-layout>
                            <v-flex xs3 md3 lg3 >
                                <b> ● 住所</b> 
                            </v-flex>
                            <v-flex> : 
                                {{info.collegeInfo.address}}
                            </v-flex>
                        </v-layout>
                        <v-layout>
                            <v-flex xs3 md3 lg3 >
                                <b> ● ホームページ</b> 
                            </v-flex>
                            <v-flex> :  
                                <a :href="info.collegeInfo.homepage_url">{{info.collegeInfo.homepage_url !== null ? info.collegeInfo.homepage_url : "─"}}</a>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-flex>
                <v-flex xs12 md4 lg4>
                <!-- 지도 시작 -->
                <v-card style="height: 220px;">
                <GmapMap style="height: 220px;" :zoom=6 :center="{lat: 36.48, lng :127.62205110000002}">
                <GmapMarker v-for="(marker, index) in markers"
                :key="index"
                :position="marker.position"
                />
                </GmapMap>
                </v-card>
                <!-- 지도 끝 -->
                </v-flex>
            </v-layout>
        <!-- {{info.collegeInfo}} -->
        </v-flex>
        <v-flex xs12 md12 lg12 text-center id="mainbar">
            就活情報
        </v-flex>
        <v-layout row wrap>
            <v-flex xs8 md10>
            </v-flex>
            <v-flex xs4 md2>
                <v-select
                    :items="yearList"
                    label="年度選択"
                    item-text="value"
                    item-value="value"
                    v-model="searchYear"
                ></v-select>
            </v-flex>
        </v-layout>
        <b-table responsive :fields="headers" :filter="searchYear" :items="info.facultyInfo" item-key="faculty_id" >
            <template slot="num" slot-scope="data">
                <p id="tdstyle">{{++data.index}}</p>
            </template>
            <template slot="department_name" slot-scope="data">
                <p  id="tdstyle" style="width:130px">{{data.item.department_name === null ? "─" : data.item.department_name}}</p>
            </template>
            <template slot="major" slot-scope="data">
                <p ><v-chip disabled dark id="gray" text-color="white">{{ data.item.major_tag === null ? "─" : data.item.major_tag}}</v-chip></p>
            </template>
            <template slot="class_name" slot-scope="data">
                <p id="tdstyle">{{ data.item.class_name === null ? "─" : data.item.class_name }}</p>
            </template>
            <template slot="college_category_sub" slot-scope="data">
                <p id="tdstyle">{{ data.item.college_category_sub === null ? "─" : data.item.college_category_sub }}</p>
            </template>
            <template slot="student_count" slot-scope="data">
                <p id="tdstyle">{{ data.item.student_count}}人</p>
            </template>
            <template slot="student_end_count" slot-scope="data">
                <p id="tdstyle">{{ data.item.student_end_count}}人</p>
            </template>
            <template slot="acceptance_count" slot-scope="data">
                <p id="tdstyle">{{data.item.acceptance_count}}個</p>
            </template>
            <template slot="acceptance_ok_count" slot-scope="data">
                <p id="tdstyle">{{data.item.acceptance_ok_count}}個</p>
            </template>
            <template slot="btn" slot-scope="data">
                <v-btn large v-if="user.classification == 'agent'" color="success " block @click="openfacultyResultDialog(data.item.faculty_id, data.item)">就活結果</v-btn>
                <v-btn large color="secondary" block @click="professorCk(data.item.faculty_id)">教授情報</v-btn>
            
            </template>
        </b-table>
        <v-layout row wrap style="margin-top : 15px">
            <v-flex text-center>
                <h4>就職率</h4>
                <div class="radius  Hover">
                    <div class="not">
                    <h1>{{info.collegeInfo.student_end_count}}/{{info.collegeInfo.student_count}}</h1>
                    <h4>{{info.collegeInfo.student_count == 0 ? "─" :
                    info.collegeInfo.student_end_count == 0 ? 0 : allPercentage1}}%</h4> 
                    </div>
                    <div class="unnot">
                        <h4>{{info.collegeInfo.student_end_count}}/{{info.collegeInfo.student_count}}</h4>
                        <h1>{{info.collegeInfo.student_count == 0 ? "─" :
                        info.collegeInfo.student_end_count == 0 ? 0 :allPercentage1}}%</h1> 
                    </div>
                </div> 
                <p style="font-size:15px;">就活終了学生数／全体学生数</p>   
            </v-flex>  

            <v-flex text-center>
                <h4>採用進行率</h4>
                    <div class="radius  Hover" >
                        <div class="not">
                            <h1>{{info.collegeInfo.employment_end_count}}/{{info.collegeInfo.employment_count}}</h1>
                            <h4>{{info.collegeInfo.employment_count == 0 ? "─" :
                            info.collegeInfo.employment_end_count == 0 ? 0 :allPercentage2}}%</h4>
                        </div>
                        <div class="unnot">
                            <h4>{{info.collegeInfo.employment_end_count}}/{{info.collegeInfo.employment_count}}</h4>
                            <h1>{{info.collegeInfo.employment_count == 0 ? "─" :
                            info.collegeInfo.employment_end_count == 0 ? 0 :allPercentage2}}%</h1>
                        </div>
                    </div>    
                <p style="font-size:15px">採用完了件数／採用件数</p>   
            </v-flex> 

            <v-flex text-center>
                <h4>agent連携率</h4>
                <div class="radius  Hover" >

                    <div class="not">
                        <h1>{{info.collegeInfo.acceptance_ok_count}}/{{info.collegeInfo.student_count}}</h1>
                        <h4>{{info.collegeInfo.student_count == 0 ? "─" :
                        info.collegeInfo.acceptance_ok_count == 0 ? 0 :allPercentage3}}%</h4>
                    </div>
                    <div class="unnot">
                        <h4>{{info.collegeInfo.acceptance_ok_count}}/{{info.collegeInfo.student_count}}</h4>
                        <h1>{{info.collegeInfo.student_count == 0 ? "─" :
                        info.collegeInfo.acceptance_ok_count == 0 ? 0 : allPercentage3}}%</h1>
                    </div>
                </div>    
                <p style="font-size:15px">agent経由内定者数／全体学生数</p>   
            </v-flex>

            <v-flex text-center>
                <h4>内定承諾率</h4>
                <div class="radius  Hover" >
                    <div class="not">
                        <h1>{{info.collegeInfo.acceptance_ok_count}}/{{info.collegeInfo.acceptance_count}}</h1>
                        <h4>{{info.collegeInfo.acceptance_count == 0 ? "─" :
                        info.collegeInfo.acceptance_ok_count == 0 ? 0 : allPercentage4}}%</h4>
                    </div>
                    <div class="unnot">
                        <h4>{{info.collegeInfo.acceptance_ok_count}}/{{info.collegeInfo.acceptance_count}}</h4>
                        <h1>{{info.collegeInfo.acceptance_count == 0 ? "─" :
                        info.collegeInfo.acceptance_ok_count == 0 ? 0 : allPercentage4}}%</h1>
                    </div>
                </div>    
                <p style="font-size:15px">内定承諾数／全体内定数</p>
            </v-flex>
        </v-layout>
        <b-table responsive :fields="statistics_headers" :items="info.employmentList" item-key="faculty_id">
            <template slot="num" slot-scope="data">
                <p>{{++data.index}}</p>
            </template>
            <template slot="companyname" slot-scope="data">
                <p style="width:130px">{{data.item.org_name === null ? "─" : data.item.org_name}}</p>
            </template>
            <template slot="title" slot-scope="data">
                <p>
                <v-chip v-if="data.item.employment_owari_ox == 'o'" label outline disabled>終了</v-chip>
                <v-chip v-else label outline disabled color="primary">進行中</v-chip>
                {{ data.item.employment_name}}
                </p>
            </template>

            <template slot="applicant" slot-scope="data">
                <p>{{ data.item.apply_count === null ? "─" : data.item.apply_count }}人</p>
            </template>
            <template slot="pass" slot-scope="data">
                <p>{{ data.item.acceptance_count === null ? "─" : data.item.acceptance_count }}人</p>
            </template>
            <template slot="confirmation" slot-scope="data">
                <p>{{ data.item.acceptance_ok_count}}人</p>
            </template>
        </b-table>

    <!-- 교수 정보 다이얼로그 -->
    <v-dialog persistent v-model="professorDialog" width="882px" scrollable>
        <v-card>
            <v-card-title>
            <v-flex>
                <v-layout>  
                <v-chip v-if="chipclick == false" @click="chipclick = false" label color="cyan" text-color="white" style="height:40px" class="title">{{items[0]}}</v-chip>
                <v-chip v-else @click="chipclick = false" label outline color="cyan" style="height:40px" >{{items[0]}}</v-chip>
                <v-chip v-if="chipclick == true" @click="chipclick = true" label color="cyan" text-color="white" style="height:40px" class="title">{{items[1]}}</v-chip>
                <v-chip v-else @click="chipclick = true" label outline color="cyan" style="height:40px" >{{items[1]}}</v-chip>
                <v-spacer></v-spacer>
                <v-btn large color="error" @click="professorDialog = false">X</v-btn>
                </v-layout>
            </v-flex>
            </v-card-title>
        
            
            <v-card-text>
            <!-- 교수정보 -->
            <div xs12 v-if="chipclick == false"  style="min-height :600px">
                <v-layout wrap v-if="info.professorInfo[faculty_id] != null && info.professorInfo[faculty_id].length != 0">
                <div v-for="(professor, professorkey) in info.professorInfo[faculty_id]" :key="professorkey"> 
                    <v-card width="205px" style="margin:8px 0 8px 8px" >
                    <v-card-text text-center  width="200px">
                        <img :src="professor.profile_photo_url " width="170px" height="220px">
                        {{professor.name}}/{{professor.name_kana}}<br>
                        {{professor.email}}<br>
                        専攻 : {{professor.major}}<br>
                        日本語 {{professor.japaness_skill_ox}}
                    </v-card-text>
                    </v-card>
                </div>
                </v-layout>
                <v-layout row v-else>
                <v-flex xs12>
                    <v-alert :value="true" color="warning" icon="warning">
                    加入できている教授がいません。
                    </v-alert>
                </v-flex>
                </v-layout>
            </div>

            <div v-else style=" min-height:600px">
                <v-layout >
                <v-flex xs5 text-center>
                    <div style="margin-right : 8px; margin-left : 8px"  >
                    <v-text-field
                        label="名前"
                        v-model="registProfessor.name"
                    ></v-text-field>
                    </div>
                </v-flex>
                <v-flex xs5 text-center>
                    <div style="margin-right : 8px; margin-left : 8px"  >
                    <v-text-field      
                        label="メールアドレス"
                        v-model="registProfessor.email"
                        :rules="[emailrules]"
                    ></v-text-field>
                    </div>
                </v-flex>
                <v-flex  xs2 text-center>
                    <v-btn large color="success" @click="createAddress(faculty_id)">教授登録</v-btn>
                </v-flex>
                </v-layout>

                <!-- 교수 테이블 -->
                <b-table responsive :fields="professorBookheaders" :items="info.professorBookInfo[faculty_id]" item-key="faculty_id">
                <template slot="checkbox" slot-scope="data">
                    <p id="tdstyle">
                    <v-checkbox
                        :value="data.item"
                        v-model="send"
                    ></v-checkbox>
                    </p>
                </template>
                <template slot="name" slot-scope="data">
                    <p id="tdstyle" style="width:130px">{{ data.item.name}}</p>
                </template>
                <template slot="email" slot-scope="data">
                    <p id="tdstyle">
                    {{ data.item.email}}
                    </p>
                </template>

                <template slot="delect" slot-scope="data">
                    <v-btn icon @click="deleteAddress(data.item.no)"><v-icon color="error"  large >delete_forever</v-icon></v-btn>
                </template>
                </b-table>
                <div style="float:right;">
                    <v-btn large color="secondary" @click="inviteUser(send, 'p', orgAgentId)">招待メールを送る（教授）</v-btn>
                </div>
            </div>
            </v-card-text>
        </v-card>
    </v-dialog>


    <!-- 취업 결과 다이얼로그 -->
    <v-dialog persistent v-model="facultyResultDialog" scrollable>
        <v-card>
            <v-card-title>
            <h3>{{facultyInfo.department_name}}就活結果通計</h3>
                <v-spacer></v-spacer>
                <v-btn large color="error" @click="facultyResultDialog = false">X</v-btn>
            </v-card-title>
            <v-card-text>
            
            <v-layout row wrap>
                <v-flex text-center>
                <h4>就職率</h4>
                <div class="radius  Hover">
                    <div class="not">
                    <h1>{{facultyInfo.student_end_count}}/{{facultyInfo.student_count}}</h1>
                    <h4>{{facultyInfo.student_count == 0 ? "─" :
                        facultyInfo.student_end_count == 0 ? 0 : facultyInfo.Percentage1}}%</h4> 
                    </div>
                    <div class="unnot">
                    <h4>{{facultyInfo.student_end_count}}/{{facultyInfo.student_count}}</h4>
                    <h1>{{facultyInfo.student_count == 0 ? "─" :
                        facultyInfo.student_end_count == 0 ? 0 : facultyInfo.Percentage1}}%</h1> 
                    </div>
                </div> 
                <p style="font-size:15px">就活終了学生数／全体学生数</p>   
                </v-flex>  

                <v-flex text-center>
                <h4>採用進行率</h4>
                <div class="radius  Hover" >
                    <div class="not">
                    <h1>{{facultyInfo.employment_end_count}}/{{facultyInfo.employment_count}}</h1>
                    <h4>{{facultyInfo.employment_count == 0 ? "─" : 
                        facultyInfo.employment_end_count == 0 ? 0 : facultyInfo.Percentage2}}%</h4>
                    </div>
                    <div class="unnot">
                    <h4>{{facultyInfo.employment_end_count}}/{{facultyInfo.employment_count}}</h4>
                    <h1>{{facultyInfo.employment_count == 0 ? "─" : 
                        facultyInfo.employment_end_count == 0 ? 0 : facultyInfo.Percentage2}}%</h1>
                    </div>
                </div>    
                <p style="font-size:15px">採用完了件数／採用件数</p>   
                </v-flex> 

                <v-flex text-center>
                <h4>agent連携率</h4>
                <div class="radius  Hover" >
                    
                    <div class="not">
                    <h1>{{facultyInfo.acceptance_ok_count}}/{{facultyInfo.student_count}}</h1>
                    <h4>{{
                        facultyInfo.student_count == 0 ? "─" :
                        facultyInfo.acceptance_ok_count == 0 ? 0 :facultyInfo.Percentage3}}%</h4>
                    </div>
                    <div class="unnot">
                    <h4>{{facultyInfo.acceptance_ok_count}}/{{facultyInfo.student_count}}</h4>
                    <h1>{{facultyInfo.student_count == 0 ? "─" : 
                        facultyInfo.acceptance_ok_count == 0 ? 0 : facultyInfo.Percentage3}}%</h1>
                    </div>
                </div>    
                <p style="font-size:15px">agent経由内定者数／全体学生数</p>   
                </v-flex>

                <v-flex text-center>
                <h4>内定承諾率</h4>
                <div class="radius  Hover" >
                    
                    <div class="not">
                    <h1>{{facultyInfo.acceptance_ok_count}}/{{facultyInfo.acceptance_count}}</h1>
                    <h4>{{facultyInfo.acceptance_count == 0 ? "─" : 
                        facultyInfo.acceptance_ok_count == 0 ? 0 : facultyInfo.Percentage4}}%</h4>
                    </div>
                    <div class="unnot">
                    <h4>{{facultyInfo.acceptance_ok_count}}/{{facultyInfo.acceptance_count}}</h4>
                    <h1>{{facultyInfo.acceptance_count == 0 ? "─" : 
                        facultyInfo.acceptance_ok_count == 0 ? 0 : facultyInfo.Percentage4}}%</h1>
                    </div>
                </div>    
                <p style="font-size:15px">内定承諾数／全体内定数</p>
                </v-flex>
            </v-layout>

            <!-- 채용건 리스트 테이블 -->
            <b-table responsive :fields="statistics_headers" :items="info.employmentList" item-key="faculty_id">
                <template slot="num" slot-scope="data">
                <p>{{++data.index}}</p>
                </template>
                <template slot="companyname" slot-scope="data">
                <p style="width:130px">{{data.item.org_name === null ? "─" : data.item.org_name}}</p>
                </template>
                <template slot="title" slot-scope="data">
                <p>
                    <v-chip v-if="data.item.employment_owari_ox == 'o'" label outline disabled>終了</v-chip>
                    <v-chip v-else label outline disabled color="primary">進行中</v-chip>
                    {{ data.item.employment_name}}
                </p>
                </template>

                <template slot="applicant" slot-scope="data">
                <p>{{ data.item.apply_count === null ? "─" : data.item.apply_count }}人</p>
                </template>
                <template slot="pass" slot-scope="data">
                <p>{{ data.item.acceptance_count === null ? "─" : data.item.acceptance_count }}人</p>
                </template>
                <template slot="confirmation" slot-scope="data">
                <p>{{ data.item.acceptance_ok_count}}人</p>
                </template>
            </b-table>
        </v-card-text>
      </v-card>
    </v-dialog> 
    </v-container>
</template>




<script>
export default {
  props : ['orgCollegeId','orgAgentId', 'user'],

  created : function(){

    //년도 리스트 출력
    let thisYear = new Date().getFullYear();
    this.thisYear = thisYear;
    this.searchYear = thisYear;

    for(let i = thisYear; i >= 2016; i--)
        this.yearList.push({value : i});

    this.getCollegeInfo(thisYear);
    
    // this.percentageEmploymentCom = Math.round(3/5*100);
    // // this.percentageEnd = this.no_acceptance_employment_count/this.employment_owari_count*100
    // this.percentageEnd = Math.round(1/3*100);
    
  },

  data : ()=> ({

    // 지도에 필요한 변수
    markers : [],
    place   : null,
    lat     : '',
    lng     : '',

    //전체 취업 현황
    allPercentage1: null,
    allPercentage2: null,
    allPercentage3: null,
    allPercentage4: null,


    thisYear : null,
    yearList : [],

    searchYear : null,
    allYear : null,
    
    //백분률
    percentageEmploymentStd : 0,
    percentageEmploymentCom : null,
    percentageEnd : null,

    //교수 정보와 초대구분
    chipclick:false,
    //다이얼로그 온오프
    professorDialog : false,
    facultyResultDialog : false,

    faculty_id : null,
    facultyInfo : {

    },


    //전체 학생수
    sumStdNumArr : [],
    sumStdNum : 0,
    //전체 내정자 수
    sumNaiteiariStdNum : null,
    //전체 미 내정자 수
    sumNaiteinasiStdNum : null,
    
    //학부 갯수
    //facultyInfoNum : 0,
    
    //Mnumber : 0,

    info : null,
    tab : null,
    items: [
      '教授情報','教授招待'
      
    ],
    //교수 등록
    registProfessor : {
      name : null,
      email: null,
    },
  //  professorBookheaders1:[
  //     {text : 'check', value: 'checkbox', width: '10%', align : 'center', sortable:false},
  //     {text : '이름名前', value: 'name', width: '20%', align : 'center'},
  //     {text : '이메일メールアドレス', value: 'email', width: '40%', align : 'center'},
  //     {text : '삭제削除', value: 'delect', width: '10%', align : 'center'},
  //  ],
   professorBookheaders:[
      { key: "checkbox", label: "check" },
      { key: "name", label: "名前" },
      { key: "email", label: "メールアドレス" },
      { key: "delect", label: "削除" },
      
   ],
    // statistics_headers1 :[
    //   {text : '순번順番', value: 'num'},
    //   {text : '기업명企業名', value: 'companyname'},
    //   {text : '채용건 제목募集ポジション', value: 'title'},
    //   {text : '지원자 수志願者数', value: 'applicant'},
    //   {text : '내정자 수内定者数', value: 'pass'},
    //   {text : '내정 확정자 수内定確定者数', value: 'confirmation'},
    //   // {text : '진행 여부', value: 'progress'},
    // ] ,
    statistics_headers :[
      { key: "num", label: "順番" },
      { key: "companyname", label: "企業名" },
      { key: "title", label: "募集タイトル" },
      { key: "applicant", label: "志願者数" },
      { key: "pass", label: "内定者数" },
      { key: "confirmation", label: "内定確定者数" },

      // {text : '순번', value: 'num'},
      // {text : '기업명', value: 'companyname'},
      // {text : '채용건 제목', value: 'title'},
      // {text : '지원자 수', value: 'applicant'},
      // {text : '내정자 수', value: 'pass'},
      // {text : '내정 확정자 수', value: 'confirmation'},
      // {text : '진행 여부', value: 'progress'},
    ] ,

    headers :[
      { key: "num", label: "順番" },
      { key: "department_name", label: "学部" },
      { key: "major", label: "専攻分類" },
      { key: "class_name", label: "専攻／クラス" },
      { key: "college_category_sub", label: "卒業年数" },
      { key: "student_count", label: "全体学生数" },
      { key: "student_end_count", label: "就活終了学生数" },
      { key: "acceptance_count", label: "内定数" },
      { key: "acceptance_ok_count", label : '内定承諾数'},
      { key: "btn", label: "Action" },
    ],

    group_headers :[
      {text : 'グループ番号', value: 'group_num'},
      {text : 'グループ名', value: 'major'},
      {text : 'プロジェクト名', value: 'major_name'},
      {text : 'プロジェクト内容', value: 'class_name'},
      {text : '発表年度', value: 'college_category_sub'},
    ],

    emailrules: (value) => {
      const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      return pattern.test(value) || 'Invalid e-mail.'
    },
    
    //교수 등록 
    professorregist : [],

    //메일 발송 체크
    send:[]

  }),

  mounted () {
     this.callMap();
  },

  methods : {
    // 지도 마커 호출 함수
    callMap() {
      let uri  = '/map';
      // 학교 아이디 값 넘김
      let data = {
        collegeId : this.orgCollegeId,
      };

      this.axios.post(uri, data).then((res) => {
        // 좌표값을 받아 구글 객체 생성
        let location = new google.maps.LatLng(res.data.lat, res.data.lng);
        
        // 생성된 객체를 좌표를 찍어 줄 수 있는 객체 형태로 변형
        let position = {
            position : location,
        }
        // 마커 배열에 변형된 좌표값을 넘김
        this.markers.push(position);
      });
    },

    //학교 정보 가져오기
    getCollegeInfo(){
      let org_college_id = this.orgCollegeId;
      let searchYear = this.searchYear;
      let classification = this.user.classification;
      let org_agent_id = this.orgAgentId;

      this.axios.post('/agent/getCollegeInfo',{org_college_id, classification, org_agent_id, searchYear})
                .then(rep=>{
                  this.info = rep.data;
          
                  this.allPercentage1 = Math.round(this.info.collegeInfo.student_end_count/this.info.collegeInfo.student_count*100*10)/10;
                  this.allPercentage2 = Math.round(this.info.collegeInfo.employment_end_count/this.info.collegeInfo.employment_count *100*10)/10;
                  this.allPercentage3 = Math.round(this.info.collegeInfo.acceptance_ok_count/this.info.collegeInfo.student_count*100*10)/10;
                  this.allPercentage4 = Math.round(this.info.collegeInfo.acceptance_ok_count/this.info.collegeInfo.acceptance_count*100*10)/10;
                
                })
                .catch(ex=>{
                  console.log(ex);
                });

    },

    //학과 실적 정보 가져오기
    // getFacultyResultInfo(){
    //   let searchYear = this.searchYear;
    //   let faculty_id = this.faculty_id;
    //   let org_college_id = this.orgCollegeId;
    //   let org_agent_id = this.orgAgentId;

    //   this.axios.post('/school/getFacultyResultInfo',{ searchYear, faculty_id, org_college_id, org_agent_id})
    //             .then(rep=>{
    //               this.facultyResultInfo = rep.data;
    //             })
    //             .catch(ex=>{
    //               console.log(ex);
    //             });

    // },

    professorCk(faculty_id){
      this.faculty_id = faculty_id;
      this.professorDialog = true;
    },

    openfacultyResultDialog(faculty_id, faculty){
      this.facultyInfo = null;
      this.facultyInfo = faculty;
      this.faculty_id = faculty_id;
     

      this.facultyResultDialog = true;
      this.facultyInfo.Percentage1 = Math.round(this.facultyInfo.student_end_count/this.facultyInfo.student_count*100*10)/10;
      this.facultyInfo.Percentage2 = Math.round(this.facultyInfo.employment_end_count/this.facultyInfo.employment_count *100*10)/10;
      this.facultyInfo.Percentage3 = Math.round(this.facultyInfo.acceptance_ok_count /this.facultyInfo.student_count*100*10)/10;
      this.facultyInfo.Percentage4 = Math.round(this.facultyInfo.acceptance_ok_count/this.facultyInfo.acceptance_count*100*10)/10;

    },
    //이메일 유효성 검사
    email_check( email ) {    
        var regex=/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        let returnBool =  regex.test(email); 
        return returnBool;
    },
    //교수 주소록 등록
    createAddress(){
      let yesNo = confirm('教授を登録しますか？');
      
      if(!yesNo){
        alert('キャンセルされました。');
        return;
      }
      if(!this.email_check(this.registProfessor.email)){
        alert('間違っているメールです。');
        return;
      }
      let addressItem = this.registProfessor;
      addressItem.faculty_id = this.faculty_id;
      addressItem.org_agent_id = this.orgAgentId;

      let type = 'p';
      
      this.axios.post('/agent/createAddress', {addressItem, type})
                .then(rep=>{
                  if(rep.data.returnBool){
                    alert('教授のメールアドレスを登録しました。교수 회원 주소록 등록이 완료되었습니다.');
                    this.info.professorBookInfo = rep.data.professorBookInfo;
                    this.send = [];
                  }else {
                    alert('もう登録されているメールです。');
                  }

                  this.registProfessor.email = null;
                  this.registProfessor.name = null;
                })
                .catch(ex=>{
                  console.log(ex);
                });
    },

    //교수 등록 삭제
    deleteAddress(no){
        let yeaNo = confirm('削除しますか？');
        if(!yeaNo){
          alert('キャンセルされました。');
          return;
        }

        let classification = this.user.classification;
        let faculty_id = this.faculty_id;
        let org_agent_id = this.orgAgentId;
        let type = 'p'

        this.axios.post('/agent/deleteAddress', {classification, faculty_id, org_agent_id, no, type})
                  .then(rep=>{
                    if(rep.data.returnBool){
                      alert('教授のメールアドレスが削除されました。');
                      this.info.professorBookInfo = rep.data.professorBookInfo;
                      this.send = [];
                    }else {
                      alert('削除できまいメールアドレスです。');
                    }
                  })
                  .catch(ex=>{
                    console.log(ex);
                  });
    },

    //메일 발송 하기
    inviteUser(params, type, org_agent_id){

      alert('教授招待メールを送りますか？');
      if(params.length == 0){
        alert('メールアドレスを選択してください。');
        return ; 
      }
       
      let url = "/MailSned";
      let info = { info : params, type : type, org_agent_id : org_agent_id};
      this.axios.post(url, info).then((res) => {
              //this.result = response.data;
              if(!res.data) return;

              alert('送りました。');
              this.send = [];
          })
          .catch(ex=>{
            console.log(ex);
          });
    },

  },

  watch : {
    // faculty_id : function(val){
    //   this.getFacultyResultInfo();
    // },

    searchYear : function(val){
      this.getCollegeInfo();
    }
  }

}
</script>

<style scoped lang="css" src="../static/css/agent/agnetAndStudentStyleSheet.css"></style>
<style>

</style>

