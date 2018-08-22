<template>
    <div>
    
    <button class="btn btn-primary" @click="Eexport()">SdNED</button>
     <div class="row">
          <div class="col-xs-4 col-md-4">
            <input type="file" name="excel" @change="onFileChange"/>
            <div v-if="image.data">
              
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
             file_name : "excel",
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
             this.image={src:null, name:null};
         },

         uploadImage(){
            //https://appdividend.com/2018/02/13/vue-js-laravel-file-upload-tutorial/
            

           this.axios.post('/import', this.image)
                    this.src = response.data;
                     this.image.data = null;
                     this.image.name = null;
                  
         },
         Eexport() {
             this.axios({
                url: 'http://127.0.0.1:8000/export',
                method: 'GET',
                responseType: 'blob', // important
                }).then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'file.csv');
                document.body.appendChild(link);
                link.click();
            });
                    
                  
         
         }
     }
   }

</script>