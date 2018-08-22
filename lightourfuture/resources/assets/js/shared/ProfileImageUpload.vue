<template>
    <v-card :width="(width) + 'px'">
        <div id="contai">
            <div></div> 
            <div>
                <img :src="backgroundImage" id="thum">
            </div>
            <div></div>
        </div>
     
        <v-card-actions v-if="mode != 'read'">
            <input id="orgFile" type="file" class="file-photo" @change="createProfileImage" accept=".jpg, .jpeg, .png, .svg"/>
            <v-btn color="error" v-if="value != null || preview != null" @click="removeProfileImage">CANCEL</v-btn>
            <v-btn color="success" v-else @click="check">UPLOAD</v-btn>
            <p>권장 사이즈 : {{width}} * {{height}}</p>
        </v-card-actions>
    </v-card>
</template>

<style>
    .file-photo {
        opacity: 0;
        width: 0;
        height: 0;
    }

    #contai { 
        /* width:320px; 
        height:300px;  */

        text-align:center; 
        display: grid;
        grid-template-rows: 1fr 1fr 1fr;
    }
    #thum{
        /* max-width: 320px;
        max-height: 300px; */
        width: auto;
        height: auto;
    }
 

</style>

<script>
    export default {
        props: ['value', 'width', 'height','mode'],

         created : function (){
            this.alt = 'http://placehold.it/' + this.width+ 'x' + this.height;
         },

         mounted (){
            let contai = document.getElementById('contai');
            let thum = document.getElementById('thum');

            contai.style.width = this.width+"px";
            contai.style.height = this.height+"px";
            thum.style.maxWidth = this.width+"px";
            thum.style.maxHeight = this.height+"px";
         },

        data : ()=>({
            alt : null,
            preview: null,
        }),
        
        methods: {

            eventOccur(evEle, evType){
                    //MouseEvents가 포인트 그냥 Events는 안됨~ ??
                    var mouseEvent = document.createEvent('MouseEvents');

                    /* API문서 initEvent(type,bubbles,cancelable) */
                    mouseEvent.initEvent(evType, true, false);
                    var transCheck = evEle.dispatchEvent(mouseEvent);

                    //만약 이벤트에 실패했다면
                    if (!transCheck) 
                        console.log("클릭 이벤트 발생 실패!");
            },

            check(){
                this.eventOccur(document.getElementById('orgFile'),'click');
                 //alert(orgFile.value); //이벤트 처리가 끝나지 않은 타이밍이라 값 확인 안됨! 시간차 문제 
            },


            createProfileImage(event) {
                if (this.disabled) {
                  return;
                }
                let files = event.target.files;
                if (files.length === 0){
                  return;
                }
                let reader = new FileReader();
                reader.onload = (event) => {
                    this.preview = event.target.result;
                    this.value = null;
                    this.$emit('createProfileImage', files[0].type, this.preview);
                };
                reader.readAsDataURL(files[0]);
            },

            removeProfileImage : function(e){
                document.getElementById('orgFile').dispatchEvent;
                this.$emit('removeProfileImage');
                this.preview = null;
                this.value = null;
            },
        },

        computed: {

            backgroundImage() {
                // let image = this.preview || this.value;
                // if (!image) {
                //     return this.alt;
                // }
                // return `url('${image}')`;
                if(this.value != null)
                    return this.value;
                else if(this.preview != null)
                    return this.preview;
                else 
                    return this.alt;
            }
        }
    }
</script>