<template>
<v-container grid-list-xs grid-list-md grid-list-lg>
    <v-layout row justify-center>
        <v-flex xs12 md12 lg12 text-xs-center>
            <input id="inputFile" type="file" class="input-file" @change="uploadImage" :disabled="disabled" accept=".jpg, .jpeg, .png"/> 
            <v-btn color="success" @click="check" block><v-icon left>photo_library</v-icon> PHOTO UPLOAD</v-btn>
        </v-flex> 
    </v-layout>

   <!-- 썸네일 -->
    <v-layout row wrap>
        <v-flex xs2 md2 lg2 v-for="(img, key, index) in preview" :key="index" v-show="img">
            <v-card>
                <v-card-media :src="img.data == null? img.photo_url : img.data" height="100px">
                        <v-layout row wrap>
                            <v-flex xs1 md1 lg1 offset-xs5 offset-md7 offset-lg8>
                                <v-btn icon small color="error" @click="removeImage(key)">x</v-btn>
                            </v-flex>
                        </v-layout>                 
                </v-card-media>
            </v-card>
        </v-flex>
    </v-layout>   

    <!-- 실사 이미지 -->
    <v-layout row justify-center>    
        <v-flex xs8 md8 lg8> 
            <v-card v-for="(img, key, index) in preview" :key="index" v-show="img">
                <img :src="img.data == null? img.photo_url : img.data" alt="" width="100%" height="auto"> 
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <span>{{img.name}}</span>
                    <v-btn color="error" @click="img.photo_url == null? removeImage(key) : removePropsImage(key, img.key)">CANCEL</v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
    </v-layout>
</v-container>
</template>

<style>
    .input-file {
        opacity: 0;
        width: 0;
        height: 0;
    }

</style>

<script>
  export default {

    props: ['value', 'disabled', 'propsImages'],

    created : function(){

        // for(let i in this.propsImages)
        //     this.preview[i] = { 'photo_url' : this.propsImages[i].photo_url, 'key' : this.propsImages[i].key};
    },

    // mounted : function(){
        
    //     if(this.propsImages != null){
    //         alert('mounted');
    //         for(let i in this.propsImages){
    //             let src = this.propsImages[i].photo_url;
    //             alert(src);
    //             this.preview[i] = src;
    //         }
    //     }
    // },

    data : () =>({       
        preview: [

        ],
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
            this.eventOccur(document.getElementById('inputFile'),'click');
              //alert(orgFile.value); //이벤트 처리가 끝나지 않은 타이밍이라 값 확인 안됨! 시간차 문제 
        },


        uploadImage(event) {
            if (this.disabled) 
              return;
            
            let files = event.target.files;
            if (files.length === 0)
              return;
            
            let reader = new FileReader();
            reader.onload = (event) => {
                this.preview.push({name : files[0].name, tpye : files[0].type, data : event.target.result});
                this.$emit('uploadImage', this.preview.length - 1, files[0].type, event.target.result);
            };
            reader.readAsDataURL(files[0]);
        },

        removeImage(index){
            alert('asdf');
            document.getElementById('inputFile').dispatchEvent;
            this.$emit('removeImage', index);
            // this.preview.splice(index, 1);
        },

        removePropsImage(index, key){
            alert('asdfasfasdfasfdasdadsdasdf');
            document.getElementById('inputFile').dispatchEvent;
            this.$emit('removePropsImage', index, key);
            this.preview.splice(index, 1);
        },


 
    },                                                   

    computed: {

        backgroundImage() {
            let image = this.preview || this.value;
            if (!image) {
                return null;
            }
            return `url('${image}')`;
        },

        reverseItems(){
          return this.preview.slice().reverse();
        }
    }
}
</script>