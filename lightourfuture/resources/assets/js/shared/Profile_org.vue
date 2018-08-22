<template>
<v-container fluid grid-list-md>
  <v-layout row>
    <v-flex xs12 md12 lg12>
        <v-layout>
            <v-flex xs12 md8 lg8>
                <v-card color="white" height="100%">
                    <h2>PROFILE</h2>    
                    <!--  Form : UserID, School 수정불가 -->
                    <v-layout wrap style="margin:3%">
                        <v-flex xs6 md6 lg6>
                            <b-form-group 
                                label="User ID:">
                                <b-form-input 
                                    type="text"
                                    disabled
                                    v-model="org_agent_id">
                                </b-form-input>
                            </b-form-group>
                        </v-flex>
                        <v-flex xs6 md6 lg6>
                            <b-form-group 
                                label="User ID:">
                                <b-form-input 
                                    type="text"
                                    disabled
                                    v-model="manager_login_id">
                                </b-form-input>
                            </b-form-group>
                        </v-flex>
                
                        <!-- Form : english, japan, katakana -->
                        <!-- 이름 -->
                        <v-flex xs6 sm6 md6 lg6> 
                            <b-form-group 
                                label="Name:">
                                <b-form-input 
                                    type="text"
                                    v-model="org_name"
                                    disabled>                            
                                </b-form-input>
                            </b-form-group>
                        </v-flex>               

                        <v-flex xs6 sm6 md6 lg6> 
                            <b-form-group 
                                label="katakanaName:">
                                <b-form-input id="Name"
                                    type="text"
                                    v-model="org_name_kana"
                                    disabled>     
                                </b-form-input>
                            </b-form-group>
                        </v-flex>

                        <!-- 주소 -->

                        <v-flex xs2 sm2 md2 lg2> 
                            <b-form-group 
                                label="country:">
                                <b-form-input id="Name"
                                    type="text"
                                    v-model="country_code"
                                    disabled>     
                                </b-form-input>
                            </b-form-group>
                        </v-flex>
                        <v-flex xs10 sm10 md10 lg10> 
                            <b-form-group 
                                label="address:">
                                <b-form-input id="Name"
                                    type="text"
                                    v-model="address"
                                    disabled>     
                                </b-form-input>
                            </b-form-group>
                        </v-flex>

                        <!-- Form : e-mail -->
                        <v-flex xs12 sm12 md12 lg12>
                            <b-form-group
                                label="homepageUrl">
                                <b-form-input id="Name"
                                    type="text"
                                    v-model="homepage_url"
                                    disabled>                        
                                </b-form-input>
                            </b-form-group>
                        </v-flex>                        
                    </v-layout>
                </v-card>                
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
                <v-card color="white" height="100%">
                    <v-layout wrap row>
                        <v-flex xs12 sm12 md12 lg12>   
                            <v-card-text style="text-align :center;" >
                                <div>
                                    <v-avatar responsive :size=130>
                                        <img v-if="!photo_url" :src="src ? src : '/profileImages2/nullImage.JPG'" class="img-circle userImg">
                                        <img v-else :src= "photo_url" class="img-circle userImg">
                                            
                                    </v-avatar>
                                </div>
                                <div style="padding:10%">
                                    <h3>{{org_name}}</h3>
                                    <h5>{{org_name_kana}}</h5>
                                </div>
                            </v-card-text>                 
                        </v-flex>
                        <v-flex xs12 sm12 md12 lg12 text-xs-right style="margin:5%; margin-top:70%">   
                            <label for="file_input"><v-icon>settings</v-icon></label>                        
                            <input v-show="false" accept=".jpg, .jpeg, .png" id="file_input" type="file" @change="onFileChange"/>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex> 
        </v-layout>
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
    created : function(){
        this.org_agent_id = this.$store.getters.identification;
        //에이전트 정보 가져오기
    },

    data : () =>({ 

        org_agent_id : 'orgac01',
        manager_login_id : 'root',
        org_name : 'halo',
        name_kana : 'ハロー',
        country_code : 'JP',
        address : '東京都新宿区',
        homepage_url : 'http://www.kling.org/aperiam-dolorem-nihil-quae-atque-eos-laborum',
        photo_url:'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTJQYqUVaRT0-nxBJqRWu39IhDVbChgL2nkUox-zwVjsOJdSLnfbA',
        
    }),
    methods : {
        //프로필 사진 수정
            onFileChange(e){
            
                var files = e.target.files || e.dataTransfer.files;
                if(!files.length)
                    return;
                this.createImage(files[0]);

           // this.uploadImage();
            },

            createImage(file){
                var image = new Image();
                var reader= new FileReader();

                reader.onload = (e)=>{
                    this.photo_url = e.target.result;
                };
                reader.readAsDataURL(file);
            },
    }
}
</script>