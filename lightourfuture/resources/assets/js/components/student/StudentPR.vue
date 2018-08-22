<template>
    <v-container grid-list-xs fluid>
        <v-container>
            <v-layout row>
                <v-flex xs12>
                    <v-card>
                        <!-- <v-card-title class="grey lighten-4 py-4 title">
                            PR VIDEO
                        </v-card-title> -->
                        <v-card-text>
                            <v-container grid-list-xs>
                                <v-layout row justify-center>
                                    <v-flex xs6>
                                        <v-text-field
                                        v-if="!updateMode"
                                        label="ビデオ URL"
                                        v-model="videoSrc"
                                        disabled>
                                        </v-text-field>

                                        <v-text-field
                                        v-else
                                        label="ビデオ URL"
                                        v-model="editedVideoSrc"
                                        >
                                        </v-text-field>
                                </v-flex>
                                    <v-flex xs3 v-if="!updateMode">
                                        <v-btn dark color="dahong" @click="editedVideoSrc = videoSrc; updateMode = true;">修正</v-btn>
                                    </v-flex>
                                    <v-flex xs3 v-else>
                                        <v-btn color="success" @click="updateVideoSrc">完了</v-btn>
                                        <v-btn color="error" @click="updateMode = false; editedVideoSrc = null;">取り消す</v-btn>
                                    </v-flex> 
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        
                        <v-card-media height="580px">
                            <iframe :src="videoSrc" frameborder="0" width="100%" allow="autoplay; encrypted-media"></iframe>

                        </v-card-media>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-container>
</template>

<script>
    
export default {
    created : function(){
        this.user.login_id = this.$store.getters.identification;
        this.user.classification = this.$store.getters.classification;
        
        this.getVideoSrc();
    },

    data : ()=>({
        user : {
            login_id : null,
            classification : null,
        },

        updateMode : false,
        editedVideoSrc : null,
        videoSrc : null,



    }),

    methods : {
        getVideoSrc(){
            let login_id = this.user.login_id;
            this.axios.post('/student/getVideoSrc', {login_id})
                        .then(rep=>{
                            this.videoSrc = rep.data.videoSrc;
                        })
                        .catch(ex=>{
                            console.log(ex);
                        });
        },

        updateVideoSrc(){
            let login_id = this.user.login_id;
            let editedVideoSrcArr = this.editedVideoSrc.split('/');
            let editedVideoSrc = null;

            if(editedVideoSrcArr[2] == 'youtu.be' ){
                editedVideoSrc = "https://www.youtube.com/embed/" + editedVideoSrcArr[3];

            }else if(editedVideoSrcArr[2] == 'www.youtube.com'){
                if(editedVideoSrcArr[3] == 'embed'){
                    editedVideoSrc = this.editedVideoSrc;
                }else {
                    let editedVideoSrcArr2 = editedVideoSrcArr[3].split('=');
                    editedVideoSrc = "https://www.youtube.com/embed/" + editedVideoSrcArr2[1];
                }
                
            }else{
                alert('youtubeのurlのみ登録可能です。');
                return;
            }
           

            this.axios.post('/student/updateVideoSrc', {login_id, editedVideoSrc})
                .then(rep=>{
                    let returnBool = rep.data.returnBool;
                    if(returnBool){
                        alert('修正されました');
                        this.updateMode = false;
                        this.getVideoSrc();
                    }else{
                        alert('すみません。問題が発生しました。');
                    }
                })
                .catch(ex=>{
                    console.log(ex);
                });
        }
    },


}

</script>
