<template>
  <v-container fluid grid-list-lg >
    <v-layout row >  
        <v-flex xs12 id="managementMainbar" style="margin : 0px 4px 0px 4px; font-size:25px">1. 企業情報</v-flex>
        <v-flex xs12 style="margin-top: -1px">
            <management-table class="table" v-for="item in business" :table = "item" :key="item.key" @delete-item="deleteItem"></management-table>
        </v-flex>
    </v-layout>
    <v-layout row>
        <v-flex xs12 id="managementMainbar" style="margin : 0px 4px 0px 4px; font-size:25px">2. 採用情報</v-flex>
        <v-flex xs12 style="margin-top: -1px">
            <management-table class="table" v-for="item in employment" :table = "item" :key="item.key"></management-table>
        </v-flex>
        
    </v-layout>
  </v-container>
</template>

<script>
import table from './ManagementTable'
import managementList from './ManagementList.js'
export default {
    data : () => ({
        item : {
            table : null,
            id : null,
            content : null
        },

        business :{     
            business_big_infos : {name : 'business_big_infos', title : '1.1 事業内容種別（大分類)', value : null},
            business_small_infos : {name : 'business_small_infos', title : '1.2 事業内容種別（小分類)', value : null},
        },

        employment : {
            work_infos : {name : 'work_infos', title : '2.1 仕事の内容種別', value : null},
            welfare_infos : {name : 'welfare_infos', title : '2.2 待遇・福利厚生', value : null},
            desired_employment_infos : {name : 'desired_employment_infos', title : '2.3 求めている人材種別', value : null},
            interview_infos : {name : 'interview_infos', title : '2.4 選考内容', value : null}
        }
     
    }),

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
                    this.employment.interview_infos.value = rep.data.interview_infos;       
                })
                .catch(ex=>{console.log(ex)});

    },
    components : {
        'management-table' : table
    }


}
</script>

<style>
/* .table{
    border: 1px solid gray;
    margin-bottom: 1rem;
    background-color: lightgoldenrodyellow;
} */


</style>
<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
