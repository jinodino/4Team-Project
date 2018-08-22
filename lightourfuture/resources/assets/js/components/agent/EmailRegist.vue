<template>
  <v-card>
    <v-card-title  class="grey lighten-4 py-4 headline">
        {{type == 'p' ? '교수' : '기업가'}} 주소록 등록
    </v-card-title>
    <v-container class="pa-1" grid-list-md>
      <v-layout row>
        <v-flex xs12> 

          <!-- 소속 기관 선택 -->
          <v-layout row justify-center>
            <v-flex xs10>
                <v-select
                  v-if="type == 'p'"
                  label="학교 선택"
                  :items="orgList"
                  v-model="org_id"
                  item-text="org_name"
                  item-value="org_college_id"
                ></v-select>
                <v-select
                  v-else
                  :items="orgList"
                  label="소속 선택"
                  v-model="org_id"
                  item-text="org_name"
                  item-value="org_company_id"
                ></v-select>
            </v-flex>
          </v-layout>

           <!-- 교수인 경우, 학부선택 -->
          <v-layout row justify-center v-if="type == 'p'">
            <v-flex xs10>
              <v-select
                label="학부 선택"
                :disabled="!org_id"
                :items="facultyList[org_id]"
                v-model="faculty_id"
                item-text="department_name"
                item-value="faculty_id"
              ></v-select>
            </v-flex>
          </v-layout>

          <v-layout row justify-center>
            <v-flex xs10  text-center>
              <v-text-field
               :disabled="(type=='p'&&!faculty_id) || (type=='c'&&!org_id)"
                label="이름 입력"
                v-model="name"
              ></v-text-field>
            </v-flex>
          </v-layout>

          <v-layout row justify-center>
            <v-flex xs10  text-center>
              <v-text-field
               :disabled="(type=='p'&&!faculty_id) || (type=='c'&&!org_id)"                           
                label="이메일 입력"
                v-model="email"
                :rules="[emailrules]"
              ></v-text-field>
            </v-flex>
          </v-layout>
          
        </v-flex>
      </v-layout>
    </v-container>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="primary" @click="createAddress">저장</v-btn>
      <v-btn color="error" @click="closeDialog">취소</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {

  props:['orgAgentId','orgList', 'type', 'facultyList'],

  data : ()=>({
    Save : '등록',
    dialog : false,
    status : "유저 등록",

    org_id: null,
    name : '',
    email : '',
    faculty_id : null,
    emailrules: (value) => {
      const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      return pattern.test(value) || 'Invalid e-mail.'
    }
  }),

  methods: {
    userTypeChange :function(value){
      
        var type = this.userType.select;
        if(type != typetest){
        this.userType.id = '';
        var typetest = this.userType.selectTest;
        }
    },

    //다이얼로그 창 닫기
    closeDialog(){
      this.email = null;
      this.name = null;
      this.org_id = null;
      this.faculty_id = null;

      this.$emit('closeDialog');
    },


    //주소록에 등록
    createAddress(){

      if(!this.validationCheck()){
        alert('값을 제대로 입력해주세요');
        return;
      }
        
        
      let type = this.type;
      let sendData = {};

      sendData.org_agent_id = this.orgAgentId;
      sendData.name = this.name;
      sendData.email = this.email;

      if(this.type == 'p')
        sendData.faculty_id = this.faculty_id; 
      else if(this.type == 'c')
        sendData.org_id = this.org_id;

      this.axios.post('/agent/createAddress', sendData)
      .then(rep=>{
        if(rep.data.returnBool){
          if(this.type == 'p')
            alert('교수 회원 등록이 완료 되었습니다.');
          else if(this.type =='c')
            alert('기업 회원 등록이 완료 되었습니다.');
        }else {
          alert('이미 등록된 이메일 입니다.');
        }

    
        this.email = null;
        this.name = null;
        this.org_id = null;
        this.faculty_id = null;

        this.$emit('createAddress');
      })
      .catch(ex=>{
        console.log(ex);
      });
      
    },
    
    //유효값 검사
    validationCheck(){
      var email = this.email;
      
      if(this.name == null || this.email == null ){ 
        return false;
      } else {
        if(this.type=='p' && this.faculty_id == null)
          return false;
        else if(this.type == 'c' && this.org_id == null)
            return false;
      }

      //return true;
      
      if(!this.email_check(email) ) { 
        alert('잘못된 형식의 이메일 주소입니다.');
        $(this).focus();
        return false;
      }
        return true;
      
      //
    },
    //email 유효성 검사
    email_check( email ) {    
        var regex=/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        let returnBool =  regex.test(email); 
        return returnBool;

    }
  }

}


</script>

<style>
  .section{
    margin-bottom: 1rem;
    background-color:rgb(224, 255, 224); 
    
  }
  #border {
    border-bottom:1px black solid;
  }
</style>
