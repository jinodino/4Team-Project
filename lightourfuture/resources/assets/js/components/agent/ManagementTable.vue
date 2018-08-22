<template>
  <v-container fluid grid-list-sm>
    <v-layout>
    </v-layout>
    <v-layout row align-center style="background: #ffffff;">
      <v-flex xs12  id="gray" style="padding-top : 5px; padding-bottom : 5px;">
        {{table.title}}
      </v-flex >
      <v-flex xs12>
        <table-row v-for="item in table.value" :key="item.key" :row="item" @delete-item="deleteItem(item.id)" @update-item="updateItem"></table-row>
      </v-flex>
      <v-flex xs5>
        <b-form-input type="text" v-model="content"></b-form-input>
      </v-flex>
      <v-flex xs2>
        <v-btn @click="createItem" :disabled="!content" color="primary">追加</v-btn>
      </v-flex>
    </v-layout>
 </v-container>
</template>

<script>

import tableRow from './ManagementItem'
export default {
  props : ['table'],
  components : {
    'table-row' : tableRow
  },
  created : function(){
    console.log(this.table);    
  },
  data : () => ({
    value : null,
    content : null
  }),

  methods : {

    deleteItem(id){
      let table = this.table.name;
            //console.log(id,table);
      this.axios.post('/item/delete', {id, table})
                .then(rep=>{
                  let returnBool = rep.data.returnBool;
                  
                  if(returnBool){
                    this.table.value = rep.data.returnData;
                    alert('消しました。');
                  }
                  else
                    alert('消せません。');
                })
                .catch(ex=>{console.log(ex)});
    },

    createItem(){
      let table = this.table.name;
      let content = this.content;
      this.axios.post('/item/create',{content,table})
                .then(rep=>{
                  alert('追加しました。');
                  this.table.value = rep.data;
                  this.content = "";
                })
                .catch(ex=>{console.log(ex)})
      },

    updateItem(id, content){
      let table = this.table.name;
      this.axios.post('/item/update',{id,content,table})
                .then(rep=>{
                  alert('修正が出来上がりました。');
                  this.table.value = rep.data;
                })
                .catch(ex=>{console.log(ex)})
  
    }
  }
 
}
</script>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>

