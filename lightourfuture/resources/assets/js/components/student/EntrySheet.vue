<template>

<div>
<!-- <h1>entrysheet .doc, .pptx 올리면 서버에서 pdf로 바꾸는 작업 해야함</h1>   
<h1>저장할 때 저장 경로 설정 어떻게 할 지 정해야함</h1>  
<h1>저장할 때 저장 경로 설정 어떻게 할 지 정해야함</h1>  
<h1>파일 사이즈에 따라서 iframe 태그 넓이 높이 정해야함 </h1>   

<a href="https://github.com/PHPOffice/PHPWord">https://github.com/PHPOffice/PHPWord</a> -->
<div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">

        <div class="form-group">
            <input type="file" @change="onFileChange" class="form-control">
        </div>

        <!-- 파일에 데이터가 있으면 업로드 버튼 생성 -->
        <div>
        <button  class="btn btn-primary" v-if="file.data" @click="uploadFile">
            upload
        </button>
        </div>
    </div>
    
        <iframe :src = "file.src + file.name" width='100%' height='500px' allowfullscreen webkitallowfullscreen>
        </iframe>
    </div>
</div>
      
</template>

<script>
export default {
    name : 'entrySheet',
    data : ()=>({
        file : {
            name : '',
            data : '',
            src : ''
        }
    }),

    methods : {

        //파일을 읽는다
        onFileChange (e){
            let files = e.target.files||e.dataTransfer.files;

            //파일 사이즈가 0이면 리턴
             if(!files.length)
                return;

            this.createFile(files[0]);

        },
        createFile(file){
         
            var fileReader = new FileReader();
            
            fileReader.onload = (e)=>{
                this.file.data = e.target.result;
                this.file.name = file.name;
                this.file.src = "/ViewerJS/#../entrySheet/";
            };
            fileReader.readAsDataURL(file);
        },

        //업로드
        uploadFile(){
            this.$http
            .post('/repository/store', this.file)
            .then(response=>{console.log(response)})
            .catch((ex)=>{console.log('에러났네용')})
        }
        // create(){
        //     this.$http.post('/repository', this.file).then(response=>{console.log(response)}).catch((ex)=>{console.log('에러발생')})
        // },

       
    },
    created () {

  }

}
</script>

<style>

</style>