<template>
    <div class="col-xs-4 col-md-4 text-center">
        <div class="row">
          <div class="col-xs-4 col-md-4">
              <!-- src가 있을 때 -->
              <img v-if="!image.data" :src="src ? src : 'http://placehold.it/200x300'" alt="" style="width:200px;"/>
              <!-- src가 없을 때 -->
              <img v-else :src="image.data" alt="" style="width:200px;"/>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-4 col-md-4">
            <input type="file" @change="onFileChange"/>
            <div v-if="image.data">
              <button @click="removeImage" class="btn">Remove Image</button>
              <button @click="uploadImage" class="btn" >Upload Image</button>
            </div>
          </div>
        </div>
        
    </div>
</template>

<script>
export default {
    
    props : ['src'],

    data : ()=>({
        image : {
            data : null,
            name : null,
            path : '/profileImages'
        },
    }),


    methods : {

        onFileChange(e){
            var files = e.target.files || e.dataTransfer.files;
            if(!files.length)
                return;
            this.createImage(files[0]);
            console.log(files[0])
        },

        createImage(file){
            var image = new Image();
            var reader= new FileReader();
            var vm = this;

            reader.onload = (e)=>{
                vm.image.data = e.target.result;
                vm.image.name = file.name;
                };
            reader.readAsDataURL(file);
        },

        removeImage : function(e){
            this.axios.post('/img/remove', this.image)
                        .then(res=>{
                            console.log(res);
                        })
                        .catch(ex=>{console.log('에러 발생')})
            this.image={data:null, name:null};
        },

        uploadImage(){
            //https://appdividend.com/2018/02/13/vue-js-laravel-file-upload-tutorial/
            

           this.axios.post('/img/store', this.image)
                .then(response=>{
                    this.src = response.data;
                    this.image.data = null;
                    this.image.name = null;
                })
                .catch((ex)=>{ 
                    console.log(ex)
                })
        },
    }
  
}
</script>

<style>

</style>
