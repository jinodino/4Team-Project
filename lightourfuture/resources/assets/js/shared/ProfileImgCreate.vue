<template>
    <div class="col-xs-4 col-md-4 text-center"> 
        <h5>확인 누르면 버튼 없어 취소 , 확인 버튼 없어지게...</h5>
        <div class="row">
           
          <div class="col-xs-4 col-md-4">
              <!--
                  0 path가 있을 때 -->
              <img v-if="!thisImage.data" :src="src ? src : 'http://placehold.it/200x200'" alt="" style="width:200px;"/>
              <!-- path가 없을 때 -->
              <img v-else :src="thisImage.data" style="width:500px;"/>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-4 col-md-4">
            <input v-if="!thisImage.data" type="file" @change="onFileChange" accept=".jpg, .jpeg, .png, .gif"/>
            <div v-else>
              <button @click="removeImage" class="btn">취소</button>
              <button @click="uploadImage" class="btn" >확인</button>
            </div>
          </div>
        </div>
        
    </div>
</template>

<script>
export default {

    props : ['index'],

    data : ()=>({
        thisImage : {
            data : null,
            name : null
        },
        src : null,
    }),
 
 
    methods : {

        onFileChange(e){
            var files = e.target.files || e.dataTransfer.files;
            if(!files.length)
                return;
            this.createImage(files[0]);
            console.log(files[0]);
            this.upload = false;
        },

        createImage(file){
            var image = new Image();
            var reader= new FileReader();

            reader.onload = (e)=>{
                this.thisImage.data = e.target.result;

                this.thisImage.name = file.name;
                };
            reader.readAsDataURL(file);
        },

        removeImage : function(e){
            this.thisImage={data:null, name:null};
            this.$emit('removeImage', this.index);
        },

        uploadImage(){
            //https://appdividend.com/2018/02/13/vue-js-laravel-file-upload-tutorial/
            this.$emit('getImage',this.index, this.thisImage);
        },
        
    }
  
}
</script>

<style>

</style>
