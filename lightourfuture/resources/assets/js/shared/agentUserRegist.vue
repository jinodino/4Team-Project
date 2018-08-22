<template>

  <div class="container-fluid">
    <div class="row text-center section">
      <div class="col-xs-6  col-md-6"><h1>{{status}}</h1></div>
    </div>

    <div class="row text-center section">
      <!-- User type selection -->
      <div class="col-xs-4 col-md-12">
        <div class="col-xs-2 col-md-5">
          <span>유저타입 선택</span> 
        </div>
        <div class="col-xs-5 col-md-5">
          <v-select
            :items="userType.type"
            v-model="userType.select"
            label="Select"
            item-text="text"
            single-line
          ></v-select> 
        </div>
      </div>
      
      <!-- Select company type -->
      <div class="col-xs-4 col-md-12" v-if="userType.select.text === division.divC">
        <div class="col-xs-2 col-md-5">
          <span>{{division.select}}</span>
        </div>
        <div class="col-xs-2 col-md-5">
          <v-select
            :items="division.company"
            v-model="division.Cselect"
            label="Select"
            item-text="Cvalue"
            single-line
          ></v-select>
        </div>
      </div>

      <!-- Select School Type -->
      <div class="col-xs-4 col-md-12" v-else-if="userType.select.text === division.divS">
        <div class="col-xs-2 col-md-5">
          <span>{{division.select}}</span>
        </div>
        <div class="col-xs-2 col-md-5">
          <v-select
            :items="division.school"
            v-model="division.Sselect"
            label="Select"
            item-text="Svalue"
            single-line
          ></v-select>
        </div>
      </div>
      
      <!-- Enter country information -->
      <div class="col-xs-4 col-md-12">
        <div class="col-xs-2 col-md-5">{{select_country.title}}</div>
        <div class="col-xs-2 col-md-5">
          <v-text-field
            name="selectCountry"
            v-model="select_country.value"
            disabled
          ></v-text-field>
        </div>
        <div class="col-xs-2 col-md-1">
          <button type="button" class="btn btn-primary" @click.stop="dialog = !dialog;">{{select_country.default}}</button>
        </div>
      </div>

      <!-- Enter information -->
      <div class="col-xs-4 col-md-12" v-for="(itemvalue, key) in items" :key="key.key">
        <div class="col-xs-2 col-md-5">{{itemvalue.title}}</div>
        <div class="col-xs-2 col-md-5">
          <v-text-field
          v-bind:name="itemvalue.name"
          v-model="itemvalue.value"
        ></v-text-field>
        </div>
      </div> 

      <!-- Enter professor information -->
      <div class="col-xs-4 col-md-12" v-if="userType.select.text === division.divS">
        <hr>
        <div class="col-xs-2 col-md-5">
          {{Professor_enrollment}} 
          <button style="border-radius: 100px">
            <v-icon>add_circle_outline</v-icon>
          </button>
        </div>
      </div>

      <div class="col-xs-4 col-md-12" v-for="(add, key) in Professor" :key="key" v-if="userType.select.text === division.divS">
        <div class="col-xs-2 col-md-5">{{add.Pitem}}</div>
        <div class="col-xs-2 col-md-5">
          <v-text-field
            v-bind:name="add.key"
            v-model="add.Pvalue"
          ></v-text-field>
        </div>
        
      </div>   
      <div  class="col-xs-4 col-md-12" v-if="userType.select.text === division.divS">
        <div class="col-xs-2 col-md-5">
          {{Professor_Classes.select}}
        </div>
        <div class="col-xs-2 col-md-5">
          <v-select
            :items="Professor_Classes.classes"
            v-model="division.Pselect"
            label="Select"
            item-text="Classes"
            single-line
          ></v-select>
        </div> 
      </div>  
    </div>
  
    <div class="text-left">
      <button type="button" class="btn btn-primary pull-right col-md-1">{{enrollment}}</button>
    </div>
    
    <!-- Select country modal -->
    <v-dialog v-model="dialog" width="800px">
      <v-card>
        <v-card-title class="grey lighten-4 py-4 title">
          {{select_country.default}}
          <v-spacer></v-spacer>   
          <v-icon @click="dialog = false;">clear</v-icon>
        </v-card-title>
        <v-container grid-list-sm class="pa-4">
          <v-layout row wrap>
            <v-flex xs12>
              <v-card-text v-for="(countryvalue, key) in select_country.select" :key="key">
                <v-btn @click="select_country.value = countryvalue.country; dialog = false">{{countryvalue.country}}</v-btn>
              </v-card-text>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>
    </v-dialog>

  </div>

</template>

<script>
export default {
  data : ()=>({
    dialog : false,
    status : "유저 등록",
    userType : {
      select : '유저 구분',
      type : [
        {division : 'shcool' , text : '학교'},
        {division : 'enterprise' , text : '기업'}
      ]
    },
    // country : {
    //   value:'',
    //   btnname:'찾기'
    // },
    select_country : {
      title : '국가 선택',
      value : '',
      default : '국가 선택',
      select : [
        {countrykey : 'japan', country : '일본'},
        {countrykey : 'korea', country : '한국'},
        {countrykey : 'srilanka', country : '스리랑카'},
        {countrykey : 'india', country : '인도'},
        {countrykey : 'USA', country : '미국'},
        {countrykey : 'china', country : '중국'},
      ]
    },
    items : [
      {
        name : 'selectName',
        title : '학교/기업 이름',
        value : ''
      },
      {
        name : 'selectCode',
        title : '학교/기업 코드',
        value : ''
      },
      {
        name : 'selectAddress',
        title : '주소',
        value : ''
      }
    ],
    division : {
      divC : '기업',
      divS : '학교',
      select : '구분',
      Cselect : '기업 구분',
      Sselect : '학교 구분',
      school : [
        {division : 'university', Svalue : '일반대학(4년제)'},
        {division : 'college', Svalue : '전문대학(2~3년제)'}
      ],
      company : [
        {division : 'venture', Cvalue : '0~29명'},
        {division : 'small', Cvalue : '30~99명'},
        {division : 'Medium-sized', Cvalue : '100~900명'},
        {division : 'large', Cvalue : '1000명~'}
      ]
    },
    Professor_enrollment : '학교 교수 등록',
    Professor : [
      {
        key : 'professorname',
        Pitem : '교수이름',
        Pvalue : '',
      },
      {
        key : 'professoremail',
        Pitem : 'Email',
        Pvalue : ''
      }
    ],
    enrollment : '등록',
    Professor_Classes : {
      select : '교수 구분',
      Pselect : '교수 구분',
      classes : [
        {Classes : '담임'},
        {Classes : '비담임'}
      ]
    }
  })
}
</script>

<style>
  .section{
    margin-bottom: 1rem;
    background-color:lavender; 
    
  }
  #border {
    border-bottom:1px black solid;
  }
</style>