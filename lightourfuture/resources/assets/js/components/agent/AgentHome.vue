<template>
<v-container >
  <!-- <h1>AgentHome</h1>
    <v-layout row section wrap>
      <v-flex xs4 md2 lg2 border text-center style ="background : #B9F6CA">
        기업 선택
      </v-flex>
      <v-flex xs8 md10 lg10 border text-center style ="background : #ffffff">
          <div class="col-xs-2  col-md-3" v-for="(check, comkey) in company" :key="comkey.key">
            <v-checkbox :label = "check.companyname" :value = "companyHeaders[comkey].id" hide-details v-model="headers"></v-checkbox>
          </div>
      </v-flex>
      {{items[0]}}
    </v-layout>

    <v-data-table
    :headers="headers"
    class="elevation-1"
    :items="items"
    >
        <template slot="items" slot-scope="props">
            <td class="text-xs-center">
              {{ props.item.no }}
            </td>  
            <td class="text-xs-center">
              {{ props.item.school }}
            </td>  
            <td class="text-xs-center">
              {{ props.item.team }}
            </td>  
            <td class="text-xs-center">
              {{ props.item.name }}
            </td>  
            <td class="text-xs-center">
              {{ props.item.img }}
            </td>  
            <td class="text-xs-center">
              {{ props.item.gen }}
            </td>  
            <td class="text-xs-center">
              {{ props.item.status }}
            </td>  
            <td class="text-xs-center" >
              {{ props.item.A }}
            </td>  
            <td class="text-xs-center">
              {{ props.item.B }}
            </td>  
            <td class="text-xs-center">
              {{ props.item.C }}
            </td>         
            <td class="text-xs-center">
              {{ props.item.D }}
            </td>         
            
        </template>
    </v-data-table> -->
  </v-container>
</template>

<script>
// export default {
//   data : ()=>({ 
//     headers : [
//       {text : 'NO'},
//       {text : '학교'},
//       {text : '팀'},
//       {text : '이름'},
//       {text : '사진'},
//       {text : '성별'},
//       {text : '내정 상태'},
//       {text : 'A사'},
//       {text : 'B사'},
//       {text : 'C사'},
//       {text : 'D사'},
//     ],
//     school : [
//       {schoolname : '영진전문대'},
//       {schoolname : '경북대'},
//       {schoolname : 'DBJ대'},
//     ],
//     companyHeaders : [
//       {id: {'text' : 'A사'}},
//       {id: {'text' : 'B사'}},
//       {id: {'text' : 'C사'}},
//       {id: {'text' : 'D사'}}
//     ],
//     company : [
//       {companyname: 'A사'},
//       {companyname: 'B사'},
//       {companyname: 'C사'},
//       {companyname: 'D사'}
//     ],
//     items : [
//       {no : 'y001', school : '영진', team : '3' , name : '길똥이', img : '사진' , gen : '남', status : '내정' , A : '3일 면접', B : '' ,C : '12일 12시 면접',D : ''}
//     ]
//   })
// }
</script>

<style>

</style>
