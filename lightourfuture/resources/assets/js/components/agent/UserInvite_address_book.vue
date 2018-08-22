<template>
  <v-container grid-list-md>
    <div class="row text-center section">
      <div class="col-xs-12"><h1>{{orgAgent.org_name}}({{orgAgent.org_name_kana}})</h1></div>
    </div>
    <v-layout row>

      <!-- 학교 회뭔 초대 -->
      <v-flex xs12>
        <v-card>
          <v-card-title  class="display-1" >
            교수 회원 초대
            <v-btn color="primary" @click="openDialog('p')">교수 회원 등록</v-btn>
            <v-btn color="primary" @click="inviteUser(professorAddress, 'p', orgAgentId)">교수 초대 메일 발송 하기</v-btn>

            <v-spacer></v-spacer>
            <!-- 기관별 선택 -->
            <v-select
              label="학교 선택"
              :items="college_list"
              v-model="searchCollegeNume"
              item-text="org_name"
              item-value="org_name"
              single-line
            ></v-select>
          </v-card-title>
          <v-data-table
            id="inspire"
            v-model="professorAddress"
            :headers="headers"
            :items="professor_list"
            select-all
            :search="searchCollegeNume"
            :pagination.sync="pagination"
            item-key="no"
            class="elevation-1"
          >
            <template slot="headers" slot-scope="props" class="row text-center section">
              <tr>
                <th></th>
                <th
                  v-for="header in props.headers"
                  :key="header.text"
                  class="text-xs-center"
                >
                  {{ header.text }}
                </th>
              </tr>
            </template>
            <template slot="items" slot-scope="props">
              <tr :active="props.selected" >
                <td>
                  <v-checkbox
                    @click="props.selected = !props.selected" 
                    primary
                    hide-details
                    :input-value="props.selected"
                    v-if="props.item.join_ox == 'x'"
                  ></v-checkbox>
                </td>
                <td class="text-xs-center">{{ props.item.no }}</td>
                <td class="text-xs-center">{{ props.item.org_name }}</td>
                <td class="text-xs-center">{{ props.item.name }}</td>
                <td class="text-xs-center">{{ props.item.email }}</td>
                <td class="text-xs-center">{{ props.item.join_ox }}</td>
                <td class="justify-center">
                    <v-btn icon @click="deleteMatchingItem(props.item.org_matchings_id)" :disabled="props.item.matching_date != thisYear">
                        <v-icon color="error">delete</v-icon>
                    </v-btn>
                </td>
              </tr>
            </template>
          </v-data-table>
        </v-card>
      </v-flex>
 
      <v-flex xs12>
        <v-card>
          <v-card-title  class="display-1">
            기업 회원 초대
            <v-btn color="primary" @click="openDialog('c')">기업 회원 등록</v-btn>
             <v-btn color="primary" @click="inviteUser(companyAddress, 'c', orgAgentId)">기업 회원 초대 메일 발송 하기</v-btn>
            <v-spacer></v-spacer>
            <!-- 기관별 선택 -->
            <v-select
              label="기업 선택"
              :items="company_list"
              v-model="searchCompanyNume"
              item-text="org_name"
              item-value="org_name"
              single-line
            ></v-select>
          </v-card-title>

          <v-data-table
            id="inspire"
            v-model="companyAddress"
            :headers="headers"
            :items="companyAgent_list"
            select-all
            :search="searchCompanyNume"
            :pagination.sync="pagination"
            item-key="no"
            class="elevation-1"
            sort="no"
          >
            <template slot="headers" slot-scope="props" class="row text-center section">
              <tr>
                <th></th>
                <th
                  v-for="header in props.headers"
                  :key="header.text"
                  class="text-xs-center"
                >
                  {{ header.text }}
                </th>
              </tr>
            </template>
            <template slot="items" slot-scope="props">
              <tr :active="props.selected" >
                <td>
                  <v-checkbox
                    @click="props.selected = !props.selected" 
                    primary
                    hide-details
                    :input-value="props.selected"
                    v-if="props.item.join_ox == 'x'"
                  ></v-checkbox>
                </td>
                <td class="text-xs-center" >{{ props.item.no }}</td>
                <td class="text-xs-center">{{ props.item.org_name }}</td>
                <td class="text-xs-center">{{ props.item.name }}</td>
                <td class="text-xs-center">{{ props.item.email }}</td>
                <td class="text-xs-center">{{ props.item.join_ox }}</td>
                <td class="justify-center">
                    <v-btn icon @click="deleteMatchingItem(props.item.org_matchings_id)" :disabled="props.item.matching_date != thisYear">
                        <v-icon color="error">delete</v-icon>
                    </v-btn>
                </td>
              </tr>
            </template>
          </v-data-table>
        </v-card>
      </v-flex>

    </v-layout>

    <div class="text-left">
      <button type="button" class="btn btn-primary pull-right col-md-1" @click="mailsend()">{{ sebmit }}</button>
      <button type="button" class="btn btn-primary pull-right col-md-1" @click="dialog = !dialog">{{ register }}</button>
    </div>


    <v-dialog v-model="companyDialog" persistent width="600px">
      <emailregist :orgAgentId="orgAgentId" @closeDialog="closeDialog" :orgList="company_list" :type="'c'" @createAddress="createAddress"></emailregist>
    </v-dialog>

    <v-dialog v-model="professorDialog" persistent width="600px">
      <emailregist :orgAgentId="orgAgentId" @closeDialog="closeDialog" :orgList="college_list" :type="'p'" :facultyList="faculty_list" @createAddress="createAddress"></emailregist>
    </v-dialog>

    <v-dialog v-model="inviteCheck" width="600px">
      <v-card>
        <v-card-title>

        </v-card-title>
        
      </v-card>
    </v-dialog>
  </v-container>

</template>

<script>
import emailregist from './EmailRegist.vue';
export default {
  components:{ 'emailregist' : emailregist },
  props :[
    'orgAgentId',
    'orgAgent'
  ],

  created : function(){
    //주소록 교수, 기업 리스트 가져오기
    this.getAddressBook();

    //학교 리스트 기업 리스트 가져오기
    this.getSchoolList(this.orgAgentId);
    this.getCompanyList(this.orgAgentId);

  },

  data : () => ({
    professorDialog: false,
    companyDialog: false, 

    sebmit: '보내기',
    register: '등록하기',
    pagination: {
      descending: 'no'
    },

    professorAddress: [],
    companyAddress : [],

    headers: [
      {text : 'no', value : 'no'},
      {text : '소속', value : 'org_name'},
      {text : 'name', value : 'name'},
      {text : 'email', value : 'email'},
      {text : '가입 여부', value : 'join_ox'},
      {text : '삭제', value : 'delete'}
    ],

    professor_list : [],
    companyAgent_list : [],
    company_list : [],
    college_list : [],

    searchCollegeNume : "",
    searchCompanyNume : ""
  }),

  methods: {
    StoggleAll () {
      if (this.selected.length) this.selected = []
      else this.selected = this.school_items.slice()
    },

    CtoggleAll () {
      if (this.selected.length) this.selected = []
      else this.selected = this.company_items.slice()
    },

    changeSort (column) {
      if (this.pagination.sortBy === column) {
        this.pagination.descending = !this.pagination.descending
      } else {
        this.pagination.sortBy = column
        this.pagination.descending = false
      }
    },

    inviteUser(params, type, org_agent_id){

      if(params.length == 0){
        alert('회원을 선택해 주세요');
        return ; 
      }
       
      let url = "/MailSned";
      let info = { info : params, type : type, org_agent_id : org_agent_id};
      this.axios.post(url, info).then((res) => {
              //this.result = response.data;
              if(!res.data) return;

              alert('전송되었습니다');
          })
          .catch(ex=>{
            console.log(ex);
          });
    },


    //주소록 정보 가져오기
    getAddressBook(){
      let org_agent_id = this.orgAgentId;
      this.axios.post('/agent/getAddressBook',{org_agent_id})
      .then(rep=>{
        this.companyAgent_list = null;
        this.professor_list = null;
        console.log(rep.data);
        this.companyAgent_list = rep.data.companyAgent_list;
        this.professor_list =  rep.data.professor_list;
      })
      .catch(ex=>{
        console.log(ex);
      })

    },

    //학교 리스트 가져오기
    getSchoolList(org_agent_id){
      this.axios.post('/agent/getOrgRelColInfo', {org_agent_id})
                  .then(rep=>{
                    this.college_list = null;
                    this.college_list = rep.data.slice();
                    if(this.college_list.length != 0);
                      this.getFaculties(this.college_list);
                  })
                  .catch(ex=>{
                      console.log(ex);
                  });
    },

    //학부 리스트 뽑아오기
    getFaculties(college_list){
      this.axios.post('/agent/getFaculties', {college_list})
      .then(rep=>{
          this.faculty_list = null;
          this.faculty_list = rep.data;
      })
      .catch(ex=>{
        console.log(ex);
      })
    },


    //기업 리스트 가져오기
    getCompanyList(org_agent_id){
      this.axios.post('/agent/getOrgRelComInfo', {org_agent_id})
                  .then(rep=>{
                    this.company_list = null;
                    this.company_list = rep.data.slice();
                  })
                  .catch(ex=>{
                      console.log(ex);
                  });
    },

    //모달 창 열기
    openDialog(params){
      if(params == 'c')
        this.companyDialog = true;
      else if(params == 'p')
        this.professorDialog = true;
    },

    closeDialog(){
      this.professorDialog = false;
      this.companyDialog = false;
    },

    createAddress(){
      this.getAddressBook();
      this.closeDialog();
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
