<template>
  <div class="container-fluid">
    <div class="row text-center section">
      <div class="col-xs-2 col-md-12">
        <div class="col-xs-2 col-md-5">학부/계열 선택</div>
        <div class="col-xs-2 col-md-5">
            <v-select
              :items="department"
              item-text="title"
              v-model="info.department_name"
              single-line
            ></v-select>
        </div>
      </div>
      <div class="col-xs-2 col-md-12">
        <div class="col-xs-2 col-md-5">학과/전공 선택</div>
        <div class="col-xs-2 col-md-5">
            <v-select
              :items="major"
              item-text="title"
              v-model="info.major_name"
              single-line
            ></v-select>
        </div>
      </div>
      <div class="col-xs-2 col-md-12">
        <div class="col-xs-2 col-md-5">전공/학반 선택</div>
        <div class="col-xs-2 col-md-5">
            <v-select
              :items="Academic"
              item-text="title"
              v-model="info.class_name"
              single-line
            ></v-select>
        </div>
      </div>
      <div class="col-xs-2 col-md-12">
        <div class="col-xs-2 col-md-5">년제 선택</div>
        <div class="col-xs-2 col-md-5">
            <v-select
              :items="college_category"
              item-text="title"
              v-model="info.college_category_sub"
              single-line
            ></v-select>
        </div>
      </div>
    </div>
  </div>

</template>

<script>

export default {
  created : function (){

  },
  data : ()=>({
    info : {
      department_name : "",
      major_name : "",
      class_name : "",
      college_category_sub : ""
    },
    department : [
      {title : '공과 대학' , key : '1'},
      {title : '컴퓨터 정보 계열' , key : '1'}
    ],
    major : [
      {title : '기계 공학' , key : '2'},
      {title : '웹 데이터베이스' , key : '2'}
    ],
    Academic : [
      {title : '로봇 공학' , key : '3'},
      {title : '3WDJ' , key : '3'},
      {title : '3ISN' , key : '3'},
    ],
    college_category : [
      {title : '2년제' , key : '4'},
      {title : '3년제' , key : '4'},
      {title : '4년제' , key : '4'},
    ]

  }),

  methods: {
  
  }
}
</script>

<style>
  .section{
    margin-bottom: 1rem;
    background-color:rgb(224, 255, 224); 
    
  }
</style>
