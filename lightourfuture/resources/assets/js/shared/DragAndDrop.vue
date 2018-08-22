<style>
  form{
    height: 200px;
    width: 200px;
    background: #ccc;
    margin: auto;
    text-align: center;
  }

  a.submit-button{
    display: block;
    margin: auto;
    text-align: center;
    width: 200px;
    padding: 10px;
    text-transform: uppercase;
    background-color: #CCC;
    color: white;
    font-weight: bold;
    margin-top: 20px;
  }

    div.file-listing{
    width: 400px;
    margin: auto;
    text-align: center;
    border-bottom: 1px solid #ddd;
  }


</style>

<template>
  <div id="file-drag-drop">
    <form ref="fileform">
      <span class="drop-files">Drop the files here!</span>
    </form>

    <div class="file-listing">
      {{ files.name }}
    </div>
    <a class="submit-button" v-on:click="submitFiles()" v-show="files">Submit</a>
  </div>
</template>

<script>
  export default {

    data(){
      return {
        dragAndDropCapable: false,
        files: ""
      }
    },

    mounted(){

      this.dragAndDropCapable = this.determineDragAndDropCapable();

      
      if( this.dragAndDropCapable ){
        
        ['drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop'].forEach( function( evt ) {
        
         this.$refs.fileform.addEventListener(evt, function(e){
            e.preventDefault();
            e.stopPropagation();
          }.bind(this), false);
        }.bind(this));

        
        this.$refs.fileform.addEventListener('drop', function(e){
         
          this.files = e.dataTransfer.files[0];
          console.log(this.files)
          console.log(this.files.name);

        }.bind(this));
      }
        console.log(this.files);
    },

    methods: {
     
      determineDragAndDropCapable(){
       
        var div = document.createElement('div');

        return ( ( 'draggable' in div )
                || ( 'ondragstart' in div && 'ondrop' in div ) )
                && 'FormData' in window
                && 'FileReader' in window;
      },

     
      submitFiles(){
        
        let formData = new FormData();
        let reqHttpAddr = "/file-drag-drop";
        let sendData    = {
                            professorId : 'KRPPRO',//this.professorId
                            file        :  JSON.stringify(this.files) 
                          };
        this.axios.post(reqHttpAddr, sendData).then((res)=>{
           
        }).catch((err)=>{
            console.log(err.message);
        });
      }
    }
  }
</script>