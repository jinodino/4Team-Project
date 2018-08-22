<template> 
    <v-btn block @click="go2back">뒤로 가기</v-btn>
</template>
<script>


export default {

    methods: {
      go2back(){
          this.$router.go(-1);
      }
    

    }
}
</script>