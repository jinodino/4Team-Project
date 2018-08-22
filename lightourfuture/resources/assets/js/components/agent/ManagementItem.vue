<template>
<v-container fluid grid-list-sm>
  <v-layout row v-if="mode == 'read'" align-center>
    <v-flex xs6 sm4 md4 lg4>
      <b-form-input type="text" disabled :value="row.content"></b-form-input>
    </v-flex>
    <v-flex xs6 sm4 md4 lg4>
      <v-btn @click="mode='write', content=row.content" color="success" small>修正</v-btn>
      <v-btn @click="$emit('delete-item')" color="error" small>削除</v-btn>
    </v-flex>
  </v-layout>
  <v-layout v-else align-center>
    <v-flex xs6 sm4 md4 lg4>
      <b-form-input type="text" v-model="content"></b-form-input>
    </v-flex>
    <v-flex xs6 sm4 md4 lg4>
      <v-btn @click="mode='read'; $emit('update-item', row.id, content)" :disabled = "!content" color="success" small>確定</v-btn>
      <v-btn @click="mode = 'read'" color="error" small>取り消し</v-btn>
    </v-flex>
   
  </v-layout>

     
</v-container>
</template>

<script>


export default {
  props : ['row'],
  data : ()=>({
    mode : 'read',
    content : "",

  })
}
</script>

<style>

</style>
